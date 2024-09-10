Changelog Version 2
====================

Summary
-------

- Minimum version of PHP : 8.2
- new way to indicate output type



Breaking changes
----------------

- Output type parameter has been removed to most methods. See details below.


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


