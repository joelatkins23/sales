@extends('layout.main') @section('content')
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{!! session()->get('message') !!}</div> 
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div> 
@endif

<section>
    {!! Form::open(['route' => 'delivery.dayrange', 'method' => 'post']) !!}
    <div class="row mt-4">
        <div class="col-md-4 offset-md-3 ">
       
            <div class="form-group row">
                <label class="d-tc mt-2"><strong>{{trans('file.Choose Your Date')}}</strong> &nbsp;</label>
                <div class="d-tc">
                    <div class="input-group">
                        <input type="text" class="daterangepicker-field form-control" value="{{$start_date}} To {{$end_date}}" required />
                        <input type="hidden" name="start_date" value="{{$start_date}}" />
                        <input type="hidden" name="end_date" value="{{$end_date}}" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">       
            <div class="form-group row">
                <button class="btn btn-primary" type="submit">{{trans('file.submit')}}</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
    <div class="table-responsive">
        <table id="delivery-table" class="table table-striped">
            <thead>
                <tr>
                    <th class="not-exported"></th>
                    <th>Asignacion</th>
                    <th>{{trans('file.Date')}}</th>
                    <th>{{trans('file.Sale Reference')}}</th>
                    <th>{{trans('file.customer')}}</th>
                    <th>{{trans('file.Address')}}</th>
                    <th>{{trans('file.Status')}}</th>
                    <th class="not-exported">{{trans('file.action')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lims_delivery_all as $key=>$delivery)
                <?php 
                    $customer_sale = DB::table('sales')->join('customers', 'sales.customer_id', '=', 'customers.id')->where('sales.id', $delivery->sale_id)->select('sales.reference_no','customers.name')->get();
                    $create_date = explode(" ", $delivery->created_at)[0];
                    $result_customer = App\Customer::find($delivery->sale->customer_id);
                    $customer_info='';
                             
                    $customer_info = $result_customer->name;
                    if($result_customer->zone)
                        $customer_info .= ' ['.$result_customer->zone.']';
                    if($result_customer->company_name)
                        $customer_info .= ' ['.$result_customer->company_name.']';
                    if($delivery->status == 1)
                        $status = trans('file.Packing');
                    elseif($delivery->status == 2)
                        $status = trans('file.Delivering');
                    elseif($delivery->status == 4)
                        $status = trans('file.Collect');
                    else
                        $status = trans('file.Delivered');
                ?>
                <tr data-id="{{$delivery->id}}">
                    <td>{{$key}}</td>
                    @if($delivery->truck_id == '1')
                    <td>SRD520-1</td>
                    @elseif($delivery->truck_id == '2')
                    <td>WORKER 1</td>
                    @elseif($delivery->truck_id == '3')
                    <td>BEP429-1</td>
                    @elseif($delivery->truck_id == '4')
                    <td>CARGO 1</td>
                    @elseif($delivery->truck_id == '5')
                    <td>JAC-1</td>
                    @elseif($delivery->truck_id == '6')
                    <td>HKA31E</td>
                    @elseif($delivery->truck_id == '7')
                    <td>NQR36E</td>
                    @elseif($delivery->truck_id == '11')
                    <td>CON-PEDIDO</td>
                    @elseif($delivery->truck_id == '12')
                    <td>LLAMAR</td>
                    @elseif($delivery->truck_id == '13')
                    <td>CONSIGNA</td>
                    @elseif($delivery->truck_id == '14')
                    <td>BEP429-2</td>
                    @elseif($delivery->truck_id == '15')
                    <td>SRD520-2</td>
                    @elseif($delivery->truck_id == '16')
                    <td>JAC-2</td>
                    @elseif($delivery->truck_id == '18')
                    <td>WORKER 2</td>
                    @elseif($delivery->truck_id == '19')
                    <td>CARGO 2</td>
                    @elseif($delivery->truck_id == '20')
                    <td>Sobrantes CARGO</td>
                    @elseif($delivery->truck_id == '21')
                    <td>Sobrantes WORKER</td>
                    @elseif($delivery->truck_id == '22')
                    <td>Sobrantes 520</td>
                    @elseif($delivery->truck_id == '23')
                    <td>Sobrantes 429</td>
                    @elseif($delivery->truck_id == '24')
                    <td>Sobrantes JAC</td>
                    
                    @endif
                    <td>{{$create_date}}</td>
                    <td>{{ $customer_sale[0]->reference_no }}</td>
                    <td>{{ $customer_info }}</td>
                    <td>{{ $delivery->address }}</td>
                    @if($delivery->status == 1)
                    <td><div class="badge badge-info">{{$status}}</div></td>
                    @elseif($delivery->status == 2)
                    <td><div class="badge badge-primary">{{$status}}</div></td>
                    @elseif($delivery->status == 4)
                    <td><div class="badge badge-success">{{$status}}</div></td>
                    @else
                    <td><div class="badge badge-success">{{$status}}</div></td>
                    @endif
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{trans('file.action')}}
                              <span class="caret"></span>
                              <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                                <li>
                                    <button type="button" data-id="{{$delivery->id}}" class="open-EditCategoryDialog btn btn-link"><i class="fa fa-edit"></i> {{trans('file.edit')}}</button>
                                </li>
                                <li class="divider"></li>
                                {{ Form::open(['route' => ['delivery.delete', $delivery->id], 'method' => 'post'] ) }}
                                <li>
                                  <button type="submit" class="btn btn-link" onclick="return confirmDelete()"><i class="fa fa-trash"></i> {{trans('file.delete')}}</button> 
                                </li>
                                {{ Form::close() }}
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</seaction>

