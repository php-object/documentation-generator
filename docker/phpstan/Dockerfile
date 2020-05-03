FROM php:7.4.5-cli-alpine3.10

COPY --from=composer /usr/bin/composer /usr/local/bin/composer
COPY docker/phpstan/composer.json /root/.composer/composer.json

RUN \
    # zip is for Composer, git to clone repository and for CircleCI and openssh for CircleCI
    apk add --no-cache zip git openssh \
    && composer global update \
    && rm /usr/local/bin/composer

COPY docker/phpstan/phpstan.neon /phpstan/phpstan.neon

COPY docker/phpstan/docker-entrypoint /usr/local/bin/docker-entrypoint
ENTRYPOINT ["docker-entrypoint"]
