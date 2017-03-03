<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Paytech\Invoice\Core\PaymentModeEnum;
use Paytech\Invoice\Core\InvoiceTypeEnum;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vats', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 128)->unique()->comment('áfa név');
            $table->decimal('multiplier', 5, 2)->unsigned()->nullable()->comment('szorzó');//százalék

            $table->boolean('is_default')->comment('alapértelmezett');
            $table->boolean('is_active')->comment('aktív');
            $table->timestamps();

        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number', 64)->unique()->comment('számla sorszám');
            $table->enum('type', InvoiceTypeEnum::getArray())->index()->comment('számla típusa');
            $table->boolean('is_cancelled')->index()->comment('stornózva');
            $table->boolean('is_corrected')->index()->comment('helyesbítve');
            $table->integer('related_id')->unsigned()->nullable()->comment('kapcsolodó számla');// stornózott, helyesbített, előzmény, stb

            $table->string('vendor_name', 100)->comment('kiállító neve');
            $table->string('vendor_zip_code', 10)->comment('kiállító irányítószám');
            $table->string('vendor_city', 64)->comment('kiállító város');
            $table->string('vendor_street', 100)->comment('kiállító utca');
            $table->string('vendor_country', 2)->comment('kiállító ország');
            $table->string('vendor_tax_number', 20)->comment('kiállító adószám');
            $table->string('vendor_eu_tax_number', 20)->comment('kiállító közösségi adószám');
            $table->string('vendor_bank_account', 32)->comment('kiállító bankszámlaszáma');
            $table->string('vendor_email', 32)->comment('kiállító email címe');
            $table->string('vendor_phone', 32)->comment('kiállító telefonszáma');
            $table->string('vendor_logo', 128)->comment('kiállító logo');

            $table->string('customer_name', 100)->comment('vevő neve');
            $table->string('customer_zip_code', 10)->comment('vevő irányítószám');
            $table->string('customer_city', 64)->comment('vevő város');
            $table->string('customer_street', 100)->comment('vevő utca');
            $table->string('customer_country', 2)->comment('vevő ország');
            $table->string('customer_tax_number', 20)->comment('vevő adószám');
            $table->string('customer_eu_tax_number', 20)->comment('vevő közösségi adószám');

            $table->date('release_d')->comment('kiállítás dátuma');
            $table->date('supply_d')->nullable()->comment('teljesítés dátuma');
            $table->date('deadline_d')->nullable()->comment('fizetési határidő');
            $table->enum('payment_mode', PaymentModeEnum::getArray())->index()->comment('fizetési mód');
            $table->boolean('is_paid')->index()->comment('kiegyenlítve');
            $table->date('payment_d')->nullable()->comment('kiegyenlítés dátuma');

            $table->decimal('total_net_amount', 12, 2)->comment('számla nettó érték');
            $table->decimal('total_vat_amount', 12, 2)->comment('számla áfa érték');
            $table->decimal('total_gross_amount', 12, 2)->comment('számla bruttó érték');

            $table->string('remark_global', 128)->comment('globális megjegyzés minden számlára');// pl.: Kellemes karácsonyi ünnepeket!
            $table->string('remark_vendor', 128)->comment('kiállító cég megjegyzése');// pl.: Kisadózó; Pénzforgalmi elszámolás, stb
            $table->string('remark_custom', 128)->comment('egyedi megjegyzés számlánként');// pl.: Különbözet szerinti szabályozás – használt cikkek; Fordított adózás, stb

            $table->string('language', 2)->index()->comment('számla nyelve');
            $table->string('currency', 3)->index()->comment('pénznem');
            $table->boolean('is_electronic')->index()->comment('e-számla');
            $table->string('template', 64)->comment('számla sablon');
            $table->string('pad_class', 128)->comment('számlatömb osztály');
            $table->integer('pad_id')->unsigned()->comment('számlatömb pk');

            $table->timestamps();

            $table->foreign('related_id')->references('id')->on('invoices');
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('vat_id')->unsigned();
            $table->string('description')->comment('megnevezés');
            $table->decimal('net_unit_price', 12, 2)->comment('nettó egységár');
            $table->integer('quantity')->comment('mennyiség');
            $table->string('unit', 32)->comment('egység');
            $table->string('vat_name', 128)->comment('áfa neve');
            $table->decimal('vat_multiplier', 5, 2)->comment('áfa szorzó');
            $table->decimal('gross_unit_price', 12, 2)->comment('bruttó egységár');
            $table->decimal('net_price', 12, 2)->comment('nettó ár');
            $table->decimal('gross_price', 12, 2)->comment('bruttó ár');
            $table->decimal('vat_amount', 12, 2)->comment('áfa tartalom');
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('vats');
    }
}
