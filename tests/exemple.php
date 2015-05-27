<?php 
error_reporting(E_ALL);
include '../vendor/autoload.php';

\Jelix\Castor\Config::$cachePath = realpath(__DIR__.'/temp/') . '/';
\Jelix\Castor\Config::$localizedMessagesPath = realpath(__DIR__.'/../lib/tpl/locales/') . '/';
\Jelix\Castor\Config::$templatePath = __DIR__.'/';
\Jelix\Castor\Config::addPluginsRepository(realpath(__DIR__.'/../lib/plugins/'));

$tpl = new \Jelix\Castor\Castor();

$countries = array('France', 'Italie', 'Espagne', 'Belgique');
$tpl->assign('countries', $countries);
$tpl->assign('titre', 'This is a test !');
$tpl->display('test.tpl');

$tpl = new \Jelix\Castor\Castor();
$tpl->assign('titre', 'This is an other test !');
$tpl->display('foo/test.tpl');



