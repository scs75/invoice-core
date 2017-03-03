<?php

namespace Paytech\Invoice\Core\Service;

use App\Support\ColorEnum;
use Carbon\Carbon;
use Collective\Html\HtmlFacade as Html;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Paytech\Invoice\Core\Contract\InvoiceCustomer;
use Paytech\Invoice\Core\Contract\InvoiceItem;
use Paytech\Invoice\Core\Contract\InvoiceManager;
use Paytech\Invoice\Core\Contract\InvoicePad;
use Paytech\Invoice\Core\Contract\InvoiceVendor;
use Paytech\Invoice\Core\InvoiceTypeEnum;
use Paytech\Invoice\Core\Model\Invoice;
use Paytech\Invoice\Core\Model\InvoiceItem as InvoiceItemModel;
use DB;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Számlázás szolgáltatás
 * Singleton. Ha többszöri használatnál óvatosan kell eljárni.
 *
 * InvoiceManager::pad($pad)
->customer($customer)
->supply($supply_date)
->payment(PaymentMethod::BANK_TRANSFER, $deadline)
->item($product1, $quantity1, $net_unit_price1)
->item($product2, $quantity2, $net_unit_price2)
->create();
 *
 * @package App\Support\Service
 * @author  Sáray Csaba <csaba.saray@paytech.hu>
 * @license http://paytech.hu All rights reserved.
 */
class InvoiceBuilder implements InvoiceManager
{
    private $settings = [];
    private $items = [];
    /**
     * @var InvoicePad
     */
    private $pad;

    /**
     * @var InvoiceVendor
     */
    private $vendor;

    /**
     * @var InvoiceCustomer
     */
    private $customer;

    /**
     * @var Carbon
     */
    private $supply_d;

    private $payment_mode;

    /**
     * @var Carbon
     */
    private $deadline_d;

    /**
     * @var string
     */
    private $remark_custom = '';

    public function __construct()
    {
        logger('hello const');
        $this->setDefaultSettings();
        if(isset($this->settings['pad_class'])) {
            $pad = $this->settings['pad_class']::findOrFail($this->settings['pad_id']);
            if($pad instanceof InvoicePad) {
                $this->pad($pad);
            }
        }
    }
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
    public function pad(InvoicePad $pad)
    {
        $this->vendor = $pad->getInvoiceVendor();
        $this->pad = $pad;
        return $this;
    }

    /**
     * Vevő
     *
     * @param InvoiceCustomer $customer
     * @return InvoiceManager
     */
    public function customer(InvoiceCustomer $customer)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * Teljesítés dátuma
     *
     * @param Carbon $supply_d
     * @return InvoiceManager
     */
    public function supply(Carbon $supply_d)
    {
        $this->supply_d = $supply_d;
        return $this;
    }

    /**
     * Fizetés módja és határideje
     *
     * @param string $payment_mode
     * @param Carbon|null $deadline_d
     * @return InvoiceManager
     */
    public function payment(string $payment_mode, Carbon $deadline_d = null)
    {
        $this->payment_mode = $payment_mode;
        $this->deadline_d = $deadline_d;
        return $this;
    }

    /**
     * Számla tétel hozzáadás
     *
     * @param InvoiceItem $item
     * @param float $net_unit_price
     * @param int $quantity
     * @return InvoiceManager
     */
    public function item(InvoiceItem $item, float $net_unit_price, int $quantity)
    {
        $multiplier = $item->getInvoiceVat()->multiplier;
        $gross_unit_price = $this->calculateGross($net_unit_price, $multiplier);
        $net_price = $net_unit_price * $quantity;
        $gross_price = $this->calculateGross($net_price, $multiplier);
        $vat_amount = $gross_price - $net_price;
        $this->items[] = new InvoiceItemModel([
            'product_id' => $item->id,
            'vat_id' => $item->getInvoiceVat()->id,
            'description' => $item->getInvoiceDescription(),
            'net_unit_price' => $net_unit_price,
            'quantity' => $quantity,
            'unit' => $item->getInvoiceUnit(),
            'vat_name' => $item->getInvoiceVat()->name,
            'vat_multiplier' => $multiplier,
            'gross_unit_price' => $gross_unit_price,
            'net_price' => $net_price,
            'gross_price' => $gross_price,
            'vat_amount' => $vat_amount,
        ]);
        return $this;
    }

    /**
     * Egyedi megjegyzés (remark_custom)
     * pl.: Különbözet szerinti szabályozás – használt cikkek; Fordított adózás, stb
     *
     * @param string $remark
     * @return InvoiceManager
     */
    public function remark(string $remark)
    {
        $this->remark_custom = $remark;
        return $this;
    }

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
     * @throws \Exception
     */
    public function profile(string $profile)
    {
        if(!array_has(config('invoice'), 'profiles.'.$profile)) {
            throw new \Exception('No such profile: '.$profile);
        }
        $this->settings['profile'] = $profile;
        $this->settings = array_merge($this->settings, config('invoice.profiles.'.$profile));
        return $this;
    }

