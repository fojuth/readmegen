#!/usr/bin/env php
<?php

include_once 'vendor/autoload.php';

if (2 > count($argv)) {
    die('Example usage: php generate.php --from <tag / commit> --release <release number>');
}

new \ReadmeGen\Bootstrap($argv);