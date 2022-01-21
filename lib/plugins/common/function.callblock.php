<?php

/**
 * Displays the bloc indicated as first parameter of the tag.
 *
 * Example: `{callbock 'myblock'}`
 * If the block accepts some parameters, you can give them:
 * `{callbock 'myblock', $arg1, 'arg2'}`
 *
 * A block should be declared with `{callableblock}`
 *
 * @param \Jelix\Castor\CastorCore
 */
function jtpl_function_common_callblock($tpl, $blockName, ...$parameters)
{
    $tpl->callBlock($blockName, $parameters);
}