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
