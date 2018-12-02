<?php $cachePath = DOC_ROOT . '/system/'; // если не указать, кеш не будет сохран€тьс€ на диск
$userAgent = 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)';
$version   = '1.0';
$locale    = '';
$logFile   = '';
$extraPluginPath = ''; // путь к папке с плагинами, как оно работает, пока загадка
$localeStorageQuote = 5 * 1024 * 1024; // bytes // во всех браузерах это ограничение = 5 ћЅ
$sessionStorageQuota = 0; // no limit, 0 - без лимита
$jsFlags = 0;
$autoDetectProxy = true;

// set setting for all Chromium in App
chromium_settings(
        $cachePath,
        $userAgent,
        $version,
        $locale,
        $logFile,
        $extraPluginPath,
        $localeStorageQuote,
        $sessionStorageQuota,
        $jsFlags,
        $autoDetectProxy
);
chromium_allowedcall(array('myFunction1', 'myFunction2', 'myClass::myMethod1'));
