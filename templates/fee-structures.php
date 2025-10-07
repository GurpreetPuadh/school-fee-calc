<?php
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$fee_structures = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sfc_fee_structures ORDER BY grade_level, student_type");

function sfc_format_currency($amount) {
    return '$' . number_format($amount, 2);
}

function sfc_calculate_total_fees($structure) {
    return $structure->tuition_fee + $structure->transport_fee + $structure->hostel_fee +
           $structure->lab_fee + $structure->library_fee + $structure->sports_fee +
           $structure->exam_fee + $structure->misc_fee;
}

function sfc_get_grade_label($grade) {
    $grades = [
        'pre-school' => 'Pre-School',
        'primary' => 'Primary',
        'middle' => 'Middle',
        'high' => 'High',
        'senior' => 'Senior'
    ];
    return $grades[$grade] ?? $grade;
}

function sfc_get_student_type_label($type) {
    $types = [
        'day_scholar' => 'Day Scholar',
        'boarder' => 'Boarder',
        'international' => 'International'
    ];
    return $types[$type] ?? $type;
}
?>

<div class="wrap sfc-admin-wrap">
    <h1 class="sfc-page-title">Fee Structures Management</h1>
    
    <div class="sfc-admin-container">
        <div class="sfc-card">
            <div class="sfc-card-header">
                <h2 class="sfc-card-title">Add New Fee Structure</h2>
            </div>
            <div class="sfc-card-body">
                <form method="post" class="sfc-form">
                    <?php wp_nonce_field('sfc_admin_action', 'sfc_nonce'); ?>
                    <input type="hidden" name="add_fee_structure" value="1">
                    
                    <div class="sfc-form-grid">
                        <div class="sfc-form-group">
                            <label for="name">Structure Name</label>
                            <input type="text" id="name" name="name" class="sfc-input" required>
                        </div>
                        
                        <div class="sfc-form-group">
                            <label for="grade_level">Grade Level</label>
                            <select id="grade_level" name="grade_level" class="sfc-select" required>
                                <option value="">Select Grade Level</option>
                                <option value="pre-school">Pre-School</option>
                                <option value="primary">Primary (1-5)</option>
                                <option value="middle">Middle (6-8)</option>
                                <option value="high">High (9-10)</option>
                                <option value="senior">Senior (11-12)</option>
                            </select>
                        </div>
                        
                        <div class="sfc-form-group">
                            <label for="student_type">Student Type</label>
                            <select id="student_type" name="student_type" class="sfc-select" required>
                                <option value="">Select Student Type</option>
                                <option value="day_scholar">Day Scholar</option>
                                <option value="boarder">Boarder</option>
                                <option value="international">International</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="sfc-form-grid">
                        <div class="sfc-form-group">
                            <label for="tuition_fee">Tuition Fee</label>
                            <input type="number" id="tuition_fee" name="tuition_fee" class="sfc-input" step="0.01" min="0" required>
                        </div>
                        
                        <div class="sfc-form-group">
                            <label for="transport_fee">Transport Fee</label>
                            <input type="number" id="transport_fee" name="transport_fee" class="sfc-input" step="0.01" min="0" value="0">
                        </div>
                        
                        <div class="sfc-form-group">
                            <label for="hostel_fee">Hostel Fee</label>
                            <input type="number" id="hostel_fee" name="hostel_fee" class="sfc-input" step="0.01" min="0" value="0">
                        </div>
                    </div>
                    
                    <div class="sfc-form-grid">
                        <div class="sfc-form-group">
                            <label for="lab_fee">Laboratory Fee</label>
                            <input type="number" id="lab_fee" name="lab_fee" class="sfc-input" step="0.01" min="0" value="0">
                        </div>
                        
                        <div class="sfc-form-group">
                            <label for="library_fee">Library Fee</label>
                            <input type="number" id="library_fee" name="library_fee" class="sfc-input" step="0.01" min="0" value="0">
                        </div>
                        
                        <div class="sfc-form-group">
                            <label for="sports_fee">Sports Fee</label>
                            <input type="number" id="sports_fee" name="sports_fee" class="sfc-input" step="0.01" min="0" value="0">
                        </div>
                    </div>
                    
                    <div class="sfc-form-grid">
                        <div class="sfc-form-group">
                            <label for="exam_fee">Examination Fee</label>
                            <input type="number" id="exam_fee" name="exam_fee" class="sfc-input" step="0.01" min="0" value="0">
                        </div>
                        
                        <div class="sfc-form-group">
                            <label for="misc_fee">Miscellaneous Fee</label>
                            <input type="number" id="misc_fee" name="misc_fee" class="sfc-input" step="0.01" min="0" value="0">
                        </div>
                        
                        <div class="sfc-form-group">
                            <label for="tax_rate">Tax Rate (%)</label>
                            <input type="number" id="tax_rate" name="tax_rate" class="sfc-input" step="0.01" min="0" max="100" value="0">
                        </div>
                    </div>
                    
                    <div class="sfc-form-actions">
                        <button type="submit" class="sfc-btn sfc-btn-primary">Add Fee Structure</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="sfc-card">
            <div class="sfc-card-header">
                <h2 class="sfc-card-title">Existing Fee Structures</h2>
            </div>
            <div class="sfc-card-body">
                <?php if (empty($fee_structures)): ?>
                    <div class="sfc-empty-state">
                        <p>No fee structures found.</p>
                    </div>
                <?php else: ?>
                    <div class="sfc-table-container">
                        <table class="sfc-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Grade Level</th>
                                    <th>Student Type</th>
                                    <th>Tuition Fee</th>
                                    <th>Total Fees</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($fee_structures as $structure): ?>
                                    <tr>
                                        <td class="sfc-cell-bold"><?php echo esc_html($structure->name); ?></td>
                                        <td><span class="sfc-badge"><?php echo sfc_get_grade_label($structure->grade_level); ?></span></td>
                                        <td><span class="sfc-badge sfc-badge-secondary"><?php echo sfc_get_student_type_label($structure->student_type); ?></span></td>
                                        <td class="sfc-cell-currency"><?php echo sfc_format_currency($structure->tuition_fee); ?></td>
                                        <td class="sfc-cell-currency sfc-cell-total"><?php echo sfc_format_currency(sfc_calculate_total_fees($structure)); ?></td>
                                        <td>
                                            <span class="sfc-status <?php echo $structure->is_active ? 'sfc-status-active' : 'sfc-status-inactive'; ?>">
                                                <?php echo $structure->is_active ? 'Active' : 'Inactive'; ?>
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

