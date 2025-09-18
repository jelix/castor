<?php
/**
 * @author      Laurent Jouanneau
 * @copyright   2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
namespace Jelix\Castor\CacheManager;

use Jelix\Castor\Compiler\CompilationResult;
use Jelix\Castor\ContentGeneratorInterface;

class FileCacheManager implements TemplateCacheManagerInterface
{

    /**
     * the path of the cache directory.  The path should have a / at the end.
     */
    protected $cachePath = '';

    protected $umask = 0000;

    /**
     * permissions for directories created in the cache directory.
     */
    protected $chmodDir = 0755;

    /**
     * permissions for cache files.
     */
    protected $chmodFile = 0644;

    protected $metadata = array();


    public function __construct($cachePath, $chmodDir=0755, $chmodFile=0644, $umask=0000)
    {
        if ($cachePath == '/' || $cachePath == '') {
            throw new \InvalidArgumentException('cache path is invalid ! It should be a full path to a real directory.');
        }
        $this->cachePath = $cachePath;
        $this->umask = $umask;
        $this->chmodFile = $chmodFile;
        $this->chmodDir = $chmodDir;
    }

    protected function getCompiledTemplatePath($templateName, $createDir=false): string
    {
        $cacheSubdir = dirname($templateName).'/';
        if ($cacheSubdir == './') {
            $cacheSubdir = '';
        }
        $cacheDir = $this->cachePath.$cacheSubdir;
        if ($createDir) {
            if (!is_dir($cacheDir)) {
                umask($this->umask);
                mkdir($cacheDir, $this->chmodDir, true);
            } elseif (!@is_writable($cacheDir)) {
                throw new Exception('Impossible to write into the file '.$templateName.', verify that rights are enabled');
            }
        }
        return $cacheDir. basename($templateName);
    }

    public function hasToBeCompiled($templateName, $entityTag) : bool
    {
        $path = $this->getCompiledTemplatePath($templateName);
        if (!isset($this->metadata[$templateName])) {
            if (!file_exists($path.'-metadata.json')) {
                return true;
            }
            if (!file_exists($path.'.php')) {
                return true;
            }
            $metadata = json_decode(file_get_contents($path.'-metadata.json'), true);
        }
        else {
            $metadata = $this->metadata[$templateName];
        }

        if ($metadata === null || !isset($metadata['entityTag']) || $metadata['entityTag'] != $entityTag) {
            return true;
        }
        return false;
    }

    public function saveCompiledTemplate(string $templateName, CompilationResult $compiledTemplate, string $entityTag = '') : bool
    {
        $path = $this->getCompiledTemplatePath($templateName, true);
        $metadata = ['entityTag' => $entityTag, 'class' => $compiledTemplate->getClassName()];

        if (!$this->saveFile($path.'-metadata.json', json_encode($metadata))) {
            return false;
        }

        $this->metadata[$templateName] = $metadata;

        if (!$this->saveFile($path.'.php', $compiledTemplate->getTemplateClassSource())) {
            return false;
        }

        return true;
    }

    protected function saveFile($filename, $content)
    {
        $_dirname = dirname($filename).'/';

        // write to tmp file, then rename it to avoid
        // file locking race condition
        $_tmp_file = tempnam($_dirname, 'wrt');

        if (!($fd = @fopen($_tmp_file, 'wb'))) {
            $_tmp_file = $_dirname.'/'.uniqid('wrt');
            if (!($fd = @fopen($_tmp_file, 'wb'))) {
                throw new Exception('A problem has occured during the writing of the file '.$filename.' by using the temporary file '.$_tmp_file);
            }
        }

        fwrite($fd, $content);
        fclose($fd);

        // Delete the file if it already exists (this is needed on Win,
        // because it cannot overwrite files with rename()
        if (substr(PHP_OS, 0, 3) == 'WIN' && file_exists($filename)) {
            @unlink($filename);
        }

        @rename($_tmp_file, $filename);
        @chmod($filename, $this->chmodFile);
        return true;
    }

    public function getTemplateContent($templateName): ContentGeneratorInterface
    {
        $path = $this->getCompiledTemplatePath($templateName);

        if (!isset($this->metadata[$templateName])) {
            if (!file_exists($path.'-metadata.json')) {
                throw new \Exception('template metadata is missing');
            }
            if (!file_exists($path.'.php')) {
                throw new \Exception('compiled template is missing');
            }
            $metadata = json_decode(file_get_contents($path.'-metadata.json'), true);
        }
        else {
            $metadata = $this->metadata[$templateName];
        }

        require_once $path.'.php';

        $className = $metadata['class'];

        /** @var ContentGeneratorInterface $tplObj */
        return new $className();
    }
}