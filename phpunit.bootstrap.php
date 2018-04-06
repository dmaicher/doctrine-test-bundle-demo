<?php

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

require_once 'vendor/autoload.php';

(new Dotenv())->load(__DIR__.'/.env');
$kernel = new Kernel('test', false);

$application = new Application($kernel);
$application->setAutoExit(false);
$application->setCatchExceptions(false);

$runCommand = function (string $name, array $options = []) use ($application) {
    $input = new ArrayInput(array_merge(['command' => $name], $options));
    $input->setInteractive(false);
    $application->run($input);
};

$runCommand('doctrine:database:drop', [
    '--force' => 1,
]);
$runCommand('doctrine:database:create');
$runCommand('doctrine:schema:create');

$kernel->shutdown();
