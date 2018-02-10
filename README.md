Symfony Reproducible Builds
===========================

A demo project to show and test reproducible builds (tracker issue [symfony/symfony#25958](https://github.com/symfony/symfony/issues/25958)) with Symfony

### Setup
Install:
```
composer install
```

Apply patches:
```
php apply-patches.php
```

Test:
```
./test-reproducible-build.sh
```