<!-- Modal -->
<div id="edit-delivery" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="exampleModalLabel" class="modal-title">{{trans('file.Update Delivery')}}</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => 'delivery.update', 'method' => 'post', 'files' => true]) !!}
                <?php 
                    $lims_truck_list = \App\Truck::all();
                ?>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label><strong>{{trans('file.Delivery Reference')}}</strong></label>
                        <p id="dr"></p>
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong>{{trans('file.Sale Reference')}}</strong></label>
                        <p id="sr"></p>
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong>{{trans('file.Status')}} *</strong></label>
                        <select name="status" required class="form-control selectpicker">
                            <option value="1">{{trans('file.Packing')}}</option>
                            <option value="2">{{trans('file.Delivering')}}</option>
                            <option value="3">{{trans('file.Delivered')}}</option>
                            <option value="4">{{trans('file.Collect')}}</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong>{{trans('file.Trucks')}} *</strong></label>
                        <select name="truck_id" class="selectpicker form-control" required data-live-search="true" data-live-search-style="begins" title="Select Truck...">
                            @foreach($lims_truck_list as $truck)
                            <option value="{{$truck->id}}">{{$truck->name}} [ {{$truck->capacity.' '.$truck->unit->unit_code}} ]</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mt-2 form-group">
                        <label><strong>{{trans('file.Delivered By')}}</strong></label>
                        <input type="text" name="delivered_by" class="form-control">
                    </div>
                    <div class="col-md-6 mt-2 form-group">
                        <label><strong>{{trans('file.Recieved By')}}</strong></label>
                        <input type="text" name="recieved_by" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong>{{trans('file.customer')}} *</strong></label>
                        <p id="customer"></p>
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong>{{trans('file.Attach File')}}</strong></label>
                        <input type="file" name="file" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong>{{trans('file.Address')}} *</strong></label>
                        <textarea rows="3" name="address" class="form-control" required></textarea>
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong>{{trans('file.Note')}}</strong></label>
                        <textarea rows="3" name="note" class="form-control"></textarea>
                    </div>
                </div>
                <input type="hidden" name="reference_no">
                <input type="hidden" name="delivery_id">
                <button type="submit" class="btn btn-primary">{{trans('file.submit')}}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $("ul#sale").siblings('a').attr('aria-expanded','true');
    $("ul#sale").addClass("show");
    $("ul#sale #delivery-menu").addClass("active");

    var delivery_id = [];
    var user_verified = <?php echo json_encode(env('USER_VERIFIED')) ?>;
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function confirmDelete() {
      if (confirm("Are you sure want to delete?")) {
          return true;
      }
      return false;
    }
