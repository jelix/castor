<?php
/**
 * @author      Laurent Jouanneau
 * @copyright   2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
namespace Jelix\Castor\PluginsProvider\Legacy;


use Jelix\Castor\Compiler\CompilerCore;
use Jelix\Castor\PluginsProvider\BlockPluginInterface;
use Jelix\Castor\PluginsProvider\MetaPluginInterface;
use Jelix\Castor\PluginsProvider\ModifierPluginInterface;
use Jelix\Castor\PluginsProvider\PluginInterface;
use Jelix\Castor\PluginsProvider\PluginsProviderInterface;

class LegacyPluginsProvider implements PluginsProviderInterface
{
    protected $pluginPathList = array();

    public function __construct(array $repositories)
    {
        foreach($repositories as $path) {
            $this->addPluginsRepository($path);
        }
    }

    public function addPluginsRepository($path) : void
    {
        if (trim($path) == '') {
            return;
        }

        if (!file_exists($path)) {
            throw new \Exception('The given path, '.$path.' doesn\'t exists');
        }

        if (substr($path, -1) != '/') {
            $path .= '/';
        }

        if ($handle = opendir($path)) {
            while (false !== ($f = readdir($handle))) {
                if ($f[0] != '.' && is_dir($path.$f)) {
                    $this->pluginPathList[$f][] = $path.$f.'/';
                }
            }
            closedir($handle);
        }
    }

    /**
     * @var ModifierPluginInterface[]
     */
    protected $modifierPlugins = [];

    public function getModifierPlugin(CompilerCore $compiler, string $modifierName) : ?ModifierPluginInterface
    {
        if (isset($this->modifierPlugins[$modifierName]))
        {
            return $this->modifierPlugins[$modifierName];
        }

        if ($path = $this->_getPlugin($compiler->outputType, 'cmodifier', $modifierName)) {
            $plugin = new LegacyModifierPlugin('cmodifier', $path[0], $path[1]);
        } elseif ($path = $this->_getPlugin($compiler->outputType,'modifier2', $modifierName)) {
            $plugin = new LegacyModifierPlugin('modifier2', $path[0], $path[1]);
        } elseif ($path = $this->_getPlugin($compiler->outputType, 'modifier', $modifierName)) {
            $plugin = new LegacyModifierPlugin('modifier', $path[0], $path[1]);
        } else {
            return null;
        }

        $this->modifierPlugins[$modifierName] = $plugin;
        return $plugin;
    }

    /**
     * @var MetaPluginInterface[]
     */
    protected $metaPlugins = [];

    public function getMetaPlugin(CompilerCore $compiler, string $metaName) : ?MetaPluginInterface
    {
        if (isset($this->metaPlugins[$metaName]))
        {
            return $this->metaPlugins[$metaName];
        }

        if ($path = $this->_getPlugin($compiler->outputType, 'meta', $metaName)) {
            $plugin = new LegacyMetaPlugin($metaName, $path[0], $path[1]);
            $this->metaPlugins[$metaName] = $plugin;
            return $plugin;
        }
        return null;
    }

    /**
     * @var BlockPluginInterface[]
     */
    protected $blockPlugins = [];

    public function getBlockPlugin(CompilerCore $compiler, string $blockName) : ?BlockPluginInterface
    {
        if (isset($this->blockPlugins[$blockName]))
        {
            return $this->blockPlugins[$blockName];
        }

        if ($path = $this->_getPlugin($compiler->outputType, 'block', $blockName)) {
            $plugin = new LegacyBlockPlugin($blockName, $path[0], $path[1]);
            $this->blockPlugins[$blockName] = $plugin;
            return $plugin;
        }
        return null;
    }

    /**
     * @var PluginInterface[]
     */
    protected $plugins = [];
    public function getPlugin(CompilerCore $compiler, string $name) : ?PluginInterface
    {
        if (isset($this->plugins[$name]))
        {
            return $this->plugins[$name];
        }

        //TODO implement

        return null;
    }


    /**
     * @param $outputType
     * @param $type
     * @param $name
     * @return false|string[] 0:file path, 1: function name
     */
    protected function _getPlugin($outputType, $type, $name)
    {
        if (isset($this->pluginPathList[$outputType])) {
            foreach ($this->pluginPathList[$outputType] as $path) {
                $foundPath = $path.$type.'.'.$name.'.php';

                if (file_exists($foundPath)) {
                    return array($foundPath, 'jtpl_'.$type.'_'.$outputType.'_'.$name);
                }
            }
        }
        if (isset($this->pluginPathList['common'])) {
            foreach ($this->pluginPathList['common'] as $path) {
                $foundPath = $path.$type.'.'.$name.'.php';
                if (file_exists($foundPath)) {
                    return array($foundPath, 'jtpl_'.$type.'_common_'.$name);
                }
            }
        }

        return false;
    }

}