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

use Jelix\Castor\CacheManager\FileCacheManager;
use Jelix\Castor\CacheManager\TemplateCacheManagerInterface;

class Config
{
    /**
     * the path of the directory which contains the
     * templates. The path should have a / at the end.
     */
    public $templatePath = '';

    /**
     * boolean which indicates if the templates
     * should be compiled at each call or not.
     */
    public $compilationForce = false;

    /**
     * the lang activated in the templates.
     */
    protected $lang = 'en';

    /**
     * the charset used in the templates.
     */
    public $charset = 'UTF-8';

    /**
     * the function which allow to retrieve the locales used in your templates.
     *
     * @var callable
     */
    public $localesGetter = null;

    /**
     * the path of the cache directory.  The path should have a / at the end.
     */
    public $cachePath = '';

    /**
     * the path of the directory which contains the localized error messages
     * of the template engine. The path should have a / at the end.
     */
    protected $localizedMessagesPath = '';

    /**
     * umask for directories created in the cache directory.
     */
    public $umask = 0000;

    /**
     * permissions for directories created in the cache directory.
     */
    public $chmodDir = 0755;

    /**
     * permissions for cache files.
     */
    public $chmodFile = 0644;

    /**
     * @internal
     * @var \Jelix\SimpleLocalization\Container[]
     */
    protected $localizedMessages = array();

    protected $pluginsRepositories = array();

    public readonly TemplateCacheManagerInterface $cacheManager;

    /**
     * @param string|TemplateCacheManagerInterface $cachePath
     * @param $tplPath
     * @throws \Exception
     */
    public function __construct($cachePath, $tplPath = '')
    {

        if (is_string($cachePath)) {
            $this->cacheManager = new FileCacheManager($cachePath, $this->chmodDir, $this->chmodFile, $this->umask);
        }
        else if ($cachePath instanceof TemplateCacheManagerInterface) {
            $this->cacheManager = $cachePath;
        }
        else {
            throw new \InvalidArgumentException('The $cachePath argument must be a string or a TemplateCacheManagerInterface instance');
        }

        $this->cachePath = $cachePath;
        $this->templatePath = $tplPath;
        $this->addPluginsRepository(realpath(__DIR__.'/../plugins/'));
        $this->localizedMessagesPath = realpath(__DIR__.'/locales/').'/%LANG%.php';
        $this->setLang($this->lang);
    }

    public function setLang($lang)
    {
        $this->lang = $lang;
        if (!isset($this->localizedMessages[$lang])) {
            $this->localizedMessages[$lang] = new \Jelix\SimpleLocalization\Container($this->localizedMessagesPath, $lang);
        }
    }

    public function getLang()
    {
        return $this->lang;
    }

    public function getMessage($key, $params = null)
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

    public function setLocalizedMessagesPath($path)
    {
        $this->localizedMessagesPath = rtrim($path, '/').'/%LANG%.php';
        $this->setLang($this->lang);
    }

    public function addPluginsRepository($path)
    {
        if (is_array($path)) {
            $this->pluginsRepositories = array_merge($this->pluginsRepositories, $path);
        }
        else {
            $this->pluginsRepositories[] = $path;
        }
    }

    public function getPluginsRepositories()
    {
        return $this->pluginsRepositories;
    }
}
