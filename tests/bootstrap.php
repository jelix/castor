<?php
require_once('../vendor/autoload.php');
\Jelix\Castor\Config::$cachePath = realpath(__DIR__.'/temp/') . '/';
\Jelix\Castor\Config::$localizedMessagesPath = realpath(__DIR__.'/../lib/tpl/locales/') . '/';
\Jelix\Castor\Config::$templatePath = __DIR__.'/';
\Jelix\Castor\Config::addPluginsRepository(realpath(__DIR__.'/../lib/plugins/'));
\Jelix\Castor\Config::$lang = 'fr';
