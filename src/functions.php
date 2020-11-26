<?php

/**
 * @param string $path
 * @return string
 */
function coaster_base_path($path = '') {

    return realpath(__DIR__ . DIRECTORY_SEPARATOR . '..') . DIRECTORY_SEPARATOR . $path;

}

