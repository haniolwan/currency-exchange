<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AmountController extends Controller
{
    public function index()
    {
        $currencies = config('exchange-rates.currencies');
        $amounts = config('transactions.amounts');
        return view('amounts.index', [
            'amounts' => $amounts,
            'currencies' => $currencies
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'to' => 'required|string',
        ]);

        $exchangeRate = config('exchange-rates.currencies');

        $amounts = config('transactions.amounts');
        $newRecord = [
            'amount' => $request->amount,
            'to' => $request->to,
            'result' => $request->amount * $exchangeRate[$request->to],
        ];
        $amounts[] = $newRecord;
        $this->updateConfig($amounts); //update config file
        return redirect()->route('amounts.index');
    }

    private function updateConfig(array $amounts)
    {
        $path = config_path('transactions.php');
        $content = "<?php\n\nreturn [\n    'amounts' => " . var_export($amounts, true) . ",\n];\n";
        file_put_contents($path, $content);
    }
}
