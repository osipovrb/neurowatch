# Neurowatch
Мониторинг файлов в локальной сети по протоколу SMB и их фильтрация через нейросеть. Включает в себя сканер хостов, определение залогиненного на удаленной машине пользователя, авторизацию через AD\LDAP.

# Требуемые расширения
- Установите расширения php-ldap, php-smbclient, php-pgsql, php-sybase
- Установите python3, tflite-runtime, scikit-image

# БД
```
sudo -u postgres psql
postgres=# create database neurodb;
postgres=# create user neurouser with encrypted password 'mypass';
postgres=# grant all privileges on database neurodb to neurouser;
```
Запустите миграции

# Запуск
Необходимо запустить вебсокет-сервер и слушателя очереди задач (желательно как минимум 2)
```
$ php artisan websockets:serve
$ php artisan queue:listen --timeout=0
