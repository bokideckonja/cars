# Cars

### Installation

1. git clone https://github.com/bokideckonja/cars.git
2. cd cars
3. composer install
4. rename .env.example to .env and modify the following:

App url:
```sh
APP_URL=http://localhost
```

DB settings:
```sh
DB_CONNECTION=mysql
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

Mail settings(to enable mail sending for password resets - optional):
```sh
MAIL_DRIVER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
```
5. php artisan key:generate
6. php artisan migrate --seed
7. configure your apache/nginx to point to public dir

### Explanation

Project consists of 2 auth systems. Basic User(member) and Admin(administrator).
To access admin section, just add /admin

Both auths have working login, register, reset functionality, but there are 2 seeded accounts for ease of use.
member@gmail.com pass: asdasd
admin@gmail.com pass: asdasd