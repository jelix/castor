<?php
/**
 * @author      Hadrien Lanneau
 * @contributor Laurent Jouanneau
 * @copyright   2008 Hadrien.eu, 2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
namespace Jelix\Castor\Plugins;

use Jelix\Castor\Compiler\CompilerCore;
use Jelix\Castor\PluginsProvider\BlockPluginInterface;

/**
 * Format a js block code by removing spaces, tabs and returns.
 *
 * ```
 * {jscompress}
 * var foo = bar;
 *
 * {/jscompress}
 * ```
 */
class JsCompressPlugin implements BlockPluginInterface
{
    public function compileBegin(CompilerCore $compiler, $name, array $compiledTagArgs): string
    {
        return 'ob_start();';
    }

    public function compileElse(CompilerCore $compiler, $name, array $compiledTagArgs): string
    {
        return '';
    }

    public function compileEnd(CompilerCore $compiler, $name): string
    {
        return '
        $buffer = preg_replace(
                array(
                        "/\/\/.*\n/",
                        "/[\t\n]+/",
                        "/\/\*.*?\*\//"
                ),
                array(
                        " ",
                        " ",
                        " "
                ),
                ob_get_contents()
        ) . "\n";
        ob_end_clean();
        echo $buffer;';
    }

}