<?php
/**
* @author      Laurent Jouanneau
* @copyright   2015 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/


class configTest extends \PHPUnit\Framework\TestCase {
    
    
    function testLang() {
        $cachePath = realpath(__DIR__.'/temp/') . '/';
        $templatePath = __DIR__.'/';
        $config = new \Jelix\Castor\Config($cachePath, $templatePath);

        $this->assertEquals('en', $config->getLang());

        $this->assertEquals('In the template %2$s, invalid syntax on tag %1$s', $config->getMessage('errors.tpl.tag.syntax.invalid'));
    }
}
