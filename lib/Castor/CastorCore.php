<?php

/**
 * @author      Laurent Jouanneau
 * @contributor Dominique Papin
 *
 * @copyright   2005-2025 Laurent Jouanneau, 2007 Dominique Papin
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

namespace Jelix\Castor;

use Jelix\Castor\Compiler\CompilerCore;

/**
 * base class of the template engine.
 */
abstract class CastorCore
{

    protected RuntimeContainer $container;

    public function __construct()
    {
        $this->container = new RuntimeContainer();
        
        $this->container->_vars['j_datenow'] = date('Y-m-d');
        $this->container->_vars['j_timenow'] = date('H:i:s');
    }

    /**
     * assign a value in a template variable.
     *
     * @param string|array $name  the variable name, or an associative array 'name'=>'value'
     * @param mixed        $value the value (or null if $name is an array)
     */
    public function assign($name, $value = null)
    {
        if (is_array($name)) {
            $this->container->_vars = array_merge($this->container->_vars, $name);
        } else {
            $this->container->_vars[$name] = $value;
        }
    }

    /**
     * assign a value by reference in a template variable.
     *
     * @param string $name  the variable name
     * @param mixed  $value the value
     */
    public function assignByRef($name, &$value)
    {
        $this->container->_vars[$name] = &$value;
    }

    /**
     * concat a value in with a value of an existing template variable.
     *
     * @param string|array $name  the variable name, or an associative array 'name'=>'value'
     * @param mixed        $value the value (or null if $name is an array)
     */
    public function append($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $key => $val) {
                if (isset($this->container->_vars[$key])) {
                    $this->container->_vars[$key] .= $val;
                } else {
                    $this->container->_vars[$key] = $val;
                }
            }
        } else {
            if (isset($this->container->_vars[$name])) {
                $this->container->_vars[$name] .= $value;
            } else {
                $this->container->_vars[$name] = $value;
            }
        }
    }

    /**
     * assign a value in a template variable, only if the template variable doesn't exist.
     *
     * @param string|array $name  the variable name, or an associative array 'name'=>'value'
     * @param mixed        $value the value (or null if $name is an array)
     */
    public function assignIfNone($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $key => $val) {
                if (!isset($this->container->_vars[$key])) {
                    $this->container->_vars[$key] = $val;
                }
            }
        } else {
            if (!isset($this->container->_vars[$name])) {
                $this->container->_vars[$name] = $value;
            }
        }
    }

    /**
     * says if a template variable exists.
     *
     * @param string $name the variable template name
     *
     * @return bool true if the variable exists
     */
    public function isAssigned($name)
    {
        return isset($this->container->_vars[$name]);
    }

    /**
     * return the value of a template variable.
     *
     * @param string $name the variable template name
     *
     * @return mixed the value (or null if it isn't exist)
     */
    public function get($name)
    {
        if (isset($this->container->_vars[$name])) {
            return $this->container->_vars[$name];
        }
        return null;
    }

    /**
     * Return all template variables.
     *
     * @return array
     */
    public function getTemplateVars()
    {
        return $this->container->_vars;
    }

    /**
     * process all meta instruction of a template.
     *
     * @param TemplateContentInterface $tpl        template selector
     */
    protected function processMeta(TemplateContentInterface $tpl)
    {
        $tplName = $tpl->getName();
        if (in_array($tplName, $this->processedMeta)) {
            // we want to process meta only one time, when a template is included
            // several time in another template, or, more important, when a template
            // is included in a recursive manner (in this case, it did cause infinite loop, see #1396).
            return $this->container->_meta;
        }
        $this->processedMeta[] = $tplName;
        $contentGenerator = $this->getTemplate($tpl);

        return $contentGenerator->meta($this->container);
    }

    /**
     * display the generated content from the given template.
     *
     * @param TemplateContentInterface $tpl   template selector
     */
    protected function processDisplay(TemplateContentInterface $tpl)
    {
        $previousTpl = $this->container->_templateName;
        $this->container->_templateName = $tpl->getName();
        $this->recursiveTpl[] = $this->container->_templateName;

        $contentGenerator = $this->getTemplate($tpl);

        $contentGenerator->content($this->container);

        array_pop($this->recursiveTpl);
        $this->container->_templateName = $previousTpl;
    }


    /**
     * @var string[] list of processed included template to check infinite recursion
     */
    protected $recursiveTpl = array();

    /**
     * @var array list of already processed meta information, to not duplicate
     *            meta content
     */
    protected $processedMeta = array();

    /**
     * include the compiled template file
     *
     * @param TemplateContentInterface $tplLoader   template file
     *
     * @return ContentGeneratorInterface
     */
    protected function getTemplate(TemplateContentInterface $tplLoader)
    {
        $compiler = $this->getCompiler();
        $compiledContent = $compiler->compile($tplLoader, $this->userModifiers, $this->userFunctions);

            $this->cacheManager->saveCompiledTemplate(
                $tplLoader->getName(),
                $compiledContent,
                $tplLoader->cacheTag()
            );

        return $this->cacheManager->getTemplateContent($tplLoader->getName());
    }

    /**
     * @param TemplateContentInterface $tpl        the template name
     * @param string $getTemplateArg
     * @param bool   $callMeta   false if meta should not be called
     *
     * @return false|string
     */
    protected function processFetch(TemplateContentInterface $tplLoader, $callMeta = true)
    {
        $previousTpl = $this->container->_templateName;
        $this->container->_templateName = $tplLoader->getName();
        if ($callMeta) {
            if (in_array($this->container->_templateName, $this->processedMeta)) {
                $callMeta = false;
            } else {
                $this->processedMeta[] = $this->container->_templateName;
            }
        }
        $this->recursiveTpl[] = $this->container->_templateName;

        $contentGenerator = $this->getTemplate($tplLoader);

        ob_start();
        try {
            if ($callMeta) {
                $contentGenerator->meta($this->container);
            }
            $contentGenerator->content($this->container);
            array_pop($this->recursiveTpl);
            $this->container->_templateName = $previousTpl;
            $content = ob_get_clean();
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        } finally {
            array_pop($this->recursiveTpl);
            $this->container->_templateName = $previousTpl;
        }

        return $content;
    }

    abstract protected function getCompiler() : CompilerCore;


    protected $userModifiers = array();

    /**
     * register a user modifier. The function should accept at least a
     * string as first parameter, and should return this string
     * which can be modified.
     *
     * @param string $name         the name of the modifier in a template
     * @param string $functionName the corresponding PHP function
     */
    public function registerModifier($name, $functionName)
    {
        $this->userModifiers[$name] = $functionName;
    }

    protected $userFunctions = array();

    /**
     * register a user function. The function should accept at least a CastorCore object
     * as first parameter.
     *
     * @param string $name         the name of the modifier in a template
     * @param string $functionName the corresponding PHP function
     */
    public function registerFunction($name, $functionName)
    {
        $this->userFunctions[$name] = $functionName;
    }

    /**
     * return the current encoding.
     *
     * @return string the charset string
     */
    public function getEncoding()
    {
        return '';
    }

    /**
     * @return \Exception
     */
    public function getInternalException($messageKey, $parameters)
    {
        $msg = $this->config->getMessage($messageKey, $parameters);

        return new \Exception($msg);
    }

}
