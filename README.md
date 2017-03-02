VK user parser
========================

Set up
--------------

1. Clone repository
--------------

2. Update composer
--------------

3. Insert credentials
--------------

a. For db (framework will ask it when composer will be updating), they can be found in app\config\parameters.yml
b. For RabbitMQ - in app\config\config.yml, old_sound_rabbit_mq block
c. Access token for VK api (framework will ask it when composer will be updating), can be found in app\config\parameters.yml

4. Database set-up
--------------

a. Create database

~~~
php bin/console doctrine:database:create
~~~

b. Migrate database structure

~~~
php bin/console doctrine:migrations:migrate
~~~

5. RabbitMQ set-up
--------------

Run endless loop from command line, project is using bundle php-amqplib/rabbitmq-bundle ,
check documentation on https://github.com/php-amqplib/RabbitMqBundle

~~~
php bin/console rabbitmq:consumer import_user
~~~

Done! Try to use application


How to use
========================

1. Import user
--------------

a) Single ID

~~~
php bin/console vk:import_user [user_id]
~~~

b) Path to file in csv format

~~~
php bin/console vk:import_user [path] --data_type=csv
~~~

Get imported user data
--------------

a. Get user data

~~~
php bin/console vk:get_user [user_id]
~~~

b. Get user albums

~~~
php bin/console vk:get_user_albums [user_id] --limit=[int] --offset=[int]
~~~

c. Get photos in album

~~~
php bin/console vk:get_user_photos [user_id] --album_id=[int] --offset=[int] --limit=[int]
~~~