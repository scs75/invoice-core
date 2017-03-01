<?php

namespace Paytech\Invoice\Core\Model;

use App\Models\MyModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DB;
use Collective\Html\Eloquent\FormAccessible;

/**
 * Számla
 *
 * @package App\Models
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
        $row = InvoiceItem::where('invoice_id', $this->id)
            ->select(
                DB::raw('SUM(net_price) as total_net_amount'),
                DB::raw('SUM(vat_amount) as total_vat_amount'),
                DB::raw('SUM(gross_price) as total_gross_amount')
            )->first();
        $this->total_net_amount = $row->total_net_amount;
        $this->total_vat_amount = $row->total_vat_amount;
        $this->total_gross_amount = $row->total_gross_amount;
        $this->save();
    }

    public function getConfigDateFormat()
    {
        return config('invoice.languages.'.$this->language.'.date_format');
    }

    public function moneyFormat(string $field)
    {
        $lang = config('invoice.languages.'.$this->language);
        $amount = number_format($this->$field,
            $lang['decimals'],
            $lang['dec_point'],
            $lang['thousands_sep']
        );
        $amount = $amount.' '.config('invoice.currencies.'.$this->currency.'.code');
        return $amount;
    }

    public function hasVatSummary()
    {
        return config('invoice.countries.'.$this->vendor_country.'.vat_summary');
    }
}
