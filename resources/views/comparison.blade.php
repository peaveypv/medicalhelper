@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @isset($response)
        <link href="https://cdn.datatables.net/v/bs5/jq-3.6.0/jszip-2.5.0/dt-1.13.4/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/b-print-2.3.6/date-1.4.1/fc-4.2.2/fh-3.3.2/r-2.4.1/rg-1.3.1/rr-1.3.3/sc-2.1.1/sb-1.4.2/sp-2.1.2/sl-1.6.2/datatables.min.css"
              rel="stylesheet"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/v/bs5/jq-3.6.0/jszip-2.5.0/dt-1.13.4/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/b-print-2.3.6/date-1.4.1/fc-4.2.2/fh-3.3.2/r-2.4.1/rg-1.3.1/rr-1.3.3/sc-2.1.1/sb-1.4.2/sp-2.1.2/sl-1.6.2/datatables.min.js"></script>

        <table id="example" class="table table-striped pt-1" style="width:100%">
            <thead>
            <tr>
                <th nowrap>Название</th>
                <th nowrap>Список сопоставленных услуг из пакетных назначений</th>
                <th nowrap>Список сопоставленных услуг из приказов минздрава</th>
                <th></th>
            </tr>
            <tbody>

            @foreach($response as $responseData)
            <tr>
                <td>{{ $responseData['name'] }}</td>
                <td>
                    <ul>
                    @foreach($responseData['batchAppointmentsServices'] as $serviceId)
                        @isset($responseAppointmentsServices[$serviceId])<li>{{ $responseAppointmentsServices[$serviceId] }}</li>@endisset
                    @endforeach
                    </ul>
                </td>
                <td>
                    <ul>
                    @foreach($responseData['generalStandardsServices'] as $serviceId)
                        @isset($responseStandardsServices[$serviceId])<li>{{ $responseStandardsServices[$serviceId] }}</li>@endisset
                    @endforeach
                    </ul>
                </td>
                <td>
                    <a href="{{ route('comparison.edit', $responseData['id']) }}">Изменить</a>
            </tr>
            @endforeach
            </tbody>
            </thead>

            <div class="modal fade" id="modalEditService" tabindex="-1" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Сопоставление для: </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body ">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                            <button type="button" class="btn btn-primary">Сохранить</button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function () {
                    var table = $('#example').DataTable({
                        "stateSave": true,
                        "stateSaveCallback": function (settings, data) {
                            localStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(data));
                        },
                        "stateLoadCallback": function (settings, callback) {
                            var data = localStorage.getItem('DataTables_' + window.location.pathname);
                            callback(JSON.parse(data));
                        },
                        columnDefs: [
                            { orderable: false, targets: [1,2,3] }
                        ],
                        "language": {
                            "lengthMenu": "Показывать _MENU_ записей",
                            "search": "Поиск по названию:",
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
                        dom: "<'row mb-3'<'col-3'l><'col-6'f><'col-3 text-end'B>>tip",
                        buttons: [


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


                        ]
                    });



                });
            </script>
        @endisset
    </div>
</div>
@endsection