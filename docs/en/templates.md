# Templates

Templates are files with the ".tpl" extension and located in the directory you indicate
to the configuration object, or in the current directory.

## Template files

A template file is made of HTML, XML or whatever you want. It contains also some
instructions to incorporate values that you will define, instructions to repeat the
generation of parts of HTML, etc.

The syntax used in Castor is using some "tags" for instructions, and PHP for 
the expressions. The goal is to have templates readable enough, easy to modify
and easy to learn when you know PHP.

Since 1.2, there are two different syntaxes (v1 and v2). 
The new syntax (v2) is using new delimiters for instructions.

- [Documentation of the V1 syntax](syntax-v1.md)
- [Documentation of the V2 syntax](syntax-v2.md)
