<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AnalysisController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        return view('analysis');
    }

    public function store(Request $request)
    {
        $params = $request->validate([
            'dateBegin' => 'required|date',
            'dateEnd' => 'required|date',
        ]);

        $response = Http::withHeaders(['XApiKey' => 'f340bbdf-77ca-4448-bc03-092e72dd8803'])->get('https://api.zub.ru/dtl23api/GetDetailedInfo', $params);

        if ($response->ok()) {
            $response = $response->json();
            $data = compact('response','params');
            return view('analysis', $data);
        } else {
            return back()->withErrors(['msg' => $response->body()]);
        }
    }
}
