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

class IfDefinedMacro implements BlockPluginInterface
{
    public function compileBegin(CompilerCore $compiler, $name, array $compiledTagArgs): string
    {
        $content = '';
        if (count($compiledTagArgs) != 1) {
            $compiler->doError1('errors.tplplugin.block.bad.argument.number', $name, '1');
        } else if ($name == 'ifdefinedmacro' ){
            $content = ' if($t->isMacroDefined('.$compiledTagArgs[0].')) {';
        } else if ($name == 'ifundefinedmacro' ){
            $content = ' if(!$t->isMacroDefined('.$compiledTagArgs[0].')) {';
        }
        return $content;
    }

    public function compileElse(CompilerCore $compiler, $name, array $compiledTagArgs): string
    {
        return ' } else { ';
    }

    public function compileEnd(CompilerCore $compiler, $name): string
    {
        return ' } ';
    }

}
