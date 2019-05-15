[![](https://images.microbadger.com/badges/image/keinos/jsqueeze.svg)](https://microbadger.com/images/keinos/jsqueeze "View image info on microbadger.com") [![](https://img.shields.io/docker/cloud/automated/keinos/jsqueeze.svg)](https://hub.docker.com/r/keinos/jsqueeze "View on Docker Hub") [![](https://img.shields.io/docker/cloud/build/keinos/jsqueeze.svg)](https://hub.docker.com/r/keinos/jsqueeze/builds "View Build Status on Docker Hub")

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
    - Note: The branch "`dockerfile`" is the main branch and "`master`" branch is the "JSqueeze" forked source code.
- Base Image: `keinos/alpine:latest` [[See more image info](https://microbadger.com/images/keinos/jsqueeze)]
- Issues: https://github.com/KEINOS/Dockerfile_of_JSqueeze/issues @ GitHub

## Usage

- Basic syntax

    ```bash
    docker run --rm keinos/jsqueeze [OPTIONS]
    ```

- Via STDIN

    ```shellsession
    $ # Provide Javascript through STDIN with '.' option.
    $ # Note that you need an -i/--interactive option of docker run.
    $ cat ./fat_js_code.js | docker run --rm -i keinos/jsqueeze .
    ...
    ```

- Via argument

    ```shellsession
    $ # Provide Javascript as a arg value
    $ docker run --rm -i keinos/jsqueeze -c="{'some':'fat js code'}"
    ...
    ```

    ```shellsession
    $ # Provide Javascript as a arg value via variable.
    $ # Note that the $str_sample variable is given by quotation.
    $ str_sample=$(cat ./fat_js_code.js)
    $ docker run --rm -v $(pwd)/src/index.php:/app/index.php test -c "$str_sample"
    ...
    ```

- Help

    ```shellsession
    $ # View help
    $ docker run --rm keinos/jsqueeze --help
    ...
    ```

- Version

    ```shellsession
    $ # View version info
    $ docker run --rm keinos/jsqueeze --version
    ...
    ```

- Run the built-in simple test

    ```shellsession
    $ docker run --rm keinos/jsqueeze --test
    ;if('this_is'==/an_example/){of_jsueeze()}
    else{var a=b?(c%d):e[f]};
    ```

## Features

- See: ["JSqueeze" document](https://github.com/tchwork/jsqueeze) @ GitHub

## License

- GNU General Public License v2.0 (see provided LICENCE.GPLv2 file).
- See: [LICENSE](https://github.com/KEINOS/Dockerfile_of_JSqueeze/blob/dockerfile/LICENSE)
