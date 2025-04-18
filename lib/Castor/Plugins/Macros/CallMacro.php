<?php
/**
 * @author      Laurent Jouanneau
 * @copyright   2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
namespace Jelix\Castor\Plugins\Macros;

use Jelix\Castor\Compiler\CompilerCore;
use Jelix\Castor\PluginsProvider\FunctionPluginInterface;

class CallMacro implements FunctionPluginInterface
{
    public function compile(CompilerCore $compiler, string $funcName, array $compiledTagArgs): string
    {
        if (count($compiledTagArgs) < 1) {
            $compiler->doError2('errors.tplplugin.block.bad.argument.number', 'usemacro', ">=1");
        }

        $macroname = array_shift($compiledTagArgs);
        return '$t->callMacro($engine, '.$macroname.', ['.implode(',', $compiledTagArgs).']);';
    }

}