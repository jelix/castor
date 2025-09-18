<?php
/**
 * @author      Laurent Jouanneau
 * @copyright   2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */


namespace Jelix\Castor;

interface LocalizedMessagesInterface
{
    public function getMessage($key, $params = null) : string;

    public function setLang($lang) : void;

    public function getLang() : string;

}