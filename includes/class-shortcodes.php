<?php

class SFC_Shortcodes {
    
    public function __construct() {
        add_shortcode('school_fee_calculator', array($this, 'calculator_shortcode'));
        add_shortcode('fee_calculator_results', array($this, 'results_shortcode'));
    }
    
    public function calculator_shortcode($atts) {
        $atts = shortcode_atts(array(
            'class' => '',
            'style' => ''
        ), $atts);
        
        ob_start();
        
        include SFC_PLUGIN_PATH . 'templates/calculator-form.php';
        return ob_get_clean();
    }
    
    public function results_shortcode($atts) {
        $atts = shortcode_atts(array(
            'class' => '',
            'style' => ''
        ), $atts);

        ob_start();
        include SFC_PLUGIN_PATH . 'templates/results-display.php';
        return ob_get_clean();
    }
}