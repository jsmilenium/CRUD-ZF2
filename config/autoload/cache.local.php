<?php

return [
    'caches' => [
        'Cache\FileSystem' => [
            'adapter'   => 'filesystem',
            'options'   => [
                'cache_dir' => __DIR__ . '/../../data/cache/'
            ],
            'plugins' => [
                'exception_handler' => [
                    'throw_exceptions' => false,
                ],
                'Serializer'
            ],
        ],
    ],
];

