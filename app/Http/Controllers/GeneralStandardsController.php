<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use function Termwind\ValueObjects\my;

class GeneralStandardsController extends Controller
{
    public const SCHEDULER_CACHE_TIME = 300;
    public const CONNECTION_TIME_OUT = 180;

    public function __construct()
    {
        $this->middleware('auth');
        ini_set('max_execution_time', self::CONNECTION_TIME_OUT);
    }

    public function index($id = 1)
    {
        $response = Http::connectTimeout(self::CONNECTION_TIME_OUT)->timeout(self::CONNECTION_TIME_OUT)->withHeaders(['XApiKey' => env('API_KEY')])->get(env('API_URL').'/GetGeneralStandards', [
            'pageNumber' => $id,
            'pageSize' => 10
        ]);

        if ($response->ok()) {
            $response = $response->json();

            if($response) {
                $data = compact('response');
                return view('generalStandards', $data);
            }

        }
        return view('generalStandards');
    }


}
