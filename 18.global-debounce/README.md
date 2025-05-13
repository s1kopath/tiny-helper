# 18. global debounce

## setup
```code
    <script>
        // Global debounce map to track multiple debounced functions by key
        const debounceMap = new Map();

        function globalDebounce(key, func, wait = 300) {
            const mapKey = typeof key === 'object' ? key : String(key);
            if (debounceMap.has(mapKey)) {
                clearTimeout(debounceMap.get(mapKey));
            }
            const timeout = setTimeout(func, wait);
            debounceMap.set(mapKey, timeout);
        }
    </script>
```

### usage

```code
    <input type="text" onkeyup="globalDebounce(this, () => handleInput(this.value), 300)">

```

```code
    <script>
        $('#shipping_charge, #discount, #paid').on('input', handleInput);

        $(document).on('input', '[id^="quantity_"]', function () {
            const id = $(this).attr('id').split('_')[1];
            globalDebounce(`quantity_${id}`, () => handleInput(id), 300);
        });

        $(document).on('input', '[id^="unit_price_"]', function () {
            const id = $(this).attr('id').split('_')[2];
            globalDebounce(`price_${id}`, () => handleInput(id), 300);
        });
    </script>
```