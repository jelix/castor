<?php

/**
 * Displays the macro indicated as first parameter of the tag.
 *
 * Example: `{usemacro 'mymacro'}`
 * If the macro accepts some parameters, you can give them:
 * `{usemacro 'mymacro', $arg1, 'arg2'}`
 *
 * A macro should be declared with `{macro}`
 *
 * @param \Jelix\Castor\CastorCore $tpl
 */
function jtpl_function_common_usemacro($tpl, $blockName, ...$parameters)
{
    $tpl->callMacro($blockName, $parameters);
}