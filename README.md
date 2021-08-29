# Drupal 9 Code Examples

This repository contains working code examples for Drupal 9, described in posts made on my website [Lorique.net](https://www.lorique.net). What follows is an installation guide and links to examples.

## Installation
I'm going to assume you know how to set up a local server, if not i recommend looking at the [Valet instructions](https://laravel.com/docs/8.x/valet). Valet covers drupal, and is installed using Brew.

### Taskfile.dev (Optional)
To make my life a little easier, i use [gotask](https://taskfile.dev/#/) in this project, because it allows me to give you clear installation instructions. If you don't want to use taskfile, you can look at the [Taskfile.yml](Taskfile.yml) file, to see what task's do what.

### Installation
Installation is as easy as running the following three commands. The first uses composer to install all the required code. The second runs a site install using the supplied configuration, and the third gives you a onetime login to the site. You can also log in using admin/1234 as credentials.

```shell
$ composer install
$ task site-install
$ task uli
```

#### Environmental variables
The site makes use of a couple of environment variables.

`DB_USERNAME` defines the database username.
`DB_PASSWORD` defines the database password.
`DB_HOST` defines the database host.

My .valet-env.php file looks as follows.
```php
return [
  '*' => [
    'DB_USERNAME' => 'drupal',
    'DB_PASSWORD' => 'drupal',
    'DB_HOST'=>'localhost',
  ]
];
```
Inside the [Taskfile.yml](Taskfile.yml) file you'll find an env section with the same values. These are used when executing tasks.
