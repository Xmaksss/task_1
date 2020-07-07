<?php

use App\Models\Job;

require_once __DIR__ . "/bootstrap.php";

$app = \App\App::instance();

$command = $argv[1] ?? null;

$db = $app->getLib(\App\Libs\DB::class);

switch ($command) {
    case '-h':
    case '--help':
    case 'help':
        echo "migration:up\t\t- Up migrations\n";
        echo "migration:fresh\t\t- Fresh migrations\n";
        echo "insert-demo-data\t\t- Insert demo data\n";
        break;
    case 'migration:up':
        /** @var \App\Libs\DB $db */
        $db->upMigrations();
        echo "\nDone\n";
        break;
    case 'migration:fresh':
        /** @var \App\Libs\DB $db */
        $db->freshMigrations();
        echo "\nDone\n";
        break;
    case 'insert-demo-data':
        $urls = [
            'http://google.com',
            'http://www.reddit.com/',
            'http://www.badurl.co/',
            'https://habr.com/',
            'http://www.habr0.com/',
            'https://facebook.com/',
            'https://vk.com/',
        ];

        foreach ($urls as $url) {
            (new Job([
                'url' => $url,
                'status' => Job::STATUS_NEW
            ]))->save();
        }
        echo "\nDone\n";

        break;

}