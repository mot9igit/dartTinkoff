<?php

class dartTinkoffPaymentUpdateProcessor extends modObjectUpdateProcessor
{
    public $objectType = 'dartTinkoffPayment';
    public $classKey = 'dartTinkoffPayment';
    public $languageTopics = ['darttinkoff'];
    //public $permission = 'save';


    /**
     * We doing special check of permission
     * because of our objects is not an instances of modAccessibleObject
     *
     * @return bool|string
     */
    public function beforeSave()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $id = (int)$this->getProperty('id');
        $name = trim($this->getProperty('name'));
        if (empty($id)) {
            return $this->modx->lexicon('darttinkoff_item_err_ns');
        }

        return parent::beforeSet();
    }
}

return 'dartTinkoffPaymentUpdateProcessor';
