<p align="center">
    <img width="150px" hieght="150px" src="https://i.imgur.com/tiWh6qL.png"/>
</p>
<h1 align="center">Welcome to Jobber 👋</h1>
<p align="center">
    
  <img alt="Version" src="https://img.shields.io/badge/version-1.0-blue.svg?cacheSeconds=2592000" />
  <a href="https://choosealicense.com/licenses/mit/" target="_blank">
    <img alt="License: MIT" src="https://img.shields.io/badge/License-MIT-yellow.svg" />
  </a>
</p>

> Powerful, simple, message queue client

## Install

```sh
composer require ghostwalker/jobber
```

## Usage

Change bootServer.php file

```php
GhostWalker\Jobber\JobberServer::bootSystem([
  'jobDirectory' => __DIR__ . '/jobs', //You directory
]);
```

Start server

```sh
php bootServer.php //To keep the process running permanently in the background,
you should use a process monitor such as Supervisor to ensure that the queue worker does not stop running.
```

create a class in the directory you specified and create a "handle" method

with arguments that accepts an array of $date

```php
<?php

class test {
    public function handler(array $data)
    {
        echo $data[0]; //int(999)
    }
}
```

Create a client class and load a new task

```php
<?php

require __DIR__ . '/vendor/autoload.php';

$jobber = new \GhostWalker\Jobber\JobberClient();

$jobber->addTask(test::class, [999]);
$jobber->addTask(test1::class, [123]);
```


## Author

👤 **GhostWalker**

* Github: [@GhostWalkers](https://github.com/GhostWalkers)

## 🤝 Contributing

Contributions, issues and feature requests are welcome!<br />Feel free to check [issues page](https://github.com/GhostWalkers/Jobber/issues). 

## Work on

the application is based on [ReactPhp](https://reactphp.org/), as well as a [robot-loader](https://github.com/nette/robot-loader) for loading classes

## Show your support

Give a ⭐️ if this project helped you!

## 📝 License

Copyright © 2022 [GhostWalker](https://github.com/GhostWalkers).<br />
This project is [MIT](https://choosealicense.com/licenses/mit/) licensed.

***
❤️ [readme](https://github.com/kefranabg/readme-md-generator)
