<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap sfc-admin-wrap">
    <h1><?php _e('School Fee Calculator - Dashboard', 'school-fee-calc'); ?></h1>
    
    <div class="sfc-dashboard-widgets">
        <div class="sfc-widget">
            <h3><?php _e('Quick Stats', 'school-fee-calc'); ?></h3>
            <div class="sfc-stats-grid">
                <div class="sfc-stat-card">
                    <span class="sfc-stat-number"><?php echo $this->get_fee_structures_count(); ?></span>
                    <span class="sfc-stat-label"><?php _e('Fee Structures', 'school-fee-calc'); ?></span>
                </div>
                <div class="sfc-stat-card">
                    <span class="sfc-stat-number"><?php echo $this->get_discounts_count(); ?></span>
                    <span class="sfc-stat-label"><?php _e('Active Discounts', 'school-fee-calc'); ?></span>
                </div>
                <div class="sfc-stat-card">
                    <span class="sfc-stat-number"><?php echo $this->get_calculations_count(); ?></span>
                    <span class="sfc-stat-label"><?php _e('Calculations', 'school-fee-calc'); ?></span>
                </div>
            </div>
        </div>

        <div class="sfc-widget">
            <h3><?php _e('Quick Actions', 'school-fee-calc'); ?></h3>
            <div class="sfc-quick-actions">
                <a href="<?php echo admin_url('admin.php?page=sfc-fee-structures'); ?>" class="sfc-action-button">
                    <?php _e('Manage Fee Structures', 'school-fee-calc'); ?>
                </a>
                <a href="<?php echo admin_url('admin.php?page=sfc-discounts'); ?>" class="sfc-action-button">
                    <?php _e('Configure Discounts', 'school-fee-calc'); ?>
                </a>
                <a href="<?php echo admin_url('admin.php?page=sfc-payment-plans'); ?>" class="sfc-action-button">
                    <?php _e('Payment Plans', 'school-fee-calc'); ?>
                </a>
            </div>
        </div>

        <div class="sfc-widget">
            <h3><?php _e('Recent Calculations', 'school-fee-calc'); ?></h3>
            <div class="sfc-recent-calculations">
                <?php $this->display_recent_calculations(); ?>
            </div>
        </div>
    </div>
</div>