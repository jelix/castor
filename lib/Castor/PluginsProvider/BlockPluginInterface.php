<?php
/**
 * @author      Laurent Jouanneau
 * @copyright   2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
namespace Jelix\Castor\PluginsProvider;


use Jelix\Castor\Compiler\CompilerCore;

interface BlockPluginInterface
{
    public function compileBegin(CompilerCore $compiler, $name, array $compiledTagArgs) : string;

    public function compileElse(CompilerCore $compiler, $name, array $compiledTagArgs) : string;

    public function compileEnd(CompilerCore $compiler, $name) : string;
}