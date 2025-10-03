# Templates syntax version 2

This is a syntax available since 1.2. It avoids to use `{literal}` in html templates containing javascript,
as delimiters for instructions are made of two characters instead of one for the
v1 syntax.

Templates using version 2 syntax should be stored into files with the ".ctpl"
extension.

## Instruction syntax


There are four types of instructions :

- `{! .. !}` for pragma parameters. These are parameters for the compiler.
- `{{ .. }}` to display values
- `{% .. %}` for control statements or to call plugins or functions.
- `{# .. #}` and `{* .. *}` for comments

You can write an instruction on several lines:

```html
<div>
{% myfunction 
    'foo~bar',
    array(
        'foo1'=>$foo1,
        'foo2'=>$foo2) %}
</div>
```


If you have a block into which there are some instructions you don't want to
execute (like displaying an example of Castor code ;-)), you
can use `{% literal %}`  or `{% verbatim %}`.

```html
  <script type="text/javascript">
   {% literal %}
      for (i=0;i<max;i++) {
         if (foo){ ...}
      }
   {% endliteral %}
  </script>
```

If you want to put comments, use `{*...*}` or `{# .. #}`. Of course they did not 
appear in the generated  content.

```html
   <p>bla bla</p>
   {* this is a comment *}
   {# this is another comment #}
```

## Pragma parameters

These are parameters for the compiler. They are indicated between `{!` and `!}`.

Supported parameters:

- `{! autoescape !}`, to automatically escape strings. See below the "escaping content" section.
- `{! output-type = <type> !}` to indicate the content type of the template (since 1.2.0). Replace `<type>` by `html`, `text`, `xml` or whatever. BY default, type is html.

## Expressions

A Castor expression is identical to a PHP expression, and it returns a value, like in PHP. You
can use classical PHP operators, objects, arrays, etc. You can use also template variables,
like any PHP variables. You use Castor expressions as arguments of Castor instructions.
Here is a simple expression:

```php
   $template_variable
```

An expression can also contain some selector of locales, by using a specific syntax. This
selector should be introduced between two `@` characters.

```
   @key.of.localized.string@."foo bar"
```

It is equal to this PHP code :

```php
   $tpl->getLocaleString("key.of.localized.string")."foo bar"
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

To display the result of an expression, you should put it between curly braces. 

```
  {{ $myvariable }}
  {{ $myvariable * 3 }}
  {{ intval($myvariable)*3 }}
  {{ $myvariable." - ".@message.ok@ }}
  {{ @a.key.of.locale@."-".$anOtherVariable }}
  {{ @a.key.$dynamique@."-".$anOtherVariable }}
```

This is equal to:

```php
  <?php echo $myvariable; ?>
  <?php echo $myvariable * 3; ?>
  <?php echo intval($myvariable) * 3; ?>
  <?php echo $myvariable." - ".$tpl->getLocaleString("message.ok"); ?>
  <?php echo $tpl->getLocaleString("a.key.of.locale")."-".$anOtherVariable; ?>
  <?php echo $tpl->getLocaleString("a.key.".$dynamique)."-".$anOtherVariable; ?>
```

## Predefined constants

Some variables are pre-defined, so you have not to assign them:

- `$j_datenow`: current date (aaaa-mm-jj)
- `$j_timenow`: current hour (hh:mm:ss)

## Creating variables into a template

You may want to create a variable or modify a variable from inside the template.

You should use the tag `{% assign %}`, or `{% set %}`.

Example: 

```
{% assign $vaname = 'a value' %}
{% set $vaname2 = $a + $b %}
```

Instead of the operator `=`, you can use also `.=`, `+=`, `-=`, `*=`, `/=`,  `|=`,  `&=`, `%=`...
The value can be an expression.


## Modifiers

A modifier is a function that modifies the output of an expression. You can use many
modifiers at the same time. You can use it only when displaying a value (so inside a `{{ }}`
tag) :

```
  {{ $avariable|upper }}
  {{ $avariable|upper|escxml }}
  {{ $aUrl|escurl }}
```

It is equal to:

```php
  <?php echo strtoupper($avariable);?>
  <?php echo htmlspecialchars(strtoupper($avariable));?>
  <?php echo rawurlencode($aUrl);?>
```

Some modifiers, like those in the previous example, are simple aliases to some native PHP
functions:

- `upper`  (strtoupper)
- `lower`  (strtolower)
- `escxml` and `eschtml` (htmlspecialchars)
- `strip_tags` (strip_tags)
- `escurl` (rawurlencode)
- `capitalize` (ucwords)
- `stripslashes` (stripslashes)
- `upperfirst` (ucfirst)

Many others are functions defined in plugins for Castor. See the "plugins" directory.

### Modifiers with parameters

Some modifiers need some parameters. You should put these parameters after the
modifier name and the `:` character, and you should separate parameters with a 
coma `,`. Parameters are expressions.

Example with the datetime modifier:

```html
  <p>The date is {{ $myDate|datetime:'Y-m-d','r' }}.</p>
```

## Control statements

They are similar to statements of PHP, except that parenthesizes are not required around
conditions or expressions.

### if, else, elseif

```
  {% if <condition_1> %}
     // code here
  {% elseif <condition_2> %}
     // code here
  {% else %}
     // code here
  {% endif %}
```

Note that you can create some plugins that act like a `if` statement.

### while

```
  {% while <condition> %}
    // code here
  {% endwhile %}
