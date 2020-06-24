<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="{{url('public/logo', $general_setting->site_logo)}}" />
    <title>{{$general_setting->site_title}}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">

    <style type="text/css">
        * {
            font-size: 14px;
            line-height: 24px;
            font-family: 'Ubuntu', sans-serif;
            text-transform: capitalize;
        }
        .btn {
            padding: 7px 10px;
            text-decoration: none;
            border: none;
            display: block;
            text-align: center;
            margin: 7px;
            cursor:pointer;
        }

        .btn-info {
            background-color: #999;
            color: #FFF;
        }

        .btn-primary {
            background-color: #6449e7;
            color: #FFF;
            width: 100%;
        }
        td,
        th,
        tr,
        table {
            border-collapse: collapse;
        }
        tr {border-bottom: 1px dotted #ddd;}
        td,th {padding: 7px 0;width: 50%;}

        table {width: 100%;}
        tfoot tr th:first-child {text-align: left;}

        .centered {
            text-align: center;
            align-content: center;
        }
        small{font-size:11px;}

        @media print {
            * {
                font-size:12px;
                line-height: 20px;
            }
            td,th {padding: 5px 0;}
            .hidden-print {
                display: none !important;
            }
            @page { margin: 0; } body { margin: 0.5cm; margin-bottom:1.6cm; } 
        }
    </style>
  </head>
<body>

<div style="max-width:1200px;margin:0 auto">
    @if(preg_match('~[0-9]~', url()->previous()))
        @php $url = '../../pos'; @endphp
    @else
        @php $url = url()->previous(); @endphp
    @endif
    <div class="hidden-print">
        <table>
            <tr>
                <td><a href="{{$url}}" class="btn btn-info"><i class="fa fa-arrow-left"></i> {{trans('file.Back')}}</a> </td>
                <td><button onclick="window.print();" class="btn btn-primary"><i class="fa fa-print"></i> {{trans('file.Print')}}</button></td>
            </tr>
        </table>
        <br>
    </div>
    {{-- Header --}}
        <table class="table mt-5">
            <tbody>
                <tr>
                    <td class="border-0 pl-0" width="70%">
                            <p><strong>{{$lims_biller_data->company_name}}
                            <br>NIT: {{$lims_biller_data->vat_number}}</strong>
                            <br><strong>DIRECCION: </strong>{{$lims_warehouse_data->address}}
                            <br><strong>TELEFONO: </strong>{{$lims_warehouse_data->phone}}</p>
                    </td>
                    <td class="border-0 pl-0">
                        @if($lims_biller_data->company_name == 'PRODECON - COMERCIALIZADORA PRODECON')
                        <img src="{{url('public/logo/CERT PRODECON.jpg')}}" height="135" width="240" style="float:right;margin:1px 0;filter: brightness(1);">
                        @endif
                        @if($lims_biller_data->company_name == 'DEMACOL - COMERCIALIZADORA DE MATERIALES DE COLOMBIA')
                        <img src="{{url('public/logo/DEMACOL.jpg')}}" height="135" width="240" style="float:right;margin:1px 0;filter: brightness(1);">
                        @endif
                        <p><strong>FECHA: {{$lims_sale_data->created_at}}</strong>
                        @if($lims_sale_data->reference_no > 1)
                        <br><strong>REFERENCIA: COTIZACION-{{$lims_sale_data->reference_no}}</strong>
                        @else
                        <br><strong>REFERENCIA: {{$lims_sale_data->reference_no}}</strong>
                        @endif
                        <br><strong>CLIENTE: </strong>{{$lims_customer_data->name}}, {{$lims_customer_data->company_name}}
                        <br><strong>NIT: </strong>{{$lims_customer_data->tax_no}}
                        <br><strong>DIRECCION: </strong>{{$lims_customer_data->address}}, {{$lims_customer_data->zone}}
                        <br><strong>TELEFONO: </strong>{{$lims_customer_data->phone_number}}</p>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <table class="table">
            <thead>
               <tr style="border-bottom:1px solid;background-color:#ddd;">
                    <th style="padding: 5px;width:20%">Descripcion</th>
                    <th style="padding: 5px;width:20%">cantidad</th>
                    <th style="text-align:left">Pecio unitario</th>
                    <th style="padding: 5px;width:20%">subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lims_product_sale_data as $product_sale_data)
                @php 
                    $lims_product_data = \App\Product::find($product_sale_data->product_id);
                    if($product_sale_data->variant_id) {
                        $variant_data = \App\Variant::find($product_sale_data->variant_id);
                        $product_name = $lims_product_data->name.' ['.$variant_data->name.']';
                    }
                    else
                        $product_name = $lims_product_data->name;
                @endphp
                <tr style="border-bottom:1px dotted"><td style="padding: 5px;width:30%">{{$product_name}}</td>
                    <td style="text-align:center">{{$product_sale_data->qty}}</td>
                    <td style="text-align:left">{{number_format((float)($product_sale_data->total / $product_sale_data->qty), 2, '.', ',')}}</td>
                    <td style="padding: 5px;width:20%">{{number_format((float)$product_sale_data->total, 2, '.', ',')}}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="border-bottom:1px dotted">
                    <th colspan="3">Subtotal</th>
                    <th style="text-align:right">{{number_format((float)$lims_sale_data->total_price, 2, '.', ',')}}</th>
                </tr>
                @if($lims_sale_data->order_tax)
                <tr style="border-bottom:1px dotted">
                    <th colspan="3">IVA 19%</th>
                    <th style="text-align:right">{{number_format((float)$lims_sale_data->order_tax, 2, '.', ',')}}</th>
                </tr>
                @endif
                @if($lims_sale_data->order_discount)
                <tr style="border-bottom:1px dotted">
                    <th colspan="3">Retencion</th>
                    <th style="text-align:right">{{number_format((float)$lims_sale_data->order_discount, 2, '.', ',')}}</th>
                </tr>
                @endif
                @if($lims_sale_data->coupon_discount)
                <tr style="border-bottom:1px dotted">
                    <th colspan="3">{{trans('file.Coupon Discount')}}</th>
                    <th style="text-align:right">{{number_format((float)$lims_sale_data->coupon_discount, 2, '.', ',')}}</th>
                </tr>
                @endif
                @if($lims_sale_data->shipping_cost)
                <tr style="border-bottom:1px dotted">
                    <th colspan="3">ICA</th>
                    <th style="text-align:right">{{number_format((float)$lims_sale_data->shipping_cost, 2, '.', ',')}}</th>
                </tr>
                @endif
                <tr style="border-bottom:1px solid;background-color:#ddd;">
                    <th colspan="3">TOTAL</th>
                    <th style="text-align:right">{{number_format((float)$lims_sale_data->grand_total, 2, '.', ',')}}</th>
                </tr>
                <tr>
                    @if($general_setting->currency_position == 'prefix')
                    <th class="centered" colspan="3">{{trans('file.In Words')}}: <span>{{$general_setting->currency}}</span> <span>{{str_replace("-"," ",$numberInWords)}}</span></th>
                    @else
                    <th class="centered" colspan="3">{{trans('file.In Words')}}: <span>{{str_replace("-"," ",$numberInWords)}}</span> <span>{{$general_setting->currency}}</span></th>
                    @endif
                </tr>
            </tfoot>
        </table>
        <table>
            <tbody>
                @foreach($lims_payment_data as $payment_data)
                <tr style="background-color:#ddd;">
                    <td style="padding: 5px;width:30%">{{trans('file.Paid By')}}: Efectivo</td>
                    <td style="padding: 5px;width:40%"><strong>Cantidad pagada: {{number_format((float)$payment_data->amount, 2, '.', ',')}}</strong></td>
                    <td style="padding: 5px;width:30%">{{trans('file.Change')}}: {{number_format((float)$payment_data->change, 2, '.', ',')}}</td>
                </tr>
                <tr><td class="centered" colspan="3"><strong>GRACIAS POR FREFERIRNOS {{$lims_biller_data->company_name}} SIEMPRE CONTIGO</strong></td></tr>
                @endforeach
            </tbody>
        </table>
    <div id="receipt-data">
                 
        <!--<div class="centered">
            
        </div>
        <p>{{trans('file.Date')}}: {{$lims_sale_data->created_at}}<br>
            {{trans('file.reference')}}: {{$lims_sale_data->reference_no}}<br>
            {{trans('file.customer')}}: {{$lims_customer_data->name}}
        </p>
        <table>
            <tbody>
                @foreach($lims_product_sale_data as $product_sale_data)
                @php 
                    $lims_product_data = \App\Product::find($product_sale_data->product_id);
                    if($product_sale_data->variant_id) {
                        $variant_data = \App\Variant::find($product_sale_data->variant_id);
                        $product_name = $lims_product_data->name.' ['.$variant_data->name.']';
                    }
                    else
                        $product_name = $lims_product_data->name;
                @endphp
                <tr><td colspan="2">{{$product_name}}<br>{{$product_sale_data->qty}} x {{number_format((float)($product_sale_data->total / $product_sale_data->qty), 2, '.', ',')}}</td>
                    <td style="text-align:right;vertical-align:bottom">{{number_format((float)$product_sale_data->total, 2, '.', '')}}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">{{trans('file.Total')}}</th>
                    <th style="text-align:right">{{number_format((float)$lims_sale_data->total_price, 2, '.', ',')}}</th>
                </tr>
                @if($lims_sale_data->order_tax)
                <tr>
                    <th colspan="2">{{trans('file.Order Tax')}}</th>
                    <th style="text-align:right">{{number_format((float)$lims_sale_data->order_tax, 2, '.', ',')}}</th>
                </tr>
                @endif
                @if($lims_sale_data->order_discount)
                <tr>
                    <th colspan="2">{{trans('file.Order Discount')}}</th>
                    <th style="text-align:right">{{number_format((float)$lims_sale_data->order_discount, 2, '.', ',')}}</th>
                </tr>
                @endif
                @if($lims_sale_data->coupon_discount)
                <tr>
                    <th colspan="2">{{trans('file.Coupon Discount')}}</th>
                    <th style="text-align:right">{{number_format((float)$lims_sale_data->coupon_discount, 2, '.', ',')}}</th>
                </tr>
                @endif
                @if($lims_sale_data->shipping_cost)
                <tr>
                    <th colspan="2">{{trans('file.Shipping Cost')}}</th>
                    <th style="text-align:right">{{number_format((float)$lims_sale_data->shipping_cost, 2, '.', ',')}}</th>
                </tr>
                @endif
                <tr>
                    <th colspan="2">{{trans('file.grand total')}}</th>
                    <th style="text-align:right">{{number_format((float)$lims_sale_data->grand_total, 2, '.', ',')}}</th>
                </tr>
                <tr>
                    @if($general_setting->currency_position == 'prefix')
                    <th class="centered" colspan="3">{{trans('file.In Words')}}: <span>{{$general_setting->currency}}</span> <span>{{str_replace("-"," ",$numberInWords)}}</span></th>
                    @else
                    <th class="centered" colspan="3">{{trans('file.In Words')}}: <span>{{str_replace("-"," ",$numberInWords)}}</span> <span>{{$general_setting->currency}}</span></th>
                    @endif
                </tr>
            </tfoot>
        </table>
        <table>
            <tbody>
                @foreach($lims_payment_data as $payment_data)
                <tr style="background-color:#ddd;">
                    <td style="padding: 5px;width:30%">{{trans('file.Paid By')}}: {{$payment_data->paying_method}}</td>
                    <td style="padding: 5px;width:40%">{{trans('file.Amount')}}: {{number_format((float)$payment_data->amount, 2, '.', ',')}}</td>
                    <td style="padding: 5px;width:30%">{{trans('file.Change')}}: {{number_format((float)$payment_data->change, 2, '.', ',')}}</td>
                </tr>
                <tr><td class="centered" colspan="3">{{trans('file.Thank you for shopping with us. Please come again')}}</td></tr>
                @endforeach
            </tbody>
        </table>-->
        <!-- <div class="centered" style="margin:30px 0 50px">
            <small>{{trans('file.Invoice Generated By')}} {{$general_setting->site_title}}.
            {{trans('file.Developed By')}} LionCoders</strong></small>
        </div> -->
    </div>
</div>

<script type="text/javascript">
    function auto_print() {     
        window.print()
    }
    setTimeout(auto_print, 1000);
</script>

</body>
</html>
