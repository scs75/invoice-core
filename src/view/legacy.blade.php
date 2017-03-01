<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML>
<HEAD>
    <META http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <META http-equiv="X-UA-Compatible" content="IE=8">
    <TITLE>Számla</TITLE>
    <STYLE type="text/css">

        html,body {font-family: 'DejaVu Sans', sans-serif; line-height: 1.5;}
        table td {font-size: 10pt;}

        div.page {position: relative; border: 2px solid #92a6bc; width: 100%;}

        div.page_header_title {padding: 0 2mm; text-align: center;}
        div.page_header_title h2 {margin: 0; font-size: 6mm;}

        div.page_header table.page_header_info td {height: 26mm; background: #92a6bc; vertical-align: top; font-size: 7pt;}
        div.page_header table.page_header_info td p {margin: 0 0 6px 0; line-height: 1.3;}
        div.page_header table.page_header_dates {border-bottom: 2px solid #92a6bc;}
        div.page_header table.page_header_dates tr td {font-size: 7pt; vertical-align: middle; text-align: center;}
        div.page_header table.page_header_delivery tr td {font-size: 7pt; vertical-align: middle; }
        div.page_header table.page_header_desc td {border-bottom: 2px solid #92a6bc; height: 8mm; vertical-align: top; font-size: 7pt;}

        div.page_body {line-height: 1; height: 137mm;}
        div.page_body table {margin: 0px; border-collapse: collapse; font-size: 7pt;}
        div.page_body table td {margin: 0px; border: solid 1px #92a6bc; font-size: 7pt;}
        div.page_body table tr td {text-align: right;}
        div.page_body table tr:first-of-type td:first-of-type,
        div.page_body table tr td:first-of-type {text-align: left;}
        div.page_body table tr:first-of-type td,
        div.page_body table tr td:nth-of-type(2),
        div.page_body table tr td:nth-of-type(4) {text-align: center;}
        div.page_body table tr td:first-of-type {border-left: none;}
        div.page_body table tr td:last-of-type {border-right: none;}
        div.page_body table tr td.osszesites1 {width: 8%;}
        div.page_body table tr td.osszesites2 {width: 12%;}
        div.page_body table tr td.osszesites3 {width: 12%;}
        div.page_body table tr td.megnevezes {width: 35%;}

        div.page_vat_sum {width: 100%; margin-top: 5mm;}
        div.page_vat_sum table table tr:first-of-type td {border-top: none;}
        div.page_vat_sum table tr:last-of-type td {border-bottom: none;}

        div.page_footer {border-top: 2px solid #92a6bc;}
        div.page_footer table.page_footer_header td {font-size: 7pt;}
        div.page_footer table.page_footer_signo td {text-align: center;}
        div.page_footer table.page_footer_pager {margin-top: 4mm; background: #92a6bc; font-size: 7pt;}
        div.page_footer table.page_footer_pager td {font-size: 7pt;}

    </STYLE>
</HEAD>

<BODY>

        <DIV class='page'>
            <div class='page_header_title'>
                    <h2>SZÁMLA</h2>

            </div>
            <div class='page_header'>
                <!-- ////////////////////// elado - vevo adatai //////////////// -->
                <table cellpadding='5' cellspacing='2' width='100%' class='page_header_info'>
                    <tr>
                        <td width='50%'>
                            <p>
                                Szállító:&nbsp;
                                <b>{{ $inc_draw->drawer_name }}</b>
                            </p>
                            <p>
                                {{ $inc_draw->zip_code }} {{ $inc_draw->city }}, {{ $inc_draw->street }}<br>
                                Adószám: {{ $inc_draw->tax_number }} EU adószám: {{ $inc_draw->public_tax_number }} <br>
                                Bankszámlaszám. {{ $inc_draw->account_number }}
                            </p>
                        </td>
                        <td width='50%'>
                            <p>
                                Vevő:&nbsp;
                                <b>{{ $inc_cust->billing_name }}</b>
                            </p>
                            <p>
                                {{ $inc_cust->billing_zip_code }} {{ $inc_cust->billing_city }}, {{ $inc_cust->billing_street }}<br>
                                Adószám: {{ $inc_cust->tax_number }}
                            </p>
                        </td>
                    </tr>
                </table>
                <!-- ////////////////////// dátumok megjegyzés //////////////////// -->
                <table cellpadding='2' cellspacing='0' width='100%' class='page_header_dates'>
                    <tr>
                        <td width='25%'>Fizetési mód<br> {{ $pay_type }} </td>
                        <td width='25%'>Kelt<br> {{ $inc_d_completion }} </td>
                        <td width='25%'>Teljesítés<br> {{ $inc_d_invoice }} </td>
                        <td width='25%'>Fiz.hat.<br> {{ $inc_d_payment }} </td>
                        <td width='25%'>Számla sorszáma<br> {{ $inc_serial_string }} </td>
                    </tr>
                </table>
                <table cellpadding='2' cellspacing='0' width='100%' class='page_header_desc'>
                    <tr>
                        <td colspan='2'>
                            Közlemény: {{ $inc_note }}
                        </td>
                    </tr>
                </table>
                <!-- ////////////////////// szállítási cím megjegyzés //////////////////// -->
                <table cellpadding='2' cellspacing='0' width='100%' class='page_header_delivery'>
                    <tr>
                        <td width='100%' style='text-align: left'>Szállítási cím: {{ $delivery_name }} - {{ $delivery_zip_code }}, {{ $delivery_city }}  {{ $delivery_street }}    </td>
                    </tr>
                    {{--
                    <tr>
                        <td width='50%' style='text-align: right'>Szállítási cím: {{ $delivery_name }} </td>
                        <td width='50%' style='text-align: left'> {{ $delivery_zip_code }} </td>
                    </tr>
                    <tr>
                        <td width='50%' style='text-align: right'> {{ $delivery_city }} </td>
                        <td width='50%' style='text-align: left'> {{ $delivery_street }} </td>
                    </tr>--}}
                </table>

            </div>
            <!-- ////////////////////// header vege //////////////////// -->
            <div class='page_body'>

                        <!-- ////////////////// Tetelek  /////////////////////// -->

                <!-- ///////////// Tetelek  ///////////////////-->

                <table width='100%' cellpadding="2" cellspacing="0">
                    <tr>
                        <td class="megnevezes">Megnevezés (Vtsz/Szj) </td>
                        <td>Áfa<br>Kulcs</td>
                        <td>Mennyiség</td>
                        <td>Egység</td>
                        <td class="osszesites1" >Nettó<br>egységár</td>
                        <td class="osszesites2" >Nettó<br>érték</td>
                        <td class="osszesites2" >ÁFA<br>érték</td>
                        <td class="osszesites3" >Bruttó<br>érték</td>
                    </tr>
                    <?php
                    $from =  $from  - 1;
                    $to =  $to  - 1;
                    ?>
                    @for($i=$from;$i<=$to;$i++)
                        @if ($invoice_items[$i]->quantity != 0)
                            <tr>
                                <td style="height: 20px">
                                    <div>{{ $invoice_items[$i]->product->name }}
                                        @if ($invoice_items[$i]->expiration != NULL)
                                            (<?php echo date('Y-m-d', strtotime($invoice_items[$i]->expiration))?>)
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $invoice_items[$i]->product->vat->name }}</div>
                                </td>

                                <td>
                                    <div>{{ number_format(($invoice_items[$i]->quantity*$invoice_items[$i]->multiplier),0,","," ")  }}</div>
                                </td>

                                <td>
                                    <div>
                                        @if ($invoice_items[$i]->invoice_quantity_id != NULL)
                                            {{ $invoice_items[$i]->invoice_quantity->quantity_name }}
                                        @else
                                            ismeretlen
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>{{ number_format(($invoice_items[$i]->item_sum_calc_net / ($invoice_items[$i]->quantity*$invoice_items[$i]->multiplier)),2,","," " ) }}</div>
                                </td>
                                <td>
                                    <div>{{ number_format($invoice_items[$i]->item_sum_calc_net,2,","," ") }}</div>
                                </td>
                                <td>
                                    <div>{{ number_format(($invoice_items[$i]->item_sum_calc_gross - $invoice_items[$i]->item_sum_calc_net),2,","," " ) }}</div>
                                </td>
                                <td>
                                    <div>{{ number_format($invoice_items[$i]->item_sum_calc_gross,2,","," ") }}</div>
                                </td>
                            </tr>
                        @endif
                    @endfor
                </table>

                <div class='page_vat_sum'>
                    <!-- ////////////////// Áfa Összesítő ///////////////////////// -->
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width='55%' valign="top">
                                <table width="100%" cellpadding="2" cellspacing="0">
                                    <tr>
                                        <td>Áfakulcs</td>
                                        <td>Adóalap</td>
                                        <td>Áfa érték</td>
                                        <td>Bruttó érték</td>
                                    </tr>
                                    {{--                <tr>
                                                        <td colspan='4'>&nbsp;</td>
                                                    </tr>--}}

                                    @foreach ($vats_in_group as $grouped_vat)

                                        <tr>
                                            <td><?php
                                                $vat_value = App\Models\Vat::findOrFail($grouped_vat->vat_id);
                                                $vat_name = $vat_value->name;
                                                echo $vat_name;
                                                ?></td>
                                            <td>{{ number_format($grouped_vat->v1,2,","," ") }} HUF</td>
                                            <td><?php
                                                $a = $grouped_vat->v2;
                                                $b = $grouped_vat->v1;
                                                //echo 'a'.$a.'-';
                                                //  echo 'b'.$b.'-';
                                                $c = $a-$b;
                                                echo number_format($c,2,","," ").' HUF';
                                                ?></td>
                                            <td>{{ number_format($grouped_vat->v2,2,","," ") }} HUF</td>
                                        </tr>
                                    @endforeach

                                </table>
                            </td>
                            <td width='45%' valign="top">
                                <table width="100%" cellpadding="2" cellspacing="0">
                                    <tr>
                                        <td>Adóalap</td>
                                        <td>{{ number_format($invoice->sum_value_net,2,","," ") }}</td>
                                        <td>HUF</td>
                                    </tr>
                                    <tr>
                                        <td>Áfa érték</td>
                                        <td>{{ number_format($invoice->sum_value_tax,2,","," ") }}</td>
                                        <td>HUF</td>
                                    </tr>
                                    <tr>
                                        <td colspan='3'>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>Fizetend&otilde;/Végösszeg</td>
                                        <td>{{ number_format(round($invoice->sum_value_gross),0,","," ") }}</td>
                                        <td>HUF</td>
                                    </tr>
                                    <tr>
                                        <td colspan='3'>
                                            azaz:
                                            <?php
                                            $total_brutto = round($invoice->sum_value_gross);
                                            if ($total_brutto == 0) {
                                                echo 'nulla forint';
                                            } elseif ($total_brutto < 0) {
                                                echo 'minusz '.numberTOZOtext(substr($total_brutto, 1)).' forint';
                                            } else {
                                                echo numberTOZOtext($total_brutto).' forint';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

                </div>

                </div> <!-- /////////////// Page_body vége ///////////////// -->
                    <div class='page_footer'>
                        <!-- ////////////////// Lábléc ///////////////////////// -->
                        <div class='page_footer'> <!-- ///////////// Lábléc ///////////// -->
                            <table width='100%' class='page_footer_header' cellpadding='5' cellspacing='0'>
                                <tr>
                                    <td>
                                        Köszönjük, hogy cégünk szolgáltatásait választotta!
                                    </td>
                                </tr>
                            </table>
                            <table width='100%' class='page_footer_signo' cellpadding='5' cellspacing='0'>
                                <tr>
                                    <td width='10%'>&nbsp;</td>
                                    <td width='35%' style='border-bottom: 1px solid #000;'>Eladó:<br>&nbsp;</td>
                                    <td width='10%'>&nbsp;</td>
                                    <td width='35%' style='border-bottom: 1px solid #000;'>Vev&otilde;:<br>&nbsp;</td>
                                    <td width='10%'>&nbsp;</td>
                                </tr>
                            </table>
                            <table width='100%' class='page_footer_pager' cellpadding='5' cellspacing='0'>
                                <tr>
                                    <td width='20%'>Készült: <br>2 példányban</td>
                                    <td width='60%' style='text-align: center'>A számla a Paytech Kft. Vízsztár ERP rendszerével készült.</td>
                                    <td width='20%' style='text-align: right'>Oldal: <br> {{ $page_act }} / {{ $page_all }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{-- sortörés és lapozó --}}
                        <div style="page-break-before:always"></div>

                </div>
</BODY>
</HTML>