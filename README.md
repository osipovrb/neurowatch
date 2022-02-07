# Neurowatch
Мониторинг файлов в локальной сети по протоколу SMB и их фильтрация через нейросеть. Включает в себя сканер хостов, определение залогинившегося в настоящий момент пользователя, авторизацию через AD\LDAP.

# Установка
Установите расширение php-ldap и включите его в php.ini 
```
extension=ldap
```
Склонируйте репо
```
$ git clone https://github.com/osipovrb/sambawatch.git
$ cd sambawatch
$ cp .env.example .env
$ php artisan key:generate
$ composer install
$ npm install
$ npm run dev
```
Настройте LDAP в файле .env
```
LDAP_CONNECTION=default
LDAP_HOST=
LDAP_USERNAME=
LDAP_PASSWORD=
LDAP_PORT=
LDAP_BASE_DN=
LDAP_TIMEOUT=5
LDAP_SSL=false
LDAP_TLS=false
LDAP_LOGGING=true
LDAP_CACHE=false
```
Cоздайте БД
```
sudo -u postgres psql
postgres=# create database neurodb;
postgres=# create user neurouser with encrypted password 'mypass';
postgres=# grant all privileges on database neurodb to neurouser;
```
Настройте БД в файле .env
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=neurodb
DB_USERNAME=neurouser
DB_PASSWORD=mypass
```
