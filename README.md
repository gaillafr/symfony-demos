# Symfony Demos

Requirements
------------

* [Git](https://git-scm.com/downloads)
* [Docker](https://docs.docker.com/get-docker/)
* [Docker Compose](https://docs.docker.com/compose/install/)

Installation
------------

1. Clone the demo you want. For example, if you're interested in a demo about the `Symfony Serializer` :

```bash
git clone --branch serializer https://github.com/gaillafr/symfony-demos.git
cd symfony-demos
```

2. Start Docker services

```bash
make install
```

3. Access the demo through your favourite web browser: http://localhost:8080/

Available Demos
---------------

| Demo                                                                                                     | Branch                         |
| -------------------------------------------------------------------------------------------------------- | ------------------------------ |
| [Symfony base](https://github.com/gaillafr/symfony-demos/tree/master/)                                   | `master`                       |
| [Basic controller](https://github.com/gaillafr/symfony-demos/tree/basic-controller/)                     | `basic-controller`             |
| [Data transformers](https://github.com/gaillafr/symfony-demos/tree/data-transformers/)                   | `data-transformers`            |
| [Decorator](https://github.com/gaillafr/symfony-demos/tree/decorator/)                                   | `decorator`                    |
| [Http Client decorator](https://github.com/gaillafr/symfony-demos/tree/http-client-decorator/)           | `http-client-decorator`        |
| [Messenger / Sync](https://github.com/gaillafr/symfony-demos/tree/messenger_sync/)                       | `messenger_sync`               |
| [Multiple entity managers](https://github.com/gaillafr/symfony-demos/tree/multi-entity-managers/)        | `multi-entity-managers`        |
| [Serializer](https://github.com/gaillafr/symfony-demos/tree/serializer/)                                 | `serializer`                   |
| [Web assets](https://github.com/gaillafr/symfony-demos/tree/web-assets/)                                 | `web-assets`                   |
| [Web assets / Webpack Encore](https://github.com/gaillafr/symfony-demos/tree/web-assets_webpack-encore/) | `web-assets_webpack-encore`    |