$(document).ready(function() {
    $('.open-EditCategoryDialog').on('click', function(){
      var url ="delivery/"  
      var id = $(this).data('id').toString();
      url = url.concat(id).concat("/edit");
      
      $.get(url, function(data){
            $('#dr').text(data[0]);
            $('#sr').text(data[1]);
            $('select[name="status"]').val(data[2]);
            $('input[name="delivered_by"]').val(data[3]);
            $('input[name="recieved_by"]').val(data[4]);
            $('#customer').text(data[5]);
            $('textarea[name="address"]').val(data[6]);
            $('textarea[name="note"]').val(data[7]);
            $('input[name="reference_no"]').val(data[0]);
            $('input[name="delivery_id"]').val(id);
            $('#edit-delivery select[name="truck_id"]').val(data[8]);
            $('.selectpicker').selectpicker('refresh');

      });
      $("#edit-delivery").modal('show');
    });
});
$(".daterangepicker-field").daterangepicker({
  callback: function(startDate, endDate, period){
    var start_date = startDate.format('YYYY-MM-DD');
    var end_date = endDate.format('YYYY-MM-DD');
    var title = start_date + ' To ' + end_date;
    $('.daterangepicker-field').val(title);
    $('input[name="start_date"]').val(start_date);
    $('input[name="end_date"]').val(end_date);
  }
});

    $('#delivery-table').DataTable( {
        "order": [],
        'language': {
            'lengthMenu': '_MENU_ {{trans("file.records per page")}}',
             "info":      '{{trans("file.Showing")}} _START_ - _END_ (_TOTAL_)',
            "search":  '{{trans("file.Search")}}',
            'paginate': {
                    'previous': '{{trans("file.Previous")}}',
                    'next': '{{trans("file.Next")}}'
            }
        },
        'columnDefs': [
            {
                "orderable": false,
                'targets': [0, 7]
            },
            {
                'checkboxes': {
                   'selectRow': true
                },
                'targets': 0
            }
        ],
        'select': { style: 'multi',  selector: 'td:first-child'},
        'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
        dom: '<"row"lfB>rtip',
        buttons: [
            {
                extend: 'pdf',
                text: '{{trans("file.PDF")}}',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
            },
            {
                extend: 'csv',
                text: '{{trans("file.CSV")}}',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
            },
            {
                extend: 'print',
                text: '{{trans("file.Print")}}',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
            },
            {
                text: '{{trans("file.delete")}}',
                className: 'buttons-delete',
                action: function ( e, dt, node, config ) {
                    if(user_verified == '1') {
                        delivery_id.length = 0;
                        $(':checkbox:checked').each(function(i){
                            if(i){
                                delivery_id[i-1] = $(this).closest('tr').data('id');
                            }
                        });
                        if(delivery_id.length && confirm("Are you sure want to delete?")) {
                            $.ajax({
                                type:'POST',
                                url:'delivery/deletebyselection',
                                data:{
                                    deliveryIdArray: delivery_id
                                },
                                success:function(data){
                                    alert(data);
                                }
                            });
                            dt.rows({ page: 'current', selected: true }).remove().draw(false);
                        }
                        else if(!delivery_id.length)
                            alert('Nothing is selected!');
                    }
                    else
                        alert('This feature is disable for demo!');
                }
            },
            {
                extend: 'colvis',
                text: '{{trans("file.Column visibility")}}',
                columns: ':gt(0)'
            },
        ],
    } );
</script>
@endsection