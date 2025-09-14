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

class StringModifiersPlugin implements ModifierPluginInterface
{
    public function compile(CompilerCore $compiler, $name, array $compiledModifierArgs, $compiledExpression) : string
    {
        switch($name) {
            /**
             * cat: concatenate two strings
             *
             * <pre>
             * {$var|cat:"foo"}
             * {$var|cat:$othervar}
             * </pre>
             */
            case 'cat':
                if (count($compiledModifierArgs) != 1) {
                    $compiler->doError2('errors.tplplugin.cmodifier.bad.argument.number', $name, '1');
                }
                return $compiledExpression.'.'.$compiledModifierArgs[0];
            /**
             * indent: indent lines of a text.
             *
             *  <pre>{$mytext|indent}
             *  {$mytext|indent:$number_of_spaces}
             *  {$mytext|indent:$number_of_chars:$chars_to_repeat}
             *  </pre>
             */
            case 'indent':
                $char = "' '";
                if (count($compiledModifierArgs) > 0) {
                    $chars = $compiledModifierArgs[0];
                    if (count($compiledModifierArgs) > 1) {
                        $char = $compiledModifierArgs[1];
                    }
                }
                else {
                    $chars = "4";
                }
                return "preg_replace('!^!m', str_repeat($char, $chars), ".$compiledExpression.");";
            /**
             * replace: replace a substring in a string.
             *
             * You should provide two arguments, like the first both of str_replace
             * <pre>{$mystring|replace:'foo':'bar'}</pre>
             */
            case 'replace':
                if (count($compiledModifierArgs) != 2) {
                    $compiler->doError2('errors.tplplugin.cmodifier.bad.argument.number', $name, '2');
                }
                return 'str_replace('.$compiledModifierArgs[0].', '.$compiledModifierArgs[1].', '.$compiledExpression.')';


            /**
             * spacify: add spaces between characters in a string.
             *
             * <pre>{$mytext|spacify}
             * {$mytext|spacify:$characters_to_insert}</pre>
             */
            case 'spacify':
                if (count($compiledModifierArgs) > 0) {
                    $spacifyChar = $compiledModifierArgs[0];
                }
                else {
                    $spacifyChar = "' '";
                }
                return 'implode('.$spacifyChar.', preg_split(\'//\', '.$compiledExpression.', -1, PREG_SPLIT_NO_EMPTY))';
            /**
             * Replace all repeated spaces, newlines, tabs with a single space
             * or supplied replacement string.
             *
             * <pre>{$var|strip}
             * {$var|strip:"&nbsp;"}</pre>
             */
            case 'strip':
                if (count($compiledModifierArgs) > 0) {
                    $replace = $compiledModifierArgs[0];
                }
                else {
                    $replace = "' '";
                }
                return 'preg_replace(\'!\\s+!\', '.$replace.', '.$compiledExpression.')';
            /**
             * Truncate a string.
             *
             * Truncate a string to a certain length if necessary, optionally splitting in
             * the middle of a word, and appending a given string.
             * <pre>{$mytext|truncate}
             * {$mytext|truncate:40}
             * {$mytext|truncate:45:'...'}
             * {$mytext|truncate:60:'...':true}
             */
            case 'truncate':
                if (count($compiledModifierArgs) > 3) {
                    $compiler->doError2('errors.tplplugin.cmodifier.bad.argument.number', $name, '3');
                }

                return  get_class($this) . '::truncateModifier('.
                    $compiledExpression.',\''.$compiler->getEncoding().'\','.
                    implode(',', $compiledModifierArgs) . ')';
            /**
             * wrap a string of text at a given length.
             *
             * Same parameters as the php wordwrap function.
             *
             * <pre>{$mytext|wordwrap}
             * {$mytext|wordwrap:40}
             * {$mytext|wordwrap:45:"\n"}
             * {$mytext|wordwrap:60:"\n":true}
             * </pre>.
             */
            case 'wordwrap':
                if (count($compiledModifierArgs) < 1 || count($compiledModifierArgs) > 3) {
                    $compiler->doError2('errors.tplplugin.cmodifier.bad.argument.number', $name, '1-3');
                }
                return 'wordwrap('.$compiledExpression.', '.implode(',', $compiledModifierArgs).')';
            /**
             * regular expression search/replace.
             *
             * You should provide two arguments, like the first both of preg_replace
             * {$mystring|regex_replace:'/(\w+) (\d+), (\d+)/i':'${1}1,$3'}
             */
            case 'regex_replace':
                if (count($compiledModifierArgs) != 2) {
                    $compiler->doError2('errors.tplplugin.cmodifier.bad.argument.number', $name, '2');
                }
                return  get_class($this) . '::pregReplaceModifier('.
                    $compiledExpression.',\''.$compiler->getEncoding().'\','.
                    implode(',', $compiledModifierArgs) . ')';
            /**
             * cut a html formated string and close all opened tags so that
             * it doesn't inpact on the rest of the page.
             *
             * You should use this modifier in a zone so that the return value is cached.
             * <pre>
             * {$mytext|truncatehtml:150:"\n<a href="...">read full article</a>"}
             * {$mytext|truncatehtml:45}
             */
            case 'truncatehtml':
                if (count($compiledModifierArgs) > 3) {
                    $compiler->doError2('errors.tplplugin.cmodifier.bad.argument.number', $name, '1-3');
                }
                return  get_class($this) . '::truncateHTMLModifier('.
                    $compiledExpression.',\''.$compiler->getEncoding().'\','.
                    implode(',', $compiledModifierArgs) . ')';
            /**
             * convert \r\n, \r or \n to "<br/>"
             *
             * <pre>
             *     {$text|nl2br}.
             * </pre>
             */
            case 'nl2br':
                return 'nl2br('.$compiledExpression.')';

            /**
             * format strings via sprintf.
             *
             *  <pre>{$mytext|sprintf:'my format %s'}</pre>
             */
            case 'sprintf':
                return 'sprintf('.$compiledModifierArgs[0].', '.$compiledExpression.')';
        }
        return '';
    }


