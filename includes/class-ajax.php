<?php

class SFC_Ajax {
    
    public function __construct() {
        add_action('wp_ajax_sfc_calculate_fees', array($this, 'calculate_fees'));
        add_action('wp_ajax_nopriv_sfc_calculate_fees', array($this, 'calculate_fees'));
        add_action('wp_ajax_sfc_save_calculation', array($this, 'save_calculation'));
        add_action('wp_ajax_nopriv_sfc_save_calculation', array($this, 'save_calculation'));
    }
    
    public function calculate_fees() {
        check_ajax_referer('sfc_nonce', 'nonce');
        
        $data = array(
            'grade_level' => sanitize_text_field($_POST['grade_level']),
            'student_type' => sanitize_text_field($_POST['student_type']),
            'payment_plan' => intval($_POST['payment_plan']),
            'need_transport' => isset($_POST['need_transport']) ? true : false,
            'need_hostel' => isset($_POST['need_hostel']) ? true : false,
            'siblings_count' => intval($_POST['siblings_count']),
            'early_payment' => isset($_POST['early_payment']) ? true : false
        );
        
        $calculator = new SFC_Calculator();
        $result = $calculator->calculate($data);
        
        if (is_wp_error($result)) {
            wp_send_json_error($result->get_error_message());
        } else {
            wp_send_json_success($result);
        }
    }
    
    public function save_calculation() {
        check_ajax_referer('sfc_nonce', 'nonce');
        
        global $wpdb;
        
        $session_id = sanitize_text_field($_POST['session_id']);
        $student_data = json_encode($_POST['student_data']);
        $calculation_data = json_encode($_POST['calculation_data']);
        $total_amount = floatval($_POST['total_amount']);
        
        $result = $wpdb->insert(
            "{$wpdb->prefix}sfc_calculations",
            array(
                'session_id' => $session_id,
                'student_data' => $student_data,
                'calculation_data' => $calculation_data,
                'total_amount' => $total_amount
            )
        );
        
        if ($result) {
            wp_send_json_success(array('id' => $wpdb->insert_id));
        } else {
            wp_send_json_error(__('Failed to save calculation.', 'school-fee-calc'));
        }
    }
}