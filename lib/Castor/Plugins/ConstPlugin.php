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
 * Display the value of a constant. Not available in untrusted templates.
 *
 * ```
 * {const 'foo'}
 * ```
 */
class ConstPlugin implements FunctionPluginInterface
{
    public function compile(CompilerCore $compiler, string $funcName, array $compiledTagArgs): string
    {
        if (!$compiler->trusted) {
            $compiler->doError1('errors.tplplugin.untrusted.not.available', 'const');

            return '';
        }
        if (count($compiledTagArgs) == 1) {
            if ($compiler->outputType == 'html' || $compiler->outputType == 'xml') {
                return 'echo htmlspecialchars(constant('.$compiledTagArgs[0].'), ENT_QUOTES | ENT_SUBSTITUTE, "'.$compiler->getEncoding().'");';
            }
            return 'echo constant('.$compiledTagArgs[0].');';
        } else {
            $compiler->doError2('errors.tplplugin.cfunction.bad.argument.number', 'const', '1');

            return '';
        }
    }

}