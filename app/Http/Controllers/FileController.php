<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function createFiles()
    {
        $folder = 'data';

        $files = [
            'currencies.json' => '[{
        "id": "0",
        "name": "EGP"
    },
    {
        "id": 1,
        "name": "USD"
    }]',
            'exchange-rates.json' => '
            [
    {
        "id": 0,
        "from": {
            "id": "0",
            "name": "USD"
        },
        "to": {
            "id": 1,
            "name": "EGP"
        },
        "rate": "123"
    }
]',
            'transactions.json' => '[
        {
            "id": 0,
            "from": {
                "id": "0",
                "name": "EGP"
            },
            "to": {
                "id": "1",
                "name": "USD"
            },
            "amount": "100",
            "result": 4900
        }
    ]',
        ];

        // Loop through each file and create it in the specified folder
        foreach ($files as $filename => $contents) {
            Storage::disk('local')->put("$folder/$filename", $contents);
        }

        $navigation = '<ul>
        <li><a href="/currencies">Currencies</a></li>
        <li><a href="/exchange_rates">Exchange Rates</a></li>
        <li><a href="/amounts">Amounts</a></li>
        </ul>';

        return response('Folder and files created successfully' . $navigation, 200);
    }
}
