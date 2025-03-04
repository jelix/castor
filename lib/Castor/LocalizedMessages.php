<?php
/**
 * @author      Laurent Jouanneau
 * @copyright   2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */


namespace Jelix\Castor;

class LocalizedMessages implements LocalizedMessagesInterface
{

    /**
     * the lang activated in the templates.
     */
    protected $lang = 'en';

    /**
     * @internal
     * @var \Jelix\SimpleLocalization\Container[]
     */
    protected $localizedMessages = array();

    /**
     * the path of the directory which contains the localized error messages
     * of the template engine. The path should have a / at the end.
     */
    protected $localizedMessagesPath = '';


    public function __construct($localesPath = '')
    {
        if (!$localesPath) {
            $this->localizedMessagesPath = realpath(__DIR__.'/locales/').'/%LANG%.php';
        }
        else {
            $this->localizedMessagesPath = $localesPath;
        }
        $this->setLang('en');
    }

    public function getMessage($key, $params = null) : string
    {
        if (isset($this->localizedMessages[$this->lang])) {
            try {
                $str = $this->localizedMessages[$this->lang]->get($key, $params);
            } catch (\Jelix\SimpleLocalization\Exception $e) {
                $str = $key;
            }
        } else {
            $str = $key;
        }

        return $str;
    }


    public function setLang($lang) : void
    {
        $this->lang = $lang;
        if (!isset($this->localizedMessages[$lang])) {
            $this->localizedMessages[$lang] = new \Jelix\SimpleLocalization\Container($this->localizedMessagesPath, $lang);
        }
    }

    public function getLang() : string
    {
        return $this->lang;
    }



}