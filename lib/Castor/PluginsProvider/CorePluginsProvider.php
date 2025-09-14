<?php
/**
 * @author      Laurent Jouanneau
 * @copyright   2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
namespace Jelix\Castor\PluginsProvider;

class CorePluginsProvider extends GenericPluginsProvider
{
    /** @var  array  key: plugin name, value : plugin class */
    protected $modifierPlugins = [
        'count_paragraphs' => 'Jelix\Castor\Plugins\Modifiers\TextCountPlugin',
        'count_sentences' => 'Jelix\Castor\Plugins\Modifiers\TextCountPlugin',
        'count_words' => 'Jelix\Castor\Plugins\Modifiers\TextCountPlugin',
        'count_characters' => 'Jelix\Castor\Plugins\Modifiers\TextCountPlugin',
        'cat' => 'Jelix\Castor\Plugins\Modifiers\StringModifiersPlugin',
        'indent' => 'Jelix\Castor\Plugins\Modifiers\StringModifiersPlugin',
        'replace' => 'Jelix\Castor\Plugins\Modifiers\StringModifiersPlugin',
        'spacify' => 'Jelix\Castor\Plugins\Modifiers\StringModifiersPlugin',
        'strip' => 'Jelix\Castor\Plugins\Modifiers\StringModifiersPlugin',
        'truncate' => 'Jelix\Castor\Plugins\Modifiers\StringModifiersPlugin',
        'wordwrap' => 'Jelix\Castor\Plugins\Modifiers\StringModifiersPlugin',
        'regex_replace' => 'Jelix\Castor\Plugins\Modifiers\StringModifiersPlugin',
        'truncatehtml' => 'Jelix\Castor\Plugins\Modifiers\StringModifiersPlugin',
        'nl2br' => 'Jelix\Castor\Plugins\Modifiers\StringModifiersPlugin',
        'sprintf' => 'Jelix\Castor\Plugins\Modifiers\StringModifiersPlugin',
        'implode' => 'Jelix\Castor\Plugins\Modifiers\ArrayModifiersPlugin',
        'count_array' => 'Jelix\Castor\Plugins\Modifiers\ArrayModifiersPlugin',
        'round' => 'Jelix\Castor\Plugins\Modifiers\NumberModifiersPlugin',
        'number_format' => 'Jelix\Castor\Plugins\Modifiers\NumberModifiersPlugin',
        'datetime' => 'Jelix\Castor\Plugins\Modifiers\DateModifiersPlugin',
        'json_decode' => 'Jelix\Castor\Plugins\Modifiers\JsonDecodeModifierPlugin',
    ];

    /** @var  array  key: plugin name, value : plugin class */
    protected $metaPlugins = [

    ];

    /** @var  array  key: plugin name, value : plugin class */
    protected $blockPlugins = [
        'ifdefinedmacro' => 'Jelix\Castor\Plugins\Macros\IfDefinedMacro',
        'ifundefinedmacro' => 'Jelix\Castor\Plugins\Macros\IfDefinedMacro',
        'macro' => 'Jelix\Castor\Plugins\Macros\DefineMacro',
        'stripws' => 'Jelix\Castor\Plugins\StripWhitespacePlugin',
        'jscompress' => 'Jelix\Castor\Plugins\JsCompressPlugin',
    ];

    /** @var  array  key: plugin name, value : plugin class */
    protected $functionPlugins = [
        'usemacro' => 'Jelix\Castor\Plugins\Macros\CallMacro',
        'counter_init' => 'Jelix\Castor\Plugins\CounterPlugin',
        'counter' => 'Jelix\Castor\Plugins\CounterPlugin',
        'counter_reset' => 'Jelix\Castor\Plugins\CounterPlugin',
        'counter_reset_all' => 'Jelix\Castor\Plugins\CounterPlugin',
        'cycle_init' => 'Jelix\Castor\Plugins\CyclePlugin',
        'cycle' => 'Jelix\Castor\Plugins\CyclePlugin',
        'cycle_reset' => 'Jelix\Castor\Plugins\CyclePlugin',
        'const' => 'Jelix\Castor\Plugins\ConstPlugin',
        'include' => 'Jelix\Castor\Plugins\IncludePlugin',
    ];

}