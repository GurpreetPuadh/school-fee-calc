<?php
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$payment_plans = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sfc_payment_plans ORDER BY installments");
?>

<div class="wrap sfc-admin-wrap">
    <h1><?php _e('Payment Plans Management', 'school-fee-calc'); ?></h1>
    
    <div class="sfc-admin-container">
        <div class="sfc-list-section">
            <h2><?php _e('Available Payment Plans', 'school-fee-calc'); ?></h2>
            
            <?php if (empty($payment_plans)): ?>
                <p><?php _e('No payment plans found.', 'school-fee-calc'); ?></p>
            <?php else: ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php _e('Plan Name', 'school-fee-calc'); ?></th>
                            <th><?php _e('Installments', 'school-fee-calc'); ?></th>
                            <th><?php _e('Payment Months', 'school-fee-calc'); ?></th>
                            <th><?php _e('Late Fee Penalty', 'school-fee-calc'); ?></th>
                            <th><?php _e('Status', 'school-fee-calc'); ?></th>
                            <th><?php _e('Actions', 'school-fee-calc'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payment_plans as $plan): 
                            $months = json_decode($plan->months);
                        ?>
                            <tr>
                                <td><?php echo esc_html($plan->name); ?></td>
                                <td><?php echo $plan->installments; ?></td>
                                <td><?php echo implode(', ', $months); ?></td>
                                <td><?php echo $plan->late_fee_penalty; ?>%</td>
                                <td>
                                    <span class="sfc-status <?php echo $plan->is_active ? 'active' : 'inactive'; ?>">
                                        <?php echo $plan->is_active ? __('Active', 'school-fee-calc') : __('Inactive', 'school-fee-calc'); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="#" class="button button-small"><?php _e('Edit', 'school-fee-calc'); ?></a>
                                    <a href="#" class="button button-small button-link-delete"><?php _e('Delete', 'school-fee-calc'); ?></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <div class="sfc-form-section">
            <h2><?php _e('Add New Payment Plan', 'school-fee-calc'); ?></h2>
            <form method="post" class="sfc-form">
                <?php wp_nonce_field('sfc_admin_action', 'sfc_nonce'); ?>
                <input type="hidden" name="add_payment_plan" value="1">
                
                <div class="sfc-form-group">
                    <label for="plan_name"><?php _e('Plan Name', 'school-fee-calc'); ?></label>
                    <input type="text" id="plan_name" name="name" required>
                </div>
                
                <div class="sfc-form-group">
                    <label for="installments"><?php _e('Number of Installments', 'school-fee-calc'); ?></label>
                    <input type="number" id="installments" name="installments" min="1" max="12" required>
                </div>
                
                <div class="sfc-form-group">
                    <label for="late_fee_penalty"><?php _e('Late Fee Penalty (%)', 'school-fee-calc'); ?></label>
                    <input type="number" id="late_fee_penalty" name="late_fee_penalty" step="0.01" min="0" value="0">
                </div>
                
                <div class="sfc-form-group">
                    <label>
                        <input type="checkbox" name="is_active" value="1" checked>
                        <?php _e('Active Plan', 'school-fee-calc'); ?>
                    </label>
                </div>
                
                <div class="sfc-form-actions">
                    <button type="submit" class="button button-primary"><?php _e('Add Payment Plan', 'school-fee-calc'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>