<?php

namespace Paytech\Invoice\Core\Contract;

/**
 * InvoiceVendor interface
 * Ezt kell implementálnia a számlakiállító modellnek
 *
 * @package Paytech\Invoice\Core\Contract
 * @author  Sáray Csaba <csaba.saray@paytech.hu>
 * @license http://paytech.hu All rights reserved.
 */
interface InvoiceVendor
{
    /**
     * Számla kiállító neve
     *
     * @return string
     */
    public function getVendorName();

    /**
     * Ir. szám
     *
     * @return string
     */
    public function getVendorZipCode();

    /**
     *
     *
     * @return string
     */
    public function getVendorCity();

    /**
     *
     *
     * @return string
     */
    public function getVendorStreet();

    /**
     *
     *
     * @return string
     */
    public function getVendorTaxNumber();

    /**
     *
     *
     * @return string
     */
    public function getVendorEuTaxNumber();

    /**
     *
     *
     * @return string
     */
    public function getVendorBankAccount();

    /**
     *
     *
     * @return string
     */
    public function getVendorEmail();

    /**
     *
     *
     * @return string
     */
    public function getVendorPhone();

    /**
     *
     *
     * @return string
     */
    public function getVendorCountry();

    /**
     * Számlakiállító megjegyzése
     * Pl.: Kisadózó; Pénzforgalmi elszámolás, stb
     *
     * @return string
     */
    public function getVendorRemark();

    /**
     * Számlakiállító logo relatív ótvonala a public könyvtárban
     * 80x80-as png vagy jpg fájl
     * A számla fejlécében jelenik meg
     * Pl.: admin-asset/img/brand_logo.png
     *
     * @return string
     */
    public function getVendorLogo();
}