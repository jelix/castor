<?php
/**
* @author      Laurent Jouanneau
* @copyright   2025 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/


class CastorTestSyntax2 extends \PHPUnit\Framework\TestCase {

    protected static $castorConfig;

    function setUp() : void {
        $cachePath = realpath(__DIR__.'/temp/') . '/';
        $templatePath = __DIR__.'/';
        self::$castorConfig = new \Jelix\Castor\Config($cachePath, $templatePath);
        self::$castorConfig->addPluginsRepository(__DIR__.'/plugins');
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
        $result = $tpl->fetch('assets_syntax2/countries.ctpl');
        $this->assertEquals(file_get_contents(__DIR__.'/assets_syntax2/countries_empty.txt'), $result);

        $countries = array('France', 'Italie', 'Espagne', 'Belgique');
        $tpl->assign('countries', $countries);
        $result = $tpl->fetch('assets_syntax2/countries.ctpl');
        $this->assertEquals(file_get_contents(__DIR__.'/assets_syntax2/countries.txt'), $result);
    }

    function testMacro() {
        $tpl = new \Jelix\Castor\Castor(self::$castorConfig);

        $tpl->assign('existingVar', 'realValueOfExistingVar');
        $result = $tpl->fetch('assets_syntax2/macros.ctpl');
        $this->assertEquals(file_get_contents(__DIR__.'/assets_syntax2/macros.txt'), $result);

    }


    function testModifiers() {
        $tpl = new \Jelix\Castor\Castor(self::$castorConfig);
        $json = json_encode(['foo'=>'foo value', 'bar'=>'bar value']);
        $tpl->assign('myjson', $json);
        $tpl->assign('mydate', '2024-08-25');

        $result = $tpl->fetch('assets_syntax2/modifiers.ctpl');
        $this->assertEquals(file_get_contents(__DIR__.'/assets_syntax2/modifiers.txt'), $result);

    }

    function testContentType() {
        $tpl = new \Jelix\Castor\Castor(self::$castorConfig);

        $result = $tpl->fetch('assets_syntax2/content_html.ctpl');
        $this->assertEquals(file_get_contents(__DIR__.'/assets_syntax2/content_html.txt'), $result);

        $result = $tpl->fetch('assets_syntax2/content_text.ctpl');
        $this->assertEquals(file_get_contents(__DIR__.'/assets_syntax2/content_text.txt'), $result);
    }
}