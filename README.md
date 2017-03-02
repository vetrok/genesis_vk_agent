VK user parser
========================

Set up
--------------

1. Clone repository
--------------

2. Update composer
--------------

???

3. Insert credentials
--------------

1. For db (framework will ask it when composer will be updating), they can be found in app\config\parameters.yml
2. For RabbitMQ - in app\config\config.yml, old_sound_rabbit_mq block
3. Access token for VK api (framework will ask it when composer will be updating), can be found in app\config\parameters.yml

4. Database set-up
--------------

1. Create database

php bin/console doctrine:database:create

2. Migrate database structure

php bin/console doctrine:migrations:migrate

5. RabbitMQ set-up
--------------

1. Run endless loop from command line

php bin/console rabbitmq:consumer import_user

Done! Try to use application

How to use
========================

1. Import user
--------------

a) Single ID

php bin/console vk:import_user [user_id]

b) Path to file in csv format

php bin/console vk:import_user [path] --data_type=csv

1. Get imported user albums with photos
--------------


php bin/console vk:get_user [user_id]

php bin/console vk:get_user_albums [user_id] --limit=[int] --offset=[int]

php bin/console vk:get_user_photos [user_id] --album_id=[int] --offset=[int] --limit=[int]


Generate DB

php bin/console doctrine:database:create

php bin/console doctrine:migrations:status

php bin/console doctrine:migrations:migrate

php bin/console doctrine:migrations:generate


