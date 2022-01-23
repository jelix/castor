# Templates

Templates are files with the ".tpl" extension and located in the directory you indicate
to the configuration object, or in the current directory.

## Template files

A template file is made of HTML, XML or whatever you want . It contains also some
instructions to incorporate values that you will define, instructions to repeat the
generation of parts of HTML, etc.

The syntax used in Castor is between the one used in smarty and the one of PHP. The
goal is to have templates readable enough, easy to modify and easy to learn.

## Instruction syntax

Instructions in Castor are specified between curly braces : `{instruction ...}`

You can write an instruction on several lines:

```html
<div>
{zone 
    'foo~bar',
    array(
        'foo1'=>$foo1,
        'foo2'=>$foo2)}
</div>
```


If you want to include curly braces in your template, outside of a Castor instruction, you
can use `{ldelim}` for "{", or `{rdelim}` for "}".

If you have a block with several curly braces, like in a piece of code of javascript, you
can use `{literal}` instead of `{ldelim}` or `{rdelim}`.

```html
  <script type="text/javascript">
   {literal}
      for (i=0;i<max;i++) {
         if (foo){ ...}
      }
   {/literal}
  </script>
```

If you want to put comments, use `{*...*}` or `{# .. #}`. Of course they did not appear in the generated
content.

```html
   <p>bla bla</p>
   {* this is a comment *}
   {# this is an other comment #}
```



## Expressions

A Castor expression is identical to a PHP expression, and returns a value, like in PHP. You
can use classical PHP operators, objects, array etc. You can use also template variables,
like any PHP variables. You use Castor expressions as arguments of Castor instructions.
Here is a simple expression:

```php
   $template_variable
```

An expression can also contain some selector of locales, by using a specific syntax. This
selectors should be introduced between two "@".

```
   @key.of.localized.string@."fooo bar"
```

It is equal to this PHP code :

```php
   $tpl->getLocaleString("key.of.localized.string")."fooo bar"
```

Inside a locale key, you can use some template variables. It allows to construct
dynamically a locale key:

```
   @key.$variable.string@."fooo bar"
```

It is equal to this PHP code :

```php
   $tpl->getLocaleString("key.".$variable.".string")."fooo bar"
```



## Displaying an expression, a variable

To display the result of an expression, you should put it between curly braces. The first
element of the expression should be a variable or a locale key.

```
  {$myvariable}
  {$myvariable * 3}
  {$myvariable." - ".@message.ok@}
  {@a.key.of.locale@."-".$anOtherVariable}
  {@a.key.$dynamique@."-".$anOtherVariable}
```

This is equal to:

```php
  <?php echo $myvariable; ?>
  <?php echo $myvariable * 3; ?>
  <?php echo $myvariable." - ".$tpl->getLocaleString("message.ok"); ?>
  <?php echo $tpl->getLocaleString("a.key.of.locale")."-".$anOtherVariable; ?>
  <?php echo $tpl->getLocaleString("a.key.".$dynamique)."-".$anOtherVariable; ?>
```

For more complex expressions , you can use the syntax with `=`:

```
  {=$myvariable}
  {=intval($myvariable)*3}
```

## Predefined constants

Some variables are predefined so you have not to assign them:

- `$j_datenow`: current date (aaaa-mm-jj)
- `$j_timenow`: current hour (hh:mm:ss)

## Creating variables into a template

You may want to create a variable or modify a variable from inside the template.

You should use the tag `{assign}`, or since version 1.1, its alias `{set}`.

Example: 

```
{assign $vaname = 'a value'}
{set $vaname2 = $a + $b}
```

Instead of the operator `=`, you can use also `.=`, `+=`, `-=`, `*=`, `/=`,  `|=`,  `&=`, `%=`...
The value can be an expression.


## Modifiers

A modifier is a function which modify the output of an expression. You can use many
modifiers at the same time. It works in fact like modifiers of Smarty:

```
  {$avariable|upper}
  {$avariable|upper|escxml}
  {$aUrl|escurl}
```

It is equal to:

```php
  <?php echo strtoupper($avariable);?>
  <?php echo htmlspecialchars(strtoupper($avariable));?>
  <?php echo rawurlencode($aUrl);?>
```

Some modifiers, like those in the previous example, are simple aliases to some native PHP
functions:

- upper  (strtoupper)
- lower  (strtolower)
- escxml and eschtml (htmlspecialchars)
- strip_tags (strip_tags)
- escurl (rawurlencode)
- capitalize (ucwords)
- stripslashes (stripslashes)
- upperfirst (ucfirst)

Many others are function defined in plugins for Castor. See the plugins directory.

### Modifiers with parameters

Some modifiers need some parameters. You should put this parameters after a ":" after the
modifier name, and you should separate parameters with ",". Parameters are expressions.

Example with the datetime modifier:

```html
  <p>The date is {$myDate|datetime:'Y-m-d','r'}.</p>
```

## Control statements

They are similar to statements of PHP, except that parenthesis are not required around
conditions or expressions.

### if, else, elseif

```
  {if condition_1}
     // code here
  {elseif condition_2}
     // code here
  {else}
     // code here
  {/if}
```

Note that you can create some plugins that act like a `if` statement.

### while

```
  {while condition}
    // code here
  {/while}
```

### foreach

```
  {foreach $iterator_or_array as $key=>$value}
    // code here
  {/foreach}
```

### for

