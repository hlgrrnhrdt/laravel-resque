## Features

This package provides tools for the following, and more:

- Add jobs to queue
- Console command to run a single worker

## Installation

Via composer

``` bash
$ composer require hlgrrnhrdt/laravel-resque
```

Copy the config file ```config/resque.php``` from the package to your config folder.
Additionally a default redis connection needs to be configured in ```config/redis.php```.

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
