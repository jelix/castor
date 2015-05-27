<?php
/**
* @author      Laurent Jouanneau
* @copyright   2015 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/


class CastorTest extends PHPUnit_Framework_TestCase {

    protected static $castorConfig;

    function setUp() {
        $cachePath = realpath(__DIR__.'/temp/') . '/';
        $templatePath = __DIR__.'/';
        self::$castorConfig = new \Jelix\Castor\Config($cachePath, $templatePath);        
        $this->clearDir($cachePath);
    }

    protected function clearDir($path) {
        $dir = new DirectoryIterator($path);
        foreach ($dir as $dirContent) {
            if ($dirContent->isFile() || $dirContent->isLink()) {
                if ($dirContent->getBasename() != '.dummy') {
                    unlink($dirContent->getPathName());
                }
            }
        }
        unset($dir);
        unset($dirContent);
    }

    function testCountries() {
        $tpl = new \Jelix\Castor\Castor(self::$castorConfig);
        
        $tpl->assign('countries', array());
        $tpl->assign('titre', 'This is a test !');
        $result = $tpl->fetch('countries.tpl');
        $this->assertEquals(file_get_contents(__DIR__.'/countries_empty.txt'), $result);

        $countries = array('France', 'Italie', 'Espagne', 'Belgique');
        $tpl->assign('countries', $countries);
        $result = $tpl->fetch('countries.tpl');
        $this->assertEquals(file_get_contents(__DIR__.'/countries.txt'), $result);
    }

}