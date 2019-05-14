<?php
namespace KEINOS\JSqueeze;

require_once('./JSqueeze.php.inc');
require_once('./app-release.php.inc');

const FAILURE = 1; // Exit status code on failure
const SUCCESS = 0; // Exit status code on success

$msg_help = <<<HELP
Usage:
  -h, --help     Show this help.
  -v, --version  Show version info.

HELP;

switch (true) {
    case is_arg_version():
        print_version_info();
        exit(SUCCESS);

    case is_arg_help():
        print_help();
        exit(SUCCESS);

    default:
        print_help();
        exit(FAILURE);
}

$jz = new \Patchwork\JSqueeze();

$minifiedJs = $jz->squeeze(
    get_fatcode(),
    get_opt_singleLine(),
    get_opt_keepImportantComments(),
    get_opt_specialVarRegEx()
);

if (false === $minifiedJs) {
    echo_error('Failed to mijify the code.');
    exit(FAILURE);
}

echo $minifiedJs;
exit(SUCCESS);

/* [Functions] ============================================================= */

function echo_error($message)
{
    echo $message . PHP_EOL;
}

function get_fatcode()
{
    return get_fatcode_sample();
}

function get_fatcode_sample()
{
    $path_file_sample='./sample.js';

    if (! file_exists($path_file_sample)) {
        echo_error('Sample file not found.');
        exit(FAILURE);
    }
    return file_get_contents($path_file_sample);
}

function get_opt_keepImportantComments()
{
    // don't remove /*! comments
    return true;
}

function get_opt_singleLine()
{
    // keep the break line if a semicolon is found.
    return true;
}

function get_opt_specialVarRegEx()
{
    // Use regular expression of special variables names for global vars,
    // methods, properties and in string substitution. Set it to false if you
    // don't want any.
    return false;
}

function is_arg_help()
{
    global $argv;

    foreach ($argv as $arg) {
        $arg = trim($arg);
        if ('--help' === $arg) {
            return true;
        }
        if ('-h' === $arg) {
            return true;
        }
    }

    return false;
}

function is_arg_version()
{
    global $argv;

    foreach ($argv as $arg) {
        $arg = trim($arg);
        if ('--version' === $arg) {
            return true;
        }
        if ('-v' === $arg) {
            return true;
        }
    }

    return false;
}

function print_help()
{
    global $msg_help;

    echo trim($msg_help) . PHP_EOL . PHP_EOL;
}

function print_version_info()
{
    echo VERSION_APP, PHP_EOL;
    echo 'PHP ', phpversion(), PHP_EOL;
    echo VERSION_OS, PHP_EOL;
}
