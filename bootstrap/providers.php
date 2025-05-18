<?php

return [

    // Providers del framework...
    Illuminate\Filesystem\FilesystemServiceProvider::class,



    App\Providers\AppServiceProvider::class,

     // Añade esto al final:
    Barryvdh\Snappy\ServiceProvider::class,
];

