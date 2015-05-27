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

class Compiler extends CompilerCore {

    private $_locales;
    /**
     * Initialize some properties
     */
    function __construct () {
        parent::__construct();
        require_once(Config::$localizedMessagesPath.Config::$lang.'.php');
        $this->_locales = Config::$localizedMessages;
    }

    /**
     * Launch the compilation of a template
     *
     * Store the result (a php content) into a cache file inside the cache directory
     * @param string $tplfile the file name that contains the template
     * @return boolean true if ok
     */
    public function compile ($tplName, $tplFile, $outputtype, $trusted,
                             $userModifiers = array(), $userFunctions = array()) {
        $this->_sourceFile = $tplFile;
        $this->outputType = $outputtype;
        $cachefile = Config::$cachePath .dirname($tplName).'/'.$this->outputType.($trusted?'_t':'').'_'. basename($tplName);
        $this->trusted = $trusted;
        $md5 = md5($tplFile.'_'.$this->outputType.($this->trusted?'_t':''));

        if (!file_exists($this->_sourceFile)) {
            $this->doError0('errors.tpl.not.found');
        }

        $this->compileString(file_get_contents($this->_sourceFile), $cachefile,
            $userModifiers, $userFunctions, $md5);
        return true;
    }

    protected function _saveCompiledString($cachefile, $result) {
        $_dirname = dirname($cachefile).'/';

        if (!is_dir($_dirname)) {
            umask(jTplConfig::$umask);
            mkdir($_dirname, jTplConfig::$chmodDir, true);
        }
        else if (!@is_writable($_dirname)) {
            throw new \Exception (sprintf($this->_locales['file.directory.notwritable'], $cachefile, $_dirname));
        }

        // write to tmp file, then rename it to avoid
        // file locking race condition
        $_tmp_file = tempnam($_dirname, 'wrt');

        if (!($fd = @fopen($_tmp_file, 'wb'))) {
            $_tmp_file = $_dirname . '/' . uniqid('wrt');
            if (!($fd = @fopen($_tmp_file, 'wb'))) {
                throw new \Exception(sprintf($this->_locales['file.write.error'], $cachefile, $_tmp_file));
            }
        }

        fwrite($fd, $result);
        fclose($fd);

        // Delete the file if it already exists (this is needed on Win,
        // because it cannot overwrite files with rename()
        if (substr(PHP_OS,0,3) == 'WIN' && file_exists($cachefile)) {
            @unlink($cachefile);
        }

        @rename($_tmp_file, $cachefile);
        @chmod($cachefile, Config::$chmodFile);
    }
    
    protected function getCompiledLocaleRetriever($locale) {
        return '$t->getLocaleString(\''.$locale.'\')';
    }
    
    protected function _getPlugin ($type, $name) {
        $foundPath = '';

        if (isset(Config::$pluginPathList[$this->outputType])) {
            foreach (Config::$pluginPathList[$this->outputType] as $path) {
                $foundPath = $path.$type.'.'.$name.'.php';

                if (file_exists($foundPath)) {
                    return array($foundPath, 'jtpl_'.$type.'_'.$this->outputType.'_'.$name);
                }
            }
        }
        if (isset(Config::$pluginPathList['common'])) {
            foreach (Config::$pluginPathList['common'] as $path) {
                $foundPath = $path.$type.'.'.$name.'.php';
                if (file_exists($foundPath)) {
                    return array($foundPath, 'jtpl_'.$type.'_common_'.$name);
                }
            }
        }
        return false;
    }

    public function doError0 ($err) {
        throw new \Exception(sprintf($this->_locales[$err], $this->_sourceFile));
    }

    public function doError1 ($err, $arg) {
        throw new \Exception(sprintf($this->_locales[$err], $arg, $this->_sourceFile));
    }

    public function doError2 ($err, $arg1, $arg2) {
        throw new \Exception(sprintf($this->_locales[$err], $arg1, $arg2, $this->_sourceFile));
    }
}
