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

class NumberModifiersPlugin implements ModifierPluginInterface
{
    public function compile(CompilerCore $compiler, $name, array $compiledModifierArgs, $compiledExpression) : string
    {
        switch($name) {
            /**
             * Rounds a float.
             *
             * <pre>
             *     {$var|round:2}
             * </pre>
             */
            case 'round':
                if (count($compiledModifierArgs) > 0) {
                    $precision = $compiledModifierArgs[0];
                }
                else {
                    $precision = "0";
                }
                return 'round('.$compiledExpression.','.$precision.')';
            /**
             * Call the number_format PHP function.
             * <pre>
             *     {$var|number_format:}
             * </pre>
             */
            case 'number_format':
                if (count($compiledModifierArgs) > 3) {
                    $compiler->doError2('errors.tplplugin.cmodifier.bad.argument.number', $name, '0-3');
                }
                if (count($compiledModifierArgs) > 0) {
                    return  get_class($this) . '::numberFormatModifier('.$compiledExpression.','.implode(',', $compiledModifierArgs) .')';
                }
                return  get_class($this) . '::numberFormatModifier('.$compiledExpression.')';
        }
        return '';
    }

    public static function numberFormatModifier($number, $decimals = 0, $dec_point = false, $thousands_sep = false)
    {
        if ($dec_point === false && $thousands_sep === false) {
            $number = number_format($number, $decimals);
        } else {
            $number = number_format($number, $decimals, ($dec_point === false ? '.' : $dec_point), ($thousands_sep === false ? ',' : $thousands_sep));
        }

        return $number;
    }
}