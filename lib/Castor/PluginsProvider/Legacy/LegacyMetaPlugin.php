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
use Jelix\Castor\PluginsProvider\MetaPluginInterface;

class LegacyMetaPlugin implements MetaPluginInterface
{

    protected $metaName = '';
    protected $filePath = '';
    protected $functionName = '';

    public function __construct($metaName, $filePath, $functionName)
    {
        $this->metaName = $metaName;
        $this->filePath = $filePath;
        $this->functionName = $functionName;
    }

    public function compileForMeta(
        CompilerCore $compiler,
        string $metaName,
        string $metaSubName,
        string $compiledTagArgs) : string
    {
        $compiler->addPathToInclude($this->filePath);
        return $this->functionName.'( $t,'."'".$metaSubName."',".$compiledTagArgs.");\n";
    }
}