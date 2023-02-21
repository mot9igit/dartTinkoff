<?php
if (file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php')) {
    /** @noinspection PhpIncludeInspection */
    require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
} else {
    require_once dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . '/config.core.php';
}
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var dartTinkoff $dartTinkoff */
$corePath = $modx->getOption('darttinkoff_core_path', null, $modx->getOption('core_path') . 'components/darttinkoff/');
$dartTinkoff = $modx->getService('dartTinkoff', 'dartTinkoff', $corePath . 'model/');
$modx->lexicon->load('darttinkoff:default');

// handle request
$path = $modx->getOption('processorsPath', $dartTinkoff->config, $corePath . 'processors/');
$modx->getRequest();

/** @var modConnectorRequest $request */
$request = $modx->request;
$request->handleRequest([
    'processors_path' => $path,
    'location' => '',
]);