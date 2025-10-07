<?php
if (!defined('ABSPATH')) {
    exit;
}

// Get counts for the dashboard
global $wpdb;
$fee_structures_count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}sfc_fee_structures");
$discounts_count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}sfc_discounts WHERE is_active = 1");
$calculations_count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}sfc_calculations");
$payment_plans_count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}sfc_payment_plans WHERE is_active = 1");

// Get recent calculations
$recent_calculations = $wpdb->get_results(
    "SELECT * FROM {$wpdb->prefix}sfc_calculations ORDER BY created_at DESC LIMIT 5"
);

// Helper function to format currency
function sfc_format_currency($amount) {
    return '$' . number_format($amount, 2);
}

// Helper function to get student type label
function sfc_get_student_type_label($type) {
    $types = [
        'day_scholar' => 'Day Scholar',
        'boarder' => 'Boarder',
        'international' => 'International'
    ];
    return $types[$type] ?? $type;
}

// Helper function to get grade level label
function sfc_get_grade_label($grade) {
    $grades = [
        'pre-school' => 'Pre-School',
        'primary' => 'Primary (1-5)',
        'middle' => 'Middle (6-8)',
        'high' => 'High (9-10)',
        'senior' => 'Senior (11-12)'
    ];
    return $grades[$grade] ?? $grade;
}
?>

