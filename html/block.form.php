<?php
/**
* @package     jelix
* @subpackage  jtpl_plugin
* @author      Jouanneau Laurent
* @copyright   2006-2008 Jouanneau laurent
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/

/**
 * a block to display an html form, with data from a jforms
 *
 * usage : {form $theformobject,'submit_action', $submit_action_params} here form content {/form}
 *
 * You can add this others parameters :
 *   string $errDecorator name of your javascript object for error listener<br/>
 *   string $helpDecorator name of your javascript object for help listener<br/>
 *   string $method : the method of submit : post or get
 *
 * @param jTplCompiler $compiler the template compiler
 * @param boolean $begin true if it is the begin of block, else false
 * @param array $param 0=>form object 
 *                     1=>selector of submit action  
 *                     2=>array of parameters for submit action 
 *                     3=>name of your javascript object for error listener
 *                     4=>name of your javascript object for help listener
 *                     5=>name of the method : 'post' or 'get'
 * @return string the php code corresponding to the begin or end of the block
 * @see jForms
 */
function jtpl_block_html_form($compiler, $begin, $param=array())
{

    if(!$begin){
        return '$t->_privateVars[\'__formbuilder\']->outputFooter(); 
unset($t->_privateVars[\'__form\']); 
unset($t->_privateVars[\'__formbuilder\']);
unset($t->_privateVars[\'__displayed_ctrl\']);';
    }

    if(count($param) < 2 || count($param) > 6){
        $compiler->doError2('errors.tplplugin.block.bad.argument.number','form','2-6');
        return '';
    }
    if(count($param) == 2){
        $param[2] = 'array()';
    }

    if(isset($param[3]) && $param[3] != '""'  && $param[3] != "''")
        $errdecorator = $param[3];
    else
        $errdecorator = "'jFormsErrorDecoratorAlert'";

    if(isset($param[4]) && $param[4] != '""'  && $param[4] != "''")
        $helpdecorator = $param[4];
    else
        $helpdecorator = "'jFormsHelpDecoratorAlert'";

    $method = strtolower(isset($param[5])?$param[5]:'post');
    if($method!='get' && $method!='post')
        $method='post';

    $content = ' $t->_privateVars[\'__form\'] = '.$param[0].';
$t->_privateVars[\'__formbuilder\'] = $t->_privateVars[\'__form\']->getBuilder(\'html\');
$t->_privateVars[\'__formbuilder\']->setAction('.$param[1].','.$param[2].');
$t->_privateVars[\'__formbuilder\']->outputHeader(array('.$errdecorator.','.$helpdecorator.',\''.$method.'\'));
$t->_privateVars[\'__displayed_ctrl\'] = array();
';
    $compiler->addMetaContent($param[0].'->getBuilder(\'html\')->outputMetaContent($t);');

    return $content;
}

