<?php

require_once('../lib/tpl/jtpl_standalone_prepend.php');
jTplConfig::$lang = 'fr';
$pluginPath = __DIR__.'/../lib/plugins/';
if (file_exists($pluginPath)) {
    jTplConfig::addPluginsRepository(realpath($pluginPath));   
}
