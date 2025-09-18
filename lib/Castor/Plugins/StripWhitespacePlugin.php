<?php
/**
 * @author      Hugues Magnier
 * @contributor Laurent Jouanneau
 * @copyright   2007 Hugues Magnier, 2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
namespace Jelix\Castor\Plugins;

use Jelix\Castor\Compiler\CompilerCore;
use Jelix\Castor\PluginsProvider\BlockPluginInterface;

/**
 * Remove all extra whitespaces
 *
 * ```
 * {stripws}
 * here are        some
 *
 *    content
 * {/stripws}
 * ```
 */
class StripWhitespacePlugin implements BlockPluginInterface
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
        $buffer = preg_replace(\'![\\t ]*[\\r\\n]+[\\t ]*!\', \'\', ob_get_contents());
        ob_end_clean();
        print $buffer;';
    }

}