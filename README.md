## Introduction

This is a program that uses the Laminas MVC layer and the module
systems.

## Installation using Composer

To create new Laminas MVC project:

```bash
$ git clone https://github.com/Drakyla60/zend.loc.git
```

After cloning, you need to make dependencies

```bash
$ composer install --ignore-platform-reqs
```

This will help assemble and run docker containers
```bash
$ docker-compose build

$ docker-compose up -d
```
OR

```bash
$ docker-compose up -d --build
```
Next you need to go to the php container and grant rights to the 'data' directory

```bash
docker exec -it php bash

chmod -R 777 data
```

The next step is to load the dump base `dump.sql`

**Note:** It was not possible to connect migrations therefore it is necessary to load a dump *manually*.

You can then visit the site at http://localhost:8080/