<div class="wrap sfc-admin-wrap">
    <h1><?php _e('School Fee Calculator - Dashboard', 'school-fee-calc'); ?></h1>
    
    <?php settings_errors('sfc_messages'); ?>
    
    <div class="sfc-dashboard-widgets">
        <!-- Quick Stats Widget -->
        <div class="sfc-widget">
            <h3><?php _e('Quick Stats', 'school-fee-calc'); ?></h3>
            <div class="sfc-stats-grid">
                <div class="sfc-stat-card">
                    <span class="sfc-stat-number"><?php echo esc_html($fee_structures_count); ?></span>
                    <span class="sfc-stat-label"><?php _e('Fee Structures', 'school-fee-calc'); ?></span>
                </div>
                <div class="sfc-stat-card">
                    <span class="sfc-stat-number"><?php echo esc_html($discounts_count); ?></span>
                    <span class="sfc-stat-label"><?php _e('Active Discounts', 'school-fee-calc'); ?></span>
                </div>
                <div class="sfc-stat-card">
                    <span class="sfc-stat-number"><?php echo esc_html($calculations_count); ?></span>
                    <span class="sfc-stat-label"><?php _e('Total Calculations', 'school-fee-calc'); ?></span>
                </div>
                <div class="sfc-stat-card">
                    <span class="sfc-stat-number"><?php echo esc_html($payment_plans_count); ?></span>
                    <span class="sfc-stat-label"><?php _e('Payment Plans', 'school-fee-calc'); ?></span>
                </div>
            </div>
        </div>

        <!-- Quick Actions Widget -->
        <div class="sfc-widget">
            <h3><?php _e('Quick Actions', 'school-fee-calc'); ?></h3>
            <div class="sfc-quick-actions">
                <a href="<?php echo admin_url('admin.php?page=sfc-fee-structures'); ?>" class="sfc-action-button">
                    <span class="dashicons dashicons-money"></span>
                    <?php _e('Manage Fee Structures', 'school-fee-calc'); ?>
                </a>
                <a href="<?php echo admin_url('admin.php?page=sfc-discounts'); ?>" class="sfc-action-button">
                    <span class="dashicons dashicons-tag"></span>
                    <?php _e('Configure Discounts', 'school-fee-calc'); ?>
                </a>
                <a href="<?php echo admin_url('admin.php?page=sfc-payment-plans'); ?>" class="sfc-action-button">
                    <span class="dashicons dashicons-calendar-alt"></span>
                    <?php _e('Payment Plans', 'school-fee-calc'); ?>
                </a>
                <a href="<?php echo admin_url('admin.php?page=sfc-settings'); ?>" class="sfc-action-button">
                    <span class="dashicons dashicons-admin-generic"></span>
                    <?php _e('Plugin Settings', 'school-fee-calc'); ?>
                </a>
            </div>
        </div>

        <!-- Recent Calculations Widget -->
        <div class="sfc-widget">
            <h3><?php _e('Recent Calculations', 'school-fee-calc'); ?></h3>
            <div class="sfc-recent-calculations">
                <?php if (empty($recent_calculations)): ?>
                    <p><?php _e('No recent calculations found.', 'school-fee-calc'); ?></p>
                <?php else: ?>
                    <div class="sfc-calculations-list">
                        <?php foreach ($recent_calculations as $calculation): 
                            $student_data = json_decode($calculation->student_data, true);
                            $calc_data = json_decode($calculation->calculation_data, true);
                        ?>
                            <div class="sfc-calculation-item">
                                <div class="sfc-calculation-header">
                                    <span class="sfc-calculation-amount">
                                        <?php echo sfc_format_currency($calculation->total_amount); ?>
                                    </span>
                                    <span class="sfc-calculation-date">
                                        <?php echo date('M j, g:i A', strtotime($calculation->created_at)); ?>
                                    </span>
                                </div>
                                <?php if ($student_data && is_array($student_data)): ?>
                                    <div class="sfc-calculation-details">
                                        <small>
                                            <?php 
                                            if (isset($student_data['grade_level'])) {
                                                echo sfc_get_grade_label($student_data['grade_level']);
                                            }
                                            if (isset($student_data['student_type'])) {
                                                echo ' • ' . sfc_get_student_type_label($student_data['student_type']);
                                            }
                                            ?>
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if ($calculations_count > 5): ?>
                        <div class="sfc-view-all">
                            <a href="#" class="button button-small"><?php _e('View All Calculations', 'school-fee-calc'); ?></a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Getting Started Widget -->
        <div class="sfc-widget">
            <h3><?php _e('Getting Started', 'school-fee-calc'); ?></h3>
            <div class="sfc-getting-started">
                <ol>
                    <li>
                        <strong><?php _e('Set up Fee Structures', 'school-fee-calc'); ?></strong>
                        <p><?php _e('Create fee structures for different grade levels and student types.', 'school-fee-calc'); ?></p>
                    </li>
                    <li>
                        <strong><?php _e('Configure Discounts', 'school-fee-calc'); ?></strong>
                        <p><?php _e('Set up discounts for siblings, early payments, scholarships, etc.', 'school-fee-calc'); ?></p>
                    </li>
                    <li>
                        <strong><?php _e('Add Payment Plans', 'school-fee-calc'); ?></strong>
                        <p><?php _e('Create installment plans for flexible payment options.', 'school-fee-calc'); ?></p>
                    </li>
                    <li>
                        <strong><?php _e('Use Shortcode', 'school-fee-calc'); ?></strong>
                        <p><?php _e('Add [school_fee_calculator] to any page or post to display the calculator.', 'school-fee-calc'); ?></p>
                    </li>
                </ol>
            </div>
        </div>

        <!-- System Status Widget -->
        <div class="sfc-widget">
            <h3><?php _e('System Status', 'school-fee-calc'); ?></h3>
            <div class="sfc-system-status">
                <div class="sfc-status-item">
                    <span class="sfc-status-label"><?php _e('Database Version', 'school-fee-calc'); ?>:</span>
                    <span class="sfc-status-value">1.0.0</span>
                </div>
                <div class="sfc-status-item">
                    <span class="sfc-status-label"><?php _e('PHP Version', 'school-fee-calc'); ?>:</span>
                    <span class="sfc-status-value <?php echo version_compare(PHP_VERSION, '7.4', '>=') ? 'sfc-status-good' : 'sfc-status-warning'; ?>">
                        <?php echo PHP_VERSION; ?>
                    </span>
                </div>
                <div class="sfc-status-item">
                    <span class="sfc-status-label"><?php _e('WordPress Version', 'school-fee-calc'); ?>:</span>
                    <span class="sfc-status-value"><?php echo get_bloginfo('version'); ?></span>
                </div>
                <div class="sfc-status-item">
                    <span class="sfc-status-label"><?php _e('Plugin Version', 'school-fee-calc'); ?>:</span>
                    <span class="sfc-status-value">1.0.0</span>
                </div>
            </div>
        </div>

        <!-- Support Widget -->
        <div class="sfc-widget">
            <h3><?php _e('Support & Documentation', 'school-fee-calc'); ?></h3>
            <div class="sfc-support-links">
                <p><?php _e('Need help? Check out these resources:', 'school-fee-calc'); ?></p>
                <ul>
                    <li>
                        <a href="https://github.com/yourusername/school-fee-calc" target="_blank">
                            <span class="dashicons dashicons-book"></span>
                            <?php _e('Plugin Documentation', 'school-fee-calc'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="https://github.com/yourusername/school-fee-calc/issues" target="_blank">
                            <span class="dashicons dashicons-sos"></span>
                            <?php _e('Report an Issue', 'school-fee-calc'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="https://wordpress.org/support/" target="_blank">
                            <span class="dashicons dashicons-forum"></span>
                            <?php _e('WordPress Support', 'school-fee-calc'); ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Quick Setup Guide -->
    <div class="sfc-widget sfc-full-width">
        <h3><?php _e('Quick Setup Guide', 'school-fee-calc'); ?></h3>
        <div class="sfc-setup-steps">
            <div class="sfc-step">
                <div class="sfc-step-number">1</div>
                <div class="sfc-step-content">
                    <h4><?php _e('Create Fee Structures', 'school-fee-calc'); ?></h4>
                    <p><?php _e('Go to Fee Structures and add fee configurations for each grade level and student type.', 'school-fee-calc'); ?></p>
                    <a href="<?php echo admin_url('admin.php?page=sfc-fee-structures'); ?>" class="button button-primary">
                        <?php _e('Add Fee Structure', 'school-fee-calc'); ?>
                    </a>
                </div>
            </div>
            <div class="sfc-step">
                <div class="sfc-step-number">2</div>
                <div class="sfc-step-content">
                    <h4><?php _e('Set Up Discounts', 'school-fee-calc'); ?></h4>
                    <p><?php _e('Configure automatic discounts for various scenarios like sibling discounts or early payments.', 'school-fee-calc'); ?></p>
                    <a href="<?php echo admin_url('admin.php?page=sfc-discounts'); ?>" class="button button-primary">
                        <?php _e('Configure Discounts', 'school-fee-calc'); ?>
                    </a>
                </div>
            </div>
            <div class="sfc-step">
                <div class="sfc-step-number">3</div>
                <div class="sfc-step-content">
                    <h4><?php _e('Add to Your Site', 'school-fee-calc'); ?></h4>
                    <p><?php _e('Use the shortcode [school_fee_calculator] on any page or post to display the calculator.', 'school-fee-calc'); ?></p>
                    <a href="<?php echo admin_url('post-new.php?post_type=page'); ?>" class="button button-primary">
                        <?php _e('Create New Page', 'school-fee-calc'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.sfc-admin-wrap {
    max-width: 1200px;
}

.sfc-dashboard-widgets {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.sfc-widget {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border-left: 4px solid #0073aa;
}

.sfc-widget.sfc-full-width {
    grid-column: 1 / -1;
}

.sfc-stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.sfc-stat-card {
    text-align: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 6px;
    border-left: 4px solid #0073aa;
}

.sfc-stat-number {
    display: block;
    font-size: 2em;
    font-weight: bold;
    color: #0073aa;
    line-height: 1;
}

.sfc-stat-label {
    display: block;
    font-size: 0.9em;
    color: #666;
    margin-top: 5px;
}

.sfc-quick-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.sfc-action-button {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 15px;
    background: #0073aa;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.3s;
}

.sfc-action-button:hover {
    background: #005a87;
    color: white;
}

.sfc-calculations-list {
    max-height: 200px;
    overflow-y: auto;
}

.sfc-calculation-item {
    padding: 10px;
    border-bottom: 1px solid #eee;
}

.sfc-calculation-item:last-child {
    border-bottom: none;
}

.sfc-calculation-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 5px;
}

.sfc-calculation-amount {
    font-weight: bold;
    color: #0073aa;
}

.sfc-calculation-date {
    font-size: 0.9em;
    color: #666;
}

.sfc-calculation-details {
    font-size: 0.85em;
    color: #888;
}

.sfc-view-all {
    margin-top: 10px;
    text-align: center;
}

.sfc-getting-started ol {
    margin-left: 0;
    padding-left: 0;
}

.sfc-getting-started li {
    margin-bottom: 15px;
    padding-left: 0;
}

.sfc-getting-started strong {
    display: block;
    margin-bottom: 5px;
}

.sfc-getting-started p {
    margin: 0;
    color: #666;
    font-size: 0.9em;
}

.sfc-system-status .sfc-status-item {
    display: flex;
    justify-content: space-between;
    padding: 5px 0;
    border-bottom: 1px solid #f0f0f0;
}

.sfc-system-status .sfc-status-item:last-child {
    border-bottom: none;
}

.sfc-status-good {
    color: #46b450;
    font-weight: bold;
}

.sfc-status-warning {
    color: #ffb900;
    font-weight: bold;
}

.sfc-support-links ul {
    margin: 0;
    padding: 0;
    list-style: none;
}

.sfc-support-links li {
    margin-bottom: 8px;
}

.sfc-support-links a {
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    color: #0073aa;
}

.sfc-support-links a:hover {
    color: #005a87;
}

.sfc-setup-steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.sfc-step {
    display: flex;
    gap: 15px;
    align-items: flex-start;
}

.sfc-step-number {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: #0073aa;
    color: white;
    border-radius: 50%;
    font-weight: bold;
    flex-shrink: 0;
}

.sfc-step-content h4 {
    margin: 0 0 8px 0;
    color: #0073aa;
}

.sfc-step-content p {
    margin: 0 0 12px 0;
    color: #666;
}

/* Responsive Design */
@media (max-width: 768px) {
    .sfc-dashboard-widgets {
        grid-template-columns: 1fr;
    }
    
    .sfc-stats-grid {
        grid-template-columns: 1fr;
    }
    
    .sfc-setup-steps {
        grid-template-columns: 1fr;
    }
    
    .sfc-step {
        flex-direction: column;
        text-align: center;
    }
    
    .sfc-step-number {
        align-self: center;
    }
}
</style>