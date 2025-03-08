<?php
/**
 * @author      Laurent Jouanneau
 * @copyright   2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
namespace Jelix\Castor\PluginsProvider;
use \Jelix\Castor\Compiler\CompilerCore;

interface PluginsProviderInterface
{

    /**
     * Return the plugin object corresponding to the given modifier name
     *
     * @param CompilerCore $compiler
     * @param string $modifierName
     * @return ModifierPluginInterface|null The plugin, or null if not supported
     */
    public function getModifierPlugin(CompilerCore $compiler, string $modifierName) : ?ModifierPluginInterface;

    /**
     * Return the plugin object corresponding to the given meta name
     *
     * @param CompilerCore $compiler
     * @param string $modifierName
     * @return MetaPluginInterface|null The plugin, or null if not supported
     */
    public function getMetaPlugin(CompilerCore $compiler, string $metaName) : ?MetaPluginInterface;

    /**
     * Return the plugin object corresponding to the given block name
     *
     * @param CompilerCore $compiler
     * @param string $modifierName
     * @return BlockPluginInterface|null The plugin, or null if not supported
     */
    public function getBlockPlugin(CompilerCore $compiler, string $blockName) : ?BlockPluginInterface;

    /**
     * Return the plugin object corresponding to the given function name
     *
     * @param CompilerCore $compiler
     * @param string $modifierName
     * @return FunctionPluginInterface|null The plugin, or null if not supported
     */
    public function getFunctionPlugin(CompilerCore $compiler, string $funcName) : ?FunctionPluginInterface;

}