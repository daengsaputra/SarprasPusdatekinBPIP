<?php

return [
    'units' => [
        'Sekretariat Utama',
        'Deputi Bidang Hubungan Antar Lembaga, Sosialisasi, Komunikasi, dan Jaringan',
        'Deputi Bidang Hukum, Advokasi, dan Pengawasan Regulasi',
        'Deputi Bidang Pengkajian dan Materi',
        'Deputi Bidang Pendidikan dan Pelatihan',
        'Deputi Bidang Pengendalian dan Evaluasi',
    ],

    // Upload settings for user photos
    'user_photo_max_kb' => env('USER_PHOTO_MAX_KB', 2048), // in KB
    'user_photo_mimes' => explode(',', env('USER_PHOTO_MIMES', 'jpg,jpeg,png,webp')),

    // Upload settings for asset photos
    'asset_photo_max_kb' => env('ASSET_PHOTO_MAX_KB', 4096), // in KB
    'asset_photo_mimes' => explode(',', env('ASSET_PHOTO_MIMES', 'jpg,jpeg,png,webp')),
];
