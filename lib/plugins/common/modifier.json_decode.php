<?php
/**
 * @author Laurent Jouanneau
 * @copyright 2023 Laurent Jouanneau
 *
 * @link https://jelix.org/
 * @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

/**
 * Plugin to extract a value from a JSON value
 *
 * <pre>{$myjson|json_decode:"mykey"}</pre>
 * @return
 */
function jtpl_modifier_common_json_decode($string, $key)
{
    $json = @json_decode($string, true);
    if (is_array($json) && array_key_exists($key, $json)) {
        return $json[$key];
    }
    return null;
}