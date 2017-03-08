<?php

namespace Paytech\Invoice\Core\Model;

use App\Models\MyModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DB;
use Collective\Html\Eloquent\FormAccessible;
use Paytech\Invoice\Core\MoneyFormatTrait;

/**
 * Számla
 *
 * @package Paytech\Invoice\Core\Model
 * @author Sáray Csaba <csaba.saray@paytech.hu>
 * @licence http://paytech.hu All rights reserved
 */
class Invoice extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Konstansok
    |--------------------------------------------------------------------------
    */
    /*
    |--------------------------------------------------------------------------
    | Trait-ek
    |--------------------------------------------------------------------------
    */
    use MoneyFormatTrait;
    /*
    |--------------------------------------------------------------------------
    | Laravel propertyk: Tömeges értékadás ($fillable vagy $guarded), láthatóság ($hidden), típusok ($dates), stb
    |--------------------------------------------------------------------------
    */
    /*protected $fillable = [
        'vendor_name', 'vendor_zip_code', 'vendor_city', 'vendor_street', 'vendor_tax_number', 'vendor_eu_tax_number',
        'vendor_bank_account', 'vendor_email', 'vendor_phone', 'customer_name', 'customer_zip_code', 'customer_city',
        'customer_street', 'customer_tax_number', 'customer_eu_tax_number', 'release_d', 'supply_d', 'deadline_d',
        'payment_mode', 'remark', 'language', 'currency', 'type', 'is_cancelled', 'is_corrected', 'related_id'
    ];*/
    protected $dates = [
        'release_d',
        'supply_d',
        'deadline_d',
        'payment_d',
    ];
    protected $casts = [
        'is_paid' => 'boolean',
        'is_cancelled' => 'boolean',
        'is_corrected' => 'boolean',
        'is_electronic' => 'boolean',
    ];
    protected $title_field = 'serial_number';

    /*
    |--------------------------------------------------------------------------
    | Audit propertyk
    |--------------------------------------------------------------------------
    */
    /*
    |--------------------------------------------------------------------------
    | Relációs metódusok (belongsTo, belongsToMany, hasMany, stb)
    |--------------------------------------------------------------------------
    */
    public function related()
    {
        return $this->belongsTo(Invoice::class);
    }
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
    public function vatItems()
    {
        return $this->hasMany(InvoiceVatItem::class)->orderBy('vat_multiplier');
    }

    /*
    |--------------------------------------------------------------------------
    | Accesorok és Mutátorok (form accessorok is)
    |--------------------------------------------------------------------------
    */
    /*
    |--------------------------------------------------------------------------
    | Lokális scope-ok
    |--------------------------------------------------------------------------
    */
    /*
    |--------------------------------------------------------------------------
    | Metódusok üzleti logikához
    |--------------------------------------------------------------------------
    */
    public function generateSerialNumber(string $pad_prefix = '')
    {
        $year = $this->release_d->format('Y');
        if(!empty(config('invoice.defaults.global_pad_prefix')) || !empty($pad_prefix)) {
            $prefix = config('invoice.defaults.global_pad_prefix').$pad_prefix.'-';
        } else {
            $prefix = '';
        }
        $prefix = $prefix.$year.'-';
        $next_number = $this::where('serial_number', 'LIKE', $prefix.'%')->count() + 1;
        $this->serial_number = $prefix . str_pad($next_number, 6, '0', STR_PAD_LEFT);
    }

    public function calculateTotals()
    {
        $total_net_amount = $this->items()->sum('net_price');
        $total_vat_amount = 0;
        foreach($this->vatItems as $vat_item) {
            $total_vat_amount += $vat_item->vat_amount;// a mutator kerekített értéket ad vissza huf esetén
        }
        if($this->currency == 'huf') {
            $this->total_net_amount = round($total_net_amount);
        } else {
            $this->total_net_amount = $total_net_amount;
        }
        $this->total_vat_amount = $total_vat_amount;
        $this->total_gross_amount = $this->total_net_amount + $total_vat_amount;
        $this->save();
    }

    public function getConfigDateFormat()
    {
        return config('invoice.languages.'.$this->language.'.date_format');
    }

    public function moneyFormat(string $field)
    {
        return $this->formatCurrency($this->$field, $this);
    }

    public function hasVatSummary()
    {
        return config('invoice.countries.'.$this->vendor_country.'.vat_summary');
    }
}
