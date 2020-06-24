@extends('layout.main') 
@section('content')
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{!! session()->get('message') !!}</div> 
@endif
<section class="forms">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header mt-2">
                <h3 class="text-center">{{trans('file.Transaction Details')}}</h3>
                <h3 class="text-center">{{trans('file.Maximum Capacity')}} [ {{$get_truck_data->capacity}} kg]</h3>
            </div>
            {!! Form::open(['route' => 'trucks.daytransaction', 'method' => 'post']) !!}
            <div class="row">
                <div class="col-md-4 offset-md-2 mt-3">
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
                <div class="col-md-4 mt-3">
                    <div class="form-group row">
                        <label class="d-tc mt-2"><strong>{{trans('file.Choose Trucks')}}</strong> &nbsp;</label>
                        <div class="d-tc">
                            <select id="trucks_id" name="trucks_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" >
                               @foreach($truck_data as $key=>$truck)
                               <option 
                                @if($truck_id == $truck->id)
                                {{"selected"}}
                                @endif
                               value="{{$truck->id}}">{{$truck->name}}</option>
                               @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 mt-3">
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">{{trans('file.submit')}}</button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}           
            <div class="table-responsive mb-4">
                <table id="transation-table" class="table table-hover">
                    <thead>
                        <tr>
                            <th class="not-exported-transaction"></th>
                            <th>{{trans('file.product')}} {{trans('file.name')}}</th>
                            <th>{{trans('file.product')}} ({{trans('file.qty')}})</th> 
                            <th>{{trans('file.weight')}}</th>
                            <th>{{trans('file.Delivery')}} {{trans('file.reference')}}</th>                           
                            <th>{{trans('file.grand total')}}</th>                            
                            <th>{{trans('file.qty')}} * {{trans('file.weight')}}</th> 
                            <th class="not-exported">{{trans('file.action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                         $total_grand=0;
                         $toal_qty_w=0;
                         ?>
                        @foreach($lims_truck_data as $k=>$item)                       
                        <tr>
                            <?php 
                            $total_grand += $item->total;
                            $toal_qty_w += $item->qty*$item->weight;
                             ?>
                            <td>{{$k}}</td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->qty}}</td>
                            <td>{{$item->weight}}</td>
                            <td>{{$item->reference_no}}</td>
                            <td>{{$item->total}}</td>                            
                            <td>{{$item->qty*$item->weight}}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{trans('file.action')}}
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                                        <li>
                                            <button class="btn btn-link adjustment"  data-id="<?php echo $item->id; ?>"><i class="fa fa-edit"></i> {{trans('file.Adjustment')}}</button>
                                        </li>                                                                        
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="tfoot active">                       
                        <tr>
                            <th></th>
                            <th>Total:</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>{{ number_format($total_grand,2)}}</th>
                            <th 
                            @if($toal_qty_w > $get_truck_data->capacity)
                            style="color:red"
                            @endif
                            >{{ number_format($toal_qty_w,2)}}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</section>
<style>
.ui-menu.ui-widget.ui-widget-content.ui-autocomplete.ui-front {
    z-index:10000;
}
</style>
<div id="adjustment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="exampleModalLabel" class="modal-title">{{trans('file.Adjustment')}}</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                {!! Form::open(['route' => 'trucks.adjuststore', 'method' => 'post', 'files' => true, 'id' => 'adjustment-form']) !!}
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><strong>{{trans('file.Warehouse')}} *</strong></label>
                                    <select required id="warehouse_id" name="warehouse_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" title="Select warehouse...">
                                        @foreach($lims_warehouse_list as $warehouse)
                                        <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><strong>{{trans('file.Attach Document')}}</strong></label>
                                    <input type="file" name="document" class="form-control" >
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label><strong>{{trans('file.Select Product')}}</strong></label>
                                <div class="search-box input-group">
                                    <button type="button" class="btn btn-secondary btn-lg"><i class="fa fa-barcode"></i></button>
                                    <input type="text" name="product_code_name" id="lims_productcodeSearch" placeholder="Please type product code and select..." class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <h5>{{trans('file.Order Table')}} *</h5>
                                <div class="table-responsive mt-3">
                                    <table id="myTable" class="table table-hover order-list">
                                        <thead>
                                            <tr>
                                                <th>{{trans('file.name')}}</th>
                                                <th>{{trans('file.Code')}}</th>
                                                <th>{{trans('file.Quantity')}}</th>
                                                <th>{{trans('file.action')}}</th>
                                                <th><i class="fa fa-trash"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot class="tfoot active">
                                            <th colspan="2">{{trans('file.Total')}}</th>
                                            <th id="total-qty" colspan="2">0</th>
                                            <th><i class="fa fa-trash"></i></th>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="total_qty" />
                                    <input type="hidden" name="item" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><strong>{{trans('file.Note')}}</strong></label>
                                    <textarea rows="5" class="form-control" name="note"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary" id="submit-button">
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#transation-table').DataTable( {
        "order": [],
        'columnDefs': [
            {
                "orderable": false,
                'targets': 0
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
                exportOptions: {
                    columns: ':visible:Not(.not-exported-transaction)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button, config);
                },
                title: '<?php echo $get_truck_name ?>\n (<?php echo $start_date ?> To <?php echo $end_date ?>)',
                footer:true
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: ':visible:Not(.not-exported-transaction)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
                },
                footer:true
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: ':visible:Not(.not-exported-transaction)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    $.fn.dataTable.ext.buttons.print.action.call(this, e, dt, button, config);
                },
                footer:true
            },
            {
                extend: 'colvis',
                columns: ':gt(0)'
            }
        ],
        drawCallback: function () {
            var api = this.api();
        }
    } );
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });     
    $(".daterangepicker-field").daterangepicker({
    callback: function(startDate, endDate, period){
        var start_date = startDate.format('YYYY-MM-DD');
        var end_date = endDate.format('YYYY-MM-DD');
        var title = start_date + ' To ' + end_date;
        $(".daterangepicker-field").val(title);
        $('input[name="start_date"]').val(start_date);
        $('input[name="end_date"]').val(end_date);
    }
    });
    $(document).on("click", "table#transation-table tbody .adjustment", function(event) {       
        $("#adjustment").modal('show');       
    });
    var lims_product_array = [];
