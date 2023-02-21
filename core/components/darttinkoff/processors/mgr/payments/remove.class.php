<?php

class dartTinkoffPaymentRemoveProcessor extends modObjectProcessor
{
	public $objectType = 'dartTinkoffPayment';
	public $classKey = 'dartTinkoffPayment';
	public $languageTopics = ['darttinkoff'];
	//public $permission = 'remove';


	/**
	 * @return array|string
	 */
	public function process()
	{
		if (!$this->checkPermissions()) {
			return $this->failure($this->modx->lexicon('access_denied'));
		}

		$ids = $this->modx->fromJSON($this->getProperty('ids'));
		if (empty($ids)) {
			return $this->failure($this->modx->lexicon('darttinkoff_payment_err_ns'));
		}
		$corePath = $this->modx->getOption('darttinkoff_core_path', array(), $this->modx->getOption('core_path') . 'components/darttinkoff/');
		$dartTinkoff = $this->modx->getService('dartTinkoff', 'dartTinkoff', $corePath . 'model/', array());
		if (!$dartTinkoff) {
			$this->modx->log(xPDO::LOG_LEVEL_ERROR, 'dartTinkoff: Не могу инициализировать класс!');
		}else{
			foreach ($ids as $id) {
				/** @var dartTinkoffItem $object */
				if (!$object = $this->modx->getObject($this->classKey, $id)) {
					return $this->failure($this->modx->lexicon('darttinkoff_payment_err_nf'));
				}
				$response = $dartTinkoff->cancel($object->get('payment_id'));
				$object->remove();
			}
			return $this->success();
		}
	}
}

return 'dartTinkoffPaymentRemoveProcessor';