Changelog Version 2
====================

Summary
-------

- Minimum version of PHP: 8.2
- new way to indicate output type
- Rework internals 
- new component to implement compiled template cache. You can provide a new cache manager implementing the `Jelix\Castor\CacheManager\TemplateCacheManagerInterface` interface
- a template name can still be given to Castor to ease the use of Castor API, but you can also give an object implementing `TemplateContentInterface`. It allows the object to implement the loading
 of the template content, and so to use templates stored into a database, a network resource, for example.
- new way to indicate untrusted templates: this information is given by the object implementing the `TemplateContentInterface`. 
- `Config` has now a property `$cacheManager` for the `TemplateCacheManagerInterface` object
- new methods `Castor::fetchFromString()`
- Fix the truncatehtml modifier: it cuts the text at the exact given position.


Breaking changes
----------------

- Output type parameter has been removed to most methods. See details below.
- methods `declareMacro`, `isMacroDefined`, `callMacro` of `CastorCore`/`Castor` have been moved to `RuntimeContainer`, has they are internal function for the compiler and compiled templates.
- new internal component `RuntimeContainer` to embed all template variables.
  these variables are not anymore into the `CastorCore` properties.
- `meta()` and `display()` methods are moved from `CastorCore` to `Castor`
- parameters of `Castor::fetch()` have changed
- no more `Config::$pluginPathList` property
- no more `CompilerCore::_getPlugin()` to override. This is replaced by the new plugin system
  and components implementing `PluginsProviderInterface`.

- remove plugins swf, swfbiscuit, ltx2pdf, date_format


Internal changes
----------------

- `Compiler` and `CompilerCore` are now into the `Jelix\Castor\Compiler` namespace.
- new internal methods `CastorCore::processMeta()` and `CastorCore::processDisplay()`
- Templates are now compiled into classes instead of functions. These
  classes implement the `ContentGeneratorInterface` interface.
- new internal class `CompilationResult` to embed results of compilation

Output type
-----------

The output type must now be indicated into a pragma tag inside a template:

```
{! output-type = text !}
```

So there are no anymore Output type parameter for these following methods:
- `Castor::fetch()`
- `Castor::display()`
- `Castor::meta()`
- `Compiler::compile()`


