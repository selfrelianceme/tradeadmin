@extends('adminamazing::teamplate')

@section('pageTitle', 'Торги / движение очереди')
@section('content')
    <style type="text/css">
        .item{
            border:1px solid #f6f6f6;
            min-width: 150px;
        }
        .tbl_content{
            overflow: auto;
        }
        .border-top{
            border-top: 1px solid #dee2e6!important;
        }
        .item:hover {
            background-color: #f2f7f8;
        }
    </style>
    @push('scripts')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
        <script type="text/javascript">
            $(function () {
                var dpDate = new Date();
                dpDate.setHours(dpDate.getHours() - 2);
                $('#datetimepicker5').datetimepicker({
                    keepOpen: true,
                    defaultDate: dpDate,
                    format:'YYYY-MM-DD HH:mm:00',
                });
            });
        </script>
    @endpush
    <div class="row">
        <!-- column -->
        <div class="col-12">
            <div class="card">
                <div class="card-block">
                    <h4 class="card-title">Движение очереди (условие за 1 минуту: {{$calc_queue['cnt_app_one']}} заявка каждые {{$calc_queue['seconds_delay']}} секунд, всего: {{($calc_queue['cnt_app_minute']*$calc_queue['cnt_app_one'])}} заявок)</h4>
                    
                    <div class="row">
                        <div class="col-12 d-flex tbl_content">
                                @foreach($stat_consolidation as $row)
                                    <div class="item text-center d-flex flex-wrap">
                                        <div class="w-50 p-2">L: {{$row['left_colom']}}</div>
                                        <div class="w-50 p-2">R: {{$row['right_colom']}}</div>
                                        
                                        <div class="w-100 p-2">Всего: <b>{{$row['total']}}</b></div>
                                        <div class="w-100 p-2 border-top">Время: {{substr($row['group_date'], -5)}}</div>
                                    </div>
                                @endforeach
                        </div>
                    </div>
                    <hr/>
                    <div class="table-responsive">
                        <table class="table table-hover no-wrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Доступно с</th>
                                    <th>Процент</th>
                                    <th>Создан</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($history_percents as $row)
                                    <tr style="background-color: #{{ ($row->id == $avalible_percent->id)?'1c68ff54':null  }}">
                                        <td>{{$row->id}}</td>
                                        <td>{{$row->avalible_at->diffForHumans()}}</td>
                                        <td><span class="text-success text-semibold">{{$row->min_percent}}%</span> - <span class="text-success text-semibold">{{$row->max_percent}}%</span></td>
                                        <td>{{$row->created_at->diffForHumans()}}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    <nav aria-label="Page navigation example" class="m-t-0">
                        {{ $history_percents->links('vendor.pagination.bootstrap-4') }}
                    </nav>


                    <div class="card">
                        <div class="card-block">
                            <h4 class="card-title">Добавление нового процента</h4>
                            @if(Session::has('error'))
                                <div class="alert alert-danger alert-rounded">{!!Session::get('error')!!}</div>
                            @endif  
                            @if(Session::has('success'))
                                <div class="alert alert-success alert-rounded">{!!Session::get('success')!!}</div>
                            @endif          
                            <form class="form" method="POST" action="{{route('AdminTradeAdminStore')}}">
                                
                                <div class="form-group row {{ $errors->has('avalible_at') ? ' error' : '' }}">
                                    <label for="avalible_at" class="col-2 col-form-label">Доступен с</label>
                                    <div class="col-10">
                                        <input type="text" value="{{old('avalible_at')}}" name="avalible_at" class="form-control datetimepicker-input" id="datetimepicker5" data-toggle="datetimepicker" data-target="#datetimepicker5"/>                           
                                    </div>
                                    
                                </div>

                                <div class="form-group row {{ $errors->has('min_percent') ? ' error' : '' }}">
                                    <label for="min_percent" class="col-2 col-form-label">Минимальный процент</label>
                                    <div class="col-10">
                                        <select class="custom-select col-12" id="inlineFormCustomSelect" name="min_percent" id="min_percent">
                                            <option selected="" value="0">Выбрать...</option>
                                            @for($i=0.15;$i<5;$i+=0.01)
                                                <option {{($i==old('min_percent'))?'selected':NULL}}  value="{{$i}}">{{$i}}%</option>
                                            @endfor
                                        </select>
                                        @if ($errors->has('min_percent'))
                                            <div class="help-block"><ul role="alert"><li>{{ $errors->first('min_percent') }}</li></ul></div>
                                        @endif                                  
                                    </div>
                                </div>

                                <div class="form-group row {{ $errors->has('max_percent') ? ' error' : '' }}">
                                    <label for="max_percent" class="col-2 col-form-label">Максимальный процент</label>
                                    <div class="col-10">
                                        <select class="custom-select col-12" id="inlineFormCustomSelect" name="max_percent" id="max_percent">
                                            <option selected="" value="0">Выбрать...</option>
                                            @for($i=0.15;$i<5;$i+=0.01)
                                                <option {{($i==old('max_percent'))?'selected':NULL}}  value="{{$i}}">{{$i}}%</option>
                                            @endfor
                                        </select>
                                        @if ($errors->has('max_percent'))
                                            <div class="help-block"><ul role="alert"><li>{{ $errors->first('max_percent') }}</li></ul></div>
                                        @endif                                  
                                    </div>
                                </div>

                                <div class="form-group m-b-0">
                                    <div class="offset-sm-2 col-sm-9">
                                        <button type="submit" class="btn btn-info waves-effect waves-light m-t-10">Добавить процент</button>
                                    </div>
                                </div>
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- column -->
    </div>
@endsection

