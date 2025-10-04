# 24. sweetalert confirm dialog

### usage

```code
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // ✅ Global confirm dialog helper
        window.confirmDialog = async function (message = 'Are you sure?') {
            const result = await Swal.fire({
                title: 'Please Confirm',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
            });
            return result.isConfirmed;
        };

        // ✅ Example usage
        async function removeCurrency(currencyId) {
            const confirmed = await confirmDialog('Are you sure you want to remove this currency?');
            if (!confirmed) return;

            // Example: Simulate AJAX request
            setTimeout(() => {
                Swal.fire('Removed!', `Currency ID ${currencyId} has been removed.`, 'success');
            }, 600);
        }
    </script>
```
