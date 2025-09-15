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
use Jelix\Castor\PluginsProvider\CorePluginsProvider;
use Jelix\Castor\PluginsProvider\Legacy\LegacyPluginsProvider;
use Jelix\Castor\PluginsProvider\PluginsProviderGroup;
use Jelix\Castor\PluginsProvider\PluginsProviderInterface;

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
     * @var string[]
     */
    protected $pluginsRepositories = array();

    /**
     * @var PluginsProviderInterface[]
     */
    protected $pluginsProviders = array();

    public readonly TemplateCacheManagerInterface $cacheManager;

    public readonly LocalizedMessagesInterface $messages;

    /**
     * @param string|TemplateCacheManagerInterface $cachePath
     * @param $tplPath
     * @throws \Exception
     */
    public function __construct($cachePath, $tplPath = '', $localizedMessagesPath = '')
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
        $this->addPluginsProvider(new CorePluginsProvider());

        if ($localizedMessagesPath == '') {
            $localizedMessagesPath = realpath(__DIR__.'/locales/').'/%LANG%.php';
        }
        $this->messages = new LocalizedMessages($localizedMessagesPath);
    }

    public function getPluginsProvider() : PluginsProviderInterface
    {
        $list = $this->pluginsProviders;
        if (count($this->pluginsRepositories)) {
            $list[] = new LegacyPluginsProvider($this->pluginsRepositories);
        }
        if (count($list) == 1) {
            return $list[0];
        }
        return new PluginsProviderGroup($list);
    }

    /**
     * Register a Castor V1 plugins repository
     *
     * @param string $path the path to the repository
     * @return void
     * @deprecated
     */
    public function addPluginsRepository($path)
    {
        if (is_array($path)) {
            $this->pluginsRepositories = array_merge($this->pluginsRepositories, $path);
        }
        else {
            $this->pluginsRepositories[] = $path;
        }
    }

    public function addPluginsProvider(PluginsProviderInterface $provider)
    {
        $this->pluginsProviders[] = $provider;
    }
}
