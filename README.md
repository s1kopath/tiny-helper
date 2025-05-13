# Tiny Helper 🤲

Welcome to the Tiny Helper repository! This repository contains a collection of functions, files, and methods that are super helpful for programming. 🪄✨

**Contents:**

- **Custom Helpers:** A collection of custom helper functions that extend functionality and provide additional utilities for common tasks.
- **Blade Directives:** Custom Blade directives that simplify common HTML rendering tasks and improve code readability.
- **Trait Classes:** ‍♀️ Reusable trait classes that encapsulate common functionality and can be easily integrated into your projects.
- **Service Providers:** ⚙️ Custom service providers that register and boot various components of your application, such as custom libraries or third-party packages.
- **Configuration Files:** Sample configuration files or presets that provide default settings and configurations for common components. ⚙️

## Usage

To use the helpers, files, or methods provided in this repository, simply clone or download the repository and integrate the desired components into your project as needed. You can also explore the individual files or collections to understand their functionalities and how they can benefit your project.

## Contributions

Contributions to this repository are welcome! If you have any useful helpers, files, or methods that you'd like to share with the community, feel free to fork this repository, add your contributions, and submit a pull request. Please ensure that your contributions adhere to the repository's guidelines and are well-documented.

## License

This project is licensed under the [MIT License](LICENSE). Feel free to use, modify, and distribute the contents of this repository according to the terms of the license.

## Author

