#!/bin/sh
set -e -x

rm -rf var/cache/*

SYMFONY__KERNEL__CONTAINER_BUILD_TIME=100 php bin/console cache:warmup

mv var/cache/dev var/cache/dev-old

SYMFONY__KERNEL__CONTAINER_BUILD_TIME=100 php bin/console cache:warmup

diff -ru var/cache/dev-old var/cache/dev
