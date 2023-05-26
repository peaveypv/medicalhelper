<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class UploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        return view('upload');

    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'file' => 'required|file|mimes:xls,xlsx|max:28672',
        ]);

        $file = $validated['file'];

        $response = Http::withHeaders(['XApiKey' => 'f340bbdf-77ca-4448-bc03-092e72dd8803'])->attach(
            'attachment', file_get_contents($file), $file->getClientOriginalName()
        )->post('https://api.zub.ru/dtl23api/UploadFile');


        if ($response->ok()) {
            $response = $response->json();
            $data = compact('response');
            return view('upload', $data);
        } else {
            return back()->withErrors(['msg' => $response->body()]);
        }


        //todo $errors добавить в них ответ сервера
        //todo перевести ошибки на русский

    }


}
