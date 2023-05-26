@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form class="" action="{{ route('analysis.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="dateBegin" class="form-label">Дата начала</label>
                    <input class="form-control" type="date" name="dateBegin"
                           @isset($params)value="{{ $params['dateBegin'] }}" @endisset id="dateBegin" required>

                </div>
                <div class="mb-3">
                    <label for="dateEnd" class="form-label">Дата окончания</label>
                    <input class="form-control" type="date" name="dateEnd"
                           @isset($params)value="{{ $params['dateEnd'] }}" @endisset id="dateEnd" required>

                </div>
                <div class="mb-3">
                    <button class="btn btn-primary" type="submit">Показать</button>
                </div>
            </form>
        </div>
        <div>
            @isset($response)


            <link href="https://cdn.datatables.net/v/bs5/jq-3.6.0/jszip-2.5.0/dt-1.13.4/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/b-print-2.3.6/date-1.4.1/fc-4.2.2/fh-3.3.2/r-2.4.1/rg-1.3.1/rr-1.3.3/sc-2.1.1/sb-1.4.2/sp-2.1.2/sl-1.6.2/datatables.min.css"
                  rel="stylesheet"/>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
            <script src="https://cdn.datatables.net/v/bs5/jq-3.6.0/jszip-2.5.0/dt-1.13.4/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/b-print-2.3.6/date-1.4.1/fc-4.2.2/fh-3.3.2/r-2.4.1/rg-1.3.1/rr-1.3.3/sc-2.1.1/sb-1.4.2/sp-2.1.2/sl-1.6.2/datatables.min.js"></script>


            <table id="example" class="table table-striped" style="width:100%">
                <thead>
                <tr>
                    <!--                    <th>Идентификатор записи</th>-->
                    <th nowrap>Пол пациента</th>
                    <th nowrap>Дата рождения пациента</th>
                    <th nowrap>ID пациента</th>
                    <th nowrap>Код МКБ-10</th>
                    <th>Диагноз</th>
                    <th nowrap>Дата оказания услуги</th>
                    <th>Должность</th>
                    <th>Назначения</th>
                    <!--                    <th>Дата и время загрузки данных в БД</th>-->
                </tr>
                </thead>
                <tbody>

                @foreach($response as $responseData)
                <tr>
                    <!--                    <td>{{ $responseData['id'] }}</td>-->
                    <td>{{ $responseData['sex'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($responseData['birthdayDate'])->format('d.m.Y') }}</td>
                    <td>{{ $responseData['clientId'] }}</td>
                    <td>{{ $responseData['mkb'] }}</td>
                    <td>{{ $responseData['diagnosis'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($responseData['treatDate'])->format('d.m.Y') }}</td>
                    <td>{{ $responseData['doctorPosition'] }}</td>
                    <td>{{ $responseData['referrals'] }}</td>
                    <!--                    <td>{{ $responseData['createDateTime'] }}</td>-->
                </tr>
                @endforeach


                </tbody>
            </table>

            <script>
                $(document).ready(function () {
                    var table = $('#example').DataTable({
                        // "columnDefs": [
                        //     { "visible": false, "targets": [0, 9] }
                        // ],
                        "language": {
                            "lengthMenu": "Показывать _MENU_ записей",
                            "search": "Поиск:",
                            "zeroRecords": "нет записей",
                            "paginate": {
                                "first": "Первый",
                                "last": "Последний",
                                "next": "Вперед",
                                "previous": "Назад"
                            },
                            "info": "Показ _START_ по _END_ из _TOTAL_ записей",
                            "infoEmpty": "",
                        },
                        //scrollX: true,
                        dom: 'Bfrtip',
                        buttons: [
                            //'colvis',
                            'columnsToggle',
                            'spacer',
                            'spacer',
                            {
                                extend: 'pdfHtml5',
                                //text: 'Save current page',
                                exportOptions: {
                                    columns: ':visible',
                                },
                                header: false
                            },
                            {
                                extend: 'excelHtml5',
                                //text: 'Save current page',
                                exportOptions: {
                                    columns: ':visible',
                                },
                                header: false
                            },
                            {
                                extend: 'print',
                                text: 'Печать',
                                exportOptions: {
                                    columns: ':visible',
                                },
                                header: false
                            },


                        ],
                        initComplete: function () {
                            this.api()
                                .columns()
                                .every(function () {
                                    var column = this;
                                    var select = $('<br><select class="filter-data"><option value=""></option></select>')
                                        .appendTo($(column.header()))
                                        .on('change', function () {
                                            var val = $.fn.dataTable.util.escapeRegex($(this).val());

                                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                                        });

                                    column
                                        .data()
                                        .unique()
                                        .sort()
                                        .each(function (d, j) {
                                            select.append('<option value="' + d + '">' + d + '</option>');
                                        });
                                });
                        },
                    });


                });
            </script>

            @endisset
        </div>
    </div>
</div>
@endsection
