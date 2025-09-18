<?php
/**
 * @author      Philippe Schelté (dubphil)
 * @contributor Laurent Jouanneau
 * @copyright   2008 Philippe Schelté, 2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
namespace Jelix\Castor\Plugins;

use Jelix\Castor\Compiler\CompilerCore;
use Jelix\Castor\PluginsProvider\FunctionPluginInterface;


/**
 * Allow to display a series of values cyclically
 *
 *
 * Examples:
 * ```
 * simple usage:  {cycle array('aa','bb','cc')}
 *
 * Initialization :
 * {cycle_init '#eeeeee,#d0d0d0d'}
 * {cycle} display the next value
 *
 * Initialization by giving a name
 * {cycle_init 'name','#eeeeee,#d0d0d0d'}
 * {cycle 'name'}
 *
 * Reseting a cycle
 * {cycle_reset 'name'}
 * {cycle_reset}
 *
 * ```
 */
class CyclePlugin implements FunctionPluginInterface
{
    public function compile(CompilerCore $compiler, string $funcName, array $compiledTagArgs): string
    {

        if ($funcName == 'cycle_init') {
            array_unshift($compiledTagArgs, '$t');
            return get_class($this) . '::init(' . implode(',', $compiledTagArgs) . ')';
        }

        if ($funcName == 'cycle') {
            array_unshift($compiledTagArgs, '$t');
            return get_class($this) . '::showAndAdvance(' . implode(',', $compiledTagArgs) . ')';
        }

        if ($funcName == 'cycle_reset') {
            array_unshift($compiledTagArgs, '$t');
            return get_class($this) . '::reset(' . implode(',', $compiledTagArgs) . ')';
        }

        return '';
    }

    public static function init(\Jelix\Castor\RuntimeContainer $tpl, $name, $values = '')
    {
        if ($name == '') {
            throw $tpl->getInternalException('errors.tplplugin.cfunction.bad.argument.number', array('cycle_init', '1', ''));
        }

        if (is_array($name)) {
            $values = $name;
            $name = 'default';
        } elseif (strpos($name, ',') === false) {
            if ($values == '') {
                throw $tpl->getInternalException('errors.tplplugin.cfunction.bad.argument.number', array('cycle_init', '2', ''));
            }
            if (!is_array($values)) {
                if (strpos($values, ',') === false) {
                    throw $tpl->getInternalException('errors.tplplugin.function.invalid', array('cycle_init', '', ''));
                }
                $values = explode(',', $values);
            }
        } else {
            $values = explode(',', $name);
            $name = 'default';
        }

        $tpl->_privateVars['cycle'][$name]['values'] = $values;
        $tpl->_privateVars['cycle'][$name]['index'] = 0;
    }

    public static function showAndAdvance(\Jelix\Castor\RuntimeContainer $tpl, $cycleName = '')
    {
        if (is_array($cycleName)) {
            static $cycle_vars;
            if (!isset($cycle_vars['values'])) {
                $cycle_vars['values'] = $cycleName;
                $cycle_vars['index'] = 0;
            }
            $retval = $cycle_vars['values'][$cycle_vars['index']];
            if ($cycle_vars['index'] >= count($cycle_vars['values']) - 1) {
                $cycle_vars['index'] = 0;
            } else {
                ++$cycle_vars['index'];
            }
        } else {
            $cycle_name = $cycleName ?: 'default';
            if (isset($tpl->_privateVars['cycle'][$cycle_name]['values'])) {
                $cycle_array = $tpl->_privateVars['cycle'][$cycle_name]['values'];
            } else {
                throw $tpl->getInternalException('errors.tplplugin.function.argument.unknown', array($cycle_name, 'cycle', ''));
            }
            $index = &$tpl->_privateVars['cycle'][$cycle_name]['index'];
            $retval = $cycle_array[$index];
            if ($index >= count($cycle_array) - 1) {
                $index = 0;
            } else {
                ++$index;
            }
        }
        echo $retval;
    }

    public static function reset(\Jelix\Castor\RuntimeContainer $tpl, $cycleName = '')
    {
        $cycle_name = $cycleName ?: 'default';
        if (isset($tpl->_privateVars['cycle'][$cycle_name])) {
            $tpl->_privateVars['cycle'][$cycle_name]['index'] = 0;
        } else {
            throw $tpl->getInternalException('errors.tplplugin.function.argument.unknown', array($cycle_name, 'cycle', ''));
        }
    }

}