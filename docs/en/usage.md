# Using Castor

Castor is composed of three components :

- A configuration object, `Config` that holds some parameters 
- A compiler, that translates template file to php file. This operation is
  transparent for you.
- The main object, `Castor`, which is a container that holds values used to
  "hydrate" the template, and which generates the content from the template
  (after calling the compiler).

## Configuration

You must instancy a configuration object. It needs at least the path of a
directory where compiled template will be store. You can also indicate the path
of the dir where template files can be found. By default they are loaded from
the current directory.

Example:

```php
$cachePath = __DIR__.'/cache/';
$templatesPath = __DIR__.'/templates/';

$config = new \Jelix\Castor\Config($cachePath, $templatesPath);
```

The `Config` object contains properties and methods to set the default lang,
the charset, the rights and the owner for created file in the cache directory.
And there is a method to add a path where some plugins can be found.


```php
$pluginsPath = '/somewhere/my/plugins/';
$config->addPluginsRepository($pluginsPath),
```

## The `Castor` object

The `Castor` object is meant to generate the content specified in a template
file. This content contains tags and instructions that processes data you give
to `Castor`.

After writing a template file (see templates.md), you can call `Castor` to generate the result.

```php
   $tpl = new \Jelix\Castor\Castor($config);
```

Here are the most important methods you have to know.

### `assign()`

```php
  $tpl->assign($name, $value);
```

This method allows you to create a template variable. A template variable is
only accessible in the template level. You will then use this method to pass
data (static values, objects, iterators, etc) to the template to be able to use
it for content generation.

You can also create or modify a variable directly in the template, using:

```html
  {assign $name = $value}
```

**Important** : the name of a template variable should follow syntaxic rules for
a name of a PHP variable. the name should contain only letters, numbers or "_".


### `assignByRef()`

Same purpose of the `assign` method, but the value is passed by reference, so
you can assign huge array without duplicating them in memory.

```php
  $tpl->assignByRef($name, $value);
```

### `assignIfNone()`

Same purpose of the `assign` method, but the value is assigned only if the
variable doesn't exist.

```php
  $tpl->assignIfNone($name, $value);
```

### `get()`  `getTemplateVars()`

If you want to get the value of a template variable that is already initialized,
you can use this method:

```php
  $value = $tpl->get('foo');
  $allVariables = $tpl->getTemplateVars();
```

### `isAssigned()`

To know if a variable is already set into the container:

```php
  $ok = $tpl->isAssigned('foo');
```


## Generating the content

After variables are created and initialized, you can call the `fetch` method to
generate the content of the template, and to retrieve it. You give the file name of the
template as the first parameter.

```php
  $content = $tpl->fetch('mytemplate.tpl');
```

The template is loaded from the directory indicated in the templatePath of the configuration,
then it is "compiled" to a PHP file and stored in the cache directory. So the compilation
phase occurs only the first time the template is called or when the template has changed.
It increases performance since the processing of the template is just similar to a PHP
include. The PHP file is executed in a context where all assigned variables are
available.

If you want to output directly the result to a browser, you can use directly the `display`
method:

```php
  $tpl->display('mytemplate.tpl');
```

It fetches the template content and print it.

### Using a template string

You can use templates that are not in files. These are called "virtual templates".
Typically, you have a template in a string (retrieved from a database for example), and
then you can generate the content into a string.

A simple example :

```php
$template = ' hello 
 		{$value} 
 		world 
 		{for $i=1;$i<=5;$i++} 
 		  {$i} 
 		{/for}';
$tpl = new \Jelix\Castor\Castor($config);
$tpl->assign('value', 'test'); 
$content = $tpl->fetchFromString($template, 'text');
```

`$content` will be:
```html
 hello 
 test 
world 
	  1 
	  2 
	  3 
	  4 
	  5 
```


## Selecting the template type

You can have template that contain HTML, or other containing raw text, or XML.

By default, Castor assumes that the template is an html template.

It's important to indicate what type of content it is, so Castor can use plugins that targets
this content type specifically. Some plugins may be available only for some content type.

If the content type is not 'html', indicates the type at second parameters to `fetch()`,
`display()` or `fetchFromString()`.


```php
  $content = $tpl->fetch('mytemplate.tpl', 'text');
```

Note that you can create your own type. You just have to give your
content type to `fetch()`, `display()` or `fetchFromString()`, and creating some plugins
for this content (if "common" plugins are not enough).


## Trusted and untrusted templates

By default, Castor assumes that template you give to him are trusted. It means that you
trust the content of the template. You know what it does, and you know that it does not
something evil.

This is not the case for templates coming from somebody or something you cannot trust. For
example, you develop a CMS allowing the user to create and import some templates for
some specific pages. You cannot trust these templates contents.

To indicate to enforce the security in this case, give `false` as third parameter to
`fetch`, `display` or `fetchFromString`.

```php
  $content = $tpl->fetch('mytemplate.tpl', 'html', false);
```

Among of thing you cannot do in trusted templates:

- you cannot use parenthesis inside `foreach` and `or` tags
- you cannot use PHP constants
- you cannot use includes

