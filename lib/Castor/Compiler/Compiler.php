<?php

/**
 * @author      Loic Mathaud
 * @contributor Laurent Jouanneau
 *
 * @copyright   2006 Loic Mathaud
 * @copyright   2006-2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

namespace Jelix\Castor\Compiler;
use Jelix\Castor\Config;
use Jelix\Castor\PluginsProvider\LegacyPluginsProvider;
use Jelix\Castor\TemplateContentInterface;

class Compiler extends CompilerCore
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * Initialize some properties.
     */
    public function __construct(Config $config)
    {
        $plugins = new LegacyPluginsProvider($config->pluginsRepositories);

        parent::__construct($plugins, $config->charset);
        $this->config = $config;
    }

    /**
     * Launch the compilation of a template.
     *
     * returns the compiled template
     *
     * @param  TemplateContentInterface $tplContent  information about the template content
     *
     * @return CompilationResult true if ok
     * @throws \Exception
     */
    public function compile(TemplateContentInterface $tplContent,
                                                     $userModifiers = array(),
                                                     $userFunctions = array())
    {
        $this->_sourceFile = $tplContent->getName();
        $this->trusted = $tplContent->isTrusted();
        $md5 = md5($tplContent->getName().'_'.($this->trusted ? '_t' : ''));


        $compilationResult = $this->compileString(
            $tplContent->getContent(),
            $userModifiers, $userFunctions, $md5);

        return $compilationResult;
    }

    protected function getCompiledLocaleRetriever($locale)
    {
        return '$t->getLocaleString(\''.$locale.'\')';
    }

    protected function _getPlugin($type, $name)
    {
        if (isset($this->config->pluginPathList[$this->outputType])) {
            foreach ($this->config->pluginPathList[$this->outputType] as $path) {
                $foundPath = $path.$type.'.'.$name.'.php';

                if (file_exists($foundPath)) {
                    return array($foundPath, 'jtpl_'.$type.'_'.$this->outputType.'_'.$name);
                }
            }
        }
        if (isset($this->config->pluginPathList['common'])) {
            foreach ($this->config->pluginPathList['common'] as $path) {
                $foundPath = $path.$type.'.'.$name.'.php';
                if (file_exists($foundPath)) {
                    return array($foundPath, 'jtpl_'.$type.'_common_'.$name);
                }
            }
        }

        return false;
    }

    public function doError0($err)
    {
        throw new \Exception($this->config->getMessage($err, array($this->_sourceFile)));
    }

    public function doError1($err, $arg)
    {
        throw new \Exception($this->config->getMessage($err, array($arg, $this->_sourceFile)));
    }

    public function doError2($err, $arg1, $arg2)
    {
        throw new \Exception($this->config->getMessage($err, array($arg1, $arg2, $this->_sourceFile)));
    }
}
