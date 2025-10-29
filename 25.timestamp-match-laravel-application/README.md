# 25. timestamp match laravel application

## 1. config/app.php

```code
    'timezone' => 'Asia/Dhaka'
```

## 2. app/Traits/RawTimestamps.php

```code
    <?php

    namespace App\Traits;

    use DateTimeInterface;

    trait RawTimestamps
    {
        /**
        * Prevent Laravel from trying to serialize DateTime objects.
        */
        protected function serializeDate(DateTimeInterface $date)
        {
            return $date->format('Y-m-d H:i:s');
        }
    }

```

## 3. app\Models\User.php

```code
    use RawTimestamps;
```
