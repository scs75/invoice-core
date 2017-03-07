<?php

namespace Paytech\Invoice\Core\Contract;

use Carbon\Carbon;
use Paytech\Invoice\Core\Model\Invoice;
use Paytech\Invoice\Core\Model\Vat;
use Paytech\Invoice\Core\PaymentModeEnum;

/**
 * InvoiceManager interface
 *
 * @package Paytech\Invoice\Core\Contract
 * @author  Sáray Csaba <csaba.saray@paytech.hu>
 * @license http://paytech.hu All rights reserved.
 */
interface InvoiceManager
{
    /*
    |--------------------------------------------------------------------------
    | Számla fő jellemzői
    |--------------------------------------------------------------------------
    */
    /**
     * Számla tömb
     *
     * @param InvoicePad $pad
     * @return InvoiceManager
     */
    public function pad(InvoicePad $pad);

    /**
     * Vevő
     *
     * @param InvoiceCustomer $customer
     * @return InvoiceManager
     */
    public function customer(InvoiceCustomer $customer);

    /**
     * Teljesítés dátuma
     *
     * @param Carbon $supply_d
     * @return InvoiceManager
     */
    public function supply(Carbon $supply_d);

    /**
     * Fizetés módja és határideje
     *
     * @param string $payment_mode
     * @param Carbon|null $deadline_d
     * @return InvoiceManager
     */
    public function payment(string $payment_mode, Carbon $deadline_d = null);

    /**
     * Számla tétel hozzáadás
     *
     * @param InvoiceItem $item
     * @param float $net_unit_price
     * @param int $quantity
     * @param Vat|null $vat
     * @return InvoiceManager
     */
    public function item(InvoiceItem $item, float $net_unit_price, int $quantity, Vat $vat = null);

    /**
     * Egyedi megjegyzés (remark_custom)
     * Különbözet szerinti szabályozás – használt cikkek; Fordított adózás, stb
     *
     * @param string $remark
     * @return InvoiceManager
     */
    public function remark(string $remark);

    /*
    |--------------------------------------------------------------------------
    | Számla egyéb jellemzői
    |--------------------------------------------------------------------------
    */
    /**
     * Számlázási profil
     *
     * @param string $profile A konfig fájlban definiált profil
     * @return InvoiceManager
     */
    public function profile(string $profile);

    /**
     * Számlakép sablon
     *
     * @param string $template
     * @return InvoiceManager
     */
    public function template(string $template);

    /**
     * Számla pénzneme
     *
     * @param string $currency A konfig fájlban definiált pénznem Pl.: huf, eur
     * @return InvoiceManager
     */
    public function currency(string $currency);

    /**
     * Számlakép nyelve
     *
     * @param string $language A konfig fájlban definiált nyelv Pl.: hu, en
     * @return InvoiceManager
     */
    public function language(string $language);

    /**
     * Papír alapú számla
     *
     * @return InvoiceManager
     */
    public function paper();

    /**
     * Elektronikus alapú számla (E-számla)
     *
     * @return InvoiceManager
     */
    public function electronic();

    /*
    |--------------------------------------------------------------------------
    | Létrehozó metódusok
    |--------------------------------------------------------------------------
    */
    /**
     * InvoiceManager alapállapotba hozása
     */
    public function reset();

    /**
     * Normál számla létrehozása
     * @return Invoice
     */
    public function create();

    /**
     * Díjbekérő létrehozása
     * @return Invoice
     */
    public function createProforma();

    /**
     * Előlegszámla létrehozása
     * @return Invoice
     */
    public function createAdvance();

    /**
     * Végszámla létrehozása proforma vagy előleg számla (számlák) alapján
     *
     * @param \Paytech\Invoice\Core\Model\Invoice[] ...$invoice
     * @return Invoice
     */
    public function createFinal(Invoice ...$invoice);

    /**
     * Stornó számla létrehozása normál számla alapján
     *
     * @param Invoice $invoice
     * @return Invoice
     */
    public function cancel(Invoice $invoice);
}

