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
 * function plugin :  Reset all the counters.
 *
 * <pre>{counter_reset_all}</pre>
 *
 * @param \Jelix\Castor\RuntimeContainer $tpl The template
 */
function jtpl_function_common_counter_reset_all(\Jelix\Castor\RuntimeContainer $tpl)
{
    if (!isset($tpl->_privateVars['counterArray'])) {
        return;
    }
    $tpl->_privateVars['counterArray'] = array('default' => array('type' => '0', 'start' => 1, 'incr' => 1));
}
