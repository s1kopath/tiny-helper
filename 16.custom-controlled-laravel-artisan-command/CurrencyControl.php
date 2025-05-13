<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;

class CurrencyControl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:control';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage currencies: list, add, or remove them interactively.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Currency Control 💱");
        $this->info("Commands: 🧾 ls | ➕ + | ➖ - | 🚪 exit");

        while (true) {
            // simple version
            // $action = trim($this->ask('›'));
            // end simple version

            // complex version 
            $this->output->write("\033[36m💱 currency-cli ▸ \033[0m");
            $action = trim(fgets(STDIN));
            // end complex version

            if ($action === 'ls') {
                $currencies = Currency::all();
                if ($currencies->isEmpty()) {
                    $this->warn("No currencies found.");
                } else {
                    $this->table(['ID', 'Code', 'Name', 'Symbol'], $currencies->toArray());
                }
            } elseif ($action === '+') {
                $jsonCurrencies = json_decode(file_get_contents(public_path('assets/Common-Currency.json')), true);

                $this->info("Type currency codes to add (e.g. USD). Type `exit` to go back.");
                while (true) {
                    $code = strtoupper(trim($this->ask("Currency Code")));

                    if ($code === 'EXIT') break;

                    if (!array_key_exists($code, $jsonCurrencies)) {
                        $this->error("Currency {$code} not found in JSON.");
                        continue;
                    }

                    if (Currency::where('code', $code)->exists()) {
                        $this->warn("Currency {$code} already exists in DB.");
                        continue;
                    }

                    $currency = $jsonCurrencies[$code];
                    Currency::create([
                        'code' => $code,
                        'name' => $currency['name'],
                        'symbol' => $currency['symbol_native'],
                    ]);

                    $this->info("Currency {$code} added.");
                }
            } elseif ($action === '-') {
                $this->info("Type currency codes to remove (e.g. USD). Type `exit` to go back.");
                while (true) {
                    $code = strtoupper(trim($this->ask("Currency Code")));

                    if ($code === 'EXIT') break;

                    $currency = Currency::where('code', $code)->first();

                    if (!$currency) {
                        $this->warn("Currency {$code} does not exist.");
                        continue;
                    }

                    $currency->delete();
                    $this->info("Currency {$code} removed.");
                }
            } elseif (in_array($action, ['exit', 'quit'])) {
                $this->info("Goodbye!");
                return Command::SUCCESS;
            } else {
                $this->warn("Invalid command. Use `ls`, `+`, `-`, or `exit`.");
            }
        }
    }
}
