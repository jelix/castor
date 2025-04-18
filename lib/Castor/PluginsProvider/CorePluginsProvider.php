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
        'count_paragraphs' => 'Jelix\Castor\Plugins\TextCountPlugin',
        'count_sentences' => 'Jelix\Castor\Plugins\TextCountPlugin',
        'count_words' => 'Jelix\Castor\Plugins\TextCountPlugin',
        'count_characters' => 'Jelix\Castor\Plugins\TextCountPlugin',
    ];

    /** @var  array  key: plugin name, value : plugin class */
    protected $metaPlugins = [

    ];

    /** @var  array  key: plugin name, value : plugin class */
    protected $blockPlugins = [
        'ifdefinedmacro' => 'Jelix\Castor\Plugins\Macros\IfDefinedMacro',
        'ifundefinedmacro' => 'Jelix\Castor\Plugins\Macros\IfDefinedMacro',
        'macro' => 'Jelix\Castor\Plugins\Macros\DefineMacro'
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
    ];

}