<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExchangeRateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ExchangeRateController extends Controller
{
    public function index()
    {
        $rates = $this->loadJsonData('data/exchange-rates.json');
        $currencies = $this->loadJsonData('data/currencies.json');

        return view('exchange_rates.index', ['rates' => $rates, 'currencies' => $currencies]);
    }

    public function updateRate($id)
    {
        $rates = $this->loadJsonData('data/exchange-rates.json');
        $currencies = $this->loadJsonData('data/currencies.json');


        $result = array_filter($rates, function ($record) use ($id) {
            return $record['id'] == $id;
        });
        $matchingRate = array_shift($result);
        return view('exchange_rates.update', ['ex_rate' => $matchingRate, 'currencies' => $currencies]);
    }

    public function store(ExchangeRateRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
        $rates = $this->loadJsonData('data/exchange-rates.json');
        $exists = $this->checkIfRateExist($validatedData['from'], $validatedData['to']);
        if (!$exists) {
            $fromCurrency = $this->getCurrencyRecord($validatedData['from']);
            $toCurrency = $this->getCurrencyRecord($validatedData['to']);

            $newRecord = [
                'id' => count($rates),
                'from' => $fromCurrency,
                'to' => $toCurrency,
                'rate' => $validatedData['rate'],
            ];

            $rates[] = $newRecord;
            $this->updateRates($rates); //update records
            return redirect()->route('exchange_rates.index')->with('success', 'Rate added successfully!');
        }
        return redirect()->route('exchange_rates.index')->with('failed', 'Rate already exists!');
    }


    public function update(ExchangeRateRequest $request, $id): RedirectResponse
    {
        $validatedData = $request->validated();

        $rates = $this->loadJsonData('data/exchange-rates.json');

        $exists = $this->checkIfRateExist($validatedData['from'], $validatedData['to']);
        if (!$exists) {

            $fromCurrency = $this->getCurrencyRecord($validatedData['from']);
            $toCurrency = $this->getCurrencyRecord($validatedData['to']);

            $newRecord = [
                'id' => $id,
                'from' => $fromCurrency,
                'to' => $toCurrency,
                'rate' => $validatedData['rate'],
            ];

            $updatedArray = array_map(function ($item) use ($id, $newRecord) {
                if ($item['id'] == $id) {
                    foreach ($newRecord as $key => $value) {
                        $item[$key] = $value;
                    }
                }
                return $item;
            }, $rates);
            $this->updateRates($updatedArray); //update records
            return redirect()->route('exchange_rates.index')->with('success', 'Rate added successfully!');
        }
        return redirect()->route('exchange_rates.index')->with('failed', 'Rate already exists!');
    }

    public function destroy($id): RedirectResponse
    {
        $rates = $this->loadJsonData('data/exchange-rates.json');

        $data = array_filter($rates, function ($record) use ($id) {
            return $record['id'] != $id;
        });
        $this->updateRates($data); //update records
        return redirect()->route('exchange_rates.index');
    }

    private function loadJsonData($path)
    {
        $jsonData = Storage::get($path);
        $data = json_decode($jsonData, true);
        return $data;
    }

    private function updateRates($data)
    {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);

        try {
            Storage::put('data/exchange-rates.json', $jsonData);
        } catch (\Exception $e) {
            // Handle exceptions
            echo ('Failed to write JSON file: ' . $e->getMessage());
        }
    }

    private function getCurrencyRecord($id)
    {
        $currencies = $this->loadJsonData('data/currencies.json');
        $collection = collect($currencies);
        $filtered = $collection->filter(function ($item) use ($id) {
            return $item['id'] == $id;
        });
        return $filtered->values()->all()[0];
    }

    private function checkIfRateExist($fromCur, $toCur): bool
    {
        $rates = $this->loadJsonData('data/exchange-rates.json');
        foreach ($rates as $rate) {
            if ($rate['from']['id'] == $fromCur && $rate['to']['id'] == $toCur) {
                return true;
            }
        }
        return false;
    }
}
