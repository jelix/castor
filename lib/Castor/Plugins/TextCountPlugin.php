<?php
/**
 * @author Laurent Jouanneau
 * @copyright  2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
namespace Jelix\Castor\Plugins;

use Jelix\Castor\Compiler\CompilerCore;
use Jelix\Castor\PluginsProvider\ModifierPluginInterface;

class TextCountPlugin implements ModifierPluginInterface
{
    public function compile(CompilerCore $compiler, $name, array $compiledTagArgs) : string
    {
        switch($name) {
            case 'count_paragraphs':
                return 'count(preg_split(\'/[\r\n]+/\', '.$compiledTagArgs[0].'))';
            case 'count_sentences':
                return 'preg_match_all(\'/[^\s]\.(?!\w)/\', '.$compiledTagArgs[0].', $match)';
            case 'count_words':
                return 'count(preg_grep(\'/\w/\',  preg_split(\'/\s+/\', '.$compiledTagArgs[0].')))';
            case 'count_characters':
                $code = 'iconv_strlen(preg_replace(\'/\s+/u\', \'\', '.$compiledTagArgs[0].'), $t->charset)';
                if (count($compiledTagArgs)> 1) {
                    return '('.$compiledTagArgs[1].'?iconv_strlen('.$compiledTagArgs[0].', $t->charset):'.$code.')';
                }
                return $code;
        }
        return '';
    }
}