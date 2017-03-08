<?php

namespace Paytech\Invoice\Core\Model;

use Illuminate\Database\Eloquent\Model;
use Paytech\Invoice\Core\MoneyFormatTrait;

/**
 * Számla áfa bontás tétel
 * Egy group by-os nézettáblára épül.
 *
 * @package Paytech\Invoice\Core\Model
 * @author Sáray Csaba <csaba.saray@paytech.hu>
 * @licence http://paytech.hu All rights reserved
 */
class InvoiceVatItem extends Model
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
    protected $title_field = 'vat_name';

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
    /*
    |--------------------------------------------------------------------------
    | Accesorok és Mutátorok (form accessorok is)
    |--------------------------------------------------------------------------
    */
    public function getVatAmountAttribute($value)
    {
        return ($this->invoice->currency == 'huf') ? round($value) : $value;
    }
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
