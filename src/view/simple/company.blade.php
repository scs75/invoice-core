<p class="company-title"><span class="title">{{ mb_strtoupper($title) }}</span></p>
<p class="company-name">{{ $name }}</p>
<p class="company-address">
    {{ $city }}<br>
    {{ $street }}<br>
    {{ $zip_code }}<br>
</p>
<p class="company-data">
    @if(!empty($tax_number))
        <span class="title">{{ mb_strtoupper(trans('invoice::prp.vendor_tax_number')) }}:</span>
        <span>{{ $tax_number }}</span><br>
    @endif
    @if(!empty($eu_tax_number))
        <span class="title">{{ mb_strtoupper(trans('invoice::prp.vendor_eu_tax_number')) }}:</span>
        <span>{{ $eu_tax_number }}</span><br>
    @endif
    @if(!empty($bank_account))
        <span class="title">{{ mb_strtoupper(trans('invoice::prp.vendor_bank_account')) }}:</span>
        <span>{{ $bank_account }}</span><br>
    @endif
</p>