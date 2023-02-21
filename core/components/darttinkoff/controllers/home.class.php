<?php

/**
 * The home manager controller for dartTinkoff.
 *
 */
class dartTinkoffHomeManagerController extends modExtraManagerController
{
    /** @var dartTinkoff $dartTinkoff */
    public $dartTinkoff;


    /**
     *
     */
    public function initialize()
    {
		$corePath = $this->modx->getOption('darttinkoff_core_path', array(), $this->modx->getOption('core_path') . 'components/darttinkoff/');
        $this->dartTinkoff = $this->modx->getService('dartTinkoff', 'dartTinkoff', $corePath . 'model/');
        parent::initialize();
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['darttinkoff:default'];
    }


    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return true;
    }


    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('darttinkoff');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->dartTinkoff->config['cssUrl'] . 'mgr/main.css');
        $this->addJavascript($this->dartTinkoff->config['jsUrl'] . 'mgr/darttinkoff.js');
        $this->addJavascript($this->dartTinkoff->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->dartTinkoff->config['jsUrl'] . 'mgr/misc/combo.js');
        $this->addJavascript($this->dartTinkoff->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->dartTinkoff->config['jsUrl'] . 'mgr/sections/home.js');

        $this->addHtml('<script type="text/javascript">
        dartTinkoff.config = ' . json_encode($this->dartTinkoff->config) . ';
        dartTinkoff.config.connector_url = "' . $this->dartTinkoff->config['connectorUrl'] . '";
        Ext.onReady(function() {MODx.load({ xtype: "darttinkoff-page-home"});});
        </script>');
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .= '<div id="darttinkoff-panel-home-div"></div>';

        return '';
    }
}