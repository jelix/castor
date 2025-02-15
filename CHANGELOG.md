Changelog
=========

next
-----

1.1.5
-----

- Fix deprecation warning (in PHP 8.4) into the `nl2br` plugin

1.1.4
-----

- Compatibility with PHP 8.4
- deprecate `date_format` as strftime is deprecated into php 8.1. Use the `datetime` modifier instead.


1.1.3
-----

- fix composer.json so the library can be used with PHP 8.2

1.1.2
-----

- New modifier `json_decode`
- Is compatible with PHP 8.2

1.1.1
-----

Fix compatibility with PHP 8.1

1.1.0
-----

- support of auto-escape, with a new tag `{! autoescape !}`. A new `raw` modifier allows to not escape
  automatically a variable.
- Support of macro, with new tags:
  - `{macro}` to declare a block of a macro
  - a function `{usemacro}`
  - some tests `{ifdefinedmacro}` and `{ifundefinedmacro}`
- Support of `{set}`, an alias for `{assign}`
- Support of `{verbatim}`, an alias for `{literal}`
- Support of a new syntax for comments, `{# .. #}`
- Add `json_encode` modifier
- Fix some exceptions
- A little code cleanup
- Tested with PHP 8.0


1.0.1
-----

- Fix compatibility with PHP 7.0 : remove the use of the `T_CHARACTER` constant, into the compiler.


1.0.0
------

First version of Castor, which is the result of the extraction of jTpl from the
framework Jelix.