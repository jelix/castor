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

class PluginsProviderGroup implements PluginsProviderInterface
{
    /** @var PluginsProviderInterface[]  */
    protected $providers = [];

    /**
     * @param PluginsProviderInterface[] $providers
     */
    public function __construct(array $providers)
    {
        $this->providers = $providers;
    }


    public function addPluginsProviders(PluginsProviderInterface $provider)
    {
        $this->providers[] = $provider;
    }

    public function getModifierPlugin(CompilerCore $compiler, string $modifierName): ?ModifierPluginInterface
    {
        foreach($this->providers as $provider) {
            $plugin = $provider->getModifierPlugin($compiler, $modifierName);
            if ($plugin) {
                return $plugin;
            }
        }
        return null;
    }

    public function getMetaPlugin(CompilerCore $compiler, string $metaName): ?MetaPluginInterface
    {
        foreach($this->providers as $provider) {
            $plugin = $provider->getMetaPlugin($compiler, $metaName);
            if ($plugin) {
                return $plugin;
            }
        }
        return null;
    }

    public function getBlockPlugin(CompilerCore $compiler, string $blockName): ?BlockPluginInterface
    {
        foreach($this->providers as $provider) {
            $plugin = $provider->getBlockPlugin($compiler, $blockName);
            if ($plugin) {
                return $plugin;
            }
        }
        return null;
    }

    public function getFunctionPlugin(CompilerCore $compiler, string $funcName): ?FunctionPluginInterface
    {
        foreach($this->providers as $provider) {
            $plugin = $provider->getFunctionPlugin($compiler, $funcName);
            if ($plugin) {
                return $plugin;
            }
        }
        return null;
    }
}