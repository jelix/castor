<?php
/**
 * @author      Laurent Jouanneau
 * @copyright   2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
namespace Jelix\Castor\PluginsProvider\Legacy;

use Jelix\Castor\Compiler\CompilerCore;
use Jelix\Castor\PluginsProvider\FunctionPluginInterface;

class LegacyFunctionPlugin implements FunctionPluginInterface
{

    protected $pluginType = '';
    protected $filePath = '';
    protected $functionName = '';

    public function __construct($pluginType, $filePath, $functionName)
    {
        $this->pluginType = $pluginType;
        $this->filePath = $filePath;
        $this->functionName = $functionName;
    }

    public function compile(
        CompilerCore $compiler,
        string $funcName,
        array $compiledTagArgs) : string
    {
        if ($this->pluginType == 'cfunction') {
            require_once $this->filePath;
            $fct = $this->functionName;
            return $fct($compiler, $compiledTagArgs);
        }

        $compiler->addPathToInclude($this->filePath);
        $args = implode(',', $compiledTagArgs);
        return $this->functionName.'( $t'.(trim($args) != '' ? ',' . $args : '').');';
    }
}