<?php


use Jelix\Castor\SyntaxConverterV1V2;

class syntaxConverterTest extends \PHPUnit\Framework\TestCase
{

    public function getConvertTests()
    {
        return array(
            ['content_html'],
            ['content_text'],
            ['countries'],
            ['macros'],
            ['modifiers'],
        );
    }

    /**
     * @dataProvider getConvertTests
     */
    public function testConvert($file)
    {
        $source = file_get_contents(__DIR__.'/assets/'.$file.'.tpl');
        $target = file_get_contents(__DIR__.'/assets_syntax2/'.$file.'.ctpl');

        $converter = new SyntaxConverterV1V2();
        $result = $converter->convert($source);
        $this->assertEquals($target, $result);
    }
}