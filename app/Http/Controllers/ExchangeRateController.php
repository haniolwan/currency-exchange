<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    public function index()
    {
        $currencies = config('exchange-rates.currencies');
        return view('exchange_rates.index', compact('currencies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'currency' => 'required|string',
            'rate' => 'required|numeric',
        ]);

        $currencies = config('exchange-rates.currencies');
        $currencies[$request->currency] = $request->rate;
        $this->updateConfig($currencies); //update config file
        return redirect()->route('exchange_rates.index');
    }

    public function destroy(Request $request)
    {
        $currency = $request->currency;
        $currencies = config('exchange-rates.currencies');
        unset($currencies[$currency]);
        $this->updateConfig($currencies);
        return redirect()->route('exchange_rates.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'rate' => 'required|numeric',
        ]);

        $currencies = config('exchange-rates.currencies');
        if (isset($currencies[$request->currency])) {
            $currencies[$request->currency] = $request->rate;
        }
        $this->updateConfig($currencies); //update config file
        return redirect()->route('exchange_rates.index');
    }

    private function updateConfig(array $currencies)
    {
        $path = config_path('exchange-rates.php');
        $content = "<?php\n\nreturn [\n    'currencies' => " . var_export($currencies, true) . ",\n];\n";
        file_put_contents($path, $content);
    }
}
