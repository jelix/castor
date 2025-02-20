<?php

/**
 * @author      Hugues Magnier
 * @contributor      Laurent Jouanneau
 * @copyright   2007 Hugues Magnier, 2025 Laurent Jouanneau
 *
 * @link        http://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

/**
 * remove all extra whitespaces.
 *
 * @param boolean $begin
 * @param  array  $param
 *
 * @return string
 */
function jtpl_block_common_stripws(\Jelix\Castor\Compiler\CompilerCore $compiler, $begin, $param = array())
{
    if ($begin) {
        $content = 'ob_start();';
    } else {
        $content = '
        $buffer = preg_replace(\'![\\t ]*[\\r\\n]+[\\t ]*!\', \'\', ob_get_contents());
        ob_end_clean();
        print $buffer;';
    }

    return $content;
}
