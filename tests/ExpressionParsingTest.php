<?php
/**
* @author      Laurent Jouanneau
* @copyright   2007-2025 Laurent Jouanneau
* @link        https://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/



class ExpressionParsingTest extends \PHPUnit\Framework\TestCase {

    protected static $castorConfig;

    function setUp() :void {
        $cachePath = realpath(__DIR__.'/temp/') . '/';
        $templatePath = __DIR__.'/';
        self::$castorConfig = new \Jelix\Castor\Config($cachePath, $templatePath);
        self::$castorConfig->messages->setLang('fr');
    }

    public function getExpressionVars()
    {
        return array(
            array('a', 'a'),
            array('"aaa"', '"aaa"'),
            array('123', '123'),
            array('  ', ' '),
            array('123.456', '123.456'),
            array('->', '->'),
            array("'aze'", "'aze'"),
            array('$aa', '$t->_vars[\'aa\']'),
            array('$aa.$bb', '$t->_vars[\'aa\'].$t->_vars[\'bb\']'),
            array('$aa."bbb"', '$t->_vars[\'aa\']."bbb"'),
            array('$aa+234', '$t->_vars[\'aa\']+234'),
            array('$aa-234', '$t->_vars[\'aa\']-234'),
            array('$aa*234', '$t->_vars[\'aa\']*234'),
            array('$aa/234', '$t->_vars[\'aa\']/234'),
            array('!$aa', '!$t->_vars[\'aa\']'),
            array('array($aa)', 'array($t->_vars[\'aa\'])'),
            array(' array(   $aa  )   ', ' array( $t->_vars[\'aa\'] ) '),
            array(' array("e"=>$aa, "tt"=>987  )', ' array("e"=>$t->_vars[\'aa\'], "tt"=>987 )'),
            array('@aa@', '$t->getLocaleString(\'aa\')'),
            array('@aa.$ooo@', '$t->getLocaleString(\'aa.\'.$t->_vars[\'ooo\'].\'\')'),
            array('@aa~bbb@', '$t->getLocaleString(\'aa~bbb\')'),
            array('@aa~trc.$abcd.popo@', '$t->getLocaleString(\'aa~trc.\'.$t->_vars[\'abcd\'].\'.popo\')'),
            array('@$aa~trc.$abcd.popo@', '$t->getLocaleString(\'\'.$t->_vars[\'aa\'].\'~trc.\'.$t->_vars[\'abcd\'].\'.popo\')'),
            array('$aa.@trc.$abcd.popo@', '$t->_vars[\'aa\'].$t->getLocaleString(\'trc.\'.$t->_vars[\'abcd\'].\'.popo\')'),
            array('@aa~trc.234.popo@', '$t->getLocaleString(\'aa~trc.234.popo\')'),
            array('@aa~trc.23.4.popo@', '$t->getLocaleString(\'aa~trc.23.4.popo\')'),
            array('@aa~trc.23.4.list@', '$t->getLocaleString(\'aa~trc.23.4.list\')'),
            array('$aa*count($bb)', '$t->_vars[\'aa\']*count($t->_vars[\'bb\'])'),
            array('$aa & $bb', '$t->_vars[\'aa\'] & $t->_vars[\'bb\']'),
            array('$aa | $bb', '$t->_vars[\'aa\'] | $t->_vars[\'bb\']'),
            array('$aa++', '$t->_vars[\'aa\']++'),
            array('$aa--', '$t->_vars[\'aa\']--'),
            array('$bb->bar', '$t->_vars[\'bb\']->bar'),
            array('$bb->$bar', '$t->_vars[\'bb\']->{$t->_vars[\'bar\']}'),
            array('$bb->$bar->yo', '$t->_vars[\'bb\']->{$t->_vars[\'bar\']}->yo'),
            array('@abstract.as.break.case.catch.class.clone@', '$t->getLocaleString(\'abstract.as.break.case.catch.class.clone\')'),
            array('@const.continue.declare.default.do.echo.else.elseif.empty@', '$t->getLocaleString(\'const.continue.declare.default.do.echo.else.elseif.empty\')'),
            array('@exit.final.for.foreach.function.global.if.implements.instanceof@', '$t->getLocaleString(\'exit.final.for.foreach.function.global.if.implements.instanceof\')'),
            array('@interface.and.or.xor.new.private.public@', '$t->getLocaleString(\'interface.and.or.xor.new.private.public\')'),
            array('@protected.return.static.switch.throw.try.use.var.eval.while@', '$t->getLocaleString(\'protected.return.static.switch.throw.try.use.var.eval.while\')'),
            array('$aa*(234+$b)', '$t->_vars[\'aa\']*(234+$t->_vars[\'b\'])'),
            array('$aa[$bb[4]]', '$t->_vars[\'aa\'][$t->_vars[\'bb\'][4]]'),
        );
    }

    public function getExpressionVarsForTrustedMode()
    {
        return array(
            array('$aaa.PHP_VERSION', '$t->_vars[\'aaa\'].PHP_VERSION'),
        );
    }

    /**
     * @dataProvider getExpressionVars
     * @return void
     */
    function testTrustedModeVarExpr($expression, $expectedResult) {
        $compil = new ContentCompilerForTests(self::$castorConfig);
        $compil->trusted = true;
        $res = $compil->testParseVarExpr($expression);
        $this->assertEquals($expectedResult, $res);
    }

    /**
     * @dataProvider getExpressionVarsForTrustedMode
     * @return void
     */
    function testTrustedModeVarExprWithTrustedExpression($expression, $expectedResult) {
        $compil = new ContentCompilerForTests(self::$castorConfig);
        $compil->trusted = true;
        $res = $compil->testParseVarExpr($expression);
        $this->assertEquals($expectedResult, $res);
    }

    /**
     * @dataProvider getExpressionVars
     */
    function testUnTrustedModeVarExpr($expression, $expectedResult) {
        $compil = new ContentCompilerForTests(self::$castorConfig);
        $compil->trusted = false;
        $res = $compil->testParseVarExpr($expression);
        $this->assertEquals($expectedResult, $res);
    }

    /**
     * @dataProvider getExpressionVarsForTrustedMode
     */
    function testUnTrustedModeVarExprWithTrustedExpression($expression, $expectedResult) {
        $compil = new ContentCompilerForTests(self::$castorConfig);
        $compil->trusted = false;
        $this->expectException('Exception');
        $compil->testParseVarExpr($expression);
    }

    public function getBadVarExpression()
    {
        return array(
            array('$', 'Dans le tag  du template , le caractère  $ n\'est pas autorisé'),
            array('foreach($a)', 'Dans le tag  du template , le code php foreach n\'est pas autorisé'),
            array('@aaa.bbb', 'Dans le tag  du template , il manque la fin de la clef de localisation'),
            array('@aaa.b,bb@', 'Dans le tag  du template , le caractère  , n\'est pas autorisé'),
            array('@@', 'Dans le tag  du template , clef de localisation vide'),
            array('[$aa/234]', 'Dans le tag  du template , le caractère  [ n\'est pas autorisé'),
            array('$b+($aa/234', 'Dans le tag  du template , il y a des erreurs au niveau des parenthèses'),
            array('$b+(($aa/234)', 'Dans le tag  du template , il y a des erreurs au niveau des parenthèses'),
            array('$aa/234)', 'Dans le tag  du template , il y a des erreurs au niveau des parenthèses'),
            array('$aa/234))', 'Dans le tag  du template , il y a des erreurs au niveau des parenthèses'),
            array('$aa[234', 'Dans le tag  du template , il y a des erreurs au niveau des parenthèses'),
            array('$aa[[234]', 'Dans le tag  du template , il y a des erreurs au niveau des parenthèses'),
            array('$aa234]', 'Dans le tag  du template , il y a des erreurs au niveau des parenthèses'),
            array('isset($t[5])', 'Dans le tag  du template , le code php isset n\'est pas autorisé'),
            array('empty($aa)', 'Dans le tag  du template , le code php empty n\'est pas autorisé'),
            array('$aa == 123', 'Dans le tag  du template , le code php == n\'est pas autorisé'),
            array('$aa != 123', 'Dans le tag  du template , le code php != n\'est pas autorisé'),
            array('$aa >= 123', 'Dans le tag  du template , le code php >= n\'est pas autorisé'),
            array('$aa !== 123', 'Dans le tag  du template , le code php !== n\'est pas autorisé'),
            array('$aa <= 123', 'Dans le tag  du template , le code php <= n\'est pas autorisé'),
            array('$aa << 123', 'Dans le tag  du template , le code php << n\'est pas autorisé'),
            array('$aa >> 123', 'Dans le tag  du template , le code php >> n\'est pas autorisé'),
            array('$aa == false', 'Dans le tag  du template , le code php == n\'est pas autorisé'),
            array('$aa == true', 'Dans le tag  du template , le code php == n\'est pas autorisé'),
            array('$aa == null', 'Dans le tag  du template , le code php == n\'est pas autorisé'),
            array('$aa && $bb', 'Dans le tag  du template , le code php && n\'est pas autorisé'),
            array('$aa || $bb', 'Dans le tag  du template , le code php || n\'est pas autorisé'),
            array('$aa and $bb', 'Dans le tag  du template , le code php and n\'est pas autorisé'),
            array('$aa or $bb', 'Dans le tag  du template , le code php or n\'est pas autorisé'),
            array('$aa xor $bb', 'Dans le tag  du template , le code php xor n\'est pas autorisé'),
            array('$aa=$bb', 'Dans le tag  du template , le caractère  = n\'est pas autorisé'),
            array('$aa+=$bb', 'Dans le tag  du template , le code php += n\'est pas autorisé'),
            array('$aa-=$bb', 'Dans le tag  du template , le code php -= n\'est pas autorisé'),
            array('$aa/=$bb', 'Dans le tag  du template , le code php /= n\'est pas autorisé'),
            array('$aa*=$bb', 'Dans le tag  du template , le code php *= n\'est pas autorisé'),
        );
    }

    public function getBadvarexprUnTrustedMode ()
    {
        return array(
            array ('$aaa.PHP_VERSION', 'Dans le tag  du template , les constantes (PHP_VERSION) sont interdites'),
        );
    }

    /**
     * @dataProvider getBadVarExpression
     * @return void
     */
    function testTrustedModeBadVarExpr($expression, $errMessage)
    {
        $compil = new ContentCompilerForTests(self::$castorConfig);
        $compil->trusted = true;
        $this->expectExceptionMessage($errMessage);
        $compil->testParseVarExpr($expression);
    }

    /**
     * @dataProvider getBadvarexprUnTrustedMode
     * @return void
     */
    function testTrustedModeBadVarExprForTrustMode($expression, $errMessage)
    {
        $compil = new ContentCompilerForTests(self::$castorConfig);
        $compil->trusted = true;
        $res = $compil->testParseVarExpr($expression);
        $this->assertNotEquals('', $res);
    }

    /**
     * @dataProvider getBadVarExpression
     * @return void
     */
    function testUnTrustedModeBadVarExpr($expression, $errMessage) {
        $compil = new ContentCompilerForTests(self::$castorConfig);
        $compil->trusted = false;
        $this->expectExceptionMessage($errMessage);
        $compil->testParseVarExpr($expression);
    }

    /**
     * @dataProvider getBadvarexprUnTrustedMode
     * @return void
     */
    function testUnTrustedModeBadVarExprForTrustMode($expression, $errMessage) {
        $compil = new ContentCompilerForTests(self::$castorConfig);
        $compil->trusted = false;
        $this->expectExceptionMessage($errMessage);
        $compil->testParseVarExpr($expression);
    }

    public function getVarTag()
    {
        return array(
            array('$aaa|escxml', 'htmlspecialchars($t->_vars[\'aaa\'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8")'),
            array('$aaa|bla', 'testjtplcontentUserModifier($t->_vars[\'aaa\'])'),
            array('$aaa|count_words', 'count(preg_grep(\'/\w/\',  preg_split(\'/\s+/\', $t->_vars[\'aaa\'])))'),
            array('$aaa|count_words|number_format:2|upper', 'strtoupper(Jelix\Castor\Plugins\Modifiers\NumberModifiersPlugin::numberFormatModifier(count(preg_grep(\'/\w/\',  preg_split(\'/\s+/\', $t->_vars[\'aaa\']))),2))'),
            array('$aaa|truncate:50|count_words|number_format:2', 'Jelix\Castor\Plugins\Modifiers\NumberModifiersPlugin::numberFormatModifier(count(preg_grep(\'/\w/\',  preg_split(\'/\s+/\', Jelix\Castor\Plugins\Modifiers\StringModifiersPlugin::truncateModifier($t->_vars[\'aaa\'],\'UTF-8\',50)))),2)'),

        );
    }

    /**
     * @dataProvider getVarTag
     * @return void
     */
    function testVarTag($tag, $expectedResult) {
        $compil = new ContentCompilerForTests(self::$castorConfig);
        $compil->trusted = true;
        $compil->setUserPlugins(array('bla'=>'testjtplcontentUserModifier'),array());

        $res = $compil->testParseVariable($tag);
        $this->assertEquals($expectedResult, $res);
    }

    public function getEscapedVarTag()
    {
        return array(
            // expression ,  autoescape , content type, result
            array('$aaa', true, 'html', 'htmlspecialchars($t->_vars[\'aaa\'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8")'),
            array('$aaa', true, 'xml', 'htmlspecialchars($t->_vars[\'aaa\'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8")'),
            array('$aaa', false, 'html', '$t->_vars[\'aaa\']'),
            array('$aaa', true, 'css', '$t->_vars[\'aaa\']'),
            array('$aaa|escxml', true, 'html', 'htmlspecialchars($t->_vars[\'aaa\'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8")'),
            array('$aaa|escxml', false, 'html', 'htmlspecialchars($t->_vars[\'aaa\'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8")'),
            array('$aaa|eschtml', true, 'html', 'htmlspecialchars($t->_vars[\'aaa\'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8")'),
            array('$aaa|eschtml', false, 'html', 'htmlspecialchars($t->_vars[\'aaa\'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8")'),
            array('$aaa|raw', true, 'html', '$t->_vars[\'aaa\']'),
            array('$aaa|raw', false, 'html', '$t->_vars[\'aaa\']'),
            array('$aaa|noesc', true, 'html', '$t->_vars[\'aaa\']'),
            array('$aaa|noesc', false, 'html', '$t->_vars[\'aaa\']'),
        );
    }

    /**
     * @dataProvider getEscapedVarTag
     * @return void
     */
    function testVarTagAutoescape($tag, $autoescape, $outputType, $expectedResult)
    {
        $compil = new ContentCompilerForTests(self::$castorConfig);
        $compil->setAutoEscape($autoescape);
        $compil->trusted = true;
        $res = $compil->testParseVariable($tag, $outputType);
        $this->assertEquals($expectedResult, $res);
    }


    public function getVarAssign()
    {
        return array(
            array('$aa=$bb', '$t->_vars[\'aa\']=$t->_vars[\'bb\']'),
            array('$aa+=$bb', '$t->_vars[\'aa\']+=$t->_vars[\'bb\']'),
            array('$aa-=$bb', '$t->_vars[\'aa\']-=$t->_vars[\'bb\']'),
            array('$aa/=$bb', '$t->_vars[\'aa\']/=$t->_vars[\'bb\']'),
            array('$aa*=$bb', '$t->_vars[\'aa\']*=$t->_vars[\'bb\']'),
            array('TEST_JTPL_COMPILER_ASSIGN', 'TEST_JTPL_COMPILER_ASSIGN')
        );
    }

    /**
     * @dataProvider getVarAssign
     * @return void
     */
    function testAssign($expression, $expectedResult)
    {
        $compil = new ContentCompilerForTests(self::$castorConfig);
        $compil->trusted = true;
        $res = $compil->testParseAssignExpr($expression);
        $this->assertEquals($expectedResult, $res);
    }


    public function getVarAssignUntrustedMode()
    {
        return array(
            array('TEST_JTPL_COMPILER_ASSIGN', 'Dans le tag  du template , les constantes (TEST_JTPL_COMPILER_ASSIGN) sont interdites'),
        );
    }
    /**
     * @dataProvider getVarAssignUntrustedMode
     * @return void
     */
    function testAssignUntrustedMode($expression, $errMessage)
    {
        $compil = new ContentCompilerForTests(self::$castorConfig);
        $compil->trusted = false;
        $this->expectExceptionMessage($errMessage);
        $compil->testParseAssignExpr($expression);
    }
}

