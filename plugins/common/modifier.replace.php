<?php
/**
 * Plugin from smarty project and adapted for jtpl
 * @package    jelix
 * @subpackage jtpl_plugin
 * @version    $Id$
 * @author
 * @copyright  2001-2003 ispi of Lincoln, Inc.
 * @link http://smarty.php.net/
 * @link http://jelix.org/
 * @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

/**
 * modifier plugin : simple search/replace
 * @param string
 * @param string
 * @param string
 * @return string
 */
function jtpl_modifier_replace($string, $search, $replace)
{
    return str_replace($search, $replace, $string);
}

/* vim: set expandtab: */

?>