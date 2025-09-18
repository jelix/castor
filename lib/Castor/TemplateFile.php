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

class TemplateFile implements TemplateContentInterface
{

    protected $tplName;
    protected $tplFilePath;
    protected $_isTrusted = true;
    protected $syntaxVersion = 1;

    public function __construct($tplName, $tplFilePath, $isTrusted = true)
    {
        $this->tplName = $tplName;
        $this->tplFilePath = $tplFilePath;
        $this->_isTrusted = $isTrusted;
        if (preg_match('/\\.ctpl$/', $tplFilePath)) {
            $this->syntaxVersion = 2;
        }
    }

    public function getName() : string
    {
        return $this->tplName;
    }

    public function isTrusted() : bool
    {
        return $this->_isTrusted;
    }

    public function getSyntaxVersion() : int
    {
        return $this->syntaxVersion;
    }

    public function getContent() : string
    {
        if (!file_exists($this->tplFilePath)) {
            throw new \Exception('Template not found');
        }
        return file_get_contents($this->tplFilePath);
    }


    public function cacheTag() : string
    {
        if (!file_exists($this->tplFilePath)) {
            return '';
        }
        return (string) filemtime($this->tplFilePath);
    }
}