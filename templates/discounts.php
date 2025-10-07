<?php
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$discounts = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sfc_discounts ORDER BY name");

function sfc_format_currency($amount) {
    return '$' . number_format($amount, 2);
}

function sfc_get_applicable_to_label($type) {
    $types = [
        'all' => 'All Students',
        'siblings' => 'Students with Siblings',
        'early_payment' => 'Early Payment',
        'staff' => 'Staff Children',
        'scholarship' => 'Scholarship'
    ];
    return $types[$type] ?? $type;
}
?>

<div class="wrap sfc-admin-wrap">
    <h1 class="sfc-page-title">Discounts Management</h1>
    
    <div class="sfc-admin-container">
        <div class="sfc-card">
            <div class="sfc-card-header">
                <h2 class="sfc-card-title">Add New Discount</h2>
            </div>
            <div class="sfc-card-body">
                <form method="post" class="sfc-form">
                    <?php wp_nonce_field('sfc_admin_action', 'sfc_nonce'); ?>
                    <input type="hidden" name="add_discount" value="1">
                    
                    <div class="sfc-form-grid">
                        <div class="sfc-form-group">
                            <label for="discount_name">Discount Name</label>
                            <input type="text" id="discount_name" name="name" class="sfc-input" required>
                        </div>
                        
                        <div class="sfc-form-group">
                            <label for="discount_type">Discount Type</label>
                            <select id="discount_type" name="discount_type" class="sfc-select" required>
                                <option value="percentage">Percentage (%)</option>
                                <option value="fixed">Fixed Amount</option>
                            </select>
                        </div>
                        
                        <div class="sfc-form-group">
                            <label for="discount_value">Discount Value</label>
                            <input type="number" id="discount_value" name="discount_value" class="sfc-input" step="0.01" min="0" required>
                        </div>
                    </div>
                    
                    <div class="sfc-form-group">
                        <label for="applicable_to">Applicable To</label>
                        <select id="applicable_to" name="applicable_to" class="sfc-select" required>
                            <option value="all">All Students</option>
                            <option value="siblings">Students with Siblings</option>
                            <option value="early_payment">Early Payment</option>
                            <option value="staff">Staff Children</option>
                            <option value="scholarship">Scholarship</option>
                        </select>
                    </div>
                    
                    <div class="sfc-form-group sfc-checkbox-group">
                        <label class="sfc-checkbox-label">
                            <input type="checkbox" name="is_active" value="1" checked class="sfc-checkbox">
                            <span class="sfc-checkbox-custom"></span>
                            Active Discount
                        </label>
                    </div>
                    
                    <div class="sfc-form-actions">
                        <button type="submit" class="sfc-btn sfc-btn-primary">Add Discount</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="sfc-card">
            <div class="sfc-card-header">
                <h2 class="sfc-card-title">Existing Discounts</h2>
            </div>
            <div class="sfc-card-body">
                <?php if (empty($discounts)): ?>
                    <div class="sfc-empty-state">
                        <p>No discounts found.</p>
                    </div>
                <?php else: ?>
                    <div class="sfc-table-container">
                        <table class="sfc-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Value</th>
                                    <th>Applicable To</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($discounts as $discount): ?>
                                    <tr>
                                        <td class="sfc-cell-bold"><?php echo esc_html($discount->name); ?></td>
                                        <td>
                                            <span class="sfc-badge <?php echo $discount->discount_type === 'percentage' ? 'sfc-badge-primary' : 'sfc-badge-secondary'; ?>">
                                                <?php echo ucfirst($discount->discount_type); ?>
                                            </span>
                                        </td>
                                        <td class="sfc-cell-currency <?php echo $discount->discount_type === 'percentage' ? 'sfc-cell-percentage' : ''; ?>">
                                            <?php 
                                            if ($discount->discount_type === 'percentage') {
                                                echo $discount->discount_value . '%';
                                            } else {
                                                echo sfc_format_currency($discount->discount_value);
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <span class="sfc-badge sfc-badge-outline">
                                                <?php echo sfc_get_applicable_to_label($discount->applicable_to); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="sfc-status <?php echo $discount->is_active ? 'sfc-status-active' : 'sfc-status-inactive'; ?>">
                                                <?php echo $discount->is_active ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="sfc-actions">
                                                <button class="sfc-btn sfc-btn-sm sfc-btn-outline">Edit</button>
                                                <button class="sfc-btn sfc-btn-sm sfc-btn-danger">Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>