<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Converts a number to it's alphabetical representation (in lower case).
 *
 * @param integer $num a number between 1 and 26.
 *
 * @return string
 */
function numToAlphaSwitch($num)
{
    if (is_numeric($num) && $num >= 1 && $num <= 26) {
        return chr($num + 96);
    } else {
        throw new BadFunctionCallException("$num can only be between 1 and 26 (inclusive)!");
    }
}

/**
 * Finds the current root directory of the project.
 *
 * @return string The root directory.
 */
function getProjectDir()
{
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $dir = shell_exec('echo %cd%');
    } else {
        $dir = shell_exec('pwd');
    }

    return trim($dir);
}
