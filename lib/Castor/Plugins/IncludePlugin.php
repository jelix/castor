<?php
/**
 * @author      Laurent Jouanneau
 * @copyright   2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
namespace Jelix\Castor\Plugins;

use Jelix\Castor\Compiler\CompilerCore;
use Jelix\Castor\PluginsProvider\FunctionPluginInterface;

/**
 * Include the content of a template inside a template
 *
 *
 * ```
 * {include 'othertemplate.tpl'}
 * ```
 *
 */
class IncludePlugin implements FunctionPluginInterface
{
    public function compile(CompilerCore $compiler, string $funcName, array $compiledTagArgs): string
    {
        if (!$compiler->trusted) {
            $compiler->doError1('errors.tplplugin.untrusted.not.available', 'include');

            return '';
        }
        if (count($compiledTagArgs) == 1) {
            $compiler->addMetaContent('$engine->meta('.$compiledTagArgs[0].');');

            return '$engine->display('.$compiledTagArgs[0].');';
        } else {
            $compiler->doError2('errors.tplplugin.cfunction.bad.argument.number', 'include', '1');

            return '';
        }
    }

}