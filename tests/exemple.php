<?php 
error_reporting(E_ALL);
include '../vendor/autoload.php';


$cachePath = realpath(__DIR__.'/temp/') . '/';
$templatePath = __DIR__.'/';
$config = new \Jelix\Castor\Config($cachePath, $templatePath);

$tpl = new \Jelix\Castor\Castor($config);

$countries = array('France', 'Italie', 'Espagne', 'Belgique');
$tpl->assign('countries', $countries);
$tpl->assign('titre', 'This is a test !');
$tpl->display('test.tpl');

$tpl = new \Jelix\Castor\Castor($config);
$tpl->assign('titre', 'This is an other test !');
$tpl->display('foo/test.tpl');



