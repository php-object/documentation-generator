<?php

use Huttopia\ConsoleBundle\{
    Application,
    CommandOption\AllCommandsOption
};
use PhpObject\DocumentationGenerator\Kernel;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\ErrorHandler\Debug;

if (!in_array(PHP_SAPI, ['cli', 'phpdbg', 'embed'], true)) {
    echo 'Warning: The console should be invoked via the CLI version of PHP, not the '.PHP_SAPI.' SAPI'.PHP_EOL;
}

set_time_limit(0);

require dirname(__DIR__).'/vendor/autoload.php';

if (!class_exists(Application::class)) {
    throw new LogicException('You need to add "symfony/framework-bundle" as a Composer dependency.');
}

$allCommands = AllCommandsOption::parseAllCommandsOption($argv);
$input = new ArgvInput();
if (null !== $env = $input->getParameterOption(['--env', '-e'], null, true)) {
    putenv('APP_ENV='.$_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = $env);
}

if ($input->hasParameterOption('--no-debug', true)) {
    putenv('APP_DEBUG='.$_SERVER['APP_DEBUG'] = $_ENV['APP_DEBUG'] = '0');
}

require dirname(__DIR__).'/config/bootstrap.php';

if ($_SERVER['APP_DEBUG']) {
    umask(0000);

    if (class_exists(Debug::class)) {
        Debug::enable();
    }
}

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
(new Application($kernel))
    ->setAllCommands($allCommands)
    ->run($input);
