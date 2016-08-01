## Features

This package provides tools for the following, and more:

- Add jobs to queue

## Installation

Via composer

``` bash
$ composer require hlgrrnhrdt/laravel-resque
```



Copy the config the file ```config/resque.php``` from the package to your config folder.

### Laravel


``` php
'providers' => [
    Hlgrrnhrdt\Resque\ResqueServiceProvider::class
]
```

### Lumen

Open ```bootstrap/app.php``` and register the required service provider

``` php
$app->register(Hlgrrnhrdt\Resque\ResqueServiceProvider::class);
```

and load the config with
``` php

$app->configure('resque');
```
