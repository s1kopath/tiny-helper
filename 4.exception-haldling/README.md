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