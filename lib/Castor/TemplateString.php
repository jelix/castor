<?php

declare(strict_types=1);
/**
 * @author      Laurent Jouanneau
 * @copyright   2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

namespace Jelix\Castor;

class TemplateString implements TemplateContentInterface
{

    protected $tplName;
    protected $content;
    protected $cacheTag;
    protected $_isTrusted = true;

    public function __construct($tplName, $content, $cacheTag = '', $isTrusted = true)
    {
        $this->tplName = $tplName;
        $this->content = $content;
        $this->cacheTag = $cacheTag;
        $this->_isTrusted = $isTrusted;
    }

    public function getName() : string
    {
        return $this->tplName;
    }

    public function isTrusted() : bool
    {
        return $this->_isTrusted;
    }

    public function getContent() : string
    {
        return $this->content;
    }


    public function cacheTag() : string
    {
        return $this->cacheTag;
    }
}