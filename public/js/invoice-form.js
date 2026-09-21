/**
 * Script responsável pelas interações dos formulários de fatura (Invoice Create / Edit).
 * Gerencia autopreenchimento de cursos, pagamentos associados com Select2 e cálculos dinâmicos com autoformatação.
 */
document.addEventListener('DOMContentLoaded', function() {
    const feeCheckboxes = document.querySelectorAll('.fee-type-checkbox');
    const totalDisplay  = document.getElementById('invoice-total-display');

    function formatKz(num) {
        return new Intl.NumberFormat('pt-AO', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num) + ' Kz';
    }

    // Inicialização do Select2 nos campos de seleção de fatura
    const singleSelects = document.querySelectorAll('.select2-single');
    if (window.jQuery && jQuery.fn.select2 && singleSelects.length > 0) {
        jQuery(singleSelects).select2({
            placeholder: "Selecione uma opção...",
            allowClear: true,
            width: '100%'
        });
    }

    function calculateTotal() {
        let total = 0;
        feeCheckboxes.forEach(function(cb) {
            if (cb.checked) {
                const price = parseFloat(cb.getAttribute('data-price')) || 0;
                total += price;
            }
        });

        if (totalDisplay) {
            totalDisplay.textContent = formatKz(total);
        }
    }

    if (feeCheckboxes.length > 0) {
        feeCheckboxes.forEach(function(cb) {
            cb.addEventListener('change', calculateTotal);
        });
        calculateTotal();
    }

    const enrollmentSelect  = document.getElementById('enrollment_id');
    const courseInput       = document.getElementById('course_id');
    const courseNameDisplay = document.getElementById('course_name_display');
    const paymentSelect     = document.getElementById('payment_id');
    const amountToPayInput  = document.getElementById('amount_to_pay');
    const amountPaidInput   = document.getElementById('amount_paid');
    const changeDisplay     = document.getElementById('change_display');
    const remainingDisplay  = document.getElementById('remaining_display');

    function updateCourseFromEnrollment() {
        if (!enrollmentSelect) return;
        const selectedOption = enrollmentSelect.options[enrollmentSelect.selectedIndex];
        if (selectedOption) {
            const courseId   = selectedOption.getAttribute('data-course-id');
            const courseName = selectedOption.getAttribute('data-course-name');
            if (courseId && courseInput) {
                courseInput.value = courseId;
            }
            if (courseNameDisplay) {
                courseNameDisplay.value = courseName || '';
            }
        }
    }

    if (enrollmentSelect) {
        enrollmentSelect.addEventListener('change', updateCourseFromEnrollment);
        if (window.jQuery) {
            jQuery(enrollmentSelect).on('change.select2', updateCourseFromEnrollment);
        }
        updateCourseFromEnrollment();
    }

    function updatePaymentValue() {
        if (!paymentSelect) return;
        const selectedOption = paymentSelect.options[paymentSelect.selectedIndex];
        if (selectedOption) {
            const paymentVal = parseFloat(selectedOption.getAttribute('data-value')) || 0;
            if (paymentVal > 0) {
                let formattedVal = window.CurrencyFormatter ? window.CurrencyFormatter.format(paymentVal) : paymentVal.toFixed(2);
                if (amountToPayInput) {
                    amountToPayInput.value = formattedVal;
                }
                if (amountPaidInput && (!amountPaidInput.value || window.CurrencyFormatter.parseRaw(amountPaidInput.value) === 0)) {
                    amountPaidInput.value = formattedVal;
                }
                updateCalculations();
            }
        }
    }

    if (paymentSelect) {
        paymentSelect.addEventListener('change', updatePaymentValue);
        if (window.jQuery) {
            jQuery(paymentSelect).on('change.select2', updatePaymentValue);
        }
        updatePaymentValue();
    }

    function updateCalculations() {
        let amountToPay = 0;
        let amountPaid  = 0;

        if (window.CurrencyFormatter) {
            amountToPay = CurrencyFormatter.parseRaw(amountToPayInput ? amountToPayInput.value : 0);
            amountPaid  = CurrencyFormatter.parseRaw(amountPaidInput ? amountPaidInput.value : 0);
        } else {
            amountToPay = amountToPayInput ? (parseFloat(amountToPayInput.value) || 0) : 0;
            amountPaid  = amountPaidInput ? (parseFloat(amountPaidInput.value) || 0) : 0;
        }

        const paymentMethodInput = document.getElementById('payment_method');
        const paymentMethod = paymentMethodInput ? paymentMethodInput.value.toLowerCase() : '';
        const changeLabel   = document.getElementById('change_label');

        if (changeLabel) {
            changeLabel.textContent = 'Saldo a Favor / Crédito';
        }

        if (amountPaid > amountToPay) {
            const change = amountPaid - amountToPay;
            if (changeDisplay) changeDisplay.value = formatKz(change);
            if (remainingDisplay) remainingDisplay.value = '0,00 Kz (Sem Pendência)';
        } else if (amountToPay > amountPaid) {
            const remaining = amountToPay - amountPaid;
            if (changeDisplay) changeDisplay.value = '0,00 Kz';
            if (remainingDisplay) remainingDisplay.value = formatKz(remaining);
        } else {
            if (changeDisplay) changeDisplay.value = '0,00 Kz';
            if (remainingDisplay) remainingDisplay.value = '0,00 Kz (Sem Pendência)';
        }
    }

    if (amountToPayInput) {
        amountToPayInput.addEventListener('input', updateCalculations);
        amountToPayInput.addEventListener('blur', updateCalculations);
    }
    if (amountPaidInput) {
        amountPaidInput.addEventListener('input', updateCalculations);
        amountPaidInput.addEventListener('blur', updateCalculations);
    }
    updateCalculations();
});
