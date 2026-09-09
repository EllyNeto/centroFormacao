/* Dynamic calculation and fee_type selection logic for Invoice form */
document.addEventListener('DOMContentLoaded', function() {
    const feeCheckboxes = document.querySelectorAll('.fee-type-checkbox');
    const totalDisplay  = document.getElementById('invoice-total-display');

    function formatKz(num) {
        return new Intl.NumberFormat('pt-AO', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num) + ' Kz';
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

    feeCheckboxes.forEach(function(cb) {
        cb.addEventListener('change', calculateTotal);
    });

    calculateTotal();
});