var product_code = [];
var product_name = [];
var product_qty = [];

	$('.selectpicker').selectpicker({
	    style: 'btn-link',
	});

	$('#lims_productcodeSearch').on('input', function(){
	    var warehouse_id = $('#warehouse_id').val();
	    temp_data = $('#lims_productcodeSearch').val();
	    if(!warehouse_id){
	        $('#lims_productcodeSearch').val(temp_data.substring(0, temp_data.length - 1));
	        alert('Please select Warehouse!');
	    }
	});

	$('select[name="warehouse_id"]').on('change', function() {
	    var id = $(this).val();
	    $.get('<?php  echo url("qty_adjustment/getproduct")?>/' + id, function(data) {
	        lims_product_array = [];
	        product_code = data[0];
	        product_name = data[1];
	        product_qty = data[2];
	        $.each(product_code, function(index) {
	            lims_product_array.push(product_code[index] + ' (' + product_name[index] + ')');
	        });
	    });
	});

	var lims_productcodeSearch = $('#lims_productcodeSearch');

	lims_productcodeSearch.autocomplete({
	    source: function(request, response) {
	        var matcher = new RegExp(".?" + $.ui.autocomplete.escapeRegex(request.term), "i");
	        response($.grep(lims_product_array, function(item) {
	            return matcher.test(item);
	        }));
	    },
	    response: function(event, ui) {
	        if (ui.content.length == 1) {
	            var data = ui.content[0].value;
	            $(this).autocomplete( "close" );
	            productSearch(data);
	        };
	    },
	    select: function(event, ui) {
	        var data = ui.item.value;
	        productSearch(data);
	    }
	});

	$("#myTable").on('input', '.qty', function() {
	    rowindex = $(this).closest('tr').index();
	    checkQuantity($(this).val(), true);
	});

	$("table.order-list tbody").on("click", ".ibtnDel", function(event) {
	    rowindex = $(this).closest('tr').index();
	    $(this).closest("tr").remove();
	    calculateTotal();
	});

	$(window).keydown(function(e){
	    if (e.which == 13) {
	        var $targ = $(e.target);
	        if (!$targ.is("textarea") && !$targ.is(":button,:submit")) {
	            var focusNext = false;
	            $(this).find(":input:visible:not([disabled],[readonly]), a").each(function(){
	                if (this === e.target) {
	                    focusNext = true;
	                }
	                else if (focusNext){
	                    $(this).focus();
	                    return false;
	                }
	            });
	            return false;
	        }
	    }
	});

	$('#adjustment-form').on('submit',function(e){
	    var rownumber = $('table.order-list tbody tr:last').index();
	    if (rownumber < 0) {
	        alert("Please insert product to order table!")
	        e.preventDefault();
	    }
	});

	function productSearch(data){
		$.ajax({
            type: 'GET',
            url: '<?php  echo url("qty_adjustment/lims_product_search")?>',
            data: {
                data: data
            },
            success: function(data) {
                var flag = 1;
                $(".product-code").each(function(i) {
                    if ($(this).val() == data[1]) {
                        rowindex = i;
	                    var qty = parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val()) + 1;
	                    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val(qty);
	                    checkQuantity(qty);
	                    flag = 0;
                    }
                });
                $("input[name='product_code_name']").val('');
                if(flag){
                    var newRow = $("<tr>");
                    var cols = '';
                    cols += '<td>' + data[0] + '</td>';
                    cols += '<td>' + data[1] + '</td>';
                    cols += '<td><input type="number" class="form-control qty" name="qty[]" value="1" required step="any" /></td>';
                    cols += '<td class="action"><select name="action[]" class="form-control act-val"><option value="-">{{trans("file.Subtraction")}}</option><option value="+">{{trans("file.Addition")}}</option></select></td>';
                    cols += '<td><button type="button" class="ibtnDel btn btn-md btn-danger">{{trans("file.delete")}}</button></td>';
                    cols += '<input type="hidden" class="product-code" name="product_code[]" value="' + data[1] + '"/>';
                    cols += '<input type="hidden" class="product-id" name="product_id[]" value="' + data[2] + '"/>';

                    newRow.append(cols);
                    $("table.order-list tbody").append(newRow);
                    rowindex = newRow.index();
                    calculateTotal();
                }  
            }
        });
	}

	function checkQuantity(qty) {
	    var row_product_code = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(2)').text();
	    var action = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.act-val').val();
	    var pos = product_code.indexOf(row_product_code);

	    if ( (qty > parseFloat(product_qty[pos])) && (action == '-') ) {
	        alert('Quantity exceeds stock quantity!');
            var row_qty = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val();
            row_qty = row_qty.substring(0, row_qty.length - 1);
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val(row_qty);
	    }
	    else {
	        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val(qty);
	    }
	    calculateTotal();
	}

	function calculateTotal() {
	    var total_qty = 0;
	    $(".qty").each(function() {

	        if ($(this).val() == '') {
	            total_qty += 0;
	        } else {
	            total_qty += parseFloat($(this).val());
	        }
	    });
	    $("#total-qty").text(total_qty);
	    $('input[name="total_qty"]').val(total_qty);
	    $('input[name="item"]').val($('table.order-list tbody tr:last').index() + 1);
	}
</script>
@endsection