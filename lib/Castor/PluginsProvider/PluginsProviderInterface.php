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

    public function getModifierPlugin(CompilerCore $compiler, string $modifierName) : ?ModifierPluginInterface;

    public function getMetaPlugin(CompilerCore $compiler, string $metaName) : ?MetaPluginInterface;

    public function getPlugin(CompilerCore $compiler, string $name) : ?PluginInterface;

}