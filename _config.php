<?php

/**
 * Fetches the name of the current module folder name.
 *
 * @return string
 */
if (!defined('EXTRAOBJECT_DIR')) {
    define('EXTRAOBJECT_DIR', ltrim(Director::makeRelative(realpath(__DIR__)), DIRECTORY_SEPARATOR));
}