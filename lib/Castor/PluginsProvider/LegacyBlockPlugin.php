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

class LegacyBlockPlugin implements BlockPluginInterface
{

    protected $blockName = '';
    protected $filePath = '';
    protected $functionName = '';

    public function __construct($blockName, $filePath, $functionName)
    {
        $this->blockName = $blockName;
        $this->filePath = $filePath;
        $this->functionName = $functionName;
    }

    public function compileBegin(CompilerCore $compiler, $name, array $compiledTagArgs): string
    {
        require_once($this->filePath);

        $fct = $this->functionName;
        return $fct($compiler, true, $compiledTagArgs);
    }

    public function compileElse(CompilerCore $compiler, $name, array $compiledTagArgs): string
    {
        return 'else:';
    }

    public function compileEnd(CompilerCore $compiler, $name): string
    {
        $fct = $this->functionName;
        return $fct($compiler, false, null);
    }
}
