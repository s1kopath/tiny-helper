<?php

use App\Models\Currency;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->unique();
            $table->string('name')->nullable();
            $table->string('symbol', 10)->nullable();
            $table->timestamps();

            $table->index('code');
        });

        $currencies = json_decode(file_get_contents(public_path('assets/Common-Currency.json')), true);

        $currencyToIncludeForNow = ['BDT', 'AED'];

        foreach ($currencies as $code => $currency) {
            if (!in_array($code, $currencyToIncludeForNow)) {
                continue;
            }
            if (!Currency::where('code', $code)->exists()) {
                Currency::create([
                    'code' => $code,
                    'name' => $currency['name'],
                    'symbol' => $currency['symbol_native'],
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
