<?php

ini_set('apc.cache_by_default', 'Off');

$stream = file_get_contents('php://input');
$stream = json_decode($stream, true);
if (!empty($stream) AND is_array($stream)) {
	$_REQUEST = array_merge($_REQUEST, $stream);
}

define('MODX_API_MODE', true);
require dirname(dirname(dirname(dirname(__FILE__)))) . '/index.php';
$modx->getService('error', 'error.modError');
$modx->setLogLevel(modX::LOG_LEVEL_ERROR);
$modx->setLogTarget('FILE');

/* @var miniShop2 $miniShop2 */
$miniShop2 = $modx->getService('minishop2', 'miniShop2', $modx->getOption('minishop2.core_path', null,
		$modx->getOption('core_path') . 'components/minishop2/') . 'model/minishop2/', []);
$miniShop2->loadCustomClasses('payment');
/*
if (!class_exists('dartTinkoff')) {
	$modx->log(xPDO::LOG_LEVEL_ERROR, '[miniShop2:dartTinkoff] could not load payment class "dartTinkoff".');
}
*/

$corePath = $modx->getOption('darttinkoff_core_path', array(), $modx->getOption('core_path') . 'components/darttinkoff/');
$dartTinkoff = $modx->getService('dartTinkoff', 'dartTinkoff', $corePath . 'model/', array());
if (!$dartTinkoff) {
	$modx->log(xPDO::LOG_LEVEL_ERROR, 'dartTinkoff: Не могу инициализировать класс!');
}else {
	if ($modx->getOption('darttinkoff_log', null, false, true)) {
		$dartTinkoff->to_log($_REQUEST, 'NOTIFY');
	}

	if (!empty($_REQUEST['OrderId'])) {
		$result = true;
		/** @var msOrder $order */
		if (!$order = $modx->getObject('msOrder', ['id' => (string)$_REQUEST['OrderId']])) {
			$order = $modx->newObject('msOrder');
			$result = false;
		}
		$modx->switchContext($order->get('context'));
		if ($result) {
			$order_id = (string)$_REQUEST['OrderId'];
			$payment_id = (string)$_REQUEST['PaymentId'];
			$dartTinkoff->receive($order_id, $payment_id, $_REQUEST);
		} else {
			$dartTinkoff->paymentError('Order not found', $_REQUEST);
		}

	} else {
		$modx->log(xPDO::LOG_LEVEL_ERROR, '[miniShop2:dartTinkoff] Wrong orderId.');
	}
}

echo 'OK';