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

        <div class="fs-2 mb-2">{{ $response['name'] }}</div>
        <div class="row">
            <div class="col-6">
                <table id="appointmentsServicesTable" class="table table-striped pt-1" style="">
                    <thead>
                    <tr>
                        <th>Услуги из пакетных назначений</th>
                        <th style="width: 21px;"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($responseAppointmentsServices as $serviceId => $service)
                    <tr id = "{{ $serviceId }}" class="@if(in_array($serviceId, $response['batchAppointmentsServices'])) selected  @endif">
                        <td>{{ $service }}</td>
                        <td data-order="{{in_array($serviceId, $response['batchAppointmentsServices'])}}"></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <table id="standardsServicesTable" class="table table-striped pt-1">
                    <thead>
                    <tr>
                        <th>Услуги из приказов минздрава</th>
                        <th style="width: 21px;"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($responseStandardsServices as $serviceId => $service)
                    <tr id = "{{ $serviceId }}" class="@if(in_array($serviceId, $response['generalStandardsServices'])) selected  @endif">
                        <td>{{ $service }}</td>
                        <td data-order="{{in_array($serviceId, $response['generalStandardsServices'])}}"></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>


        <form class="" id="serviceForm" action="{{ route('comparison.update', $response['id']) }}" method="POST">
            @csrf
            @method('patch')
            <input type="hidden" id="appointmentsServices" name="appointmentsServices" value="">
            <input type="hidden" id="standardsServices" name="standardsServices" value="">
            <div class="d-flex mt-3 gap-2 justify-content-end">
                <a href="javascript:history.back()" class="btn btn-primary">Отмена</a>
                <button class="btn btn-primary" type="submit">Сохранить</button>
            </div>
        </form>


        <script>
            $(document).ready(function () {

                const params = {
                    order: [[1, 'desc']],
                    processing: true,
                    columnDefs: [{
                        className: 'select-checkbox',
                        targets: 1
                    }],
                    select: {
                        style: 'multi'
                    },
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
                    scrollY: '50vh',
                    scrollCollapse: true,
                    paging: false,
                    dom: "<'row'<'col-12'f>>t",
                };

                const $appointmentsServicesTable = $('#appointmentsServicesTable').DataTable(params);
                const $standardsServicesTable = $('#standardsServicesTable').DataTable(params);

                $appointmentsServicesTable.rows(".selected").select();
                $standardsServicesTable.rows(".selected").select();

                $( "#serviceForm" ).submit(function( event ) {

                    let selectedAppointmentsServicesRows = $appointmentsServicesTable.rows({selected: true});
                    let selectedStandardsServicesRows = $standardsServicesTable.rows({selected: true});

                    let selectedAppointmentsServices = [];

                    selectedAppointmentsServicesRows.every( function ( rowIdx, tableLoop, rowLoop ) {
                        selectedAppointmentsServices.push(this.id());
                    } );

                    let selectedStandardsServices = [];
                    selectedStandardsServicesRows.every( function ( rowIdx, tableLoop, rowLoop ) {
                        selectedStandardsServices.push(this.id());
                    } );

                    $('#appointmentsServices').val(selectedAppointmentsServices.join(','));
                    $('#standardsServices').val(selectedStandardsServices.join(','));


                });
            });
        </script>

        @endisset
    </div>
</div>
@endsection