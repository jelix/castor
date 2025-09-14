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

class JsonDecodeModifierPlugin implements ModifierPluginInterface
{
    public function compile(CompilerCore $compiler, $name, array $compiledModifierArgs, $compiledExpression) : string
    {
        switch($name) {
            /**
             * extract a value from a JSON value
             *
             *  <pre>{$myjson|json_decode:"mykey"}</pre>
             */
            case 'json_decode':
                if (count($compiledModifierArgs) != 1) {
                    $compiler->doError2('errors.tplplugin.cmodifier.bad.argument.number', $name, '1');
                }
                return  get_class($this) . '::jsonDecode('.
                    $compiledExpression.','.$compiledModifierArgs[0] . ')';
        }
        return '';
    }

    public static function jsonDecode($jsonStr, $key)
    {
        $json = @json_decode($jsonStr, true);
        if (is_array($json) && array_key_exists($key, $json)) {
            return $json[$key];
        }
        return null;
    }
}
