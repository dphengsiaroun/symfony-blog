# Symfony-blog

This is a web project for Estiam.

# Prerequisites

1. Install WAMP (http://www.wampserver.com/). WAMP is a PHP, MySQL, Apache stack.
2. Install Composer (https://getcomposer.org/). For info: Composer is used to install php dependancies as npm does.


# Installation

## Clone the project

```
$ git clone https://github.com/dphengsiaroun/symfony-blog.git
$ cd symfony-blog
$ composer install
```

## Start the servers

1. Start the WAMP server (both Apache and MySQL)
2. Start the express server: `bin/console server:start`

## Create database

Open a new console or terminal.
```
$ cd symfony-blog
$ bin/console doctrine:schema:update --force
```

## View project

The installation should be successfull, go to the website.

1. Open a web browser on `http:localhost:8000`.
2. It should be working.

# Authors

- Dany PHENGSIAROUN
- Fayçal OSSENI
