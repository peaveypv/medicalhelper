@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif()
            @isset($response)
            @foreach($response as $responseData)
            <div class="alert alert-success">
                <ul>
                    <li>
                        <div>{{ $responseData['messageToDisplay'] }}</div>
                        <div>Файл: {{ $responseData['fileName'] }}</div>
                        <div>Строк прочитано: {{ $responseData['rowsReaded'] }}</div>
                        <div>Строк загружено: {{ $responseData['rowsUploaded'] }}</div>
                        @if($responseData['rowsWithBrokenBirthdayDate'])
                        <div class="text-danger">
                            Номера строк с некорректной датой рождения:
                            @foreach ($responseData['rowsWithBrokenBirthdayDate'] as $value)
                            {{ $loop->first ? '' : ', ' }}
                            <span class="nice">{{ $value }}</span>
                            @endforeach
                        </div>
                        @endif()
                        @if($responseData['rowsWithBrokenTreatDate'])
                        <div class="text-danger">Номера строк с некорректной датой приема:
                            @foreach ($responseData['rowsWithBrokenTreatDate'] as $value)
                            {{ $loop->first ? '' : ', ' }}
                            <span class="nice">{{ $value }}</span>
                            @endforeach
                        </div>
                        @endif()

                    </li>
                </ul>
            </div>
            @endforeach
            @endisset
            <form class="" action="{{ route('upload.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="formFileMultiple" class="form-label">Загрузка данных для анализа</label>
                    <input class="form-control" type="file" name="file" id="formFileMultiple" required>
                </div>
                <div class="mb-3">
                    <button class="btn btn-primary" type="submit">Загрузить</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection