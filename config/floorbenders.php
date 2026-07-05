<?php

return [
    'name' => 'Floorbenders Visual Library',

    'ui_name' => 'Floorbenders Atlas',

    'tagline' => 'A movement atlas for gates, realms, layers, and visual phrases.',

    'ffmpeg_path' => env('FFMPEG_PATH', 'ffmpeg'),
    'ffprobe_path' => env('FFPROBE_PATH', 'ffprobe'),

    'media' => [
        'video_width' => 1280,
        'gif_width' => 480,
        'thumbnail_width' => 640,
        'gif_fps' => 10,
        'phrase_video_width' => 1280,
        'phrase_gif_width' => 480,
        'phrase_thumbnail_width' => 640,
        'phrase_gif_fps' => 8,
        'phrase_max_recommended_seconds' => 60,
        'phrase_max_recommended_steps' => 12,
    ],

    'assets' => [
        'brand_mark' => '/images/brand/floorbenders-mark.png',

        'badges' => [
            'gates' => [
                'h-gate' => '/images/badges/gates/h-gate.png',
                'z-gate' => '/images/badges/gates/z-gate.png',
                'l-gate' => '/images/badges/gates/l-gate.png',
                'v-gate' => '/images/badges/gates/v-gate.png',
            ],

            'aspects' => [
                'sky' => '/images/badges/aspects/sky.png',
                'earth' => '/images/badges/aspects/earth.png',
            ],

            'realms' => [
                'insect' => '/images/badges/realms/insect.png',
                'reptile' => '/images/badges/realms/reptile.png',
                'mammal' => '/images/badges/realms/mammal.png',
                'amphibian' => '/images/badges/realms/amphibian.png',
                'bird' => '/images/badges/realms/bird.png',
                'fish' => '/images/badges/realms/fish.png',
            ],

            'layers' => [
                'grounded' => '/images/badges/layers/grounded.png',
                'lifted' => '/images/badges/layers/lifted.png',
                'supported' => '/images/badges/layers/supported.png',
            ],

            'orientations' => [
                'horizontal' => '/images/badges/orientations/horizontal.png',
                'vertical' => '/images/badges/orientations/vertical.png',
            ],
        ],
    ],
];
