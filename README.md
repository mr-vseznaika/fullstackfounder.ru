# Wordpress Bedrock сборка
Bedrock - это переделанный каркас для WP, позволяющий использовать Git and Composer.
http://12factor.net/
https://roots.io/twelve-factor-wordpress/

- Улучшеная структура папок
- Composer https://roots.io/bedrock/docs/composer/
- управление средой (разработка, продакшн)
- .env файл для хранения переменных среды
- авторазгрузка для mu-плагинов
- Улучшенная безопасность: вынесен корень веб сервера и применен wp-password-bcrypt
- Docker compose для локальной разработки

## Установка
cp .env.example .env
Сгенерировать соли для шифрования https://roots.io/salts.html (.env версия)
Заменить соли в .env файле
`./docker/bin/sail up -d`

http://localhost - сайт
http://localhost/wp/wp-admin/ - админка
http://localhost:8080 - phpmyadmin

## Установка плагинов
Плагины живут в папке web/app/plugins/
Команда `./docker/bin/sail composer require wpackagist-plugin/akismet` установит плагин Akismet
где akismet - название плагина из url в офиц директории плагинов https://wordpress.org/plugins/akismet/

плагины игнорируются в .gitignore и управляются через composer
если нужно управлять плагином через git, нужно явно добавить исключение в .gitignore
`!web/app/plugins/akismet`

## Работа с темой
Если нужно установить тему из общего каталога, то делается это командой
`./docker/bin/sail composer require wpackagist-theme/twentytwentythree`

В случае модификации официальной или обновляемой темы, рекомендуется сделать свою дочернюю тему и редактировать её. Это позволит получать обновления родительской темы без риска потерять свои модификации. https://codex.wordpress.org/Child_Themes

В остальных случаях рекомендуется сделать свою тему в папке web/app/themes/ и управлять её состоянием через git


## Docker
В данной сборке использует адаптация Laravel Sail
`./docker/bin/sail`