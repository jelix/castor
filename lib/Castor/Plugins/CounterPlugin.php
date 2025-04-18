<?php
/**
 * @author      Thibault Piront (nuKs)
 * @contributor Laurent Jouanneau
 * @copyright   2007 Thibault Piront, 2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
namespace Jelix\Castor\Plugins;

use Jelix\Castor\Compiler\CompilerCore;
use Jelix\Castor\PluginsProvider\FunctionPluginInterface;

class CounterPlugin implements FunctionPluginInterface
{
    public function compile(CompilerCore $compiler, string $funcName, array $compiledTagArgs): string
    {

        if ($funcName == 'counter_init') {
            array_unshift($compiledTagArgs, '$t');
            return get_class($this).'::initCounter('.implode(',', $compiledTagArgs).')';
        }

        if ($funcName == 'counter') {
            array_unshift($compiledTagArgs, '$t');
            return get_class($this).'::showAndIncrement('.implode(',', $compiledTagArgs).')';
        }

        if ($funcName == 'counter_reset') {
            array_unshift($compiledTagArgs, '$t');
            return get_class($this).'::reset('.implode(',', $compiledTagArgs).')';
        }

        if ($funcName == 'counter_reset_all') {
            return get_class($this).'::resetAll($t);';
        }

        return '';
    }

    /**
     * @param \Jelix\Castor\RuntimeContainer $tpl
     * @param string $name The name of the counter
     * @param string $type The type of the counter ('0', '00', 'aa' or 'AA').
     * @param string|int $start Where the counter start. String if type == 'aa'/'AA'
     * @param int $incr How many time the counter is increased on each call
     * @return void
     */
    public static function initCounter(\Jelix\Castor\RuntimeContainer $tpl, $name = '', $type = '0', $start = 1, $incr = 1)
    {
        if (!isset($tpl->_privateVars['counterArray'])) {
            $tpl->_privateVars['counterArray'] = array('default' => array('type' => '0', 'start' => 1, 'incr' => 1));
        }

        if (empty($name) && $name !== '0') {
            $name = 'default';
        }

        /* Reinitalize the conter and add the given variables */
        $tpl->_privateVars['counterArray'][$name] = array('type' => $type, 'start' => $start, 'incr' => $incr);

        /* Truncate the variable */
        $in_use = &$tpl->_privateVars['counterArray'][$name];

        /* Adapt the number to the type (not always necessary) */
        if (!is_string($in_use['start'])) {
            if ($in_use['type'] === 'aa') {
                $in_use['start'] = 'a';
            } elseif ($in_use['type'] === 'AA') {
                $in_use['start'] = 'A';
            }
        }
    }

    public static function showAndIncrement(\Jelix\Castor\RuntimeContainer $tpl, $name = '', $print = true)
    {
        if (!isset($tpl->_privateVars['counterArray'])) {
            $tpl->_privateVars['counterArray'] = array('default' => array('type' => '0', 'start' => 1, 'incr' => 1));
        }

        if (empty($name) && $name !== '0') {
            $name = 'default';
        }
        if (!isset($tpl->_privateVars['counterArray'][$name])) {
            $tpl->_privateVars['counterArray'][$name] = array('type' => '0', 'start' => 1, 'incr' => 1);
        }
        /* Shorten the variable */
        $in_use = &$tpl->_privateVars['counterArray'][$name];

        /* Transforms the alphabetic start into numeric one */
        if (is_string($in_use['start']) && ($in_use['type'] === 'aa' || $in_use['type'] === 'AA')) {
            $in_use['start'] = ord($in_use['start']);
        }

        /* Adapts the code if counter is more that Z/z or becomes less than A/a */
        if (($in_use['type'] === 'aa' && ($in_use['start'] < ord('a') || $in_use['start'] > ord('z'))) ||
            ($in_use['type'] === 'AA' && ($in_use['start'] < ord('A') || $in_use['start'] > ord('Z')))) {
            $in_use['type'] = '0';
            $in_use['start'] = 1;
        }

        /* Display the counter */
        if ($print) {
            if ($in_use['type'] === 'aa' || $in_use['type'] === 'AA') {
                echo chr($in_use['start']);
            } else {
                if ($in_use['type'] === '00' && $in_use['start'] < 10 && $in_use['start'] > -1) {
                    echo '0' . $in_use['start'];
                } elseif ($in_use['type'] === '00' && $in_use['start'] > -10 && $in_use['start'] < 0) {
                    echo '-0' . abs($in_use['start']);
                } else {
                    echo $in_use['start'];
                }
            }
        }

        /* Increment the counter */
        $in_use['start'] += $in_use['incr'];
    }

    public static function reset(\Jelix\Castor\RuntimeContainer $tpl, $name = '')
    {
        if (empty($name) && $name !== '0') {
            $name = 'default';
        }

        if (!isset($tpl->_privateVars['counterArray'])) {
            return;
        }
        if (!isset($tpl->_privateVars['counterArray'][$name])) {
            return;
        }

        $tpl->_privateVars['counterArray'][$name] = array('type' => '0', 'start' => 1, 'incr' => 1);
    }

    public static function resetAll(\Jelix\Castor\RuntimeContainer $tpl)
    {
        if (!isset($tpl->_privateVars['counterArray'])) {
            return;
        }
        $tpl->_privateVars['counterArray'] = array('default' => array('type' => '0', 'start' => 1, 'incr' => 1));
    }

}