```
  {for expression}
    // code here
  {/for}
```

The expression should be identical as the expression of the `for` in PHP.

### break

You can use `{break}` to insert a `break` instruction into a loop:

```
  {while condition}
    ...
     {if ...}  {break} {/if}
    ....
  {/while}
```

## Functions

Functions in a template are plugins. These plugins are simple PHP functions and you can
create them.

The syntax of the call in a template is:

```
  {function_name  expression, expression,...}
```

You mustn't use parentheses around all parameters. Expressions are Castor expressions, so
similar to PHP expressions.

Note that some functions and other template plugins are callable in general only in specific
content type. Some plugins are for HTML, some other plugins are for text content etc. See
the "type" parameter of `fetch()`.


## Personnalized functions and modifiers

If you want to have some new functions or modifiers, you can develop some plugins.
See the documentation about it: plugins.md. This is pretty simple.

An other solution is to declare the modifiers or the functions dynamically, by calling the
Castor methods `registerModifier()` or `registerFunction()`. To this methods, you should
indicate a name which will be used in the template, and a name of a php function which
implements the modifier or the function. Warning: you should not pass a PHP `callable`,
only a string containing the PHP function name. This name is inserted into the "compiled"
PHP file.

For a personnalized function, it should accept at least a first argument that is the
Castor object. For a personnalized modifier, it should accept only a string as argument.


## Meta informations

There is a special tag: `{meta}`. It doesn't change how the template is generated, it
doesn't generate some content, and more important, it cannot be influenced by any other
template instructions. Putting it inside an "if" statement for example, does nothing. The
meta tag will be interpreted, even if the if statement is false. The meta tag exists only
to provide informations for the code which uses the Castor object.

The syntax is

```html
 {meta name expression}
```

Example:

```html
  {meta author 'laurent'}
```

You can create all meta you want. Then this informations are available in the `Castor`
object, by using the `meta()` method.

```php
  $tpl = new \Jelix\Castor\Castor($config);

  $metas = $tpl->meta('thetemplate.tpl');

  $metas['author']; // contains 'laurent'

```

Note: if you use a variable in the expression of a meta tag, this variable should be
assigned from the Castor object, not from other instruction in the template itself (like
`{set ...}`).

### Advanced meta informations

Another type of meta tag allows you to automatically process meta data. Those are
implemented through template plugins.

Their syntax is:

```html
  {meta_//plugin_name// name expression}
```


Example which uses the meta_html plugin of Jelix. This plugin allows to modify the
current html response: it can add a css stylesheet, a javascript link etc.

```html
  {meta_html css '/styles/my.css'}
  {meta_html js 'fooscript.js'}
  {meta_html bodyattr array('onload'=>'alert("charge")')}
```

## Including a template into an other

There is some case where you would like shared contents of a template with other
templates. To do it, you will create a template file, and use the `{include}` tag inside
the template which will include the other template.

```
 <div>
   ...
   {include 'sharedcontent.tpl'}
 </div>
```

All template variables declared in the Castor object will be accessible into the included
template.

## Macros

In a template, you may want to repeat a piece of template without using a loop. You could include 
several time an other template, but for little piece of template, this slower than the use
of a macro.

A macro is a part of a template declared with the `{macro}` tag. A macro have a 
name and it is like a function in PHP. 

Example of the declaration:

```
{macro 'myblock'}
<p>This is a block, displaying the value of $val: {$val}</p>
{/macro}
```
The name is set using the syntax of a PHP string. Variables or any other language syntax are not allowed.

Variables used inside the block can be variables declared into the template. The scope is global.
You can use any template plugin, statements etc.

To call a macro, use `{usemacro}`:

```
This is my piece of html:
{usemacro 'myblock'}

...
I can display my piece of html again:
{usemacro 'myblock'}
```

If `$val` equals `'hello'`, the results will be :

```
This is my piece of html:
<p>This is a macro, displaying the value of $val: hello</p>

...
I can display my piece of html again:
<p>This is a macro, displaying the value of $val: hello</p>
```

You can declare parameters to a macro. Indicate parameters like for a PHP function (i.e. `$foo`), and
use parameters inside the macro like any other variables.

```
{* declaration of the macro *}
{macro 'myblock', $name, $result}
<p>This is a macro with parameters.<p>
<ul>
    <li>name= {$name}</li>
    <li>{if $result == true}this is green{else}this is red{/if}</li>
</ul>
<p>You can still see the value of $val: {$val}</p>
{/macro}

{* usage of the macro *}

{usemacro 'myblock', 'SuperGuppy', true}

```

Declared parameters are not usable outside the macro. If there is a template variable with the
same name, the value of the template variable is replaced by the value of the parameter inside
the macro, but is restored after the call of the macro.

Macro declared into an included template can be used in the template caller.


## Escaping content

By default content variables are inserted as is into the generated content. This may be not
what you want. You would escape some characters, like `<` and `>` in HTML, so the browser
will not perform these characters as tags.

So you should escape content variable:
- by using the `eschtml` or `escxml` modifier : `{$myvar|eschtml}`
- or by activating the auto-escaping: add a `{! autoescape !}` tag at the beginning of your template. All output
  of variables (with a tag `{$..}` will be escaped. 
  - If there is already an `eschtml` modifier, it will not add
    an additionnal escape process.
  - If you don't want to escape a variable, use the `raw` modifier: `{$myHTMLcontent|raw}`

