<?php
/**
 * @author Laurent Jouanneau
 * @copyright  2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
namespace Jelix\Castor\Plugins\Modifiers;

use Jelix\Castor\Compiler\CompilerCore;
use Jelix\Castor\PluginsProvider\ModifierPluginInterface;

class TextCountPlugin implements ModifierPluginInterface
{
    public function compile(CompilerCore $compiler, $name, array $compiledModifierArgs, $compiledExpression) : string
    {
        switch($name) {
            case 'count_paragraphs':
                return 'count(preg_split(\'/[\r\n]+/\', '.$compiledExpression.'))';
            case 'count_sentences':
                return 'preg_match_all(\'/[^\s]\.(?!\w)/\', '.$compiledExpression.', $match)';
            case 'count_words':
                return 'count(preg_grep(\'/\w/\',  preg_split(\'/\s+/\', '.$compiledExpression.')))';
            case 'count_characters':
                $code = 'iconv_strlen(preg_replace(\'/\s+/u\', \'\', '.$compiledExpression.'), $t->charset)';
                if (count($compiledModifierArgs)> 0) {
                    return '('.$compiledModifierArgs[0].'?iconv_strlen('.$compiledExpression.', $t->charset):'.$code.')';
                }
                return $code;
        }
        return '';
    }
}