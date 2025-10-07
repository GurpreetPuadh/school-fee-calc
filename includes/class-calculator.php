<?php

class SFC_Calculator {
    
    private $fee_structure;
    private $discounts;
    private $payment_plan;
    
    public function calculate($data) {
        // Get fee structure based on grade and student type
        $this->fee_structure = $this->get_fee_structure($data['grade_level'], $data['student_type']);
        
        if (!$this->fee_structure) {
            return new WP_Error('no_fee_structure', __('No fee structure found for the selected criteria.', 'school-fee-calc'));
        }
        
        // Calculate base fees
        $base_fees = $this->calculate_base_fees($data);
        
        // Apply discounts
        $discounted_fees = $this->apply_discounts($base_fees, $data);
        
        // Calculate tax
        $tax_amount = $this->calculate_tax($discounted_fees['subtotal']);
        
        // Calculate total
        $total_amount = $discounted_fees['subtotal'] + $tax_amount;
        
        // Calculate payment plan
        $payment_schedule = $this->calculate_payment_plan($total_amount, $data['payment_plan']);
        
        return array(
            'base_fees' => $base_fees,
            'discounts' => $discounted_fees['discounts'],
            'discounted_subtotal' => $discounted_fees['subtotal'],
            'tax_amount' => $tax_amount,
            'total_amount' => $total_amount,
            'payment_schedule' => $payment_schedule,
            'breakdown' => $this->get_breakdown($base_fees, $discounted_fees, $tax_amount)
        );
    }
    
    private function get_fee_structure($grade_level, $student_type) {
        global $wpdb;
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}sfc_fee_structures 
             WHERE grade_level = %s AND student_type = %s AND is_active = 1",
            $grade_level, $student_type
        ));
    }
    
    private function calculate_base_fees($data) {
        $fees = array(
            'tuition' => $this->fee_structure->tuition_fee,
            'transport' => $data['need_transport'] ? $this->fee_structure->transport_fee : 0,
            'hostel' => $data['need_hostel'] ? $this->fee_structure->hostel_fee : 0,
            'lab' => $this->fee_structure->lab_fee,
            'library' => $this->fee_structure->library_fee,
            'sports' => $this->fee_structure->sports_fee,
            'exam' => $this->fee_structure->exam_fee,
            'misc' => $this->fee_structure->misc_fee
        );
        
        $fees['subtotal'] = array_sum($fees);
        
        return $fees;
    }
    
    private function apply_discounts($base_fees, $data) {
        $discounts = $this->get_applicable_discounts($data);
        $total_discount = 0;
        $discount_details = array();
        
        foreach ($discounts as $discount) {
            $discount_amount = 0;
            
            if ($discount->discount_type === 'percentage') {
                $discount_amount = ($base_fees['subtotal'] * $discount->discount_value) / 100;
            } else {
                $discount_amount = $discount->discount_value;
            }
            
            $total_discount += $discount_amount;
            $discount_details[] = array(
                'name' => $discount->name,
                'type' => $discount->discount_type,
                'value' => $discount->discount_value,
                'amount' => $discount_amount
            );
        }
        
        return array(
            'subtotal' => max(0, $base_fees['subtotal'] - $total_discount),
            'discounts' => $discount_details,
            'total_discount' => $total_discount
        );
    }
    
    private function get_applicable_discounts($data) {
        global $wpdb;
        
        $discounts = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sfc_discounts WHERE is_active = 1");
        $applicable_discounts = array();
        
        foreach ($discounts as $discount) {
            if ($this->is_discount_applicable($discount, $data)) {
                $applicable_discounts[] = $discount;
            }
        }
        
        return $applicable_discounts;
    }
    
    private function is_discount_applicable($discount, $data) {
        $conditions = json_decode($discount->conditions, true) ?: array();
        
        foreach ($conditions as $condition) {
            if (!$this->check_condition($condition, $data)) {
                return false;
            }
        }
        
        return true;
    }
    
    private function check_condition($condition, $data) {
        // Implement condition checking logic
        return true;
    }
    
    private function calculate_tax($amount) {
        return ($amount * $this->fee_structure->tax_rate) / 100;
    }
    
    private function calculate_payment_plan($total_amount, $plan_id) {
        global $wpdb;
        
        $plan = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}sfc_payment_plans WHERE id = %d",
            $plan_id
        ));
        
        if (!$plan) {
            $plan = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}sfc_payment_plans WHERE installments = 1");
        }
        
        $installment_amount = $total_amount / $plan->installments;
        $months = json_decode($plan->months);
        $schedule = array();
        
        foreach ($months as $month) {
            $schedule[] = array(
                'month' => $month,
                'amount' => $installment_amount,
                'due_date' => date('Y-m-d', strtotime("+$month months"))
            );
        }
        
        return $schedule;
    }
    
    private function get_breakdown($base_fees, $discounted_fees, $tax_amount) {
        return array(
            'base_fees' => $base_fees,
            'discounts_applied' => $discounted_fees['discounts'],
            'total_discount' => $discounted_fees['total_discount'],
            'subtotal_after_discount' => $discounted_fees['subtotal'],
            'tax_amount' => $tax_amount,
            'grand_total' => $discounted_fees['subtotal'] + $tax_amount
        );
    }
}