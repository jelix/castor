<?php
/**
 * @author      Laurent Jouanneau
 * @copyright   2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
namespace Jelix\Castor\PluginsProvider;


use Jelix\Castor\Compiler\CompilerCore;

class GenericPluginsProvider implements PluginsProviderInterface
{

    /** @var  array  key: plugin name, value : plugin class */
    protected $modifierPlugins = [];

    /** @var  array  key: plugin name, value : plugin class */
    protected $metaPlugins = [];

    /** @var  array  key: plugin name, value : plugin class */
    protected $blockPlugins = [];

    /** @var  array  key: plugin name, value : plugin class */
    protected $functionPlugins = [];


    public function registerModifierPlugins(array $plugins)
    {
        $this->modifierPlugins = array_merge($this->modifierPlugins, $plugins);
    }

    public function registerMetasPlugins(array $plugins)
    {
        $this->metaPlugins = array_merge($this->metaPlugins, $plugins);
    }

    public function registerBlockPlugins(array $plugins)
    {
        $this->blockPlugins = array_merge($this->blockPlugins, $plugins);
    }

    public function registerFunctionPlugins(array $plugins)
    {
        $this->functionPlugins = array_merge($this->functionPlugins, $plugins);
    }

    public function getModifierPlugin(CompilerCore $compiler, string $modifierName): ?ModifierPluginInterface
    {
        if (isset($this->modifierPlugins[$modifierName])) {
            $class = $this->modifierPlugins[$modifierName];
            if (is_string($class)) {
                $class = $this->modifierPlugins[$modifierName] = new $class();
            }
            return $class;
        }
        return null;
    }

    public function getMetaPlugin(CompilerCore $compiler, string $metaName): ?MetaPluginInterface
    {
        if (isset($this->metaPlugins[$metaName])) {
            $class = $this->metaPlugins[$metaName];
            if (is_string($class)) {
                $class = $this->metaPlugins[$metaName] = new $class();
            }
            return $class;
        }
        return null;
    }

    public function getBlockPlugin(CompilerCore $compiler, string $blockName): ?BlockPluginInterface
    {
        if (isset($this->blockPlugins[$blockName])) {
            $class = $this->blockPlugins[$blockName];
            if (is_string($class)) {
                $class = $this->blockPlugins[$blockName] = new $class();
            }
            return $class;
        }
        return null;
    }

    public function getFunctionPlugin(CompilerCore $compiler, string $funcName): ?FunctionPluginInterface
    {
        if (isset($this->functionPlugins[$funcName])) {
            $class = $this->functionPlugins[$funcName];
            if (is_string($class)) {
                $class = $this->functionPlugins[$funcName] = new $class();
            }
            return $class;
        }
        return null;
    }
}