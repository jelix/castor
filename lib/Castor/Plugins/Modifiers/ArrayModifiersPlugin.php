<?php
/**
 * @author Laurent Jouanneau
 * @copyright  2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
namespace Jelix\Castor\Plugins\Modifiers;

use Jelix\Castor\Compiler\CompilerCore;
use Jelix\Castor\PluginsProvider\ModifierPluginInterface;

class ArrayModifiersPlugin implements ModifierPluginInterface
{
    public function compile(CompilerCore $compiler, $name, array $compiledModifierArgs, $compiledExpression) : string
    {
        switch($name) {
            /**
             * implode: implode an array with a separator
             *
             * <pre>
             *     {$var|implode}
             *     {$var|implode:","}
             * </pre>
             */
            case 'implode':
                if (count($compiledModifierArgs) > 0) {
                    $glue = $compiledModifierArgs[0];
                }
                else {
                    $glue = "' '";
                }
                return 'implode('.$glue.', '.$compiledExpression.')';
            /**
             * count the number of elements in an array
             * <pre>
             *     {$var_array|count_array}
             * </pre>
             */
            case 'count_array':
                return 'count('.$compiledExpression.')';
        }
        return '';
    }
}