<?php

class dartTinkoffPaymentGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'dartTinkoffPayment';
    public $classKey = 'dartTinkoffPayment';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';
    //public $permission = 'list';


    /**
     * We do a special check of permissions
     * because our objects is not an instances of modAccessibleObject
     *
     * @return boolean|string
     */
    public function beforeQuery()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = trim($this->getProperty('query'));
        $order_id = trim($this->getProperty('order_id'));
        if ($query) {
            $c->where([
                'payment_id:LIKE' => "%{$query}%",
                'OR:description:LIKE' => "%{$query}%",
            ]);
        }
        if($order_id){
			$c->where([
				'order_id:=' => $order_id
			]);
		}
        return $c;
    }


    /**
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $array = $object->toArray();
        $array['actions'] = [];

        // Edit
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-edit',
            'title' => $this->modx->lexicon('darttinkoff_payment_update'),
            //'multiple' => $this->modx->lexicon('darttinkoff_items_update'),
            'action' => 'updatePayment',
            'button' => true,
            'menu' => true,
        ];

		// Check
		$array['actions'][] = [
			'cls' => '',
			'icon' => 'icon icon-check action-green',
			'title' => $this->modx->lexicon('darttinkoff_payment_check'),
			'action' => 'checkPayment',
			'button' => true,
			'menu' => true,
		];

		// cancel
		$array['actions'][] = [
			'cls' => '',
			'icon' => 'icon icon-times action-red',
			'title' => $this->modx->lexicon('darttinkoff_payment_cancel'),
			'action' => 'cancelPayment',
			'button' => true,
			'menu' => true,
		];

        // Remove
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('darttinkoff_payment_remove'),
            'multiple' => $this->modx->lexicon('darttinkoff_payments_remove'),
            'action' => 'removePayment',
            'button' => true,
            'menu' => true,
        ];

        return $array;
    }

}

return 'dartTinkoffPaymentGetListProcessor';