<?php

class SFC_Database {
    
    public static function install() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $tables = array(
            "{$wpdb->prefix}sfc_fee_structures" => "
                CREATE TABLE {$wpdb->prefix}sfc_fee_structures (
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    name varchar(255) NOT NULL,
                    grade_level varchar(100) NOT NULL,
                    student_type varchar(100) NOT NULL,
                    tuition_fee decimal(10,2) NOT NULL,
                    transport_fee decimal(10,2) DEFAULT 0,
                    hostel_fee decimal(10,2) DEFAULT 0,
                    lab_fee decimal(10,2) DEFAULT 0,
                    library_fee decimal(10,2) DEFAULT 0,
                    sports_fee decimal(10,2) DEFAULT 0,
                    exam_fee decimal(10,2) DEFAULT 0,
                    misc_fee decimal(10,2) DEFAULT 0,
                    tax_rate decimal(5,2) DEFAULT 0,
                    is_active tinyint(1) DEFAULT 1,
                    created_at datetime DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (id)
                ) $charset_collate;
            ",
            
            "{$wpdb->prefix}sfc_discounts" => "
                CREATE TABLE {$wpdb->prefix}sfc_discounts (
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    name varchar(255) NOT NULL,
                    discount_type enum('percentage','fixed') NOT NULL,
                    discount_value decimal(10,2) NOT NULL,
                    applicable_to varchar(255) NOT NULL,
                    conditions text,
                    is_active tinyint(1) DEFAULT 1,
                    created_at datetime DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (id)
                ) $charset_collate;
            ",
            
            "{$wpdb->prefix}sfc_payment_plans" => "
                CREATE TABLE {$wpdb->prefix}sfc_payment_plans (
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    name varchar(255) NOT NULL,
                    installments tinyint(2) NOT NULL,
                    months text NOT NULL,
                    late_fee_penalty decimal(5,2) DEFAULT 0,
                    is_active tinyint(1) DEFAULT 1,
                    PRIMARY KEY (id)
                ) $charset_collate;
            ",
            
            "{$wpdb->prefix}sfc_calculations" => "
                CREATE TABLE {$wpdb->prefix}sfc_calculations (
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    session_id varchar(100) NOT NULL,
                    student_data text NOT NULL,
                    calculation_data text NOT NULL,
                    total_amount decimal(10,2) NOT NULL,
                    created_at datetime DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (id),
                    KEY session_id (session_id)
                ) $charset_collate;
            "
        );
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        foreach ($tables as $table_name => $sql) {
            dbDelta($sql);
        }
        
        self::insert_default_data();
    }
    
    private static function insert_default_data() {
        global $wpdb;
        
        // Insert default payment plans
        $default_plans = array(
            array('Annual', 1, '["1"]', 0),
            array('Semi-Annual', 2, '["1","7"]', 2),
            array('Quarterly', 4, '["1","4","7","10"]', 2),
            array('Monthly', 10, '["1","2","3","4","5","6","7","8","9","10"]', 5)
        );
        
        foreach ($default_plans as $plan) {
            $wpdb->insert(
                "{$wpdb->prefix}sfc_payment_plans",
                array(
                    'name' => $plan[0],
                    'installments' => $plan[1],
                    'months' => $plan[2],
                    'late_fee_penalty' => $plan[3]
                )
            );
        }
    }
}