    public static function truncateModifier($string, $charset, $length = 80, $suffix = '...',
                                            $break_words = false):string
    {
        if (function_exists('mb_strlen')) {
            $f_strlen = 'mb_strlen';
        } else {
            $f_strlen = 'iconv_strlen';
        }

        if (function_exists('mb_substr')) {
            $f_substr = 'mb_substr';
        } else {
            $f_substr = 'iconv_substr';
        }

        if ($length == 0) {
            return '';
        }

        if ($f_strlen ($string, $charset) <= $length) {
            return $string;
        }
        $length -= $f_strlen($suffix, $charset);
        if (!$break_words) {
            $string = preg_replace('/\s+?(\S+)?$/', '', $f_substr($string, 0, $length + 1, $charset));
        }

        return $f_substr($string, 0, $length, $charset).$suffix;
    }


    public static function pregReplaceModifier($string, $charset, $search, $replace):string
    {
        if (preg_match('!\W(\w+)$!s', $search, $match) &&
            (strpos($match[1], 'e') !== false)) {
            /* remove eval-modifier from $search */
            $search = substr($search, 0, -iconv_strlen($match[1], $charset)).
                str_replace('e', '', $match[1]);
        }

        return preg_replace($search, $replace, $string);
    }


    public static function truncateHTMLModifier($html, $charset, $maxLength = 200, $suffix = '')
    {
        if ($maxLength == 0) {
            return '';
        }

        // If there is a comment, we delete it
        $html = preg_replace('#<!--(.+)-->#isU', '', $html);
        //escape non closed comment
        $html = str_replace('<!--', '&lteq;!--', $html);
        // remove duplicated spaces
        $html = preg_replace('/(\s+)/', ' ', $html);

        $htmlLength = mb_strlen($html, $charset);
        $result = '';

        // get all tags and entities
        $tagPattern = '/(<\/?)([\w]*)(\s*[^>]*)>?|&[\w#]+;/i';
        preg_match_all($tagPattern, $html, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER );

        $readedTextPosition = 0;

        $textLength = 0;

        $openedTag = [];

        foreach($matches as $match) {

            $fullTag = $match[0][0];
            $tagPos = $match[0][1];

            if ($tagPos > $readedTextPosition) {
                $newCharLength = $tagPos - $readedTextPosition;
                $remainingAuthorisedLength = $maxLength - $textLength;
                $textLength += $newCharLength;

                if ($newCharLength >= $remainingAuthorisedLength) {
                    $remainingText = mb_substr($html, $readedTextPosition, $remainingAuthorisedLength, $charset);
                    $result .= $remainingText;

                    foreach(array_reverse($openedTag) as $tag) {
                        $result .= "</$tag>";
                    }
                    $openedTag = [];
                    break;
                }
                else {
                    $result .= mb_substr($html, $readedTextPosition, $newCharLength, $charset);
                }
            }

            $readedTextPosition = $tagPos + mb_strlen($fullTag, $charset);

            if (count($match) == 1) {
                // this is an entity
                $textLength++;
                $result .= $fullTag;
                if ($textLength >= $maxLength) {
                    foreach(array_reverse($openedTag) as $tag) {
                        $result .= "</$tag>";
                    }
                    $openedTag = [];
                }

                continue;
            }


            $tagName = $match[2][0];
            $tagContent = $match[3][0];

            if ($match[1][0] == '</') {
                if ($tagName == end($openedTag)) {
                    array_pop($openedTag);
                }
                $result .= $fullTag;
                continue;
            }

            if ($match[1][0] == '<' && !str_ends_with($tagContent, '/')) {
                $openedTag[] = $tagName;
                $result .= $fullTag;
            }
        }

        if (count($openedTag) && $readedTextPosition < $htmlLength) {
            $reaminingLength = $htmlLength - $readedTextPosition;
            $authorizedLength = $maxLength - $textLength;

            if ($reaminingLength <= $authorizedLength) {
                $result .= mb_substr($html, $readedTextPosition, $reaminingLength, $charset);
            }
            else if ($authorizedLength > 0) {
                $result .= mb_substr($html, $readedTextPosition, $authorizedLength, $charset);
            }
        }

        return $result.$suffix;
    }

}