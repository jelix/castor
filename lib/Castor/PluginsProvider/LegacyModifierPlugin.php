<?php
/**
 * @author      Laurent Jouanneau
 * @copyright   2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
namespace Jelix\Castor\PluginsProvider;

use Jelix\Castor\Compiler\CompilerCore;

class LegacyModifierPlugin implements ModifierPluginInterface
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

    public function compile(CompilerCore $compiler, $name, array $compiledTagArgs): string
    {
        if ($this->pluginType == 'cmodifier') {
            require_once $this->filePath;
            $fct = $this->functionName;
            return $fct($compiler, $compiledTagArgs);
        }
        if ($this->pluginType == 'modifier2') {
            $compiler->addPathToInclude($this->filePath);
            return $this->functionName.'($t, '.implode(',', $compiledTagArgs).')';
        }

        $compiler->addPathToInclude($this->filePath);
        return $this->functionName.'('.implode(',', $compiledTagArgs).')';
    }
}