/* Auto-fill amount based on selected invoice balance */
document.addEventListener('DOMContentLoaded', function() {
    const invoiceSelect = document.getElementById('invoice_id');
    const amountInput   = document.getElementById('amount');

    if (invoiceSelect && amountInput) {
        invoiceSelect.addEventListener('change', function() {
            const selectedOpt = this.options[this.selectedIndex];
            const balance     = selectedOpt.getAttribute('data-balance');
            if (balance !== null && balance !== undefined && balance > 0) {
                amountInput.value = balance;
            }
        });
    }
});
