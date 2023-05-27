@extends('layouts.app')

@section('content')
<div class="mx-5">
    @if(isset($diseaseCodes) && isset($doctorPositions))
    <form class="dashboard-form" action="{{ route('dashboard.store') }}" method="POST">
        @csrf

        <div class="d-flex justify-content-center align-items-center gap-3">
            <div class="text-nowrap">
                Дата начала
            </div>
            <div>
            <input class="form-control" type="date" name="dateBegin"
                   @isset($params)value="{{ $params['dateBegin'] }}" @endisset id="dateBegin" required>
            </div>
            <div class="text-nowrap">
                Дата окончания
            </div>
            <div>
                <input class="form-control" type="date" name="dateEnd"
                   @isset($params)value="{{ $params['dateEnd'] }}" @endisset id="dateEnd" required>
            </div>

            <div>
            <select class="form-select" aria-label="" name="mkbCode">
                <option value="" selected>Выберите диагноз</option>
                @foreach($diseaseCodes as $code)
                <option value="{{ $code['mkb']  }}">{{ $code['diagnosis'] }}</option>
                @endforeach
            </select>
            </div>

            <div>
            <select style="width:230px" class="form-select" aria-label="" name="doctorPostion">
                <option value="" selected>Выберите специальность</option>
                @foreach($doctorPositions as $key => $position)
                <option value="{{ $key }}">{{ $position }}</option>
                @endforeach
            </select>
            </div>

            <div>
                <button class="btn btn-primary" type="submit">Показать</button>
            </div>

        </div>
    </form>
    @endif

    @isset($response)
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="d-flex mt-3 gap-4">
        <div class="w-50">
            <table class="table text-center">
                <tr><td class="bg-success-subtle" colspan="5">Всего приемов: <b>{{ $response['totalTreatmentsCount'] }}</b></td></tr>
                <tr><td colspan="4">Приемы где есть стандарты оказания помощи: <b>{{ $response['treatmentsWithStandardsCount'] }}</b></td><td class="bg-body-secondary">Приемы для которых нет стандарта: <b>{{ $response['treatmentsWithoutStandardsCount'] }}</b></td></tr>
                <tr><td class="bg-success text-white">Все назначения соответсвуют стандарту: <b>{{ $response['treatmentsStatusAllInStandards'] }}</b></td><td class="bg-info fw-bold">Назначения частично соответствуют стандарту: <b>{{ $response['treatmentsStatusPartlyInStandards'] }}</b></td><td class="bg-danger text-white">Все назначения не соответсвуют стандарту: <b>{{ $response['treatmentsStatusNoneInStandards'] }}</b></td><td>Нет назначения: <b>{{ $response['treatmentsStatusHasNoReferrals'] }}</b></td><td></td></tr>
            </table>

            <div>
                <div  class="mt-2">
                    <canvas id="treatmentsChart"></canvas>
                </div>

                <script>
                    const ctx = document.getElementById('treatmentsChart');
                    var chart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: [
                                'Все назначения не соответсвуют стандарту',
                                'Все назначения соответсвуют стандарту',
                                'Назначения частично соответствуют стандарту',
                                'Приемы для которых нет стандарта'
                            ],
                            datasets: [{
                                label: 'Приемы',
                                data: [300, 50, 100, 100],
                                backgroundColor: [
                                    '#f4b184',
                                    '#bae190',
                                    '#bdd7ee',
                                    '#d9d9d9'
                                ],
                                hoverOffset: 4
                            }]
                        }
                    });
                    chart.canvas.parentNode.style.height = '500px';
                    chart.canvas.parentNode.style.width = '500px'
                </script>
            </div>
        </div>

        <div class="w-50">
            <table class="table text-center">
                <tr><td class="bg-success-subtle" colspan="5">Всего назначений: <b>{{ $response['totalReferralsCount'] }}</b></td></tr>
                <tr><td colspan="4">Назначения со стандартами: <b>{{ $response['referralsCountWithStandards'] }}</b></td><td class="bg-body-secondary">Назначения без стандарта: <b>{{ $response['referralsCountWithoutStandards'] }}</b></td></tr>
                <tr><td class="bg-success text-white">Назначения в приемах сопоставленные со стандартами, кол-во, обязательные: <b>{{ $response['referralsHasComparedNecessary'] }}</b></td><td class="bg-info fw-bold">Назначения в приемах сопоставленные со стандартами, кол-во, по назначению: <b>{{ $response['referralsHasComparedOptional'] }}</b></td><td class="bg-danger text-white">Назначения в приемах сопоставленных со стандартами, кол-во, не входит в стандарт: <b>{{ $response['referralsOutsideStandards'] }}</b></td><td></td><td></td></tr>
            </table>
            <div class="mt-2" >
                <canvas id="referralsChart"></canvas>
            </div>

            <script>
                const ctx2 = document.getElementById('referralsChart');
                var chart2 = new Chart(ctx2, {
                    type: 'pie',
                    data: {
                        labels: [
                            'Назначения в приемах сопоставленных со стандартами, кол-во, не входит в стандарт',
                            'Назначения в приемах сопоставленные со стандартами, кол-во, обязательные',
                            'Назначения в приемах сопоставленные со стандартами, кол-во, по назначению'
                        ],
                        datasets: [{
                            label: 'Назначения',
                            data: [300, 50, 100],
                            backgroundColor: [
                                '#f4b184',
                                '#bae190',
                                '#bdd7ee',
                            ],
                            hoverOffset: 4
                        }]
                    }
                });

                chart2.canvas.parentNode.style.height = '500px';
                chart2.canvas.parentNode.style.width = '500px'
            </script>
        </div>




    </div>

    @endisset
</div>
@endsection