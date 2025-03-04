<?php
/**
 * @author      Laurent Jouanneau
 * @copyright   2025 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */


namespace Jelix\Castor;


class RuntimeContainer
{

    /**
     * all assigned template variables.
     * It has a public access only for plugins. So you mustn't use directly this property
     * except from tpl plugins.
     * See methods of CastorCore to manage template variables.
     *
     * @var array
     */
    public $_vars = array();

    /**
     * temporary template variables for plugins.
     * It has a public access only for plugins. So you mustn't use directly this property
     * except from tpl plugins.
     *
     * @var array
     */
    public $_privateVars = array();

    /**
     * internal use
     * It have a public access only for plugins. So you mustn't use directly this property
     * except from tpl plugins.
     *
     * @var array
     */
    public $_meta = array();

    /**
     * internal use
     * list of macro
     *
     * See macro and usemacro plugins
     */
    public $_macros = array();

    /**
     * contains the name of the template file
     * It have a public access only for plugins. So you musn't use directly this property
     * except from tpl plugins.
     *
     * @var string
     */
    public $_templateName;

    public readonly LocalizedMessagesInterface $messages;

    protected $charset;

    public function __construct(LocalizedMessagesInterface $messages, $charset = 'UTF-8')
    {
        $this->charset = $charset;
        $this->messages = $messages;
    }

    /**
     * @param string $macroName the macro name
     * @param array $parametersNames parameter names for the macro
     * @param callable $func the macro itself, as a function accepting a CastorCore engine as a parameter.
     * @return void
     */
    public function declareMacro($macroName, array $parametersNames, callable $func)
    {
        $this->_macros[$macroName] = array(
            $func,
            $parametersNames
        );
    }

    public function isMacroDefined($macroName)
    {
        return isset($this->_macros[$macroName]);
    }

    /**
     * Call the given macro. Parameters are injected into the template engine as template variables, and removed
     * after the call of the macro.
     *
     * @param string $macroName the macro name to call
     * @param array $parameters parameters for the macro. This is an associative array, with variables names as keys.
     * @return void
     */
    public function callMacro($macroName, $parameters)
    {
        if (!isset($this->_macros[$macroName])) {
            return;
        }

        list($func, $paramNames) =  $this->_macros[$macroName];
        $backupVars = array();

        foreach ($paramNames as $k => $pName) {
            if (isset($this->_vars[$pName])) {
                $backupVars[$pName] = $this->_vars[$pName];
            }
            $this->_vars[$pName] = $parameters[$k];
        }

        $func($this);

        // delete or restore parameters
        foreach ($paramNames as $k => $pName) {
            if (array_key_exists($pName, $backupVars)) {
                $this->_vars[$pName] = $backupVars[$pName];
            }
            else {
                unset($this->_vars[$pName]);
            }
        }
    }

    /**
     * @return \Exception
     */
    public function getInternalException($messageKey, $parameters)
    {
        $msg = $this->messages->getMessage($messageKey, $parameters);

        return new \Exception($msg);
    }
}