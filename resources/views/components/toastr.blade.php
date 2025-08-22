<!-- Toastr CSS & JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    // Optional: Toastr configuration
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000"
    };

    @if (session('success'))
        toastr.success("{{ session('success') }}");
        @php
            session()->forget('success');
        @endphp
    @endif

    @if (session('error'))
        toastr.error("{{ session('error') }}");
        @php
            session()->forget('error');
        @endphp
    @endif

    @if (session('warning'))
        toastr.warning("{{ session('warning') }}");
        @php
            session()->forget('warning');
        @endphp
    @endif

    @if (session('info'))
        toastr.info("{{ session('info') }}");
        @php
            session()->forget('info');
        @endphp
    @endif
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
