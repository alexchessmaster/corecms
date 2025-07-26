<style>
.btn-close {
    background: none;
    border: none;
    position: absolute;
    top: 50%;
    right: 15px;
    transform: translateY(-50%);
    cursor: pointer;
    opacity: 0.7;
    transition: opacity 0.2s;
    padding: 5px;
    color: inherit;
}

.btn-close:hover {
    opacity: 1;
}

.btn-close i {
    font-size: 14px;
}

.alert {
    position: relative;
    padding-right: 50px;
}
</style>

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show pt-4" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif

@if (session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle manual close button clicks
    const closeButtons = document.querySelectorAll('.btn-close');
    
    closeButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const alert = this.closest('.alert');
            if (alert) {
                // Use Bootstrap's alert dismiss method if available
                if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                } else {
                    // Fallback manual removal
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        if (alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 500);
                }
            }
        });
    });
    
    // Auto-dismiss alerts after 10 seconds
    const alerts = document.querySelectorAll('.alert:not(.alert-danger)'); // Keep error alerts visible
    
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if (alert && alert.parentNode) {
                // Use Bootstrap's alert dismiss method if available
                if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                } else {
                    // Fallback manual removal
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        if (alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 500);
                }
            }
        }, 10000); // 10 seconds
    });
});
</script>