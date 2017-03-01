<table border="0" width="100%">
    <tr>
        <td width='50%' valign="top">
            <span class="title">{{ mb_strtoupper(trans('invoice::prp.release_d')) }}:</span>
            <span>{{ $invoice->release_d->format($date_format) }}</span><br>
            @if(!empty($invoice->supply_d))
                <span class="title">{{ mb_strtoupper(trans('invoice::prp.supply_d')) }}:</span>
                <span>{{ $invoice->supply_d->format($date_format) }}</span><br>
            @endif
        </td>
        <td width='50%' valign="top">
            <span class="title">{{ mb_strtoupper(trans('invoice::prp.payment_mode')) }}:</span>
            <span>{{ trans('invoice::msg.payment_mode.'.$invoice->payment_mode) }}</span><br>
            @if($invoice->payment_mode == \Paytech\Invoice\Core\PaymentModeEnum::TRANSFER)
                <span class="title">{{ mb_strtoupper(trans('invoice::prp.deadline_d')) }}:</span>
                <span><strong>{{ $invoice->deadline_d->format($date_format) }}</strong></span><br>
            @endif
        </td>
    </tr>
</table>