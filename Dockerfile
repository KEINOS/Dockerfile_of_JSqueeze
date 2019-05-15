# Download latest JSqueeze release.
# =================================
FROM keinos/alpine AS src

WORKDIR /root

COPY src/download_file.sh /root/donwload_file.sh

RUN apk update \
    && apk add \
      curl tar jq \
    && /root/donwload_file.sh

# Build JSqueeze docker image
# ===========================
FROM keinos/alpine

# trust PHP-Alpine project public key to trust their packages.
# See: https://github.com/codecasts/php-alpine
ADD https://dl.bintray.com/php-alpine/key/php-alpine.rsa.pub /etc/apk/keys/php-alpine.rsa.pub

# Copy the repository URL file of PHP-Alpine for apk.
# This file will be appended to /etc/apk/repositories file.
COPY --from=src /etc_apk_repositories /etc_apk_repositories

# Note: the @php is required to avoid getting default php packages from alpine instead.
RUN apk --update add ca-certificates \
    && cat '/etc_apk_repositories' >> /etc/apk/repositories \
    && apk add --update \
      php@php \
      php-mbstring@php \
    && rm -rf /var/cache/apk/*

COPY src/index.php /app/index.php
COPY src/sample.js /app/sample.js
COPY --from=src /JSqueeze.php         /app/JSqueeze.php.inc
COPY --from=src /app-release.php.inc  /app/app-release.php.inc

WORKDIR /app

ENTRYPOINT [ "php", "/app/index.php"]
