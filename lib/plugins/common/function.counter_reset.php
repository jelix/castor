<?php

/**
 * @author      Thibault Piront (nuKs)
 * @contributor Laurent Jouanneau
 * @copyright   2007 Thibault Piront, 2025 Laurent Jouanneau
 *
 * @link        http://jelix.org/
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

/**
 * function plugin :  Reset a counter.
 *
 * <pre>{counter_reset 'name'}</pre>
 *
 * @param \Jelix\Castor\RuntimeContainer $tpl The template
 * @param string $name The name of the counter
 */
function jtpl_function_common_counter_reset(\Jelix\Castor\RuntimeContainer $tpl, $name = '')
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
