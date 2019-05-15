<?php
namespace KEINOS\JSqueeze;

require_once('./JSqueeze.php.inc');
require_once('./app-release.php.inc');

const FAILURE = 1; // Exit status code on failure
const SUCCESS = 0; // Exit status code on success

$fatcode = '';

$msg_help = <<<HELP
USAGE: [OPTION]
OPTION:
  .                           Minifies the CODE given from STDIN.
                              Don't forget to use -i/--interactive option
                              when docker run.
  -c='CODE', --code='CODE'    Minifies the CODE given from arg value.
  -h,        --help           Show this help.
  -v,        --version        Show version info.
             --test           Use sample json file.

For more detaild usage and information see:
    https://github.com/KEINOS/Dockerfile_of_JSqueeze
HELP;

$options_available = [
    'c:', 'h', 'v', 't',
    'code:',
    'help',
    'version',
    'test',
];

switch (true) {
    case is_arg_empty():
        print_help('No argument specified.');
        exit(FAILURE);
    case is_arg_test():
        set_fatcode('test');
        break;
    case is_arg_version():
        print_version_info();
        exit(SUCCESS);
    case is_arg_help():
        print_help();
        exit(SUCCESS);
    case is_code_stdin():
        set_fatcode(file_get_contents('php://stdin'));
        break;
    case is_arg_code():
        set_fatcode(get_arg_code());
        break;
    default:
        print_help('Unknown option given');
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

function get_arg_code()
{
    $options = get_arg_options();
    if (isset($options['c'])) {
        return $options['c'];
    }

    if (isset($options['code'])) {
        return $options['ccode'];
    }

    return false;
}

function get_arg_options()
{
    global $options_available;
    static $option_result;

    if (isset($option_result)) {
        return $option_result;
    }

    $options_short = '';

    foreach ($options_available as $option) {
        $option = trim($option);
        if (1 === strlen(trim($option, ':'))) {
            $options_short .= $option;
            continue;
        }
        $options_long[] = $option;
    }

    $option_result = getopt($options_short, $options_long);

    return $option_result;
}

function get_fatcode()
{
    global $fatcode;
    return $fatcode;
}

function get_fatcode_sample()
{
    $path_file_sample = './sample.js';

    if (! file_exists($path_file_sample)) {
        echo_error('Sample file not found.');
        exit(FAILURE);
    }
    return file_get_contents($path_file_sample);
}

function get_opt_keepImportantComments()
{
    // don't remove /*! comments
    return false;
}

function get_opt_singleLine()
{
    // keep the break line if a semicolon is found.
    return false;
}

function get_opt_specialVarRegEx()
{
    // Use regular expression of special variables names for global vars,
    // methods, properties and in string substitution. Set it to false if you
    // don't want any.
    return false;
}

function is_arg_code()
{
    $options = get_arg_options();

    return (isset($options['c']) || isset($options['code']));
}

function is_arg_empty()
{
    global $argc;

    return (1 === $argc);
}

function is_arg_help()
{
    $options = get_arg_options();

    return (isset($options['h']) || isset($options['help']));
}

function is_arg_test()
{
    $options = get_arg_options();

    return (isset($options['t']) || isset($options['test']));
}

function is_arg_version()
{
    $options = get_arg_options();

    return (isset($options['v']) || isset($options['version']));
}

function is_code_stdin()
{
    global $argv;

    foreach ($argv as $arg) {
        $arg = trim($arg);
        if ('.' === $arg) {
            return true;
        }
    }
    return false;
}

function print_help($msg_additional='')
{
    global $msg_help;

    $msg_additional = trim($msg_additional);

    if (! empty($msg_additional)) {
        $header = '* ';
        $len_underline = strlen($msg_additional);
        $len_header    = strlen($header);
        echo $header . $msg_additional . PHP_EOL;
        echo str_repeat('-', $len_underline + $len_header + 1) . PHP_EOL;
    }

    echo trim($msg_help) . PHP_EOL . PHP_EOL;
}

function print_version_info()
{
    echo VERSION_APP, PHP_EOL;
    echo 'PHP ', phpversion(), PHP_EOL;
    echo VERSION_OS, PHP_EOL;
}

function set_fatcode($string)
{
    global $fatcode;

    $fatcode = ('test' === strtolower(trim($string))) ? get_fatcode_sample() : $string;

    return $fatcode;
}
