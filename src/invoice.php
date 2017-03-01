<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Számlázás alapértékek
    |--------------------------------------------------------------------------
    |
    | Az InvoiceManager példányosításakor ezek az alapértékek kerülnek használatra.
    |
    | global_pad_prefix - Számlatömb előtag MINDEN az alkalmazás általa kiállított számlán
    | electronic - elektronikus eláírással ellátott számla
    | remark_global - Megjegyzés, ami az alkalmazás által kiállított MINDEN számlán megjelenik (Pl.: Kellemes karácsonyi ünnepeket!)
    | pad_class - alapértelmezett számlatömb osztály (opcionális)
    | pad_id - alapértelmezett számlatömb pk (opcionális)
    */

    'defaults' => [
        'profile' => 'hungarian',
        'global_pad_prefix' => '',
        'electronic' => false,
        'remark_global' => '',
        'pad_class' => \App\Models\Pad::class,
        'pad_id' => 1,
    ],

    /*
    |--------------------------------------------------------------------------
    | Számlázási profilok
    |--------------------------------------------------------------------------
    |
    | A profilok arra használhatók, hogy több - elsősorban kinézettel kapcsolatos -
    | jellemzőt egy profil alá csoportosítsunk. A profil váltással így több jellemzőt
    | tudunk egyszerre átállítani. A profilokat kialakíthatjuk cégenként, nemzetiség,
    | stb szerint.
    |
    */

    'profiles' => [
        'hungarian' => [
            'currency' => 'huf',
            'language' => 'hu',
            'template' => 'simple'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Pénznemek
    |--------------------------------------------------------------------------
    |
    | A számlázásban felhasználható pénznemek.
    |
    | kulcs - 3 betüs pénznem kód kisbetüvel
    | code - 3 betüs pénznem kód (ISO 4217)
    |
    */

    'currencies' => [
        'huf' => [
            'code' => 'HUF',
            'sign' => 'Ft',
            'prefix' => false,
        ],
        /*'eur' => [
            'code' => 'EUR',
            'sign' => '&euro;',
            'prefix' => true,
        ],
        'usd' => [
            'code' => 'USD',
            'sign' => '$',
            'prefix' => true,
        ],
        'gbp' => [
            'code' => 'GBP',
            'sign' => '&pound;',
            'prefix' => true,
        ],*/
    ],

    /*
    |--------------------------------------------------------------------------
    | Nyelvek
    |--------------------------------------------------------------------------
    |
    | A számlaképen felhasználható nyelvek.
    |
    | Kulcs - Kétbetűs nyelvkód, ami megegyezik a resources/lang könyvtárban lévő nyelv kódokkal
    */

    'languages' => [
        'hu' => [
            'decimals' => 2,
            'dec_point' => ',',
            'thousands_sep' => ' ',
            'date_format' => 'Y.m.d.'
        ],
        /*'en' => [
            'decimals' => 2,
            'dec_point' => '.',
            'thousands_sep' => ',',
            'date_format' => 'j/n/Y'
        ],
        'de' => [
            'decimals' => 2,
            'dec_point' => '.',
            'thousands_sep' => ',',
            'date_format' => 'j.n.Y'
        ],*/
    ],

    /*
    |--------------------------------------------------------------------------
    | Országok
    |--------------------------------------------------------------------------
    |
    | A számla kiállító ezekben az országokban lehet bejegyezve (ahol adót fizet).
    | Ez dönti el milyen szabályrendszert követel meg a számlázó a számla kiállításnál.
    |
    | kulcs - Kétbetüs ország kód (ISO 3166)
    | vat_summary - van-e áfa bontás a számlaképen
    */

    'countries' => [
        'HU' => [
            'vat_summary' => true,
            'rules' => Paytech\Invoice\Core\Rule\HuRules::class,
        ],
        /*'UK' => [
            'vat_summary' => false,
            'rules' => '',
        ],*/
    ],

];
