
# 1. simple toast
```code
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true
    }

    @if (Session::has('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if (Session::has('error'))
        toastr.error("{{ session('error') }}");
    @endif

    @if (Session::has('info'))
        toastr.info("{{ session('info') }}");
    @endif

    @if (Session::has('warning'))
        toastr.warning("{{ session('warning') }}");
    @endif

    @if (isset($errors) && $errors->any())
        @foreach ($errors->all() as $error)
            toastr.error("{{ $error }}");
        @endforeach
    @endif
</script>

```
In the backend,
```code
return back()->with('success', 'Get going.');
return back()->with('error', 'Road-light stop.');
return back()->with('info', 'Great job !');
return back()->with('warning', 'Wait for the green light.');
```
