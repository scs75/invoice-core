{{-- Fejléc --}}
<table>
    <tr>
        <td width="50%" valign="top">
            <h1>
                {{ trans('invoice::image.type.'.$invoice->type) }}
            </h1>
            <h3>{{ $invoice->serial_number }}</h3>
        </td>
        <td width="50%" valign="middle" align="right">
            @if(!empty($invoice->vendor_logo))
                <img src="{{ public_path().'/'.$invoice->vendor_logo }}" alt="" width="auto" height="70px">
            @endif
        </td>
    </tr>
</table>