@extends('layouts.backend')

@section('content')

    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Reports</div>

                    <div class="card-body">
                        <a href="{{ url('/admin/reports/create') }}" class="btn btn-success btn-sm" title="Add New Report">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>
                        @if(false)
                            {!! Form::open(['method' => 'GET', 'url' => '/admin/reports', 'class' => 'form-inline my-2 my-lg-0 float-right', 'role' => 'search'])  !!}
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                                <span class="input-group-append">
                                <button class="btn btn-secondary" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                            {!! Form::close() !!}

                        @endif
                        <br/>
                        <br/>
                        <table border="0" cellspacing="5" cellpadding="5">
                            <tbody><tr>
                                <td>Просроченные:</td>
                                <td>
                                    <select name="overdue"  class="form-control" id="overdue">
                                        <option value="*">Выбирать..</option>
                                        <option value="0">Все</option>
                                        <option value="3">3 дня </option>
                                        <option value="5">5 дней </option>
                                        <option value="10">10 дней </option>
                                        <option value="15">15 дней </option>
                                        <option value="30">30 дней </option>
                                        <option value="45">45 дней </option>
                                    </select>
                                    <input id="myFilterDate" type="hidden" data-ison="false" name="" value="">

                                </td>
                            </tr>
                            <tr>
                                <td>Заканчивающиеся:</td>
                                <td>
                                    <select name="ending"  class="form-control" id="ending">
                                        <option value="*">Выбирать..</option>
                                        <option value="1">1 день</option>
                                        <option value="3">2 дня </option>
                                        <option value="3">3 дня </option>
                                        <option value="5">5 дней </option>
                                        <option value="7">7 дней </option>
                                        <option value="10">10 дней </option>
                                        <option value="15">15 дней </option>
                                        <option value="30">30 дней </option>
                                        <option value="45">45 дней </option>
                                    </select>
                                    <input id="myEndingDate" type="hidden" data-ison="false" name="" value="">
                                </td>
                            </tr>
                            </tbody></table>
                        <div class="table-responsive">
                            <table id="example"  class="display nowrap table table-striped table-bordered" style="width:100%">
                                <thead>
                                <tr>
                                    <th>#</th><th>Наименование </th><th>Дата добавления </th><th>Дата окончания </th><th>Контактное лицо </th><th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($reports as $item)
                                    <tr>
                                        <td>{{  $item->id }}</td>
                                        <td>{{ $item->name }}</td><td>{{ $item->start_date }}</td>
                                        <td>{{ $item->end_date }}</td>
                                        <td>{{ $item->contact_person }}</td>
                                        <td>
                                            <a href="{{ url('/admin/reports/' . $item->id) }}" title="View Report"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                                            <a href="{{ url('/admin/reports/' . $item->id . '/edit') }}" title="Edit Report"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'url' => ['/admin/reports', $item->id],
                                                'style' => 'display:inline'
                                            ]) !!}
                                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                                    'type' => 'submit',
                                                    'class' => 'btn btn-danger btn-sm',
                                                    'title' => 'Delete Report',
                                                    'onclick'=>'return confirm("Confirm delete?")'
                                            )) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>#</th><th>Наименование </th><th>Дата добавления </th><th>Дата окончания </th><th>Контактное лицо </th><th>Actions</th>
                                </tr>
                                </tfoot>
                            </table>
                            @if(false)
                                <table class="table table-borderless">
                                    <thead>
                                        <tr>
                                            <th>#</th><th>Name</th><th>Start Date</th><th>End Date</th><th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($reports as $item)
                                        @php
                                            $overClass = $item->end_date && $item->end_date >= now() ? 'red' : 'blue' ;
                                        @endphp
                                        <tr class="{{$overClass}}">
                                            <td>{{  $item->id }}</td>
                                            <td>{{ $item->name }}</td><td>{{ $item->start_date }}</td><td>{{ $item->end_date }}</td>
                                            <td>
                                                <a href="{{ url('/admin/reports/' . $item->id) }}" title="View Report"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                                                <a href="{{ url('/admin/reports/' . $item->id . '/edit') }}" title="Edit Report"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'url' => ['/admin/reports', $item->id],
                                                    'style' => 'display:inline'
                                                ]) !!}
                                                    {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                                            'type' => 'submit',
                                                            'class' => 'btn btn-danger btn-sm',
                                                            'title' => 'Delete Report',
                                                            'onclick'=>'return confirm("Confirm delete?")'
                                                    )) !!}
                                                {!! Form::close() !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="pagination-wrapper"> {!! $reports->appends(['search' => Request::get('search')])->render() !!} </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')

    <style>
        body {
            font-family: sans-serif !important;
        }
        table tr.red {
            background-color: #ff00008f !important;
            color: white;
            font-weight: bold;
        }
        table tr.blue {
            background-color: #64c249 !important ;
            color: white;
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/datetime/1.1.2/css/dataTables.dateTime.min.css">
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/datetime/1.1.2/js/dataTables.dateTime.min.js"></script>

    <script type="text/javascript" class="init">
        var minDate, maxDate, Overdue;
        const currentDate = moment().format('YYYY-MM-DD');
        // Custom filtering function which will search data in column four between two values
        $.fn.dataTable.ext.search.push(
            function( settings, data, dataIndex ) {

                var verDate = $('#myFilterDate').val(),
                    overIsOn = $('#myFilterDate').data('ison'),
                    endingDate = $('#myEndingDate').val(),
                    endingIsOn = $('#myEndingDate').data('ison'),
                    date = data[3];
                if (
                    (verDate == currentDate && verDate > date && overIsOn) ||
                    (verDate <= date && currentDate > date && overIsOn) ||
                    (currentDate <= date &&  endingDate >= date && endingIsOn) ||
                    (!overIsOn && !endingIsOn)
                ) {
                    return true;
                }
                return false;
            }
        );

        $(document).ready(function() {
            minDate = new DateTime($('#min'), {
                format: 'YYYY-MM-DD'
            });
            var overdueDays  = $('#overdue').val();
            if (overdueDays !== '') {
                if (overdueDays == 0) {var momentText = 'months', numb =  overdueDays ;}
                else  {var momentText = 'days', numb = overdueDays;}
                Overdue = moment().subtract(numb, momentText).format('YYYY-MM-DD');
            }



            maxDate = new DateTime($('#max'), {
                format: 'YYYY-MM-DD'
            });

            var table = $('#example').DataTable({
               "pageLength": 10,
                "stripeClasses": [],
                createdRow: function (row, data, index) {
                    if (data[3] <= moment().format("YYYY-MM-DD")) {
                        $(row).addClass('red');
                    } else {
                        $(row).addClass('blue');

                    }
                }
            });

            $('#min, #max, #overdue, #ending').on('change', function () {
                var overdueDays  = $('#overdue').val();
                var ending  = $('#ending').val();

                if (overdueDays != '*') {

                    if (overdueDays == 0)
                        $('#myFilterDate').val(currentDate), $('#myFilterDate').data('ison',true);
                    else if(overdueDays > 0)
                        $('#myFilterDate').val(moment().subtract(overdueDays, 'days').format('YYYY-MM-DD')), $('#myFilterDate').data('ison',true);

                } else {
                    $('#myFilterDate').val(''), $('#myFilterDate').data('ison',false);

                }

                if (ending != '*') {

                    $('#myEndingDate').val(moment().add(ending, 'days').format('YYYY-MM-DD'));
                    $('#myEndingDate').data('ison',true);

                } else  {
                    $('#myEndingDate').val(''), $('#myEndingDate').data('ison',false);

                }



                table.draw();
            });
        });

    </script>


@endsection