This repository is maintained by [Md. Asaduzzaman](https://github.com/s1kopath). If you have any questions, feedback, or suggestions, please don't hesitate to reach out.

# ============================================

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

# ============================================

# 2. change env file data

```code
$path = base_path('.env');
$test = file_get_contents($path);

if (file_exists($path)) {
    file_put_contents($path, str_replace('APP_ENV=production', 'APP_ENV=local', $test));
}

```

# ============================================

# 3. simple countdown timer

```code
<style>
    p {
        display: inline;
        font-size: 40px;
        margin-top: 0px;
    }
</style>
```

```code
<p id="days"></p>
<p id="hours"></p>
<p id="mins"></p>
<p id="secs"></p>
<h2 id="end"></h2>
```

```code
<script>
    // The data/time we want to countdown to
    var countDownDate = new Date("Sep 25, 2024 16:37:52").getTime();

    // Run myfunc every second
    var myfunc = setInterval(function () {

        var now = new Date().getTime();
        var timeleft = countDownDate - now;

        // Calculating the days, hours, minutes and seconds left
        var days = Math.floor(timeleft / (1000 * 60 * 60 * 24));
        var hours = Math.floor((timeleft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((timeleft % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((timeleft % (1000 * 60)) / 1000);

        // Result is output to the specific element
        document.getElementById("days").innerHTML = days + "d "
        document.getElementById("hours").innerHTML = hours + "h "
        document.getElementById("mins").innerHTML = minutes + "m "
        document.getElementById("secs").innerHTML = seconds + "s "

        // Display the message when countdown is over
        if (timeleft < 0) {
            clearInterval(myfunc);
            document.getElementById("days").innerHTML = ""
            document.getElementById("hours").innerHTML = ""
            document.getElementById("mins").innerHTML = ""
            document.getElementById("secs").innerHTML = ""
            document.getElementById("end").innerHTML = "TIME UP!!";
        }
    }, 1000);
</script>
```

# ============================================

# 3. exception handling

```code
use Illuminate\Support\Facades\Log;
Log::emergency($message);
Log::alert($message);
Log::critical($message);
Log::error($message);
Log::warning($message);
Log::notice($message);
Log::info($message);
Log::debug($message);
```

```code
try {
    // code
} catch (Exception $e) {
    Log::error($e);
}

```

# ============================================

# 5. import db with terminal

```bash
mysql -u user_name -p
password
use database_name
source /home/domain/public_html/web-files/test.sql

```

# ============================================

# 6. open in window with popup

```code
<button onclick="openRequestedPopup()">Open Popup</button>

```

```code
<script>
    let windowObjectReference;
    let windowFeatures = "popup";

    function openRequestedPopup() {
        windowObjectReference = window.open("/user", "mozillaWindow", windowFeatures);
    }
</script>

```

# ============================================

# 7. read from json

```code
<?php
    $students = json_decode(file_get_contents(public_path() . "/stock.json"), true);
?>

```

# ============================================

# 8. reload a specific div with jquery

```code
<div id="liveBadge">
something
</div>

```

```code
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $("#liveBadge").load(location.href + " #liveBadge");
</script>

```
# ============================================
# 9. ci/cd pipeline
-cpanel
-cloudways api
-cloudways sftp
-unmanaged server

# ============================================
# 10. prevent form double submission with js

```code
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all forms on the page
        const forms = document.querySelectorAll('form');

        forms.forEach(function(form) {
            // Add a submit event listener to each form
            form.addEventListener('submit', function(event) {
                // Get all submit buttons within the form
                const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');

                // Disable each submit button
                submitButtons.forEach(function(button) {
                    button.disabled = true;
                });
            });
        });
    });
</script>

```

--shorter version--

```code
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', () => {
                form.querySelectorAll('[type="submit"]').forEach(button => button.disabled = true);
            });
        });
    });
</script>

```

# ============================================
# 11. convert number to word with js

```code
<script>
    function convertToWords(number) {
        var integerPart = Math.floor(number);
        var decimalPart = Math.round((number - integerPart) * 100);

        var integerWords = convertIntegerToWords(integerPart);
        var decimalWords = decimalPart > 0 ? convertIntegerToWords(decimalPart) : "";

        if (decimalWords !== "") {
            return integerWords + " point " + decimalWords;
        } else {
            return integerWords;
        }
    }

    function convertIntegerToWords(integer) {
        var ones = [
            "",
            "one",
            "two",
            "three",
            "four",
            "five",
            "six",
            "seven",
            "eight",
            "nine"
        ];
        var teens = [
            "ten",
            "eleven",
            "twelve",
            "thirteen",
            "fourteen",
            "fifteen",
            "sixteen",
            "seventeen",
            "eighteen",
            "nineteen"
        ];
        var tens = [
            "",
            "ten",
            "twenty",
            "thirty",
            "forty",
            "fifty",
            "sixty",
            "seventy",
            "eighty",
            "ninety"
        ];

        function convertGroup(num) {
            if (num < 10) return ones[num];
            else if (num < 20) return teens[num - 10];
            else return tens[Math.floor(num / 10)] + (num % 10 > 0 ? " " + ones[num % 10] : "");
        }

        function convertToWordsRecursive(num, level) {
            if (num === 0) return "";

            var words = "";

            if (num >= 100) {
                words += ones[Math.floor(num / 100)] + " hundred";
                num %= 100;
                if (num > 0) words += " and ";
            }

            words += convertGroup(num);

            if (level > 0 && num > 0) {
                words += " " + levels[level - 1];
            }

            return words;
        }

        if (integer === 0) {
            return "zero";
        }

        var levels = ["thousand", "million", "billion", "trillion"];

        var words = "";
        var level = 0;

        while (integer > 0) {
            var group = integer % 1000;
            if (group !== 0) {
                words = convertToWordsRecursive(group, level) + (words ? " " : "") + words;
            }

            integer = Math.floor(integer / 1000);
            level++;
        }

        return words.trim();
    }

    // Example usage:
    console.log(convertToWords(10));      // Output: "ten"
    console.log(convertToWords(100000));  // Output: "one hundred thousand"
    console.log(convertToWords(12345.67));// Output: "twelve thousand three hundred and forty-five point sixty-seven"
</script>

```


# ============================================
# 12. db transactions

```code
use Illuminate\Support\Facades\DB;
```

## =========== Basic Usage ===========
```code
DB::transaction(function () {
    // Your database operations go here

    // If an exception is thrown within this closure, the transaction will be automatically rolled back.
});
```

## =========== Handling Rollback and Commit Manually ===========
You can also manually begin, commit, and roll back transactions if you need more control:
```code
DB::beginTransaction();

try {
    // Your database operations go here

    // If everything is fine, commit the transaction
    DB::commit();
} catch (\Exception $e) {
    // If any error occurs, roll back the transaction
    DB::rollBack();

    // Handle the exception as needed, e.g., log it or rethrow it
}
```

## =========== Using Transactions in Eloquent Events ===========
If you want to use transactions within model events (like creating, updating, etc.), you can do so like this:
```code
User::creating(function ($user) {
    DB::transaction(function () use ($user) {
        // Perform operations that need to be inside a transaction
    });
});
```

## =========== Nested Transactions ===========
Laravel also supports nested transactions. If a transaction is nested within another transaction, Laravel will only commit the outermost transaction. If any transaction fails, all nested transactions will be rolled back.
```code
DB::transaction(function () {
    // First set of operations
    DB::transaction(function () {
        // Nested operations
    });

    // More operations
});
```

# 13. php script to create shortcut

Run the Script
- Upload this script to your server (e.g., in your web root directory).
- Visit the script in your browser (e.g., https://yourdomain.com/create-shortcut.php).


## =========== Basic Usage create-shortcut.php ===========
Change the paths based on your dir,
- $targetFolder: The path to the original folder.
- $linkFolder: The path for the shortcut.

Access the script with:
```code
https://yourdomain.com/create-shortcut.php
```


## =========== Advanced Usage shortcut.php ===========
Access the script with:
```code
https://yourdomain.com/shortcut.php?target=/core/public/storage&link=/storage
```

# 14. script to store auth token postman environment 

## Go to your Postman collection or individual request.
- Under the Tests tab of your login request, paste the script.
- Ensure the API response includes the token in a format like { "token": "your_token_value" }. Adjust the script if the token is located deeper in the JSON structure.
Run the request. If the login is successful, the token will be stored in an environment variable named token.
- Run the Script

# 15. quick popup

## basic usage

```code
    <!-- Optional: Set the alert message -->
    <script>
        window.customAlertMessage = "💳 You have unpaid dues. Please clear them to continue.";
        window.customAlertImage = "https://cdn-icons-png.flaticon.com/512/190/190411.png"; // or your own image URL
    </script>

    <!-- Load the modal alert -->
    <script src="path/to/custom-alert-modal.js"></script>
```

# 16. custom controlled laravel artisan command

## usage

```code
    php artisan currency:control
```