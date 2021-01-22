<?php

/*
|--------------------------------------------------------------------------
| Galleries config
|--------------------------------------------------------------------------
|
| gallery_folder_name : Définit le nom du dossier dans lequel mettre les galeries
| gallery_path : Définit le chemin du dossier des galeries
| filesystem_driver : Définit le type de driver utiliser pour les fichiers
|
*/

return [
    'gallery_folder_name' => 'Galeries',

    'gallery_path' => 's3' == env('FILESYSTEM_DRIVER')
        ? '/Galeries'
        : storage_path('app/public/Galeries/'),

    'filesystem_driver' => env('FILESYSTEM_DRIVER'),
];