<style>
.sfc-admin-wrap {
    max-width: 1400px;
    margin: 0;
}

.sfc-page-title {
    color: #1e1e1e;
    font-size: 28px;
    font-weight: 600;
    margin: 0 0 24px 0;
    padding: 0;
}

.sfc-admin-container {
    display: grid;
    gap: 24px;
}

.sfc-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    border: 1px solid #e0e0e0;
    overflow: hidden;
}

.sfc-card-header {
    padding: 20px 24px;
    border-bottom: 1px solid #f0f0f0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.sfc-card-title {
    color: #fff;
    font-size: 18px;
    font-weight: 600;
    margin: 0;
}

.sfc-card-body {
    padding: 24px;
}

.sfc-form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 24px;
}

.sfc-form-group {
    display: flex;
    flex-direction: column;
}

.sfc-form-group label {
    font-weight: 500;
    color: #374151;
    margin-bottom: 6px;
    font-size: 14px;
}

.sfc-input, .sfc-select {
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s;
    background: #fff;
}

.sfc-input:focus, .sfc-select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.sfc-form-actions {
    padding-top: 20px;
    border-top: 1px solid #f0f0f0;
    text-align: right;
}

.sfc-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    gap: 6px;
}

.sfc-btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.sfc-btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.sfc-btn-sm {
    padding: 6px 12px;
    font-size: 12px;
}

.sfc-btn-outline {
    background: transparent;
    border: 1px solid #d1d5db;
    color: #374151;
}

.sfc-btn-outline:hover {
    background: #f9fafb;
    border-color: #9ca3af;
}

.sfc-btn-danger {
    background: #ef4444;
    color: white;
}

.sfc-btn-danger:hover {
    background: #dc2626;
}

.sfc-empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #6b7280;
}

.sfc-table-container {
    overflow-x: auto;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.sfc-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
}

.sfc-table th {
    background: #f8fafc;
    padding: 12px 16px;
    text-align: left;
    font-weight: 600;
    color: #374151;
    border-bottom: 1px solid #e5e7eb;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.sfc-table td {
    padding: 16px;
    border-bottom: 1px solid #f3f4f6;
    color: #4b5563;
}

.sfc-table tr:hover {
    background: #f9fafb;
}

.sfc-cell-bold {
    font-weight: 600;
    color: #1f2937;
}

.sfc-cell-currency {
    font-family: 'Courier New', monospace;
    font-weight: 500;
}

.sfc-cell-total {
    color: #059669;
    font-weight: 600;
}

.sfc-badge {
    display: inline-block;
    padding: 4px 8px;
    background: #e0e7ff;
    color: #3730a3;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
}

.sfc-badge-secondary {
    background: #f3e8ff;
    color: #6b21a8;
}

.sfc-status {
    display: inline-flex;
    align-items: center;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
}

.sfc-status-active {
    background: #d1fae5;
    color: #065f46;
}

.sfc-status-inactive {
    background: #fee2e2;
    color: #991b1b;
}

.sfc-actions {
    display: flex;
    gap: 8px;
}

@media (max-width: 1024px) {
    .sfc-form-grid {
        grid-template-columns: 1fr;
    }
    
    .sfc-table-container {
        font-size: 14px;
    }
    
    .sfc-table th,
    .sfc-table td {
        padding: 12px 8px;
    }
}

@media (max-width: 768px) {
    .sfc-card-body {
        padding: 16px;
    }
    
    .sfc-actions {
        flex-direction: column;
    }
    
    .sfc-btn-sm {
        width: 100%;
    }
}
</style>