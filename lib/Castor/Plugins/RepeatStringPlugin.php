<?php
/**
 * @author      Laurent Jouanneau
 * @copyright   2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
namespace Jelix\Castor\Plugins;


use Jelix\Castor\Compiler\CompilerCore;
use Jelix\Castor\PluginsProvider\FunctionPluginInterface;

/**
 * Repeat a string.
 *
 * <pre>
 *     {repeat_string 'mystring'}
 *     {repeat_string 'mystring',4}
 * </pre>
 */
class RepeatStringPlugin implements FunctionPluginInterface
{
    public function compile(CompilerCore $compiler, string $funcName, array $compiledTagArgs): string
    {
        if (count($compiledTagArgs) == 1) {
            return 'echo str_repeat(' . $compiledTagArgs[0] . ');';
        } elseif (count($compiledTagArgs) == 2) {
            return 'echo str_repeat('.$compiledTagArgs[0].', '.$compiledTagArgs[1].');';
        } else {
            $compiler->doError2('errors.tplplugin.cfunction.bad.argument.number', 'repeat_string', '1');

            return '';
        }
    }

}