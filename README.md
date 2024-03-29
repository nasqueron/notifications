# Notifications center

Notifications center is a key part of our CI microservices infrastructure.

It receives payloads from various sources like GitHub, Phabricator or Docker Hub
and fires standardized notifications to a AMQP broker.

Additionally, it can perform extra tasks like triggering a remote API. A builtin
example notifies Phabricator when a push occurs to a GitHub repository.

It allows to bridge GitHub and Phabricator, to notify an IRC/Slack bot
of what happens on your CI infrastructure or to orchestrate the communication
between the different CI software.

The broker layer interface is abstracted, you can use another broker
if you send a pull request to the keruald/broker repository.

It's built in PHP on the top of the Laravel framework.

## How to install

To install the application, we need an AMQP broker.

The application uses a `notifications` topic exchange
on the specified virtual host.

### Through Docker

We recommend RabbitMQ as broker, but any AMQP broker is suitable.

    docker pull nasqueron/rabbitmq
    docker run -dt -p 5672:5672 -p 15672:15672 \
    -v /data/log -v /data/mnesia \
    --hostname blue-rabbit --name blue-rabbit \
    nasqueron/rabbitmq

Three operations should be done on the broker at http://server:15672:

  1. Change the default login/password for administrator account: guest/guest.
  2. Declare a `topic` exchange `notifications` on the suitable vhost.
  3. Create an login/pass with right on this exchange for the application.

Fire the notification container, linking to the broker if launched as another
container and setting in environment suitable configuration:

    docker pull nasqueron/notifications
    docker run -dt -v /var/wwwroot/default/storage \
       --link blue-rabbit:mq -p 80:80 \
       -e BROKER_HOST=mq \
       -e BROKER_VHOST=$BROKER_VHOST \
       -e BROKER_USERNAME=$BROKER_USERNAME \
       -e BROKER_PASSWORD=$BROKER_PASSWORD \
       nasqueron/notifications

### Manually

Clone the repository and install the dependencies:

    git clone https://github.com/nasqueron/notifications.git
    cd notifications
    composer install

Prepare a configuration file:

    cp .env.example .env
    php artisan key:generate
    $EDITOR .env

See the section above for hints about how to configure your broker.

### Dependencies for manual installation

Composer will give you the list of extensions you need:

  - bcmath (required by the broker library)
  - curl (required by Laravel)
  - fileinfo (required by Laravel)
  - intl (required by us)
  - mbstring (required by Laravel)
  - sockets (required by the broker library)

As a developer, you also need:

  - ast (required by phan)
  - dom (required by phpunit)

As ast isn't generally packaged, to install it:

```
$ git clone https://github.com/nikic/php-ast.git
$ cd php-ast
$ ./configure
$ make
$ sudo make install
```

You can also skip the developer extensions with:

`composer install --ignore-platform-reqs`

Finally, if you wish to run the full ant build, you need to install phpdox
manually, as dependencies aren't compatible with the others.

## Notifications format

Each notification is represented as an object with the following
properties.

Three describes the origin of the notification:

  - service: the source service (e.g. GitHub, Phabricator, Jenkins)
  - project: the target project (e.g. Nasqueron)
  - group: the target group inside this project (e.g. CI, Tasacora, Operations)

Four describes the content of the notification:

  - rawContent: a representation 'as is' of the source payload/data/message
  - type: the notification's type (e.g. "commit", "task")
  - text: the notification's text
  - link: an URL to be used as the main link for widgets using the notification

Notifications are sent to the broker in JSON.
