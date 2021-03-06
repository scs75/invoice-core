<?php

namespace Paytech\Invoice\Core\Model;

use Illuminate\Database\Eloquent\Model;
use Paytech\Invoice\Core\MoneyFormatTrait;

/**
 * Számla tétel
 *
 * @package Paytech\Invoice\Core\Model
 * @author Sáray Csaba <csaba.saray@paytech.hu>
 * @licence http://paytech.hu All rights reserved
 */
class InvoiceItem extends Model
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
    protected $fillable = [
        'product_id', 'vat_id', 'description', 'net_unit_price', 'quantity', 'unit', 'vat_name', 'vat_multiplier',
        'gross_unit_price', 'net_price', 'gross_price', 'vat_amount'
    ];
    protected $dates = [
    ];
    protected $casts = [
    ];
    protected $title_field = 'id';

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
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    public function vat()
    {
        return $this->belongsTo(Vat::class);
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
    public function moneyFormat(string $field)
    {
        return $this->formatCurrency($this->$field, $this->invoice);
    }
}
