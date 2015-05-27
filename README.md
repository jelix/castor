Castor is a template engine, formerly named jTpl and used in the Jelix Framework.

# Features

- A simple Api to inject data and to generate content
- A language with a syntax similar to PHP, but a bit simpler to ease learning
- Easy localization
- Templates can be a file or a simple string
- Efficient generator: template files are "compiled" to PHP files
- A sandbox mode, to fetch untrusted templates (templates uploaded by a user in a CMS for example).
  This mode has less capabilities of course.
- A plugin system, similar to Smarty plugins.
- Plugins can be specific to a content type (HTML, XML, text…), so they produced right content.
- a system of “meta”: allow the template to expose data to PHP code. For example, a "meta"
  can be a url of a stylesheet to use with the generated content.

# installation

You can install it from Composer. In your project:

```
composer require "jelix/castor"
```

# Usage

A template file:

```
<h1>{$titre|upper}</h1>
<ul>
{foreach $countries as $country}
<li>{$country|eschtml} ({$country|count_characters})</li>
{/foreach}
</ul>
```

The PHP code:

```php

// directory where compiled templates are stored
$cachePath = realpath(__DIR__.'/temp/') . '/';

// directory where templates can be found
$templatePath = __DIR__.'/';

// create a configuration object. See its definition to learn about all of its options
$config = new \Jelix\Castor\Config($cachePath, $templatePath);

// let's create a template engine
$tpl = new \Jelix\Castor\Castor($config);

// assign some values, so they will be available for the template
$countries = array('France', 'Italie', 'Espagne', 'Belgique');
$tpl->assign('countries', $countries);
$tpl->assign('titre', 'This is a test !');

// content is generated from the given template file and returned
$content = $tpl->fetch('test.tpl');

// or content is generated from the given template file and sent directly to the browser
$tpl->display('test.tpl');
```

More doc later... see [Jelix documentation to know more](http://docs.jelix.org/en/manual-1.6/templates).
