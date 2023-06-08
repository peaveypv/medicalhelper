<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use function Termwind\ValueObjects\my;

class GeneralStandardsController extends Controller
{
    public const SCHEDULER_CACHE_TIME = 3600;
    public const CONNECTION_TIME_OUT = 180;
    public const PAGE_SIZE = 10;

    public function __construct()
    {
        $this->middleware('auth');
        ini_set('max_execution_time', self::CONNECTION_TIME_OUT);
    }

    public function index($id = 1)
    {

        $response = Cache::remember("generalStandards_$id", self::SCHEDULER_CACHE_TIME, function () use ($id) {
            $response = Http::connectTimeout(self::CONNECTION_TIME_OUT)->timeout(self::CONNECTION_TIME_OUT)->withHeaders(['XApiKey' => env('API_KEY')])->get(env('API_URL').'/GetGeneralStandards', [
                'pageNumber' => $id,
                'pageSize' => self::PAGE_SIZE
            ]);

            if ($response->ok()) {
                $responseArray = $response->json();
                return $responseArray;
            }
        });

        if($response) {
            $pageCount = (int) ceil($response['totalItems'] / self::PAGE_SIZE);
            $data = compact('response', 'pageCount', 'id');
            return view('generalStandards', $data);
        }
        else {
            return view('generalStandards');
        }
    }


}
