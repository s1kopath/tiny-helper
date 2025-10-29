# 26. Disable console logs in production

```code
    <script>
        console.log = function() {};
        console.debug = function() {};
        console.warn = function() {};
        console.error = function() {};
        console.info = function() {};
    </script>
```
