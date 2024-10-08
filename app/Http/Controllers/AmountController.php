<?php

namespace App\Http\Controllers;

use App\Http\Requests\AmountRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isEmpty;

class AmountController extends Controller
{
    public function index()
    {
        $rates = $this->loadJsonData('exchange-rates.json');
        $amounts = $this->loadJsonData('transactions.json');
        $currencies = $this->loadJsonData('currencies.json');

        return view('amounts.index', [
            'amounts' => $amounts,
            'rates' => $rates,
            'currencies' => $currencies
        ])->with('success', "Hello World");
    }

    public function update(AmountRequest $request, $id)
    {
        $validatedData = $request->validated();

        $exists = $this->checkIfExchangeExist($validatedData['from'], $validatedData['to']);
        if ($exists) {

            $rates = $this->loadJsonData('exchange-rates.json');
            $amounts = $this->loadJsonData('transactions.json');


            $fromCurrency = $this->getCurrencyRecord($validatedData['from']);
            $toCurrency = $this->getCurrencyRecord($validatedData['to']);

            $newRecord = [
                'id' => $id,
                'from' => $fromCurrency,
                'to' => $toCurrency,
                'amount' => $validatedData['amount'],
                'result' => $validatedData['amount'] * $rates[$validatedData['to']]['rate'],
            ];

            $updatedArray = array_map(function ($item) use ($id, $newRecord) {
                if ($item['id'] == $id) {
                    foreach ($newRecord as $key => $value) {
                        $item[$key] = $value;
                    }
                }
                return $item;
            }, $amounts);
            $this->updateAmounts($updatedArray); //update records
            return redirect()->route('amounts.index')->with('success', 'Amount updated successfully!');
        }
        return redirect()->route('amounts.index')->with('success', "Cannot update amount");
    }


    public function updateAmountPage($id)
    {
        $amounts = $this->loadJsonData('transactions.json');
        $currencies = $this->loadJsonData('currencies.json');

        $amount = $this->findElement($amounts, $id);

        return view('amounts.update', ['currencies' => $currencies, 'amount' => $amount]);
    }

    public function store(AmountRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        $exists = $this->checkIfExchangeExist($validatedData['from'], $validatedData['to']);
        if ($exists) {

            $rates = $this->loadJsonData('exchange-rates.json');
            $amounts = $this->loadJsonData('transactions.json');

            $fromCurrency = $this->getCurrencyRecord($validatedData['from']);
            $toCurrency = $this->getCurrencyRecord($validatedData['to']);

            $newRecord = [
                'id' => !isEmpty($amounts) ? count($amounts) : 0,
                'from' => $fromCurrency,
                'to' => $toCurrency,
                'amount' => $validatedData['amount'],
                'result' => $validatedData['amount'] * $rates[$validatedData['to']]['rate'],
            ];

            $amounts[] = $newRecord;
            $this->updateAmounts($amounts);
            return redirect()->route('amounts.index')->with('success', "Amount added successfully");
        }
        return redirect()->route('amounts.index')->with('failed', "Exchange doesn't exist");
    }

    public function destroy($id): RedirectResponse
    {
        $amounts = $this->loadJsonData('transactions.json');

        $data = array_filter($amounts, function ($record) use ($id) {
            return $record['id'] != $id;
        });
        $this->updateAmounts($data);
        return redirect()->route('amounts.index');
    }

    private function checkIfExchangeExist($fromId, $toId)
    {
        $rates = $this->loadJsonData('exchange-rates.json');

        if (($rates) > 0) {

            $data = array_filter($rates, function ($record) use ($fromId, $toId) {
                return $record['from']['id'] == $fromId && $record['to']['id'] == $toId;
            });

            return $data;
        }
    }

    private function updateAmounts($data)
    {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);

        try {
            Storage::put('data/transactions.json', $jsonData);
        } catch (\Exception $e) {
            // Handle exceptions
            echo ('Failed to write JSON file: ' . $e->getMessage());
        }
    }


    private function getCurrencyRecord($id)
    {
        $currencies = $this->loadJsonData('currencies.json');
        $collection = collect($currencies);
        $filtered = $collection->filter(function ($item) use ($id) {
            return $item['id'] == $id;
        });
        return $filtered->values()->all()[0];
    }

    private function findElement($collection, $id)
    {
        $result = array_filter($collection, function ($record) use ($id) {
            return $record['id'] == $id;
        });
        return array_shift($result);
    }

    private function loadJsonData($path)
    {
        $jsonData = Storage::get('data/' . $path);
        $data = json_decode($jsonData, true);
        return $data;
    }
}