```

### foreach

```
  {% foreach $iterator_or_array as $key=>$value %}
    // code here
  {% endforeach %}
```

### for

```
  {% for <expression>; <expression>; <expression> %}
    // code here
  {% endfor %}
```

The expression should be identical as the expression of the `for` in PHP.

### break

You can use `{% break %}` to insert a `break` instruction into a loop:

```
  {% while <condition> %}
    ...
     {% if ... %}  {% break %} {% endif %}
    ....
  {% endwhile %}
```

## Functions

Functions in a template are plugins. These plugins are simple PHP functions and you can
create them.

The syntax of the call in a template is:

```
  {% function_name  <expression>, <expression>,... %}
```

You mustn't use parentheses around all parameters. Expressions are Castor expressions, so
similar to PHP expressions.

Note that some functions and other template plugins are callable in general only in specific
content type. Some plugins are for HTML, some other plugins are for text content etc. See
the "type" parameter of `fetch()`.


## Custom functions and modifiers

If you want to have some new functions or modifiers, you can develop some plugins.
See the documentation about it: [plugins.md].

Another solution is to declare the modifiers or the functions dynamically, by calling the
Castor methods `registerModifier()` or `registerFunction()`. To these methods, you should
indicate a name which will be used in the template, and a name of a php function which
implements the modifier or the function. Warning: you should not pass a PHP `callable`,
only a string containing the PHP function name. This name is inserted into the "compiled"
PHP file.

For a custom function, it should accept at least a first argument that is the
Castor object. For a custom modifier, it should accept only a string as argument.


## Meta information

There is a special tag: `{% meta %}`. It doesn't change how the template is generated, it
doesn't generate some content, and more important, it cannot be influenced by any other
template instructions. Putting it inside an `if` statement for example, does nothing. The
`meta` tag will be interpreted, even if the `if` statement is false. The `meta` tag exists only
to provide information for the code which uses the Castor object.

The syntax is

```
 {% meta <name> <expression> %}
```

Example:

```html
  {% meta author 'laurent' %}
```

You can create all meta you want. Then these informations are available in the `Castor`
object, by using the `meta()` method.

```php
  $tpl = new \Jelix\Castor\Castor($config);

  $metas = $tpl->meta('thetemplate.tpl');

  $metas['author']; // contains 'laurent'

```

Note: if you use a variable in the expression of a `meta` tag, this variable should be
assigned from the Castor object, not from other instruction in the template itself (like
`{% set ... %}`).

### Advanced meta information

Another type of `meta` tag allows you to automatically process meta data. Those are
implemented through template plugins.

Their syntax is:

```
  {% meta_<plugin name> <name> <expression> %}
```


Example which uses the `meta_html` plugin of Jelix. This plugin allows to modify the
current html response: it can add a css stylesheet, a javascript link etc.

```
  {% meta_html css '/styles/my.css' %}
  {% meta_html js 'fooscript.js' %}
  {% meta_html bodyattr array('onload'=>'alert("charge")') %}
```

## Including a template into another

There are some cases where you would like to share content of a template with other
templates. To do it, you will create a template file, and use the `{% include %}` tag inside
the template which will include the other template.

```
 <div>
   ...
   {% include 'sharedcontent.tpl' %}
 </div>
```

All template variables declared in the Castor object will be accessible into the included
template.

## Macros

In a template, you may want to repeat a piece of template without using a loop. You could include 
several time another template, but for little piece of template, this is slower than the use
of a macro.

A macro is a part of a template declared with the `{% macro %}` tag. A macro have a 
name and it is like a function in PHP. 

Example of the declaration:

```
{% macro 'myblock' %}
<p>This is a block, displaying the value of $val: {{ $val }}</p>
{% endmacro %}
```
The name is set using the syntax of a PHP string. Variables or any other language syntax are not allowed.

Variables used inside the block can be variables declared into the template. The scope is global.
You can use any template plugin, statements etc.

To call a macro, use `{% usemacro <name> %}`:

```
This is my piece of html:
{% usemacro 'myblock' %}

...
I can display my piece of html again:
{% usemacro 'myblock' %}
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
{% macro 'myblock', $name, $result %}
<p>This is a macro with parameters.<p>
<ul>
    <li>name= {{$name}}</li>
    <li>{%if $result == true%}this is green{%else%}this is red{% endif %}</li>
</ul>
<p>You can still see the value of $val: {{$val}}</p>
{% endmacro %}

{* usage of the macro *}

{% usemacro 'myblock', 'SuperGuppy', true %}

```

Declared parameters are not usable outside the macro. If there is a template variable with the
same name, the value of the template variable is replaced by the value of the parameter inside
the macro, but is restored after the call of the macro.

Macro declared into an included template can be used in the template caller.


## Escaping content

By default, content variables are inserted as is into the generated content. This may be not
what you want. You would escape some characters, like `<` and `>` in HTML, so the browser
will not perform these characters as tags.

So you should escape content variable:
- by using the `eschtml` or `escxml` modifier : `{{ $myvar|eschtml }}`
- or by activating the auto-escaping: add a `{! autoescape !}` tag at the beginning of your template. All output
  of variables (with a tag `{{ $.. }}` will be escaped. 
  - If there is already an `eschtml` modifier, it will not add
    an additionnal escape process.
  - If you don't want to escape a variable, use the `raw` modifier: `{{ $myHTMLcontent|raw }}`

