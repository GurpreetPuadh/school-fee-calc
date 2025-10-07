<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap sfc-admin-wrap">
    <h1><?php _e('School Fee Calculator - Settings', 'school-fee-calc'); ?></h1>
    
    <div class="sfc-admin-container">
        <form method="post" action="options.php">
            <?php
            settings_fields('sfc_settings');
            do_settings_sections('sfc-settings');
            ?>
            
            <div class="sfc-form-section">
                <h2><?php _e('General Settings', 'school-fee-calc'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="sfc_currency"><?php _e('Currency', 'school-fee-calc'); ?></label>
                        </th>
                        <td>
                            <select id="sfc_currency" name="sfc_currency">
                                <option value="USD">US Dollar ($)</option>
                                <option value="EUR">Euro (€)</option>
                                <option value="GBP">British Pound (£)</option>
                                <option value="INR">Indian Rupee (₹)</option>
                                <option value="AUD">Australian Dollar (A$)</option>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="sfc_currency_position"><?php _e('Currency Position', 'school-fee-calc'); ?></label>
                        </th>
                        <td>
                            <select id="sfc_currency_position" name="sfc_currency_position">
                                <option value="left">Left ($100)</option>
                                <option value="right">Right (100$)</option>
                                <option value="left_space">Left with space ($ 100)</option>
                                <option value="right_space">Right with space (100 $)</option>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="sfc_decimal_places"><?php _e('Decimal Places', 'school-fee-calc'); ?></label>
                        </th>
                        <td>
                            <input type="number" id="sfc_decimal_places" name="sfc_decimal_places" min="0" max="4" value="2">
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="sfc-form-section">
                <h2><?php _e('Calculation Settings', 'school-fee-calc'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="sfc_default_tax"><?php _e('Default Tax Rate (%)', 'school-fee-calc'); ?></label>
                        </th>
                        <td>
                            <input type="number" id="sfc_default_tax" name="sfc_default_tax" step="0.01" min="0" max="100" value="0">
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="sfc_enable_discounts"><?php _e('Enable Discounts', 'school-fee-calc'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" id="sfc_enable_discounts" name="sfc_enable_discounts" value="1" checked>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="sfc_enable_payment_plans"><?php _e('Enable Payment Plans', 'school-fee-calc'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" id="sfc_enable_payment_plans" name="sfc_enable_payment_plans" value="1" checked>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="sfc-form-section">
                <h2><?php _e('Display Settings', 'school-fee-calc'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="sfc_default_grade"><?php _e('Default Grade Level', 'school-fee-calc'); ?></label>
                        </th>
                        <td>
                            <select id="sfc_default_grade" name="sfc_default_grade">
                                <option value=""><?php _e('None', 'school-fee-calc'); ?></option>
                                <option value="pre-school">Pre-School</option>
                                <option value="primary">Primary</option>
                                <option value="middle">Middle</option>
                                <option value="high">High</option>
                                <option value="senior">Senior</option>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="sfc_custom_css"><?php _e('Custom CSS', 'school-fee-calc'); ?></label>
                        </th>
                        <td>
                            <textarea id="sfc_custom_css" name="sfc_custom_css" rows="10" cols="50" class="large-text code"></textarea>
                        </td>
                    </tr>
                </table>
            </div>
            
            <?php submit_button(); ?>
        </form>
    </div>
</div>