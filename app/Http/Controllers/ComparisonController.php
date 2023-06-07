<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ComparisonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $response = Http::connectTimeout(180)->withHeaders(['XApiKey' => env('API_KEY')])->get(env('API_URL') . '/GetServiceComparison');

        if ($response->ok()) {
            $response = $response->json();

            $responseAppointmentsServices = Http::connectTimeout(180)->withHeaders(['XApiKey' => env('API_KEY')])->get(env('API_URL') . '/GetUniqueBatchAppointmentsServices');
            if ($responseAppointmentsServices->ok()) {
                $responseAppointmentsServices = $responseAppointmentsServices->json();

                $responseAppointmentsServices = array_reduce($responseAppointmentsServices, function ($result, $element) {
                    $result[$element['id']] = $element['name'];
                    return $result;
                }, []);
            }


            $responseStandardsServices = Http::connectTimeout(180)->withHeaders(['XApiKey' => env('API_KEY')])->get(env('API_URL') . '/GetUniqueGeneralStandardsServicesContoller');
            if ($responseStandardsServices->ok()) {
                $responseStandardsServices = $responseStandardsServices->json();

                $responseStandardsServices = array_reduce($responseStandardsServices, function ($result, $element) {
                    $result[$element['id']] = $element['name'];
                    return $result;
                }, []);
            }


            $data = compact('response', 'responseAppointmentsServices', 'responseStandardsServices');
            //dd($data);
            return view('comparison', $data);
        } else {
            return view('comparison');
        }
    }


    public function edit($id)
    {
        $response = Http::connectTimeout(180)->withHeaders(['XApiKey' => env('API_KEY')])->get(env('API_URL') . '/GetServiceComparison', [
            'id' => $id,
        ]);

        if ($response->ok()) {
            $response = $response->json();
            $response = array_shift($response);


            $responseAppointmentsServices = Http::connectTimeout(180)->withHeaders(['XApiKey' => env('API_KEY')])->get(env('API_URL') . '/GetUniqueBatchAppointmentsServices');
            if ($responseAppointmentsServices->ok()) {
                $responseAppointmentsServices = $responseAppointmentsServices->json();

                $responseAppointmentsServices = array_reduce($responseAppointmentsServices, function ($result, $element) {
                    $result[$element['id']] = $element['name'];
                    return $result;
                }, []);
            }


            $responseStandardsServices = Http::connectTimeout(180)->withHeaders(['XApiKey' => env('API_KEY')])->get(env('API_URL') . '/GetUniqueGeneralStandardsServicesContoller');
            if ($responseStandardsServices->ok()) {
                $responseStandardsServices = $responseStandardsServices->json();

                $responseStandardsServices = array_reduce($responseStandardsServices, function ($result, $element) {
                    $result[$element['id']] = $element['name'];
                    return $result;
                }, []);
            }


            $data = compact('response', 'responseAppointmentsServices', 'responseStandardsServices');

            return view('comparisonEdit', $data);
        }

    }

    public function update($id)
    {

        $params = request()->validate([
            'appointmentsServices' => '',
            'standardsServices' => '',
        ]);
        $current = Carbon::now();

        $response = Http::connectTimeout(180)->withHeaders(['XApiKey' => env('API_KEY')])->put(env('API_URL') . '/UpdateServiceComparison', [
            "id" => $id,
            "name" => "",
            "createDate" => $current,
            "batchAppointmentsServices" => explode(',' ,$params['appointmentsServices']),
            "generalStandardsServices" =>  explode(',' ,$params['standardsServices'])
        ]);

        return redirect()->route('comparison.index');
    }
}
