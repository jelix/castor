<?php
declare(strict_types=1);
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

interface TemplateCacheManagerInterface
{
    /**
     * Save a compiled template into a cache
     *
     * @param string $templateName the template identifiant. Can be a path of the template, or any other string.
     * @param string $compiledTemplate
     * @param string $entityTag a tag that identify the uniqueness of the content
     *                          If empty, the cache never expire.
     *                          A tag can be a date (modification date of a file for example),
     *                          a checksum (of a template content) or whatever.
     * @return boolean true if it has been saved successfully
     */
    public function saveCompiledTemplate(string $templateName, CompilationResult $compiledTemplate, string $entityTag = '') : bool;

    /**
     * Says if the given entityTag correspond to the entity tag given when template
     * compiled content has been saved.
     *
     * @param string $templateName the template identifiant
     * @param string $entityTag
     * @return bool true if the cache has expired and then the template should be recompiled.
     *                   It returns true also when the compiled template does not exist
     */
    public function hasToBeCompiled($templateName, $entityTag) : bool;


    public function getTemplateContent($templateName): ContentGeneratorInterface;
}