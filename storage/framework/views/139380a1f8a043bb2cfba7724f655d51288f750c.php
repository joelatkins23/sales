 <?php $__env->startSection('content'); ?>
<?php if(session()->has('message')): ?>
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo session()->get('message'); ?></div> 
<?php endif; ?>
<?php if(session()->has('not_permitted')): ?>
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo e(session()->get('not_permitted')); ?></div> 
<?php endif; ?>

<section>
    <?php echo Form::open(['route' => 'delivery.dayrange', 'method' => 'post']); ?>

    <div class="row mt-4">
        <div class="col-md-4 offset-md-3 ">
       
            <div class="form-group row">
                <label class="d-tc mt-2"><strong><?php echo e(trans('file.Choose Your Date')); ?></strong> &nbsp;</label>
                <div class="d-tc">
                    <div class="input-group">
                        <input type="text" class="daterangepicker-field form-control" value="<?php echo e($start_date); ?> To <?php echo e($end_date); ?>" required />
                        <input type="hidden" name="start_date" value="<?php echo e($start_date); ?>" />
                        <input type="hidden" name="end_date" value="<?php echo e($end_date); ?>" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">       
            <div class="form-group row">
                <button class="btn btn-primary" type="submit"><?php echo e(trans('file.submit')); ?></button>
            </div>
        </div>
    </div>
    <?php echo Form::close(); ?>

    <div class="table-responsive">
        <table id="delivery-table" class="table table-striped">
            <thead>
                <tr>
                    <th class="not-exported"></th>
                    <th>Asignacion</th>
                    <th><?php echo e(trans('file.Date')); ?></th>
                    <th><?php echo e(trans('file.Sale Reference')); ?></th>
                    <th><?php echo e(trans('file.customer')); ?></th>
                    <th><?php echo e(trans('file.Address')); ?></th>
                    <th><?php echo e(trans('file.Status')); ?></th>
                    <th class="not-exported"><?php echo e(trans('file.action')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $lims_delivery_all; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$delivery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                <tr data-id="<?php echo e($delivery->id); ?>">
                    <td><?php echo e($key); ?></td>
                    <?php if($delivery->truck_id == '1'): ?>
                    <td>SRD520-1</td>
                    <?php elseif($delivery->truck_id == '2'): ?>
                    <td>WORKER 1</td>
                    <?php elseif($delivery->truck_id == '3'): ?>
                    <td>BEP429-1</td>
                    <?php elseif($delivery->truck_id == '4'): ?>
                    <td>CARGO 1</td>
                    <?php elseif($delivery->truck_id == '5'): ?>
                    <td>JAC-1</td>
                    <?php elseif($delivery->truck_id == '6'): ?>
                    <td>HKA31E</td>
                    <?php elseif($delivery->truck_id == '7'): ?>
                    <td>NQR36E</td>
                    <?php elseif($delivery->truck_id == '11'): ?>
                    <td>CON-PEDIDO</td>
                    <?php elseif($delivery->truck_id == '12'): ?>
                    <td>LLAMAR</td>
                    <?php elseif($delivery->truck_id == '13'): ?>
                    <td>CONSIGNA</td>
                    <?php elseif($delivery->truck_id == '14'): ?>
                    <td>BEP429-2</td>
                    <?php elseif($delivery->truck_id == '15'): ?>
                    <td>SRD520-2</td>
                    <?php elseif($delivery->truck_id == '16'): ?>
                    <td>JAC-2</td>
                    <?php elseif($delivery->truck_id == '18'): ?>
                    <td>WORKER 2</td>
                    <?php elseif($delivery->truck_id == '19'): ?>
                    <td>CARGO 2</td>
                    <?php elseif($delivery->truck_id == '20'): ?>
                    <td>Sobrantes CARGO</td>
                    <?php elseif($delivery->truck_id == '21'): ?>
                    <td>Sobrantes WORKER</td>
                    <?php elseif($delivery->truck_id == '22'): ?>
                    <td>Sobrantes 520</td>
                    <?php elseif($delivery->truck_id == '23'): ?>
                    <td>Sobrantes 429</td>
                    <?php elseif($delivery->truck_id == '24'): ?>
                    <td>Sobrantes JAC</td>
                    
                    <?php endif; ?>
                    <td><?php echo e($create_date); ?></td>
                    <td><?php echo e($customer_sale[0]->reference_no); ?></td>
                    <td><?php echo e($customer_info); ?></td>
                    <td><?php echo e($delivery->address); ?></td>
                    <?php if($delivery->status == 1): ?>
                    <td><div class="badge badge-info"><?php echo e($status); ?></div></td>
                    <?php elseif($delivery->status == 2): ?>
                    <td><div class="badge badge-primary"><?php echo e($status); ?></div></td>
                    <?php elseif($delivery->status == 4): ?>
                    <td><div class="badge badge-success"><?php echo e($status); ?></div></td>
                    <?php else: ?>
                    <td><div class="badge badge-success"><?php echo e($status); ?></div></td>
                    <?php endif; ?>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo e(trans('file.action')); ?>

                              <span class="caret"></span>
                              <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                                <li>
                                    <button type="button" data-id="<?php echo e($delivery->id); ?>" class="open-EditCategoryDialog btn btn-link"><i class="fa fa-edit"></i> <?php echo e(trans('file.edit')); ?></button>
                                </li>
                                <li class="divider"></li>
                                <?php echo e(Form::open(['route' => ['delivery.delete', $delivery->id], 'method' => 'post'] )); ?>

                                <li>
                                  <button type="submit" class="btn btn-link" onclick="return confirmDelete()"><i class="fa fa-trash"></i> <?php echo e(trans('file.delete')); ?></button> 
                                </li>
                                <?php echo e(Form::close()); ?>

                            </ul>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</seaction>

<!-- Modal -->
<div id="edit-delivery" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="exampleModalLabel" class="modal-title"><?php echo e(trans('file.Update Delivery')); ?></h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <?php echo Form::open(['route' => 'delivery.update', 'method' => 'post', 'files' => true]); ?>

                <?php 
                    $lims_truck_list = \App\Truck::all();
                ?>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label><strong><?php echo e(trans('file.Delivery Reference')); ?></strong></label>
                        <p id="dr"></p>
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong><?php echo e(trans('file.Sale Reference')); ?></strong></label>
                        <p id="sr"></p>
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong><?php echo e(trans('file.Status')); ?> *</strong></label>
                        <select name="status" required class="form-control selectpicker">
                            <option value="1"><?php echo e(trans('file.Packing')); ?></option>
                            <option value="2"><?php echo e(trans('file.Delivering')); ?></option>
                            <option value="3"><?php echo e(trans('file.Delivered')); ?></option>
                            <option value="4"><?php echo e(trans('file.Collect')); ?></option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong><?php echo e(trans('file.Trucks')); ?> *</strong></label>
                        <select name="truck_id" class="selectpicker form-control" required data-live-search="true" data-live-search-style="begins" title="Select Truck...">
                            <?php $__currentLoopData = $lims_truck_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $truck): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($truck->id); ?>"><?php echo e($truck->name); ?> [ <?php echo e($truck->capacity.' '.$truck->unit->unit_code); ?> ]</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-6 mt-2 form-group">
                        <label><strong><?php echo e(trans('file.Delivered By')); ?></strong></label>
                        <input type="text" name="delivered_by" class="form-control">
                    </div>
                    <div class="col-md-6 mt-2 form-group">
                        <label><strong><?php echo e(trans('file.Recieved By')); ?></strong></label>
                        <input type="text" name="recieved_by" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong><?php echo e(trans('file.customer')); ?> *</strong></label>
                        <p id="customer"></p>
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong><?php echo e(trans('file.Attach File')); ?></strong></label>
                        <input type="file" name="file" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong><?php echo e(trans('file.Address')); ?> *</strong></label>
                        <textarea rows="3" name="address" class="form-control" required></textarea>
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong><?php echo e(trans('file.Note')); ?></strong></label>
                        <textarea rows="3" name="note" class="form-control"></textarea>
                    </div>
                </div>
                <input type="hidden" name="reference_no">
                <input type="hidden" name="delivery_id">
                <button type="submit" class="btn btn-primary"><?php echo e(trans('file.submit')); ?></button>
                <?php echo e(Form::close()); ?>

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
            'lengthMenu': '_MENU_ <?php echo e(trans("file.records per page")); ?>',
             "info":      '<?php echo e(trans("file.Showing")); ?> _START_ - _END_ (_TOTAL_)',
            "search":  '<?php echo e(trans("file.Search")); ?>',
            'paginate': {
                    'previous': '<?php echo e(trans("file.Previous")); ?>',
                    'next': '<?php echo e(trans("file.Next")); ?>'
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
                text: '<?php echo e(trans("file.PDF")); ?>',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
            },
            {
                extend: 'csv',
                text: '<?php echo e(trans("file.CSV")); ?>',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
            },
            {
                extend: 'print',
                text: '<?php echo e(trans("file.Print")); ?>',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
            },
            {
                text: '<?php echo e(trans("file.delete")); ?>',
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
                text: '<?php echo e(trans("file.Column visibility")); ?>',
                columns: ':gt(0)'
            },
        ],
    } );
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>