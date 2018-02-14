#!/bin/sh
set -e -x

rm -rf var/cache/*

export SYMFONY__KERNEL__CONTAINER_BUILD_TIME=100
export SYMFONY_CACHE_PREFIX_SEED=abc

php bin/console --env=prod --no-debug --verbose cache:warmup

mv var/cache/prod var/cache/prod-old

php bin/console --env=prod --no-debug --verbose cache:warmup

diff -ruN var/cache/prod-old var/cache/prod
