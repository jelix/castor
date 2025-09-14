<?php


class testJtplContentCompiler extends \Jelix\Castor\Compiler {

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

}

function testjtplcontentUserFunction($t,$a,$b) {

}
