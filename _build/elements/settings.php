<?php

return [
	'test' => [
		'xtype' => 'combo-boolean',
		'value' => true,
		'area' => 'darttinkoff_main',
	],
	'process_receipt' => [
		'xtype' => 'combo-boolean',
		'value' => false,
		'area' => 'darttinkoff_main',
	],
	'log' => [
		'xtype' => 'combo-boolean',
		'value' => false,
		'area' => 'darttinkoff_main',
	],
	'log_file' => [
		'xtype' => 'textfield',
		'value' => "dart.tinkoff.log",
		'area' => 'darttinkoff_main',
	],
    'test_url' => [
		'xtype' => 'textfield',
        'value' => "https://rest-api-test.tinkoff.ru/v2/",
        'area' => 'darttinkoff_main',
    ],
	'actual_url' => [
		'xtype' => 'textfield',
		'value' => "https://securepay.tinkoff.ru/v2/",
		'area' => 'darttinkoff_main',
	],
	'terminal_key' => [
		'xtype' => 'textfield',
		'value' => "",
		'area' => 'darttinkoff_main',
	],
	'secret_key' => [
		'xtype' => 'textfield',
		'value' => "",
		'area' => 'darttinkoff_main',
	],
	'pay_type' => [
		'xtype' => 'textfield',
		'value' => "T",
		'area' => 'darttinkoff_main',
	],
	'receipt_format' => [
		'xtype' => 'textfield',
		'value' => "1.00",
		'area' => 'darttinkoff_main',
	],
	'receipt_payment_mode' => [
		'xtype' => 'textfield',
		'value' => "full_prepayment",
		'area' => 'darttinkoff_main',
	],
	'receipt_payment_subject' => [
		'xtype' => 'textfield',
		'value' => "commodity",
		'area' => 'darttinkoff_main',
	],
	'payment_reference_term' => [
		'xtype' => 'textfield',
		'value' => "1d",
		'area' => 'darttinkoff_main',
	],
	'tax' => [
		'xtype' => 'textfield',
		'value' => "none",
		'area' => 'darttinkoff_main',
	],
	'taxation' => [
		'xtype' => 'textfield',
		'value' => "osn",
		'area' => 'darttinkoff_main',
	],
	'currency' => [
		'xtype' => 'textfield',
		'value' => "643",
		'area' => 'darttinkoff_main',
	],
	'success_page_id' => [
		'xtype' => 'textfield',
		'value' => "",
		'area' => 'darttinkoff_main',
	],
	'fail_page_id' => [
		'xtype' => 'textfield',
		'value' => "",
		'area' => 'darttinkoff_main',
	],
	'generate_status' => [
		'xtype' => 'textfield',
		'value' => "5",
		'area' => 'darttinkoff_main',
	]
];