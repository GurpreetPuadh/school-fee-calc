<div class="sfc-calculator-wrapper">
    <form id="sfc-calculator-form" class="sfc-form">
        <div class="sfc-form-section">
            <h3><?php _e('Student Information', 'school-fee-calc'); ?></h3>
            
            <div class="sfc-form-group">
                <label for="sfc-grade-level"><?php _e('Grade Level', 'school-fee-calc'); ?></label>
                <select id="sfc-grade-level" name="grade_level" required>
                    <option value=""><?php _e('Select Grade', 'school-fee-calc'); ?></option>
                    <option value="pre-school">Pre-School</option>
                    <option value="primary">Primary (1-5)</option>
                    <option value="middle">Middle (6-8)</option>
                    <option value="high">High (9-10)</option>
                    <option value="senior">Senior (11-12)</option>
                </select>
            </div>
            
            <div class="sfc-form-group">
                <label for="sfc-student-type"><?php _e('Student Type', 'school-fee-calc'); ?></label>
                <select id="sfc-student-type" name="student_type" required>
                    <option value=""><?php _e('Select Type', 'school-fee-calc'); ?></option>
                    <option value="day_scholar">Day Scholar</option>
                    <option value="boarder">Boarder</option>
                    <option value="international">International</option>
                </select>
            </div>
        </div>
        
        <div class="sfc-form-section">
            <h3><?php _e('Additional Services', 'school-fee-calc'); ?></h3>
            
            <div class="sfc-checkbox-group">
                <label>
                    <input type="checkbox" name="need_transport" value="1">
                    <?php _e('School Transportation', 'school-fee-calc'); ?>
                </label>
                
                <label>
                    <input type="checkbox" name="need_hostel" value="1">
                    <?php _e('Hostel Accommodation', 'school-fee-calc'); ?>
                </label>
            </div>
        </div>
        
        <div class="sfc-form-section">
            <h3><?php _e('Discount Eligibility', 'school-fee-calc'); ?></h3>
            
            <div class="sfc-form-group">
                <label for="sfc-siblings"><?php _e('Number of Siblings', 'school-fee-calc'); ?></label>
                <input type="number" id="sfc-siblings" name="siblings_count" min="0" max="10" value="0">
            </div>
            
            <div class="sfc-checkbox-group">
                <label>
                    <input type="checkbox" name="early_payment" value="1">
                    <?php _e('Early Payment Discount', 'school-fee-calc'); ?>
                </label>
            </div>
        </div>
        
        <div class="sfc-form-section">
            <h3><?php _e('Payment Plan', 'school-fee-calc'); ?></h3>
            
            <div class="sfc-form-group">
                <label for="sfc-payment-plan"><?php _e('Select Payment Plan', 'school-fee-calc'); ?></label>
                <select id="sfc-payment-plan" name="payment_plan" required>
                    <option value=""><?php _e('Select Plan', 'school-fee-calc'); ?></option>
                    <?php
                    global $wpdb;
                    $plans = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sfc_payment_plans WHERE is_active = 1");
                    foreach ($plans as $plan) {
                        echo '<option value="' . esc_attr($plan->id) . '">' . esc_html($plan->name) . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        
        <div class="sfc-form-actions">
            <button type="submit" class="sfc-calculate-btn">
                <?php _e('Calculate Fees', 'school-fee-calc'); ?>
            </button>
            <button type="reset" class="sfc-reset-btn">
                <?php _e('Reset Form', 'school-fee-calc'); ?>
            </button>
        </div>
    </form>
    
    <div id="sfc-results" class="sfc-results" style="display: none;"></div>
</div>