<?php

class SFC_Admin {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'admin_init'));
    }
    
    public function add_admin_menu() {
        add_menu_page(
            __('School Fee Calculator', 'school-fee-calc'),
            __('Fee Calculator', 'school-fee-calc'),
            'manage_options',
            'school-fee-calc',
            array($this, 'admin_dashboard'),
            'dashicons-calculator',
            30
        );
        
        add_submenu_page(
            'school-fee-calc',
            __('Fee Structures', 'school-fee-calc'),
            __('Fee Structures', 'school-fee-calc'),
            'manage_options',
            'sfc-fee-structures',
            array($this, 'fee_structures_page')
        );
        
        add_submenu_page(
            'school-fee-calc',
            __('Discounts', 'school-fee-calc'),
            __('Discounts', 'school-fee-calc'),
            'manage_options',
            'sfc-discounts',
            array($this, 'discounts_page')
        );
        
        add_submenu_page(
            'school-fee-calc',
            __('Payment Plans', 'school-fee-calc'),
            __('Payment Plans', 'school-fee-calc'),
            'manage_options',
            'sfc-payment-plans',
            array($this, 'payment_plans_page')
        );
        
        add_submenu_page(
            'school-fee-calc',
            __('Settings', 'school-fee-calc'),
            __('Settings', 'school-fee-calc'),
            'manage_options',
            'sfc-settings',
            array($this, 'settings_page')
        );
    }
    
    public function admin_dashboard() {
        include SFC_PLUGIN_PATH . 'templates/admin-dashboard.php';
    }
    
    public function fee_structures_page() {
        include SFC_PLUGIN_PATH . 'templates/fee-structures.php';
    }
    
    public function discounts_page() {
        include SFC_PLUGIN_PATH . 'templates/discounts.php';
    }
    
    public function payment_plans_page() {
        include SFC_PLUGIN_PATH . 'templates/payment-plans.php';
    }
    
    public function settings_page() {
        include SFC_PLUGIN_PATH . 'templates/settings.php';
    }
    
    public function admin_init() {
        $this->handle_forms();
        $this->register_settings();
    }
    
    private function register_settings() {
        register_setting('sfc_settings', 'sfc_currency');
        register_setting('sfc_settings', 'sfc_currency_position');
        register_setting('sfc_settings', 'sfc_decimal_places');
        register_setting('sfc_settings', 'sfc_default_tax');
        register_setting('sfc_settings', 'sfc_enable_discounts');
        register_setting('sfc_settings', 'sfc_enable_payment_plans');
        register_setting('sfc_settings', 'sfc_default_grade');
        register_setting('sfc_settings', 'sfc_custom_css');
    }
    
    private function handle_forms() {
        if (!isset($_POST['sfc_nonce']) || !wp_verify_nonce($_POST['sfc_nonce'], 'sfc_admin_action')) {
            return;
        }
        
        if (isset($_POST['add_fee_structure'])) {
            $this->save_fee_structure($_POST);
        }
        
        if (isset($_POST['add_discount'])) {
            $this->save_discount($_POST);
        }
        
        if (isset($_POST['add_payment_plan'])) {
            $this->save_payment_plan($_POST);
        }
    }
    
    private function save_fee_structure($data) {
        global $wpdb;
        
        $result = $wpdb->insert(
            "{$wpdb->prefix}sfc_fee_structures",
            array(
                'name' => sanitize_text_field($data['name']),
                'grade_level' => sanitize_text_field($data['grade_level']),
                'student_type' => sanitize_text_field($data['student_type']),
                'tuition_fee' => floatval($data['tuition_fee']),
                'transport_fee' => floatval($data['transport_fee']),
                'hostel_fee' => floatval($data['hostel_fee']),
                'lab_fee' => floatval($data['lab_fee']),
                'library_fee' => floatval($data['library_fee']),
                'sports_fee' => floatval($data['sports_fee']),
                'exam_fee' => floatval($data['exam_fee']),
                'misc_fee' => floatval($data['misc_fee']),
                'tax_rate' => floatval($data['tax_rate'])
            )
        );
        
        if ($result) {
            add_settings_error('sfc_messages', 'sfc_message', __('Fee structure saved successfully.', 'school-fee-calc'), 'success');
        } else {
            add_settings_error('sfc_messages', 'sfc_message', __('Error saving fee structure.', 'school-fee-calc'), 'error');
        }
    }
    
    private function save_discount($data) {
        global $wpdb;
        
        $result = $wpdb->insert(
            "{$wpdb->prefix}sfc_discounts",
            array(
                'name' => sanitize_text_field($data['name']),
                'discount_type' => sanitize_text_field($data['discount_type']),
                'discount_value' => floatval($data['discount_value']),
                'applicable_to' => sanitize_text_field($data['applicable_to']),
                'is_active' => isset($data['is_active']) ? 1 : 0
            )
        );
        
        if ($result) {
            add_settings_error('sfc_messages', 'sfc_message', __('Discount saved successfully.', 'school-fee-calc'), 'success');
        } else {
            add_settings_error('sfc_messages', 'sfc_message', __('Error saving discount.', 'school-fee-calc'), 'error');
        }
    }
    
    private function save_payment_plan($data) {
        global $wpdb;
        
        // For now, using simple month calculation
        $installments = intval($data['installments']);
        $months = range(1, $installments);
        
        $result = $wpdb->insert(
            "{$wpdb->prefix}sfc_payment_plans",
            array(
                'name' => sanitize_text_field($data['name']),
                'installments' => $installments,
                'months' => json_encode($months),
                'late_fee_penalty' => floatval($data['late_fee_penalty']),
                'is_active' => isset($data['is_active']) ? 1 : 0
            )
        );
        
        if ($result) {
            add_settings_error('sfc_messages', 'sfc_message', __('Payment plan saved successfully.', 'school-fee-calc'), 'success');
        } else {
            add_settings_error('sfc_messages', 'sfc_message', __('Error saving payment plan.', 'school-fee-calc'), 'error');
        }
    }
    
    // Helper methods for dashboard
    public function get_fee_structures_count() {
        global $wpdb;
        return $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}sfc_fee_structures");
    }
    
    public function get_discounts_count() {
        global $wpdb;
        return $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}sfc_discounts WHERE is_active = 1");
    }
    
    public function get_calculations_count() {
        global $wpdb;
        return $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}sfc_calculations");
    }
    
    public function format_currency($amount) {
        return '$' . number_format($amount, 2);
    }
    
    public function calculate_total_fees($structure) {
        return $structure->tuition_fee + $structure->transport_fee + $structure->hostel_fee +
               $structure->lab_fee + $structure->library_fee + $structure->sports_fee +
               $structure->exam_fee + $structure->misc_fee;
    }
    
    public function display_recent_calculations() {
        global $wpdb;
        $calculations = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}sfc_calculations ORDER BY created_at DESC LIMIT 5"
        );
        
        if (empty($calculations)) {
            echo '<p>' . __('No recent calculations found.', 'school-fee-calc') . '</p>';
            return;
        }
        
        echo '<ul>';
        foreach ($calculations as $calc) {
            $student_data = json_decode($calc->student_data, true);
            echo '<li>';
            echo '<strong>' . $this->format_currency($calc->total_amount) . '</strong>';
            echo ' - ' . date('M j, Y g:i A', strtotime($calc->created_at));
            echo '</li>';
        }
        echo '</ul>';
    }
}