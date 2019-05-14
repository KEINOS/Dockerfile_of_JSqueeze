# Dockerfile of JSqueeze

A docker container that shrinks / compresses / minifies / mangles Javascript code.

```bash
docker pull keinos/jsqueeze:latest
```

- Minifier: ["JSqueeze" @ GitHub](https://github.com/tchwork/jsqueeze)
  - "JSqueeze" is a single PHP class that has been developed, maintained and thoroughly tested since 2003 on major JavaScript frameworks (e.g. jQuery).
- Reporitories:
  - Image: https://hub.docker.com/r/keinos/jsqueeze @ Docker Hub
  - Source: https://github.com/KEINOS/Dockerfile_of_JSqueeze @ GitHub
- Base Image: `keinos/mini-php7:latest`
- Issues: https://github.com/KEINOS/Dockerfile_of_JSqueeze/issues @ GitHub

## Usage

```shellsession

```

## Features

- Removes comments and white spaces.
- Renames every local vars, typically to a single character.
- Keep Microsoft's conditional comments.
- In order to maximise later HTTP compression (deflate, gzip), new variables
  names are choosen by considering closures, variables' frequency and
  characters' frequency.
- Can rename also global vars, methods and properties, but only if they are marked
  special by some naming convention. Use JSqueeze::SPECIAL_VAR_PACKER to rename vars
  whose name begins with one or more `$` or with a single `_`.
- Renames also local/global vars found in strings, but only if they are marked
  special.
- If you use `with/eval` then be careful.

Bonus
-----

* Replaces `false/true` by `!1/!0`
* Replaces `new Array/Object` by `[]/{}`
* Merges consecutive `var` declarations with commas
* Merges consecutive concatened strings
* Can replace optional semi-colons by line feeds, thus facilitating output
  debugging.
* Keep important comments marked with `/*!...`
* Treats three semi-colons `;;;` [like single-line comments](http://dean.edwards.name/packer/2/usage/#triple-semi-colon).
* Fix special catch scope across browsers
* Work around buggy-handling of named function expressions in IE<=8

License
-------

This library is free software; you can redistribute it and/or modify it
under the terms of the (at your option):
Apache License v2.0 (see provided LICENCE.ASL20 file), or
GNU General Public License v2.0 (see provided LICENCE.GPLv2 file).
