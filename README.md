# Учёт оргтехники в организации v3.X 2013-2015

Система предназначена для учёта оргтехники в небольших организациях и будет полезна в основном инженерам IT отдела, ведущими учёт без фанатизма.

Домашняя страница проекта: [http://грибовы.рф/](http://xn--90acbu5aj5f.xn--p1ai/?page_id=1202)

Любые вопросы по skype: pvtuning или ICQ 207074753

### Установка

1. Запустить инсталлятор
2. Переименовать файл config.php.dist в config.php и отредактировать.
3. Поправить права на папки _files_, _photo_, _maps_ на 0777
4. Зайти с логином **test** и паролем **test**
5. Если поняли что всё ок, работает, то почистите таблицы от демо-данных и работайте..

Если используете пакет "Денвер", то необходимо в httpd.conf изменить кодироку по умолчанию: 
AddDefaultCharset utf-8

### Обновление

Обновления являются куммулятивными — для обновления до последней версии нужен только самый последний релиз.

1. Скачать ПОСЛЕДНЮЮ версию данного ПО.
2. Копировать её 1 в 1 с заменой файлов в каталог с предыдущей версией (за исключением _config.php_ в корне).
3. Открыть в браузере _http://адрессайта/update.php_

Не забудьте после обновления удалить файл _update.php_
