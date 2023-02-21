<?php

class createPaymentProcessor extends modProcessor {
	public $objectType = 'dartTinkoffPayment';
	public $classKey = 'dartTinkoffPayment';
	public $languageTopics = array('darttinkoff');

	public function initialize() {
		$order_id = trim($this->getProperty('order_id'));
		if (empty($order_id)) {
			$this->modx->error->addField('order_id', $this->modx->lexicon('darttinkoff_payment_err_order_id'));
		}
		return true;
	}

	/** {@inheritDoc} */
	public function process() {
		$order_id = trim($this->getProperty('order_id'));
		// создаем новый платеж
		$corePath = $this->modx->getOption('darttinkoff_core_path', array(), $this->modx->getOption('core_path') . 'components/darttinkoff/');

		$dartTinkoff = $this->modx->getService('dartTinkoff', 'dartTinkoff', $corePath . 'model/', array());
		if (!$dartTinkoff) {
			$this->modx->log(xPDO::LOG_LEVEL_ERROR, 'dartTinkoff: Не могу инициализировать класс!');
		}else{
			$data = $dartTinkoff->init($order_id);
			$dartTinkoff->to_log($data, 'CREATE');
		}
		$this->logManagerAction();
		//$this->cleanup();
		return $this->success('');
	}


	/** {@inheritDoc} */
	public function logManagerAction() {
		$this->modx->logManagerAction($this->objectType.'_sync',$this->classKey, 0);
	}


}

return 'createPaymentProcessor';