    /**
     * Számlakép sablon
     *
     * @param string $template
     * @return InvoiceManager
     */
    public function template(string $template)
    {
        $this->settings['template'] = $template;
        return $this;
    }

    /**
     * Számla pénzneme
     *
     * @param string $currency A konfig fájlban definiált pénznem Pl.: huf, eur
     * @return InvoiceManager
     */
    public function currency(string $currency)
    {
        $this->settings['currency'] = $currency;
        return $this;
    }

    /**
     * Számlakép nyelve
     *
     * @param string $language A konfig fájlban definiált nyelv Pl.: hu, en
     * @return InvoiceManager
     */
    public function language(string $language)
    {
        $this->settings['language'] = $language;
        return $this;
    }

    /**
     * Papír alapú számla
     *
     * @return InvoiceManager
     */
    public function paper()
    {
        $this->settings['electronic'] = false;
        return $this;
    }

    /**
     * Elektronikus alapú számla (E-számla)
     *
     * @return InvoiceManager
     */
    public function electronic()
    {
        $this->settings['electronic'] = true;
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Létrehozó metódusok
    |--------------------------------------------------------------------------
    */
    /**
     * InvoiceManager alapállapotba hozása
     */
    public function reset()
    {
        $this->setDefaultSettings();
        $this->items = [];
        $this->pad = null;
        $this->vendor = null;
        $this->customer = null;
        $this->supply_d = null;
        $this->payment_mode = null;
        $this->deadline_d = null;
        $this->remark_custom = null;
    }

    /**
     * Normál számla létrehozása
     * @return Invoice
     */
    public function create()
    {
        $invoice = $this->populateInvoiceHead();
        $invoice->generateSerialNumber($this->pad->getPadPrefix());
        $invoice->save();
        $invoice->items()->saveMany($this->items);
        $invoice->calculateTotals();
        $this->validateInvoice($invoice);
        $this->createPdf($invoice);
        return $invoice;
    }

    /**
     * Díjbekérő létrehozása
     * @return Invoice
     */
    public function createProforma()
    {
        $invoice = $this->populateInvoiceHead();
        $invoice->type = InvoiceTypeEnum::PROFORMA;
        $invoice->generateSerialNumber('PRFM');
        $invoice->save();
        $invoice->items()->saveMany($this->items);
        $invoice->calculateTotals();
        $this->validateInvoice($invoice);
        $this->createPdf($invoice);
        return $invoice;
    }

    /**
     * Előlegszámla létrehozása
     * @return Invoice
     */
    public function createAdvance()
    {
        return new Invoice();
    }

    /**
     * Végszámla létrehozása
     *
     * @param \Paytech\Invoice\Core\Model\Invoice[] ...$invoice
     * @return mixed
     */
    public function createFinal(Invoice ...$invoice)
    {
        return new Invoice();
    }

    /**
     * Sztornó számla létrehozása
     *
     * @param Invoice $invoice
     * @return Invoice
     * @throws \Exception
     */
    public function cancel(Invoice $invoice)
    {
        switch ($invoice->type) {
            case InvoiceTypeEnum::NORMAL:
            case InvoiceTypeEnum::PARTIAL:
                if($invoice->is_cancelled || $invoice->is_corrected) {
                    throw new \Exception("Sztornózott vagy helyesbített számla nem sztornózható: ".$invoice->serial_number);
                }
                $cancel = $invoice->replicate();
                $cancel->type = InvoiceTypeEnum::CANCEL;
                $cancel->total_net_amount *= -1;
                $cancel->total_vat_amount *= -1;
                $cancel->total_gross_amount *= -1;
                $cancel->related_id = $invoice->id;
                $cancel->remark_custom = trans('invoice::msg.remark.cancel', ['serial_number' => $invoice->serial_number]);
                $pad_class = $invoice->pad_class;
                $pad = $pad_class::findOrFail($invoice->pad_id);
                $cancel->generateSerialNumber($pad->getPadPrefix());
                $cancel->push();
                foreach($invoice->items as $item) {
                    $cancel_item = $item->replicate();
                    $cancel_item->net_unit_price *= -1;
                    $cancel_item->gross_unit_price *= -1;
                    $cancel_item->net_price *= -1;
                    $cancel_item->gross_price *= -1;
                    $cancel_item->vat_amount *= -1;
                    $cancel->items()->save($cancel_item);
                }
                $this->validateInvoice($cancel);
                $invoice->is_cancelled = true;
                $invoice->save();
                $this->createPdf($cancel->fresh());
            return $cancel;
            default:
                throw new \Exception("Számla nem sztornózható: ".$invoice->serial_number);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Privát metódusok
    |--------------------------------------------------------------------------
    */
    /**
     * A settings tömböt alapállapotba hozza a konfig fájl alapján
     */
    private function setDefaultSettings()
    {
        $this->settings = config('invoice.defaults');// init
        $this->settings = array_merge($this->settings, config('invoice.profiles.'.$this->settings['profile']));
    }

    /**
     * Számla fejléc példányt állít elő a service állapota alapján
     *
     * @return Invoice
     */
    private function populateInvoiceHead()
    {
        $invoice = new Invoice();
        $invoice->type = InvoiceTypeEnum::NORMAL;
        $invoice->is_cancelled = false;
        $invoice->is_corrected = false;
        $invoice->vendor_name = $this->vendor->getVendorName();
        $invoice->vendor_zip_code = $this->vendor->getVendorZipCode();
        $invoice->vendor_city = $this->vendor->getVendorCity();
        $invoice->vendor_street = $this->vendor->getVendorStreet();
        $invoice->vendor_country = $this->vendor->getVendorCountry();
        $invoice->vendor_tax_number = $this->vendor->getVendorTaxNumber();
        $invoice->vendor_eu_tax_number = $this->vendor->getVendorEuTaxNumber();
        $invoice->vendor_bank_account = $this->vendor->getVendorBankAccount();
        $invoice->vendor_email = $this->vendor->getVendorEmail();
        $invoice->vendor_phone = $this->vendor->getVendorPhone();
        $invoice->vendor_logo = $this->vendor->getVendorLogo();

        $invoice->customer_name = $this->customer->getCustomerName();
        $invoice->customer_zip_code = $this->customer->getCustomerZipCode();
        $invoice->customer_city = $this->customer->getCustomerCity();
        $invoice->customer_street = $this->customer->getCustomerStreet();
        $invoice->customer_country = $this->customer->getCustomerCountry();
        $invoice->customer_tax_number = $this->customer->getCustomerTaxNumber();
        $invoice->customer_eu_tax_number = $this->customer->getCustomerEuTaxNumber();

        $invoice->release_d = Carbon::today();
        $invoice->supply_d = $this->supply_d;
        $invoice->deadline_d = $this->deadline_d;
        $invoice->payment_mode = $this->payment_mode;
        $invoice->is_paid = false;
        $invoice->payment_d = null;

        $invoice->total_net_amount = 0;
        $invoice->total_vat_amount = 0;
        $invoice->total_gross_amount = 0;

        $invoice->remark_global = $this->settings['remark_global'];
        $invoice->remark_vendor = $this->vendor->getVendorRemark();
        $invoice->remark_custom = $this->remark_custom;

        $invoice->language = $this->settings['language'];
        $invoice->currency = $this->settings['currency'];
        $invoice->is_electronic = $this->settings['electronic'];
        $invoice->template = $this->settings['template'];
        $invoice->pad_class = get_class($this->pad);
        $invoice->pad_id = $this->pad->id;
        return $invoice;
    }

    private function calculateGross($net, $multiplier)
    {
        $multi = (!is_null($multiplier)) ? (1 + ($multiplier / 100)) : 1;
        $gross = round($net * $multi, 2);
        return $gross;
    }

    /**
     * Leelenőrzi a számla páldányt az ország szabályrendszere szerint
     *
     * @param $invoice
     * @throws \Exception
     */
    private function validateInvoice($invoice)
    {
        $pad_class = $invoice->pad_class;
        $pad = $pad_class::findOrFail($invoice->pad_id);
        $country_code = $pad->getInvoiceVendor()->getVendorCountry();
        if(is_null(config('invoice.countries.'.$country_code))) {
            throw new \Exception('No such country code: '.$country_code);
        }
        $rule_class = config('invoice.countries.'.$country_code.'.rules');
        $reflection_class = new \ReflectionClass($rule_class);
        $methods = $reflection_class->getMethods(\ReflectionMethod::IS_PUBLIC);
        $rule = new $rule_class;
        foreach($methods as $method) {
            $rule->{$method->name}($invoice);
        }
    }

    /**
     * Legenálja a pdf-et és elmenti a fájlrendszerbe
     *
     * @param $invoice
     */
    private function createPdf($invoice)
    {
        $orig_locale = trans()->getLocale();
        trans()->setLocale($invoice->language);

        $pdf = \PDF::loadView('invoice::'.$invoice->template.'.index', compact('invoice'));
        $pdf->save(storage_path('app/invoice/'.$invoice->serial_number.'.pdf'));

/*        $html = view('invoice::'.$invoice->template.'.index', compact('invoice'))->render();
        $options = new Options();
        $options->set('isPhpEnabled', false);
        $dompdf = new Dompdf($options);
        $dompdf->render();
        $output = $dompdf->output();
        file_put_contents(storage_path('app/invoice/'.$invoice->serial_number.'.pdf'), $output);*/


        trans()->setLocale($orig_locale);


    }
}
