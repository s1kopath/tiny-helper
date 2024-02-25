# Laravel Helper 🤲

Welcome to the Laravel Helper repository! This repository contains a collection of functions, files, and methods that are super helpful for Laravel projects. 🪄✨

**Contents:**

* **Custom Helpers:**  A collection of custom helper functions that extend Laravel's functionality and provide additional utilities for common tasks. 
* **Blade Directives:**  Custom Blade directives that simplify common HTML rendering tasks and improve code readability. 
* **Trait Classes:** ‍♀️ Reusable trait classes that encapsulate common functionality and can be easily integrated into your Laravel projects. 
* **Service Providers:** ⚙️ Custom service providers that register and boot various components of your application, such as custom libraries or third-party packages. 
* **Configuration Files:**  Sample configuration files or presets that provide default settings and configurations for common Laravel components. ⚙️

## Usage

To use the helpers, files, or methods provided in this repository, simply clone or download the repository and integrate the desired components into your Laravel project as needed. You can also explore the individual files or collections to understand their functionalities and how they can benefit your project.

## Contributions

Contributions to this repository are welcome! If you have any useful helpers, files, or methods that you'd like to share with the Laravel community, feel free to fork this repository, add your contributions, and submit a pull request. Please ensure that your contributions adhere to the repository's guidelines and are well-documented.

## License

This project is licensed under the [MIT License](LICENSE). Feel free to use, modify, and distribute the contents of this repository according to the terms of the license.

## Author

This repository is maintained by [Md. Asaduzzaman](https://github.com/s1kopath). If you have any questions, feedback, or suggestions, please don't hesitate to reach out.

# =================================================

# 1. simple toast
```code
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

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
