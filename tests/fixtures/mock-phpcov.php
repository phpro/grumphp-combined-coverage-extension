#!/usr/bin/env php
<?php

declare(strict_types=1);
$args = $_SERVER['argv'];
assert('merge' === $args[1]);
assert('--clover' === $args[2]);
assert('clover.xml' === $args[3]);
assert('php-coverage-dir' === $args[4]);

exit(0);
