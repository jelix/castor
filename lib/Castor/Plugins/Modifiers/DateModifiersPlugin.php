<?php
/**
 * @author Laurent Jouanneau
 * @copyright  2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
namespace Jelix\Castor\Plugins\Modifiers;

use DateTime;
use Jelix\Castor\Compiler\CompilerCore;
use Jelix\Castor\PluginsProvider\ModifierPluginInterface;

class DateModifiersPlugin implements ModifierPluginInterface
{
    public function compile(CompilerCore $compiler, $name, array $compiledModifierArgs, $compiledExpression) : string
    {
        switch($name) {
            /**
             * Change the format of a date.
             *
             * The date can be given as a string, or as a DateTime object.
             *
             * It uses DateTime to convert a date. It takes two optionnal arguments.
             * The first one is the format of the output date. It should be a format understood by DateTime,
             * By default, it uses 'Y-m-d'.
             * The second one is the format of the given date, if the date format is not understood by DateTime.
             *
             * examples :
             *  {$mydate|datetime}
             *  {$mydate|datetime:'d/m/Y'}
             *
             */
            case 'datetime':
                if (count($compiledModifierArgs) > 2) {
                    $compiler->doError2('errors.tplplugin.cmodifier.bad.argument.number', $name, '2');
                }
                if (count($compiledModifierArgs)) {
                    return  get_class($this) . '::dateModifier('.
                        $compiledExpression.','.implode(',', $compiledModifierArgs) . ')';
                }

                return  get_class($this) . '::dateModifier('.$compiledExpression.')';
        }
        return '';
    }


    public static function dateModifier($date, $format_out = 'Y-m-d', $format_in = '')
    {
        if (!($date instanceof DateTime)) {
            if ($date == '' || $date == '0000/00/00') {
                return '';
            }
            if ($format_in) {
                $date = date_create_from_format($format_in, $date);
            } else {
                $date = new DateTime($date);
            }
            if (!$date) {
                return '';
            }
        }

        return $date->format($format_out);
    }

}