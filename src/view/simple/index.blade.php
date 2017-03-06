<?php
$date_format = $invoice->getConfigDateFormat();
$i = 0;
$brand_color = '#3cc9f5';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=8">
    <title>Invoice</title>
    <style type="text/css">
        html,body {font-family: 'DejaVu Sans Condensed', sans-serif; padding-top: 96px;}
        div.header {position: fixed; top: 6px; width: 89%;}
        div.header table {width: 100%; border-spacing: 0px;}
        div.header h1 {font-weight: normal; margin-top: 0px; margin-bottom: 0px;}
        div.header h3 {margin-top: 0px; margin-bottom: 14px; color: grey;}
        div.company {line-height: 98%;}
        div.section {border-top: {{ $brand_color }} solid 2px;}
        p.company-title {margin-top: 0px;}
        p.company-name {font-weight: bold; font-size: 15px;}
        p.company-address {font-size: 14px;}
        span.title {color: grey; font-size: 10px;}
        p.company-data {color: grey; font-size: 14px}
        div.dates {font-size: 14px; margin-bottom: 14px;}
        div.total {width: 100%; text-align: right;}
        div.total span.title, td.index {color: {{ $brand_color }};}
        div.total span.amount {font-size: 26px;}
        table.items {margin-bottom: 14px; width: 100%; border-spacing: 0px; font-size: 14px;}
        table.items thead tr { background-color: #f0f1f1 }
        table.items tbody tr.break {page-break-after: always;}
        table.items th { font-size: 10px; border-top: black solid 1px; }
        table.items th, table.items td {border-bottom: lightgray solid 1px; padding: 4px 4px 4px 4px;}
        div.summary table {margin-bottom: 14px; width: 100%; border-spacing: 0px; font-size: 14px;}
        div.summary td.title {color: grey; font-size: 10px}
        div.summary td.amount {font-size: 14px}
        p.remark {margin-top: 0px; margin-bottom: 2px; font-style: italic;}
        div.footer {position: fixed; bottom: 6px; width: 89%; border-top: lightgray solid 1px; border-bottom: lightgray solid 1px; }
        div.footer table {width: 99%; font-size: 10px; color: gray; font-style: italic; margin: 5px;}
        div.footer span.page-counter {font-style: normal; font-weight: bold; color: black;}
    </style>
</head>

<body>
<div class='header'>
    @include('invoice::simple.header')
</div>
<div class="footer">
    @include('invoice::simple.footer')
</div>
<div class='page'>
    <div class='section company'>
        <table border="0" width="100%" cellspacing="0px" cellpadding="0px">
            <tr>
                <td width='50%' valign="top">
                    @include('invoice::simple.company', [
                        'title' => trans('invoice::image.vendor'),
                        'name' => $invoice->vendor_name,
                        'city' => $invoice->vendor_city,
                        'street' => $invoice->vendor_street,
                        'zip_code' => $invoice->vendor_zip_code,
                        'tax_number' => $invoice->vendor_tax_number,
                        'eu_tax_number' => $invoice->vendor_eu_tax_number,
                        'bank_account' => $invoice->vendor_bank_account,
                    ])
                </td>
                <td width='50%' valign="top">
                    @include('invoice::simple.company', [
                        'title' => trans('invoice::image.customer'),
                        'name' => $invoice->customer_name,
                        'city' => $invoice->customer_city,
                        'street' => $invoice->customer_street,
                        'zip_code' => $invoice->customer_zip_code,
                        'tax_number' => $invoice->customer_tax_number,
                        'eu_tax_number' => $invoice->customer_eu_tax_number,
                    ])
                </td>
            </tr>
        </table>
    </div>
    <div class='section dates'>
        @include('invoice::simple.dates')
    </div>
    <div class='section items'>
        <div class="total">
                <span class="title"><strong>{{ mb_strtoupper(trans('invoice::prp.total_gross_amount')) }}: </strong></span>
                <span class="amount">{{ $invoice->moneyFormat('total_gross_amount') }}</span>
        </div>
        <div>
            <table class="items">
                <thead>
                <tr>
                    <th></th>
                    <th>{{ mb_strtoupper(trans('invoice::prp.items-description')) }}</th>
                    <th>{{ mb_strtoupper(trans('invoice::prp.items-quantity')) }}</th>
                    <th>{{ mb_strtoupper(trans('invoice::prp.items-net_unit_price')) }}</th>
                    <th>{{ mb_strtoupper(trans('invoice::prp.items-net_price')) }}</th>
                    <th>{{ mb_strtoupper(trans('invoice::prp.items-vat_name')) }}</th>
                    <th>{{ mb_strtoupper(trans('invoice::prp.items-gross_price')) }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($invoice->items as $item)
                    <tr>
                        <td class="index">{{ $i + 1 }}</td>
                        <td>{{ $item->description }}</td>
                        <td align="right" nowrap>
                            {{ number_format($item->quantity, 0, config('invoice.languages.'.$invoice->language.'.dec_point'), config('invoice.languages.'.$invoice->language.'.thousands_sep')) }}
                            {{ $item->unit }}
                        </td>
                        <td align="right" nowrap>{{ $item->moneyFormat('net_unit_price') }}</td>
                        <td align="right" nowrap>{{ $item->moneyFormat('net_price') }}</td>
                        <td nowrap>{{ $item->vat_name }}</td>
                        <td align="right" nowrap>{{ $item->moneyFormat('gross_price') }}</td>
                    </tr>

                    <?php
                        $i++
                    ?>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class='section summary'>
        <table>
            <tr>
                <td width='80%' valign="bottom" align="right" class="title">{{ mb_strtoupper(trans('invoice::prp.total_net_amount')) }}:</td>
                <td width='20%' align="right" class="amount">{{ $invoice->moneyFormat('total_net_amount') }}</td>
            </tr>
            @if($invoice->hasVatSummary())
                @foreach($invoice->items()->select(DB::raw('SUM(vat_amount) as vat_amount, vat_name'))->groupBy('vat_name')->orderBy('vat_name')->get() as $vat_item)
                    <tr>
                        <td valign="bottom" align="right" class="title">{{ mb_strtoupper($vat_item->vat_name.' '.trans('invoice::prp.items-vat_name')) }}:</td>
                        <td align="right" class="amount">
                            {{ number_format($vat_item->vat_amount, config('invoice.languages.'.$invoice->language.'.decimals'), config('invoice.languages.'.$invoice->language.'.dec_point'), config('invoice.languages.'.$invoice->language.'.thousands_sep')) }}
                            {{ config('invoice.currencies.'.$invoice->currency.'.code') }}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td valign="bottom" align="right" class="title">{{ mb_strtoupper(trans('invoice::prp.items-vat_amount')) }}:</td>
                    <td align="right" class="amount">
                        {{ $invoice->moneyFormat('total_vat_amount') }}
                    </td>
                </tr>
            @endif
            <tr>
                <td valign="bottom" align="right" class="title">{{ mb_strtoupper(trans('invoice::prp.total_gross_amount')) }}:</td>
                <td align="right" class="amount"><strong>{{ $invoice->moneyFormat('total_gross_amount') }}</strong></td>
            </tr>
        </table>
    </div>
    <div class='section remark'>
        <span class="title">{{ mb_strtoupper(trans('invoice::prp.remark_global')) }}</span>
        @if(!empty($invoice->remark_global))
            <p class="remark">{{ $invoice->remark_global }}</p>
        @endif
        @if(!empty($invoice->remark_vendor))
            <p class="remark">{{ $invoice->remark_vendor }}</p>
        @endif
        @if(!empty($invoice->remark_custom))
            <p class="remark">{{ $invoice->remark_custom }}</p>
        @endif
    </div>
</div>
</body>
</html>