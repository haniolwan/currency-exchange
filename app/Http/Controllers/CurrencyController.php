<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurrencyRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = $this->loadJsonData();
        return view('currencies.index', compact('currencies'));
    }

    public function updateCurrencyPage($id)
    {
        $currencies = $this->loadJsonData();
        $result = array_filter($currencies, function ($record) use ($id) {
            return $record['id'] == $id;
        });
        $matchingCurr = array_shift($result);

        return view('currencies.update', ['currency' => $matchingCurr]);
    }
    

    public function store(CurrencyRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        $currencies = $this->loadJsonData();
        $newRecord = [
            'id' => count($currencies),
            'name' => $validatedData['name'],
        ];

        $currencies[] = $newRecord;
        $this->updateCurrencyRecord($currencies);
        return redirect()->route('currencies.index');
    }


    public function update(CurrencyRequest $request, $id): RedirectResponse
    {
        $validatedData = $request->validated();

        $currencies = $this->loadJsonData();
        $newRecord = [
            'id' => $id,
            'name' => $validatedData['name'],
        ];

        $updatedArray = array_map(function ($item) use ($id, $newRecord) {
            if ($item['id'] == $id) {
                foreach ($newRecord as $key => $value) {
                    $item[$key] = $value;
                }
            }
            return $item;
        }, $currencies);
        $this->updateCurrencyRecord($updatedArray); //update records
        return redirect()->route('currencies.index');
    }

    public function destroy($id): RedirectResponse
    {
        $currencies = $this->loadJsonData();

        $data = array_filter($currencies, function ($record) use ($id) {
            return $record['id'] != $id;
        });
        $this->updateCurrencyRecord($data);
        return redirect()->route('currencies.index');
    }

    private function loadJsonData()
    {
        $filePath = 'data/currencies.json';
        $jsonContent = Storage::get($filePath);
        $dataArray = json_decode($jsonContent, true);
        return $dataArray;
    }

    private function updateCurrencyRecord($data)
    {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);

        try {
            Storage::put('data/currencies.json', $jsonData);
        } catch (\Exception $e) {
            // Handle exceptions
            echo ('Failed to write JSON file: ' . $e->getMessage());
        }
    }
}
