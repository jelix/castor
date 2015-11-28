# Plugins for Castor

You can extend the template grammar by adding plugins.

There is four types of template plugins:

- functions
- modifiers
- blocks
- meta

Look in the plugins directory, you'll find here the core template plugins of Castor. They
are simple and there are some examples of how plugins work in comments.


## Location

Template plugins are stored in a folder having a precise sub-folder tree. In fact, plugins
are grouped in sub-folders. Each sub-folder is associated with a content type. It
prevents for example to pick a XML template plugin when HTML is required.

As a result, in a template plugins repository, you'll find `html`, `text` and
`common` directories. In the `common` folder are stored plugins independent of any
content type, and so usable from any templates.

The directory `plugins` is the default template plugins repository for Castor. However,
you'll more generally add your own plugins in an other directory in your application.
You shoud declare this directory to the `Config` object:

```php
$pluginsPath = '/somewhere/my/plugins/';
$config->addPluginsRepository($pluginsPath),
```

And as said before, this directory should contain sub-directories corresponding to content types.

Plugins are stored in file whose name follows a specific syntax:
`<plugin-type>.<plugin-name>.php`. And the PHP function implementing the plugin has this
kind of name: `jtpl_<plugin-type>_<content-type>_<plugin-name>`.

(jtpl was the previous name of Castor, and this name is still used for plugins to keep
compatibilities with existing application).

## plugin type "function"

Those plugins are invoked with code like this:

```
   {myfunction $param1,$param2}
```

And there are called during the content generation phase.

If as an example, this plugin is an html-only plugin, you should create a
`function.myfunction.php` in the subdirectory `html/` in which you'll declare a
`jtpl_function_html_myfunction` function. The function takes at minimum one paramater:
the Castor object used for this template. You can manipulate this object as needed. Also,
your function can declare more parameters but shall return nothing.

In your function core, do what you need, for example, you could echo something to be displayed.


```php
function jtpl_function_html_myfunction($tpl, $name, $params=array())
{
     echo "the name is $name and parameters are ", implode(',',$params);
}
```


## plugin type "cfunction" =====

On a user point of view i.e. in templates, cfunction plugins are not different from
function plugins. However, there are not coded the same, and not called at the same time.
A cfunction plugin is called before, during
template *compilation*. Remember, a template is first converted into php code
(compilation), stored in a cache file and evaluated each time it must be displayed.

As a result, a cfunction plugin does not generate any content but generate PHP code stored
in cache. In some cases, it is mandatory and in others it enhances performances.

A cfunction plugin receives as parameters, first a `Compiler` object, and second an array of
options for the plugin, specified in the template. Often, you just have to include those
parameters in the generated PHP code. Those type of plugins do not display anything nor
returns modified or generated content. 

An example of a cfunction plugin:

```php
function jtpl_cfunction_html_hello($compiler, $params=array())
{
    if (count($params) < 1 || count($params) > 2) {
        $compiler->doError2('errors.tplplugin.cfunction.bad.argument.number','hello','1-2');
    }

    if (count($params) == 1) {
         $php = ' echo "Hello ".'.$params[0].';';
    }
    else {
         $php = ' echo "Hello ".'.$params[0].'.'.$params[1].';';
    }
    return $php;
}
```



## plugin type "modifier"

A modifier plugin is a function. It shall be named `jtpl_modifier_<content-type>_<modifier-name>`
and take at minimum a string as first parameter (there can other parameters specifying
modifier options), and returns the string modified. Your modifier should be stored in a
`<content-type>/modifier.<modifier-name>.php` file.

Example of count_characters :

```php
function jtpl_modifier_html_count_characters($string, $include_spaces = false)
{
    if ($include_spaces) {
        return (strlen($string));
    }
    return preg_match_all("/[^\s]/",$string, $match);
}
```

A modifier plugin is called during the generation content phase.

There is also an other plugin type, "modifier2", that works in the same manner. The only
difference that it has at least 2 arguments : a Castor object, then the string to modify.


## plugin type "cmodifier"

As with cfunction plugins, cmodifier plugins are called during template compilation.
It should then returns PHP code that will be inserted into the compiled PHP file.

TODO: doc to complete.

## block plugins

As with cfunction plugins, block plugins are called during template compilation. As a
result a block plugin does not display anything, nor return a modified value but returns
php code to be included in the compiled template.

You implement your own specific loops or conditioned structure with a block plugin.

A block plugin is a function with a specific prefix, and ending with the name of the
block, here `myblock` in our example: `jtpl_block_html_myblock`. As parameters it
receives, first a `Compiler` object, second a boolean and optionally others. The
function is stored in a `html/block.myblock.php` file.

`jtpl_block_html_myblock` is called two times by the compiler. The first time when the
compiler finds `{myblock}` start tag and a second time when it encounters `{/myblock}` end
tag. The boolean parameter indicates wether the compiler is on the start tag (true) or on
the end tag (false).

Example, plugin `ifuserconnected` :

```php
function jtpl_block_ifuserconnected($compiler, $begin, $params=array())
{
    if ($begin) {
        // the compiler here reachs {myblock}
        if (count($params)) {
            $phpcontent = '';
            $compiler->doError1('errors.tplplugin.block.too.many.arguments','ifuserconnected');
        }
        else {
            $phpcontent = ' if (MyAuthenticationClass::isUserConnected()) {';
        }
    }
    else{
        // the compiler here reachs {myblock}
        $phpcontent = ' } ';
    }
    return $phpcontent;
}
```


## meta plugin

Meta plugins are called during evaluation of a template, but just before display.

Evaluation consists of two steps: one executed before display and the latter to actually
display content.

TODO: doc to complete.


