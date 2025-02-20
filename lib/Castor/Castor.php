<?php

/**
 * @author      Loic Mathaud
 * @contributor Laurent Jouanneau
 *
 * @copyright   2006 Loic Mathaud
 * @copyright   2006-2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

namespace Jelix\Castor;

use Jelix\Castor\CacheManager\TemplateCacheManagerInterface;
use Jelix\Castor\Compiler\Compiler;
use Jelix\Castor\Compiler\CompilerCore;

/**
 * Main class of the template engine.
 */
class Castor extends CastorCore
{
    /**
     * @var Config
     */
    protected $config = null;


    protected TemplateCacheManagerInterface $cacheManager;

    /**
     *
     * @param  Config  $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->cacheManager = $config->cacheManager;
        parent::__construct();
    }

    /**
     * include the compiled template file
     *
     * @param TemplateContentInterface $tplLoader   template file
     *
     * @return ContentGeneratorInterface
     */
    protected function getTemplate(TemplateContentInterface $tplLoader)
    {
        if ($this->cacheManager->hasToBeCompiled(
            $tplLoader->getName(),
            $tplLoader->cacheTag())
        ) {
            $compiler = $this->getCompiler();
            $compiledContent = $compiler->compile($tplLoader, $this->userModifiers, $this->userFunctions);
            $this->cacheManager->saveCompiledTemplate(
                $tplLoader->getName(),
                $compiledContent,
                $tplLoader->cacheTag()
            );
        }

        return $this->cacheManager->getTemplateContent($tplLoader->getName());
    }

    /**
     * Process all meta instructions of a template.
     *
     * @param TemplateContentInterface|string $tpl        template selector
     * @param bool   $trusted    says if the template file is trusted or not. Only relevant if $tpl is a string.
     */
    public function meta(TemplateContentInterface|string $tpl, $trusted = true)
    {
        if (is_string($tpl)) {
            $tpl = new TemplateFile(
                $tpl,
                $this->config->templatePath.'/'.$tpl,
                $trusted
            );
        }
        return $this->processMeta($tpl);
    }


    /**
     * Display the generated content from the given template.
     *
     * @param TemplateContentInterface|string $tpl   template selector
     * @param bool   $trusted    says if the template file is trusted or not. Only relevant if $tpl is a string.
     */
    public function display(TemplateContentInterface|string $tpl, $trusted = true)
    {
        if (is_string($tpl)) {
            $tpl = new TemplateFile(
                $tpl,
                $this->config->templatePath.'/'.$tpl,
                $trusted
            );
        }

        $this->processDisplay($tpl);
    }

    /**
     * return the generated content from the given template.
     *
     * @param TemplateContentInterface|string $tpl        template selector
     * @param bool   $trusted    says if the template file is trusted or not. Only relevant if $tpl is a string.
     * @param bool   $callMeta   false if meta should not be called
     *
     * @return string the generated content
     */
    public function fetch(TemplateContentInterface|string $tpl, $trusted = true, $callMeta = true)
    {
        if (is_string($tpl)) {
            $tpl = new TemplateFile(
                $tpl,
                $this->config->templatePath.'/'.$tpl,
                $trusted
            );
        }

        return $this->processFetch($tpl, $callMeta);
    }


    /**
     * Return the generated content from the given string template (virtual).
     *
     * @param string $tplContent        template content
     * @param bool   $trusted    says if the template file is trusted or not
     * @param bool   $callMeta   false if meta should not be called
     *
     * @return string the generated content
     */
    public function fetchFromString($tplContent, $trusted = true, $callMeta = true)
    {
        $tplName = md5($tplContent);

        $tpl = new TemplateString(
            $tplName,
            $tplContent,
            $tplName,
            $trusted
        );

        return $this->processFetch($tpl, $callMeta);
    }

    protected function getCompiler() : CompilerCore
    {
        return  new Compiler($this->config);
    }

    /**
     * return the current encoding.
     *
     * @return string the charset string
     */
    public function getEncoding()
    {
        return $this->config->charset;
    }

    public function getLocaleString($locale)
    {
        $getter = $this->config->localesGetter;
        if ($getter) {
            $res = call_user_func($getter, $locale);
        } else {
            $res = $locale;
        }

        return $res;
    }
}
