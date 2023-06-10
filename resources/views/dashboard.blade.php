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
                       @if($params) value="{{ $params['dateBegin'] }}" @else value="{{ date('Y-m-01') }}" @endif
                       id="dateBegin" required>
            </div>
            <div class="text-nowrap">
                Дата окончания
            </div>
            <div>
                <input class="form-control" type="date" name="dateEnd"
                       @if($params) value="{{ $params['dateEnd'] }}" @else value="{{ date('Y-m-d') }}" @endif
                       id="dateEnd" required>
            </div>

            <div>
                <select class="form-select" aria-label="" name="mkbCode">
                    <option value="">Выберите диагноз</option>
                    @foreach($diseaseCodes as $code)
                    <option value="{{ $code['mkb']  }}" @if(isset($params) && $code[
                    'mkb'] == $params['mkbCode']) selected @endif>{{ $code['mkb'] }} - {{ $code['diagnosis'] }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select style="width:230px" class="form-select" aria-label="" name="doctorPostion">
                    <option value="">Выберите должность</option>
                    @foreach($doctorPositions as $key => $position)
                    <option value="{{ $key }}" @if(isset($params) && $params[
                    'doctorPostion'] !== null && $key == $params['doctorPostion']) selected @endif>{{ $position
                    }}</option>
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

    <style>
        svg {
            width: 100%;
            height: 100%;
        }

        path.slice {
            stroke-width: 2px;
        }

        polyline {
            opacity: .3;
            stroke: black;
            stroke-width: 2px;
            fill: none;
        }

        @media (max-width: 1768px)
        {
            .graphs-container {
                flex-wrap: wrap;
            }
        }

    </style>
    <script src="https://d3js.org/d3.v3.min.js"></script>

    <div class="graphs-container d-flex mt-4 gap-7 p-4 ">
        <div class="">
            <div class="text-primary-emphasis fs-2 fw-bold">ПРИЕМЫ</div>
            <div class="bg-white text-primary-emphasis p-3 graph-container">
                <div class="container">
                    <div class="row">
                        Количество
                    </div>
                    <div class="row justify-content-center fs-1 fw-bold">
                        {{ $response['totalTreatmentsCount'] }}
                    </div>
                    <div class="row justify-content-center border-bottom border-dark-subtle">
                        Всего
                    </div>
                    <div class="row">
                        <div class="col-10 text-center border-end border-dark-subtle">
                            <div class="fs-2 fw-bold">{{ $response['treatmentsWithStandardsCount'] }}
                                <span class="fs-5 text-success-emphasis">{{ round(($response['treatmentsWithStandardsPercent'] * 100), 1) }}%</span>
                            </div>
                            <div>Есть стандарт оказания помощи</div>
                            <div class="row border-top border-dark-subtle" style="min-height: 135px">

                                <div class="col-3 border-end border-dark-subtle p-2">
                                    <div class="bg-success h-100">
                                        <div class="fs-2 fw-bold">{{ $response['treatmentsStatusAllInStandards'] }}
                                            <span class="fs-5 text-success-emphasis">{{ round(($response['treatmentsStatusAllInStandardsPercent'] * 100), 1) }}%</span>

                                        </div>
                                        <div>Все назначения соответствуют стандартам</div>
                                    </div>
                                </div>

                                <div class="col-3 border-end border-dark-subtle p-2">
                                    <div class="bg-info h-100">
                                        <div class="fs-2 fw-bold">{{ $response['treatmentsStatusPartlyInStandards'] }}
                                            <span class="fs-5 text-success-emphasis">{{ round(($response['treatmentsStatusPartlyInStandardsPercent'] * 100), 1) }}%</span>

                                        </div>
                                        <div>Назначения частично соответствуют</div>
                                    </div>
                                </div>

                                <div class="col-3 border-end border-dark-subtle p-2">
                                    <div class="bg-danger h-100">
                                        <div class="fs-2 fw-bold">{{ $response['treatmentsStatusNoneInStandards'] }}
                                            <span class="fs-5 text-success-emphasis">{{ round(($response['treatmentsStatusNoneInStandardsPercent'] * 100), 1) }}%</span>
                                        </div>
                                        <div>Все назначения не соответствуют</div>
                                    </div>
                                </div>

                                <div class="col-3 p-2">
                                    <div class="">
                                        <div class="fs-2 fw-bold">{{ $response['treatmentsStatusHasNoReferrals'] }}
                                            @if($response['treatmentsStatusHasNoReferrals'] > 0)
                                            <span class="fs-5 text-success-emphasis">{{ round(($response['treatmentsStatusHasNoReferralsPercent'] * 100), 1) }}%</span>
                                            @endif
                                        </div>
                                        <div>Нет назначений</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-2 text-center p-2">
                            <div class="bg-body-tertiary h-100">
                                <div class="fs-2 fw-bold">{{ $response['treatmentsWithoutStandardsCount'] }}
                                    <span class="fs-5 text-success-emphasis">{{ round(($response['treatmentsWithoutStandardsPercent'] * 100), 1) }}%</span>
                                </div>
                                <div>Нет стандарта</div>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class = "col-7 graphTreatments">

                        </div>
                        <div class = "col-5 justify-content-center">
                            <div class="bg-light p-2 align-middle text-black">
                                <div><div class="color-block bg-success"></div> a: Все назначения соответствуют стандартам</div>
                                <div><div class="color-block bg-info"></div> b:  Назначения частично соответствуют</div>
                                <div><div class="color-block bg-danger"></div> c: Все назначения не соответствуют</div>
                                <div><div class="color-block" style="background:#ffc000 "></div> d: Нет назначений</div>
                                <div><div class="color-block bg-body-tertiary"></div> e: Нет стандарта</div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

            @if($response['totalTreatmentsCount'])
            <script>

                var svg = d3.select(".graphTreatments")
                    .append("svg")
                    .append("g")

                svg.append("g")
                    .attr("class", "slices");
                svg.append("g")
                    .attr("class", "labels");
                svg.append("g")
                    .attr("class", "lines");

                var width = 400,
                    height = 250,
                    radius = Math.min(width, height) / 2;

                var pie = d3.layout.pie()
                    .sort(null)
                    .value(function (d) {
                        return d.value;
                    });

                var arc = d3.svg.arc()
                    .outerRadius(radius * 0.8)
                    .innerRadius(radius * 0.4);

                var outerArc = d3.svg.arc()
                    .innerRadius(radius * 0.9)
                    .outerRadius(radius * 0.9);

                svg.attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

                var key = function (d) {
                    return d.data.label;
                };

                var color = d3.scale.ordinal()
                    .domain([
                        'a: '+{{ $response['treatmentsStatusAllInStandards'] }}+'; '+{{ round(($response['treatmentsStatusAllInStandardsPercent'] * 100), 1) }}+'%'],
                'b: '+{{ $response['treatmentsStatusPartlyInStandards'] }}+'; '+{{ round(($response['treatmentsStatusPartlyInStandardsPercent'] * 100), 1) }}+'%',
                'c: '+{{ $response['treatmentsStatusNoneInStandards'] }}+'; '+{{ round(($response['treatmentsStatusNoneInStandardsPercent'] * 100), 1) }}+'%',
                'd: '+{{ $response['treatmentsStatusHasNoReferrals'] }}+'; '+{{ round(($response['treatmentsStatusHasNoReferralsPercent'] * 100), 1) }}+'%',
                'e: '+{{ $response['treatmentsWithoutStandardsCount'] }}+'; '+{{ round(($response['treatmentsWithoutStandardsPercent'] * 100), 1) }}+'%',
                )
                    .range(["#adf7b6", "#ffee93", "#ffc09f", "#ffc000", "#e8eaee"]);


                var labels = [
                    {
                        label: 'a: '+{{ $response['treatmentsStatusAllInStandards'] }}+'; '+{{ round(($response['treatmentsStatusAllInStandardsPercent'] * 100), 1) }}+'%',
                        value: {{ $response['treatmentsStatusAllInStandards'] }},
                    }, {
                        label: 'b: '+{{ $response['treatmentsStatusPartlyInStandards'] }}+'; '+{{ round(($response['treatmentsStatusPartlyInStandardsPercent'] * 100), 1) }}+'%',
                        value: {{ $response['treatmentsStatusPartlyInStandards'] }},
                    }, {
                        label: 'c: '+{{ $response['treatmentsStatusNoneInStandards'] }}+'; '+{{ round(($response['treatmentsStatusNoneInStandardsPercent'] * 100), 1) }}+'%',
                        value: {{ $response['treatmentsStatusNoneInStandards'] }},
                    }, {
                        label: 'd: '+{{ $response['treatmentsStatusHasNoReferrals'] }}+'; '+{{ round(($response['treatmentsStatusHasNoReferralsPercent'] * 100), 1) }}+'%',
                        value: {{ $response['treatmentsStatusHasNoReferrals'] }},
                    }
                    , {
                        label: 'e: '+{{ $response['treatmentsWithoutStandardsCount'] }}+'; '+{{ round(($response['treatmentsWithoutStandardsPercent'] * 100), 1) }}+'%',
                        value: {{ $response['treatmentsWithoutStandardsCount'] }},
                    }
                ];
                change(labels);




                function change(data) {

                    /* ------- PIE SLICES -------*/
                    var slice = svg.select(".slices").selectAll("path.slice")
                        .data(pie(data), key);

                    slice.enter()
                        .insert("path")
                        .style("fill", function (d) {
                            return color(d.data.label);
                        })
                        .attr("class", "slice");

                    slice
                        .transition().duration(1000)
                        .attrTween("d", function (d) {
                            this._current = this._current || d;
                            var interpolate = d3.interpolate(this._current, d);
                            this._current = interpolate(0);
                            return function (t) {
                                return arc(interpolate(t));
                            };
                        })

                    slice.exit()
                        .remove();

                    /* ------- TEXT LABELS -------*/

                    var text = svg.select(".labels").selectAll("text")
                        .data(pie(data), key);

                    text.enter()
                        .append("text")
                        .attr("dy", ".35em")
                        .text(function (d) {
                            return d.data.label;
                        });

                    function midAngle(d) {
                        return d.startAngle + (d.endAngle - d.startAngle) / 2;
                    }

                    text.transition().duration(1000)
                        .attrTween("transform", function (d) {
                            this._current = this._current || d;
                            var interpolate = d3.interpolate(this._current, d);
                            this._current = interpolate(0);
                            return function (t) {
                                var d2 = interpolate(t);
                                var pos = outerArc.centroid(d2);
                                pos[0] = radius * (midAngle(d2) < Math.PI ? 1 : -1);
                                return "translate(" + pos + ")";
                            };
                        })
                        .styleTween("text-anchor", function (d) {
                            this._current = this._current || d;
                            var interpolate = d3.interpolate(this._current, d);
                            this._current = interpolate(0);
                            return function (t) {
                                var d2 = interpolate(t);
                                return midAngle(d2) < Math.PI ? "start" : "end";
                            };
                        });

                    text.exit()
                        .remove();

                    /* ------- SLICE TO TEXT POLYLINES -------*/

                    var polyline = svg.select(".lines").selectAll("polyline")
                        .data(pie(data), key);

                    polyline.enter()
                        .append("polyline");

                    polyline.transition().duration(1000)
                        .attrTween("points", function (d) {
                            this._current = this._current || d;
                            var interpolate = d3.interpolate(this._current, d);
                            this._current = interpolate(0);
                            return function (t) {
                                var d2 = interpolate(t);
                                var pos = outerArc.centroid(d2);
                                pos[0] = radius * 0.95 * (midAngle(d2) < Math.PI ? 1 : -1);
                                return [arc.centroid(d2), outerArc.centroid(d2), pos];
                            };
                        });

                    polyline.exit()
                        .remove();
                };

            </script>
            @endif
        </div>
        <div class="">
            <div class="text-primary-emphasis fs-2 fw-bold text-danger-emphasis">НАЗНАЧЕНИЯ</div>
            <div class="container bg-white text-primary-emphasis p-3 graph-container">
                <div class="container">
                    <div class="row">
                        Количество
                    </div>
                    <div class="row justify-content-center fs-1 fw-bold text-danger-emphasis">
                        {{ $response['totalReferralsCount'] }}
                    </div>
                    <div class="row justify-content-center border-bottom border-dark-subtle">
                        Всего
                    </div>
                    <div class="row">
                        <div class="col-10 text-center border-end border-dark-subtle">
                            <div class="fs-2 fw-bold text-danger-emphasis">{{ $response['referralsCountWithStandards']
                                }}
                                <span class="fs-5 text-success-emphasis">{{ round(($response['referralsPercentWithStandards'] * 100), 1) }}%</span>

                            </div>
                            <div>Есть стандарт оказания помощи</div>
                            <div class="row border-top border-dark-subtle" style="min-height: 135px">

                                <div class="col-4 border-end border-dark-subtle p-2">
                                    <div class="bg-success h-100">
                                        <div class="fs-2 fw-bold text-danger-emphasis">{{
                                            $response['referralsHasComparedNecessary'] }}
                                            <span class="fs-5 text-success-emphasis">{{ round(($response['referralsHasComparedNecessaryPercent'] * 100), 1) }}%</span>
                                        </div>
                                        <div>Назначения соответствуют стандарту, обязательные</div>
                                    </div>
                                </div>

                                <div class="col-4 border-end border-dark-subtle p-2">
                                    <div class="bg-secondary-subtle h-100">
                                        <div class="fs-2 fw-bold text-danger-emphasis">{{
                                            $response['referralsHasComparedOptional'] }}
                                            <span class="fs-5 text-success-emphasis">{{ round(($response['referralsHasComparedOptionalPercent'] * 100), 1) }}%</span>

                                        </div>
                                        <div>Назначения соответствуют стандарту, по показаниям</div>
                                    </div>
                                </div>

                                <div class="col-4  p-2">
                                    <div class="bg-danger h-100">
                                        <div class="fs-2 fw-bold text-danger-emphasis">{{
                                            $response['referralsOutsideStandards'] }}
                                            <span class="fs-5 text-success-emphasis">{{ round(($response['referralsOutsideStandardsPercent'] * 100), 1) }}%</span>

                                        </div>
                                        <div>Назначения не соответствуют стандарту</div>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="col-2 text-center p-2">
                            <div class="bg-body-tertiary h-100">
                                <div class="fs-2 fw-bold text-danger-emphasis">{{
                                    $response['referralsCountWithoutStandards'] }}
                                    <span class="fs-5 text-success-emphasis">{{ round(($response['referralsPercentWithoutStandards'] * 100), 1) }}%</span>

                                </div>
                                <div>Нет стандарта</div>
                            </div>
                        </div>
                    </div>


                    <div class="row align-items-center">
                        <div class = "col-7 graphReferrals">

                        </div>
                        <div class = "col-5 justify-content-center">
                            <div class="bg-light p-2 align-middle text-black">
                                <div><div class="color-block bg-success"></div> Назначения соответствуют стандарту, обязательные</div>
                                <div><div class="color-block bg-secondary-subtle"></div> Назначения соответствуют стандарту, по показаниям</div>
                                <div><div class="color-block bg-danger"></div> Назначения не соответствуют стандарту</div>
                                <div><div style="color-block" class="color-block bg-body-tertiary"></div> Нет стандарта</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            @if($response['totalReferralsCount'])

            <script>


                var svg = d3.select(".graphReferrals")
                    .append("svg")
                    .append("g")

                svg.append("g")
                    .attr("class", "slices");
                svg.append("g")
                    .attr("class", "labels");
                svg.append("g")
                    .attr("class", "lines");

                var width = 400,
                    height = 250,
                    radius = Math.min(width, height) / 2;

                var pie = d3.layout.pie()
                    .sort(null)
                    .value(function (d) {
                        return d.value;
                    });

                var arc = d3.svg.arc()
                    .outerRadius(radius * 0.8)
                    .innerRadius(radius * 0.4);

                var outerArc = d3.svg.arc()
                    .innerRadius(radius * 0.9)
                    .outerRadius(radius * 0.9);

                svg.attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

                var key = function (d) {
                    return d.data.label;
                };

                var color = d3.scale.ordinal()
                    .domain([
                        'a: ' +{{ $response['referralsHasComparedNecessary'] }}+'; '+{{ round(($response['referralsHasComparedNecessaryPercent'] * 100), 1) }}+'%',
                        'b: ' +{{ $response['referralsHasComparedOptional'] }}+'; '+{{ round(($response['referralsHasComparedOptionalPercent'] * 100), 1) }}+'%',
                        'c: ' +{{ $response['referralsOutsideStandards'] }}+'; '+{{ round(($response['referralsOutsideStandardsPercent'] * 100), 1) }}+'%',
                'd: ' +{{ $response['referralsCountWithoutStandards'] }}+'; '+{{ round(($response['referralsPercentWithoutStandards'] * 100), 1) }}+'%',
                ])
                    .range(["#adf7b6", "#BFCEE7", "#ffc09f", "#e8eaee"]);


                var labels = [
                    {
                        label: 'a: ' +{{ $response['referralsHasComparedNecessary'] }}+'; '+{{ round(($response['referralsHasComparedNecessaryPercent'] * 100), 1) }}+'%',
                    value: {{ $response['referralsHasComparedNecessary'] }},
                }, {
                    label: 'b: ' +{{ $response['referralsHasComparedOptional'] }}+'; '+{{ round(($response['referralsHasComparedOptionalPercent'] * 100), 1) }}+'%',
                        value: {{ $response['referralsHasComparedOptional'] }},
                }, {
                    label: 'c: ' +{{ $response['referralsOutsideStandards'] }}+'; '+{{ round(($response['referralsOutsideStandardsPercent'] * 100), 1) }}+'%',
                        value: {{ $response['referralsOutsideStandards'] }},
                }
                , {
                    label: 'd: ' +{{ $response['referralsCountWithoutStandards'] }}+'; '+{{ round(($response['referralsPercentWithoutStandards'] * 100), 1) }}+'%',
                        value: {{ $response['referralsCountWithoutStandards'] }},
                }
                ];
                change(labels);



            </script>
            @endif
        </div>
    </div>

    @endisset
</div>
@endsection