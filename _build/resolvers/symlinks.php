<?php
/** @var xPDOTransport $transport */
/** @var array $options */
/** @var modX $modx */
if ($transport->xpdo) {
    $modx =& $transport->xpdo;

    $dev = MODX_BASE_PATH . 'Extras/dartTinkoff/';
    /** @var xPDOCacheManager $cache */
    $cache = $modx->getCacheManager();
    if (file_exists($dev) && $cache) {
        if (!is_link($dev . 'assets/components/darttinkoff')) {
            $cache->deleteTree(
                $dev . 'assets/components/darttinkoff/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_ASSETS_PATH . 'components/darttinkoff/', $dev . 'assets/components/darttinkoff');
        }
        if (!is_link($dev . 'core/components/darttinkoff')) {
            $cache->deleteTree(
                $dev . 'core/components/darttinkoff/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_CORE_PATH . 'components/darttinkoff/', $dev . 'core/components/darttinkoff');
        }
    }
}

return true;