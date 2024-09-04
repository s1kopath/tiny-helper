
# ============================================
# 12. db transactions

```code
use Illuminate\Support\Facades\DB;
```

// =========== Basic Usage ===========
```code
DB::transaction(function () {
    // Your database operations go here

    // If an exception is thrown within this closure, the transaction will be automatically rolled back.
});
```

// =========== Handling Rollback and Commit Manually ===========
// You can also manually begin, commit, and roll back transactions if you need more control:
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

// =========== Using Transactions in Eloquent Events ===========
// If you want to use transactions within model events (like creating, updating, etc.), you can do so like this:
```code
User::creating(function ($user) {
    DB::transaction(function () use ($user) {
        // Perform operations that need to be inside a transaction
    });
});
```

// =========== Nested Transactions ===========
// Laravel also supports nested transactions. If a transaction is nested within another transaction, Laravel will only commit the outermost transaction. If any transaction fails, all nested transactions will be rolled back.
```code
DB::transaction(function () {
    // First set of operations
    DB::transaction(function () {
        // Nested operations
    });

    // More operations
});
```
