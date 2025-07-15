<script>
    function showAlert(input, type, message, time = 5000) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show alert-custom`;
            alertDiv.innerHTML = `
                <div class="d-flex">
                    <div>
                        <i class="ti ti-alert-triangle icon alert-icon text-${type} me-2"></i>
                    </div>
                    <div>${message}</div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert"></a>
            `;
            
            //insert before the input element
            input.parentNode.insertBefore(alertDiv, input);
            if (time > 0) {
                setTimeout(() => {
                    if (alertDiv.parentElement) {
                        alertDiv.remove();
                    }
                }, time);
            }
    }

    function removeAlert() {
        const alertDiv = document.querySelector('.alert-custom');
        if (alertDiv) {
            alertDiv.remove();
        }
    }
</script>