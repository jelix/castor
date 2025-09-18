Castor is a template engine for PHP, using syntax similar to PHP.

# Features

- A simple Api to inject data and to generate content
- A language with a syntax similar to PHP, but a bit simpler to ease learning
- Templates can be a file or a simple string
- Efficient generator: template files are "compiled" to PHP files
- A sandbox mode, to fetch untrusted templates (templates uploaded by a user in a CMS for example).
  This mode has less capabilities of course.
- A plugin system
- Plugins can be specific to a content type (HTML, XML, text…), so they produce right contents.
- a system of “meta”: allow the template to expose data to PHP code. For example, a "meta"
  can be an url of a stylesheet to use with the generated content.

# Version

The master branch is the development branch. It has sources of the
next major version 2.0.0.

There are many breaking changes on the API compared to the 1.x version. It has
also a new plugin system. See  CHANGELOG_V2.md for more details. 

See the branch 1.x for the last stable version, used in the Jelix Framework 1.8.


# Installation

You can install it from Composer. In your project:

```
composer require "jelix/castor"
```

# Usage

A template file `test.ctpl`:

```
{! autoescape !}
<h1>{{ $titre|upper }}</h1>
<ul>
{% foreach $users as $user %}
<li>{{$user->name}} ({{$user->birthday|datetime:'d/m/Y'}})
    <div>{{$user->biography|raw}}</div>
</li>
{% endforeach %}
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

$users = array(
    // User in an example class...
    new User('Tom', '2001-02-01'), 
    new User('Laurent', '1990-03-01'), 
    new User('Bob', '1970-05-25')
 );
$tpl->assign('users', $users);
$tpl->assign('titre', 'This is a test !');

// content is generated from the given template file and returned
$content = $tpl->fetch('test.ctpl');

// or content is generated from the given template file and sent directly to the browser
$tpl->display('test.ctpl');
```

To know more, see the docs/ directory.

# History

Castor was formerly known as jTpl and was used in the [Jelix Framework](http://jelix.org)
since 2006. There was a specific version, "jTpl standalone", existing for years to
use jTpl without Jelix, but it was never released as a stable version.

In 2015, jTpl was completely "extracted" from Jelix (starting to Jelix 1.7), and is now
available as a standalone component under the name "Castor", with true stable releases. 
