<?php

/**
 * @author      Laurent Jouanneau
 * @copyright   2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
namespace Jelix\Castor\Compiler;

class CompilationResult
{
    public function __construct(
        protected string $className,
        protected string $templateClassSource
    )
    {

    }

    public function getClassName()
    {
        return $this->className;
    }

    public function getTemplateClassSource()
    {
        return $this->templateClassSource;
    }
}