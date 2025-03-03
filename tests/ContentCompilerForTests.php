<?php
/**
 * @author      Laurent Jouanneau
 * @copyright   2007-2025 Laurent Jouanneau
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */



class ContentCompilerForTests extends \Jelix\Castor\Compiler\Compiler {

    public function setUserPlugins($userModifiers, $userFunctions) {
        $this->_modifier = array_merge($this->_modifier, $userModifiers);
        $this->_userFunctions = $userFunctions;
    }

    public function compileContent2($content){
        return $this->compileContent($content);
    }

    public function setRemoveASPTags($b) {
        $this->removeASPtags = $b;
    }

    public function testParseExpr($string, $allowed=array(), $exceptchar=array(';'), $splitArgIntoArray=false){
        return $this->_compileArgs($string, $allowed, $exceptchar, $splitArgIntoArray);
    }

    public function testParseVarExpr($string){
        return $this->_compileArgs($string,$this->_allowedInVar, $this->_excludedInVar);
    }

    public function testParseForeachExpr($string){
        return $this->_compileArgs($string,$this->_allowedInForeach, array(';','!'));
    }

    public function testParseAnyExpr($string){
        return $this->_compileArgs($string, $this->_allowedInExpr, array());
    }

    public function testParseAssignExpr($string){
        return $this->_compileArgs($string,$this->_allowedAssign);
    }

    public function testParseAssignExpr2($string){
        return $this->_compileArgs($string,$this->_allowedAssign, array(';'),true);
    }

    public function testParseVariable($string, $outputType = ''){
        $this->outputType = $outputType;
        return $this->_parseVariable($string);
    }
}
