<?php
/**
 * @author      Laurent Jouanneau
 * @copyright   2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
namespace Jelix\Castor\Plugins\Macros;


use Jelix\Castor\Compiler\CompilerCore;
use Jelix\Castor\PluginsProvider\BlockPluginInterface;

class DefineMacro implements BlockPluginInterface
{
    public function compileBegin(CompilerCore $compiler, $name, array $compiledTagArgs): string
    {
        if (count($compiledTagArgs) < 1) {
            $compiler->doError2('errors.tplplugin.block.bad.argument.number', 'macro', ">=1");
        }

        if (!preg_match('/^([\'\"])[a-z0-9_]+([\'\"])$/i', $compiledTagArgs[0], $m)) {
            $compiler->doError2('errors.tpl.tag.phpsyntax.invalid', 'macro', $compiledTagArgs[0]);
        }

        if ($m[1] != $m[2]) {
            // there is no same quote at the begin and at the end
            $compiler->doError2('errors.tpl.tag.phpsyntax.invalid', 'macro', $compiledTagArgs[0]);
        }

        $blockName = $compiledTagArgs[0];
        array_shift($compiledTagArgs);
        $parametersNames = array();
        foreach($compiledTagArgs as $k => $param) {
            if (!preg_match('/^\s*\\$t->_vars\\[\'([a-z0-9_]+)\']\s*$/i', $param, $m)) {
                $compiler->doError2('errors.tpl.tag.phpsyntax.invalid', 'macro', '#'.$param.'#');
            }
            $parametersNames[] = '\''.$m[1].'\'';
        }

        $content = '$t->declareMacro('.$blockName.', array('.implode(',', $parametersNames).'), '.
            ' function($engine, $t) {'."\n";



        return $content;
    }

    public function compileElse(CompilerCore $compiler, $name, array $compiledTagArgs): string
    {
        return '';
    }

    public function compileEnd(CompilerCore $compiler, $name): string
    {
        return "\n});\n";
    }

}
