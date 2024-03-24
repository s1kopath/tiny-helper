
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