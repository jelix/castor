<?php

/**
 * @author      Julien Issler
 * @contributor Laurent Jouanneau
 * @copyright   2009 Julien Issler, 2025 Laurent Jouanneau
 *
 * @link        http://jelix.org/
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

/**
 * Repeat a string.
 *
 * <pre>{repeat_string 'mystring'}
 * {repeat_string 'mystring',4}</pre>
 *
 * @param \Jelix\Castor\RuntimeContainer $tpl The template
 * @param string $string The string to repeat
 * @param int $count How many times to repeat
 */
function jtpl_function_common_repeat_string(\Jelix\Castor\RuntimeContainer$tpl, $string = '', $count = 1)
{
    echo str_repeat($string, $count);
}
