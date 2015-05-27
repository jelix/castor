<?php
/**
* @author      Loic Mathaud
* @contributor Laurent Jouanneau
* @copyright   2006 Loic Mathaud
* @copyright   2006-2015 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/

namespace Jelix\Castor;

/**
 * Main class of Castor
 */
class Castor extends CastorCore {

    /**
     * include the compiled template file and call one of the generated function
     * @param string|jSelectorTpl $tpl template selector
     * @param string $outputtype the type of output (html, text etc..)
     * @param boolean $trusted  says if the template file is trusted or not
     * @return string the suffix name of the function to call
     */
    protected function getTemplate ($tpl, $outputtype = '', $trusted = true) {
        $tpl = Config::$templatePath . $tpl;
        if ($outputtype == '')
            $outputtype = 'html';

        $cachefile = dirname($this->_templateName).'/';
        if ($cachefile == './')
            $cachefile = '';

        if (Config::$cachePath == '/' || Config::$cachePath == '')
            throw new Exception('cache path is invalid ! its value is: "'.Config::$cachePath.'".');

        $cachefile = Config::$cachePath.$cachefile.$outputtype.($trusted?'_t':'').'_'.basename($tpl);

        $mustCompile = Config::$compilationForce || !file_exists($cachefile);
        if (!$mustCompile) {
            if (filemtime($tpl) > filemtime($cachefile)) {
                $mustCompile = true;
            }
        }

        if ($mustCompile) {
            $compiler = new Compiler();
            $compiler->compile($this->_templateName, $tpl, $outputtype, $trusted,
                               $this->userModifiers, $this->userFunctions);
        }
        require_once($cachefile);
        return md5($tpl.'_'.$outputtype.($trusted?'_t':''));
    }

    public function fetch ($tpl, $outputtype='', $trusted = true, $callMeta=true) {
        return $this->_fetch($tpl, $tpl, $outputtype, $trusted, $callMeta);
    }
    
    protected function loadCompiler() {
        return  $cachePath = Config::$cachePath . '/virtuals/';
    }

    protected function compilationNeeded($cacheFile) {
        return Config::$compilationForce || !file_exists($cacheFile);
    }

    /**
     * return the current encoding
     * @return string the charset string
     * @since 1.0b2
     */
    public static function getEncoding () {
        return Config::$charset;
    }


    public function getLocaleString($locale) {
        $getter = Config::$localesGetter;
        if ($getter)
            $res = call_user_func($getter, $locale);
        else
            $res = $locale;
        return $res;
    }
}

