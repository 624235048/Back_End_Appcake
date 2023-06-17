<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
         // Database connection settings      

          "db" => [
            "host" => "localhost",
            "dbname" => "flutterdb",
            "user" => "root",
            "pass" => ""

        // "db" => [
        //     "host" => "ec2-54-156-60-12.compute-1.amazonaws.com",
        //     "dbname" => "d5a8s38hem54b3",
        //     "user" => "eywbxecjmjdrjj",
        //     "pass" => "427122bb0fd4bd08ce8ab935ff1283b868fa701cf65871ca7631c6af677d63e8",
        ],
    ],
];
