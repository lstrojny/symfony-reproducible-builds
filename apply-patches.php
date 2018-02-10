<?php
namespace lstrojny;

require_once 'vendor/autoload.php';

use Symfony\Component\Process\Process;
const PRS = [
    'symfony/symfony' => [26127, 26128, 26131, 25978],
    'symfony/monolog-bundle' => [248],
    'twig/twig' => [2621],
    'ocramius/proxy-manager' => [411],
];


$installedPackages = json_decode(file_get_contents(__DIR__ . '/vendor/composer/installed.json'), true);

function findInstalledPackageByName(array $installedPackages, string $name): array {
    return array_values(
               array_filter(
                   $installedPackages,
                   static function (array $package) use ($name) {
                       return $package['name'] === $name;
                   }
               )
           )[0] ?? null;
}

$fs = new Filesystem

foreach (array_keys(PRS) as $package) {
    `rm -rf vendor/$package`;
}

`composer install`;

foreach (PRS as $package => $prs) {
    foreach ($prs as $pr) {

        $baseUrl = findInstalledPackageByName($installedPackages, $package)['source']['url'];
        $diffUrl = substr($baseUrl, 0, strrpos($baseUrl, '.')) . '/pull/' . $pr . '.diff';

        $h = tmpfile();
        fwrite($h, file_get_contents($diffUrl));
        $patch = stream_get_meta_data($h)['uri'];


        $process = new Process('patch -p1 < ' . $patch);
        $process->setWorkingDirectory('vendor/' . $package);
        $process->run();

        if ($process->isSuccessful()) {
            printf("Applied #%s for %s\n", $pr, $package);
        } else {
            printf("Could not apply #%d for %s \"%s\": %s\n", $pr, $package, $diffUrl, $process->getOutput());
        }
    }
}
