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