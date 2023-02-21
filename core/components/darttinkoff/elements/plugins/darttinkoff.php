<?php
/** @var modX $modx */
switch ($modx->event->name) {
	case 'msOnManagerCustomCssJs':
		if(!empty($scriptProperties['page'])) {
			if($scriptProperties['page'] == 'orders') {
				$corePath = $modx->getOption('darttinkoff_core_path', array(), $modx->getOption('core_path') . 'components/darttinkoff/');
				$controller->dartTinkoff = $modx->getService('dartTinkoff', 'dartTinkoff', $corePath . 'model/');
				$controller->dartTinkoff->loadCustomOrderJsCss();
			}
		}
		break;
	case "msOnChangeOrderStatus":
		$corePath = $modx->getOption('darttinkoff_core_path', array(), $modx->getOption('core_path') . 'components/darttinkoff/');
		$dartTinkoff = $modx->getService('dartTinkoff', 'dartTinkoff', $corePath . 'model/');
		if($dartTinkoff){
			if ($status == $modx->getOption("darttinkoff_generate_status")) {
				$order_id = $order->get('id');
				$data = $dartTinkoff->init($order_id);
				if($modx->getOption('darttinkoff_log')){
					$dartTinkoff->to_log($data, "PLUGIN");
				}
			}
		}else{
			$modx->log(xPDO::LOG_LEVEL_ERROR, 'dartTinkoff: не могу инициализировать класс!');
		}
		break;
}