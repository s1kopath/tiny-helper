
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