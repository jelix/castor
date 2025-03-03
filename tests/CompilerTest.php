<?php
/**
* @author      Laurent Jouanneau
* @copyright   2007-2022 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/



class testJtplContentCompiler extends \Jelix\Castor\Compiler\Compiler {

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


class CompilerTest extends \PHPUnit\Framework\TestCase {

    protected static $castorConfig;

    function setUp()  : void {
        $cachePath = realpath(__DIR__.'/temp/') . '/';
        $templatePath = __DIR__.'/';
        
        self::$castorConfig = new \Jelix\Castor\Config($cachePath, $templatePath);
        self::$castorConfig->setLang('fr');
    }


    public function getTemplateToCompile()
    {
        return array(
            array(
                '',
                '',
            ),
            array(
                '<p>ok</p>',
                '<p>ok</p>',
            ),
            array(
                '<p>ok<?php echo $toto ?></p>',
                '<p>ok</p>',
            ),
            array(
                '<p>ok</p>
<script>{literal}
function toto() {
}
{/literal}
</script>
<p>ko</p>',
                '<p>ok</p>
<script>
function toto() {
}

</script>
<p>ko</p>',
            ),
            array(
                '<p>ok</p>
<script>{verbatim}
function toto() {
}
{/verbatim}
</script>
<p>ko</p>',
                '<p>ok</p>
<script>
function toto() {
}

</script>
<p>ko</p>',
            ),
            array(
                '<p>ok {* toto $toto *}</p>',
                '<p>ok </p>',
            ),

            array(
                '<p>ok {* toto

 $toto *}</p>',
                '<p>ok </p>',
            ),

            array(
                '<p>ok {* toto
{$toto} *}</p>',
                '<p>ok </p>',
            ),
            array(
                '<p>ok {* toto
{$toto} *}</p> {* hello *}',
                '<p>ok </p> ',
            ),
            array(
                '<p>ok {* {if $a == "a"}aaa{/if} *}</p>',
                '<p>ok </p>',
            ),

            array(
                '<p>ok {# toto $toto #}</p>',
                '<p>ok </p>',
            ),

            array(
                '<p>ok {# toto

 $toto #}</p>',
                '<p>ok </p>',
            ),

            array(
                '<p>ok {# toto
{$toto} #}</p>',
                '<p>ok </p>',
            ),
            array(
                '<p>ok {# toto
{$toto} #}</p> {# hello #}',
                '<p>ok </p> ',
            ),
            array(
                '<p>ok {# {if $a == "a"}aaa{/if} #}</p>',
                '<p>ok </p>',
            ),
            array(
                '<p>ok<? echo $toto ?></p>',
                '<p>ok</p>',
            ),
            array(
                '<p>ok<?= $toto ?></p>',
                '<p>ok</p>',
            ),
            array(
                '<p>ok{if $foo} {/if}</p>',
                '<p>ok<?php if($t->_vars[\'foo\']):?> <?php endif;?></p>',
            ),
            array(
                '<p>ok{if ($foo)} {/if}</p>',
                '<p>ok<?php if(($t->_vars[\'foo\'])):?> <?php endif;?></p>',
            ),
            array(
                '<p>ok{while ($foo)} {/while}</p>',
                '<p>ok<?php while(($t->_vars[\'foo\'])):?> <?php endwhile;?></p>',
            ),
            array(
                '<p>ok{while $foo} {/while}</p>',
                '<p>ok<?php while($t->_vars[\'foo\']):?> <?php endwhile;?></p>',
            ),
            array(
                '<p>ok{$foo.($truc.$bbb)}</p>',
                '<p>ok<?php echo $t->_vars[\'foo\'].($t->_vars[\'truc\'].$t->_vars[\'bbb\']); ?></p>',
            ),
            array(
                '<p>ok{if ($foo || $bar) && $baz} {/if}</p>',
                '<p>ok<?php if(($t->_vars[\'foo\'] || $t->_vars[\'bar\']) && $t->_vars[\'baz\']):?> <?php endif;?></p>',
            ),
            array(
                '<p>ok{bla $foo, $params}</p>',
                '<p>ok<?php testjtplcontentUserFunction( $t,$t->_vars[\'foo\'], $t->_vars[\'params\']);?></p>',
            ),
            array('{for ($i=0;$i<$p;$i++)} A {/for}',
                '<?php for($t->_vars[\'i\']=0;$t->_vars[\'i\']<$t->_vars[\'p\'];$t->_vars[\'i\']++):?> A <?php endfor;?>'
            ),
            array('{for $i=0;$i<$p;$i++} A {/for}',
                '<?php for($t->_vars[\'i\']=0;$t->_vars[\'i\']<$t->_vars[\'p\'];$t->_vars[\'i\']++):?> A <?php endfor;?>'
            ),
            array('{for $i=count($o);$i<$p;$i++} A {/for}',
                '<?php for($t->_vars[\'i\']=count($t->_vars[\'o\']);$t->_vars[\'i\']<$t->_vars[\'p\'];$t->_vars[\'i\']++):?> A <?php endfor;?>'
            ),
            array(
                '<p>ok {const $foo}</p>',
                '<p>ok <?php echo htmlspecialchars(constant($t->_vars[\'foo\']));?></p>',
            ),
            array(
                '<p>ok{=$foo.($truc.$bbb)}</p>',
                '<p>ok<?php echo $t->_vars[\'foo\'].($t->_vars[\'truc\'].$t->_vars[\'bbb\']); ?></p>',
            ),
            array(
                '<p>ok{=intval($foo.($truc.$bbb))}</p>',
                '<p>ok<?php echo intval($t->_vars[\'foo\'].($t->_vars[\'truc\'].$t->_vars[\'bbb\'])); ?></p>',
            ),
            array(
                '<p>ok<? echo $toto ?></p>',
                '<p>ok</p>',
            ),
            array(
                '<p>ok<?
 echo $toto ?></p>',
                '<p>ok</p>',
            ),
            array(
                '<p>ok<?=$toto ?></p>',
                '<p>ok</p>',
            ),
            array(
                '<p>ok<?xml echo $toto ?></p>',
                '<p>ok<?php echo \'<?xml echo $toto ?>\'?></p>',
            ),
            array(
                '<p>ok<?browser echo $toto ?></p>',
                '<p>ok<?php echo \'<?browser echo $toto ?>\'?></p>',
            ),
            array(
                '<p>ok<?php
 echo $toto ?></p>',
                '<p>ok</p>',
            ),

            array(
                '<p>{assign $foo = \'bar\'}ok{$foo}</p>',
                '<p><?php $t->_vars[\'foo\'] = \'bar\';?>ok<?php echo $t->_vars[\'foo\']; ?></p>',
            ),

            array(
                '<p>{set $foo = \'bar\'}ok{$foo}</p>',
                '<p><?php $t->_vars[\'foo\'] = \'bar\';?>ok<?php echo $t->_vars[\'foo\']; ?></p>',
            ),

            array(
                '<p>{set $foo += 50}ok{$foo}</p>',
                '<p><?php $t->_vars[\'foo\'] += 50;?>ok<?php echo $t->_vars[\'foo\']; ?></p>',
            ),

            array(
                '<p>{$foo}</p>
<p>{$foo|raw}</p>
<p>{$foo|eschtml}</p>
',
                '<p><?php echo $t->_vars[\'foo\']; ?></p>
<p><?php echo $t->_vars[\'foo\']; ?></p>
<p><?php echo htmlspecialchars($t->_vars[\'foo\'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8"); ?></p>
',
            ),
            array(
                '{! autoescape !}
<p>{$foo}</p>
<p>{$foo|raw}</p>
<p>{$foo|eschtml}</p>
',
                '
<p><?php echo htmlspecialchars($t->_vars[\'foo\'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8"); ?></p>
<p><?php echo $t->_vars[\'foo\']; ?></p>
<p><?php echo htmlspecialchars($t->_vars[\'foo\'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8"); ?></p>
',
            ),

        );
    }

    /**
     * @dataProvider getTemplateToCompile
     * @param string $template
     * @param string $expectedResult
     */
    function testCompileContent($template, $expectedResult)
    {
        $compil = new testJtplContentCompiler(self::$castorConfig);
        $compil->outputType = 'html';
        $compil->trusted = true;
        $compil->setUserPlugins(array(), array('bla' => 'testjtplcontentUserFunction'));
        $compil->setRemoveASPtags(false);

        $this->assertEquals($expectedResult, $compil->compileContent2($template));
    }

    function testCompileContentWithASPTags()
    {
        $compil = new testJtplContentCompiler(self::$castorConfig);
        $compil->outputType = 'html';
        $compil->trusted = true;
        $compil->setUserPlugins(array(), array('bla' => 'testjtplcontentUserFunction'));
        $compil->setRemoveASPtags(false);
        $this->assertEquals('<p>ok<?php echo \'<?xml version="truc"?>\'?></p>', $compil->compileContent2('<p>ok<?xml version="truc"?></p>'));
        $this->assertEquals('<p>ok<?php echo \'<?xml version=\\\'truc\\\'?>\'?></p>', $compil->compileContent2('<p>ok<?xml version=\'truc\'?></p>'));
        $this->assertEquals('<p>ok<?php echo \'<?xml
  version="truc"?>\'?></p>', $compil->compileContent2('<p>ok<?xml
  version="truc"?></p>'));
        $this->assertEquals('<p>ok<%=$truc%></p>', $compil->compileContent2('<p>ok<%=$truc%></p>'));
        $compil->setRemoveASPtags(true);
        $this->assertEquals('<p>ok</p>', $compil->compileContent2('<p>ok<%=$truc%></p>'));
    }

    public function getUntrustedContent()
    {
        return array(
            0 => array('{for ($i=0;$i<$p;$i++)} A {/for}',
                '<?php for($t->_vars[\'i\']=0;$t->_vars[\'i\']<$t->_vars[\'p\'];$t->_vars[\'i\']++):?> A <?php endfor;?>'
            ),
            1 => array('{for $i=0;$i<$p;$i++} A {/for}',
                '<?php for($t->_vars[\'i\']=0;$t->_vars[\'i\']<$t->_vars[\'p\'];$t->_vars[\'i\']++):?> A <?php endfor;?>'
            ),
        );
    }

    /**
     * @dataProvider getUntrustedContent
     * @return void
     */
    function testCompileContentUntrusted($template, $expectedResult) {
        $compil = new testJtplContentCompiler(self::$castorConfig);
        $compil->outputType = 'html';
        $compil->trusted = false;
        $compil->setUserPlugins(array(), array('bla'=>'testjtplcontentUserFunction'));
        $this->assertEquals($expectedResult, $compil->compileContent2($template));
    }


    public function getContentPlugins()
    {
        return array(
            1 => array(
                '{macro \'foo\'}<p>ok</p>{/macro}',
                '<?php $t->declareMacro(\'foo\', array(),  function($t) {' . "\n" .
                '?><p>ok</p><?php ' . "\n});\n?>"
            ),
            2 => array(
                '{macro \'foo\', $arg1, $arg2}<p>ok {$arg1}</p>{/macro}',
                '<?php $t->declareMacro(\'foo\', array(\'arg1\',\'arg2\'),  function($t) {' . "\n" .
                '?><p>ok <?php echo $t->_vars[\'arg1\']; ?></p><?php ' . "\n});\n?>"
            ),
            3 => array(
                '{usemacro \'foo\'}',
                '<?php jtpl_function_common_usemacro( $t,\'foo\');?>'
            ),
            4 => array(
                '{usemacro \'foo\', $arg1, $arg2 }',
                '<?php jtpl_function_common_usemacro( $t,\'foo\', $t->_vars[\'arg1\'], $t->_vars[\'arg2\'] );?>'
            ),
        );
    }

    /**
     * @dataProvider getContentPlugins
     * @param $template
     * @param $expectedResult
     * @return void
     */
    function testCompilePlugins($template, $expectedResult)
    {
        $compil = new testJtplContentCompiler(self::$castorConfig);
        $compil->outputType = 'html';
        $compil->trusted = true;

        $this->assertEquals($expectedResult, $compil->compileContent2($template));
    }

    public function getTemplateErrors()
    {
        return array(
            0 => array('{if $foo}',
                'Dans le template , la fin d\'un bloc if est manquant'),
            2 => array('{foreach ($t=>$a);} A {/foreach}',
                'Dans le tag foreach ($t=>$a); du template , le caractère  ; n\'est pas autorisé'),
            3 => array('{for ($i=0;$i<$p;$i++} A {/for}',
                'Dans le tag for ($i=0;$i<$p;$i++ du template , il y a des erreurs au niveau des parenthèses'),
            5 => array('{($aaa)}',
                'Dans le template  La syntaxe de balise ($aaa) est invalide'),
        );
    }

    /**
     * @dataProvider getTemplateErrors
     * @param $template
     * @param $expectedResult
     * @return void
     */
    function testCompileErrors($template, $expectedResult) {

            $compil = new testJtplContentCompiler(self::$castorConfig);
            $compil->outputType = 'html';
            $compil->trusted = true;
            $ok = true;
            try{
                $compil->compileContent2($template);
                $ok = false;
            }catch(Exception $e){
                $this->assertEquals($expectedResult, $e->getMessage());
            }
            $this->assertTrue($ok);
    }

    public function getTplErrors2()
    {
        return array(
            0 => array('{for $i=count($a);$i<$p;$i++} A {/for}',
                'Dans le tag for $i=count($a);$i<$p;$i++ du template , le caractère  ( n\'est pas autorisé'),
            1 => array('{const \'fff\'}',
                'Le tag const dans le template  n\'est pas autorisé dans un template sans confiance'),
        );
    }

    /**
     * @dataProvider getTplErrors2
     * @param $template
     * @param $expectedResult
     * @return void
     */
    function testCompileErrorsUntrusted($template, $expectedResult) {

            $compil = new testJtplContentCompiler(self::$castorConfig);
            $compil->outputType = 'html';
            $compil->trusted = false;
            $ok = true;
            try{
                $compil->compileContent2($template);
                $ok = false;
            }catch(Exception $e){
                $this->assertEquals($expectedResult, $e->getMessage());
            }
            $this->assertTrue($ok);
    }

}
