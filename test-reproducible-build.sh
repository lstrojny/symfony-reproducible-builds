#!/bin/sh
set -e -x

rm -rf var/cache/*

SYMFONY__KERNEL__CONTAINER_BUILD_TIME=100 php bin/console --env=prod --no-debug cache:warmup

mv var/cache/prod var/cache/prod-old

SYMFONY__KERNEL__CONTAINER_BUILD_TIME=100 php bin/console --env=prod --no-debug cache:warmup

diff -ruN var/cache/prod-old var/cache/prod
