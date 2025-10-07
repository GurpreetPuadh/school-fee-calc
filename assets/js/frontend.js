jQuery(document).ready(function($) {
    'use strict';
    
    const SFC = {
        init: function() {
            this.bindEvents();
        },
        
        bindEvents: function() {
            $('#sfc-calculator-form').on('submit', this.handleCalculation.bind(this));
            $('.sfc-reset-btn').on('click', this.resetForm.bind(this));
        },
        
        handleCalculation: function(e) {
            e.preventDefault();
            
            const formData = $('#sfc-calculator-form').serialize();
            
            this.showLoading();
            
            $.ajax({
                url: sfc_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'sfc_calculate_fees',
                    nonce: sfc_ajax.nonce,
                    ...$.deparam(formData)
                },
                success: (response) => {
                    if (response.success) {
                        this.displayResults(response.data);
                        this.saveCalculation(response.data);
                    } else {
                        this.showError(response.data);
                    }
                },
                error: (xhr, status, error) => {
                    this.showError('An error occurred while calculating fees. Please try again.');
                }
            });
        },
        
        displayResults: function(data) {
            const resultsHtml = this.generateResultsHtml(data);
            $('#sfc-results').html(resultsHtml).show();
            
            // Scroll to results
            $('html, body').animate({
                scrollTop: $('#sfc-results').offset().top - 100
            }, 500);
        },
        
        generateResultsHtml: function(data) {
            let html = `
                <div class="sfc-results-header">
                    <h3>Fee Calculation Results</h3>
                </div>
                <div class="sfc-fee-breakdown">
                    <h4>Fee Breakdown</h4>
            `;
            
            // Base fees
            Object.keys(data.base_fees).forEach(feeType => {
                if (feeType !== 'subtotal' && data.base_fees[feeType] > 0) {
                    const feeName = this.formatFeeName(feeType);
                    html += `
                        <div class="sfc-fee-item">
                            <span>${feeName}:</span>
                            <span>${this.formatCurrency(data.base_fees[feeType])}</span>
                        </div>
                    `;
                }
            });
            
            // Subtotal
            html += `
                <div class="sfc-fee-item">
                    <span><strong>Subtotal:</strong></span>
                    <span><strong>${this.formatCurrency(data.base_fees.subtotal)}</strong></span>
                </div>
            `;
            
            // Discounts
            if (data.discounts.length > 0) {
                html += `<h4 style="margin-top: 20px;">Discounts Applied</h4>`;
                data.discounts.forEach(discount => {
                    html += `
                        <div class="sfc-fee-item sfc-discount-item">
                            <span>${discount.name}:</span>
                            <span>-${this.formatCurrency(discount.amount)}</span>
                        </div>
                    `;
                });
                
                html += `
                    <div class="sfc-fee-item">
                        <span>Subtotal after Discounts:</span>
                        <span>${this.formatCurrency(data.discounted_subtotal)}</span>
                    </div>
                `;
            }
            
            // Tax
            html += `
                <div class="sfc-fee-item">
                    <span>Tax (${data.breakdown.tax_rate || 0}%):</span>
                    <span>${this.formatCurrency(data.tax_amount)}</span>
                </div>
            `;
            
            // Total
            html += `
                <div class="sfc-fee-item total">
                    <span>Total Annual Fee:</span>
                    <span>${this.formatCurrency(data.total_amount)}</span>
                </div>
            `;
            
            // Payment Schedule
            html += this.generatePaymentScheduleHtml(data.payment_schedule);
            
            html += `</div>`;
            
            return html;
        },
        
        generatePaymentScheduleHtml: function(schedule) {
            let html = `
                <div class="sfc-payment-schedule">
                    <h4>Payment Schedule</h4>
            `;
            
            schedule.forEach((installment, index) => {
                html += `
                    <div class="sfc-installment">
                        <span>Installment ${index + 1} (Month ${installment.month}):</span>
                        <span>${this.formatCurrency(installment.amount)}</span>
                    </div>
                `;
            });
            
            html += `</div>`;
            return html;
        },
        
        formatFeeName: function(feeType) {
            const names = {
                'tuition': 'Tuition Fee',
                'transport': 'Transportation',
                'hostel': 'Hostel Fee',
                'lab': 'Laboratory Fee',
                'library': 'Library Fee',
                'sports': 'Sports Fee',
                'exam': 'Examination Fee',
                'misc': 'Miscellaneous Fee'
            };
            
            return names[feeType] || feeType;
        },
        
        formatCurrency: function(amount) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            }).format(amount);
        },
        
        showLoading: function() {
            $('#sfc-results').html(`
                <div class="sfc-loading">
                    <p>Calculating fees... Please wait.</p>
                </div>
            `).show();
        },
        
        showError: function(message) {
            $('#sfc-results').html(`
                <div class="sfc-error">
                    <strong>Error:</strong> ${message}
                </div>
            `).show();
        },
        
        resetForm: function() {
            $('#sfc-results').hide().empty();
            $('#sfc-calculator-form')[0].reset();
        },
        
        saveCalculation: function(data) {
            const sessionId = 'sfc_' + Date.now();
            
            $.ajax({
                url: sfc_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'sfc_save_calculation',
                    nonce: sfc_ajax.nonce,
                    session_id: sessionId,
                    student_data: $('#sfc-calculator-form').serialize(),
                    calculation_data: data,
                    total_amount: data.total_amount
                }
            });
        }
    };
    
    // Initialize the calculator
    SFC.init();
    
    // Helper function to deserialize form data
    $.deparam = function(params) {
        var pairs = params.split('&');
        var object = {};
        
        pairs.forEach(function(pair) {
            var keyValue = pair.split('=');
            var key = decodeURIComponent(keyValue[0]);
            var value = decodeURIComponent(keyValue[1] || '');
            
            if (key.endsWith('[]')) {
                key = key.slice(0, -2);
                if (!object[key]) {
                    object[key] = [];
                }
                object[key].push(value);
            } else {
                object[key] = value;
            }
        });
        
        return object;
    };
});