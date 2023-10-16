<?php
/**
* @author      Laurent Jouanneau
* @copyright   2015-2022 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/


class CastorTest extends \PHPUnit\Framework\TestCase {

    protected static $castorConfig;

    function setUp() : void {
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
        $result = $tpl->fetch('assets/countries.tpl');
        $this->assertEquals(file_get_contents(__DIR__.'/assets/countries_empty.txt'), $result);

        $countries = array('France', 'Italie', 'Espagne', 'Belgique');
        $tpl->assign('countries', $countries);
        $result = $tpl->fetch('assets/countries.tpl');
        $this->assertEquals(file_get_contents(__DIR__.'/assets/countries.txt'), $result);
    }

    function testMacro() {
        $tpl = new \Jelix\Castor\Castor(self::$castorConfig);

        $tpl->assign('existingVar', 'realValueOfExistingVar');
        $result = $tpl->fetch('assets/macros.tpl');
        $this->assertEquals(file_get_contents(__DIR__.'/assets/macros.txt'), $result);

    }


    function testModifiers() {
        $tpl = new \Jelix\Castor\Castor(self::$castorConfig);
        $json = json_encode(['foo'=>'foo value', 'bar'=>'bar value']);
        $tpl->assign('myjson', $json);
        $result = $tpl->fetch('assets/modifiers.tpl');
        $this->assertEquals(file_get_contents(__DIR__.'/assets/modifiers.txt'), $result);

    }


}