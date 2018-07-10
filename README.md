# Step to running this app
1). clone this project

2). copy .env.example to .env and setup your database

3). run command composer install

4). run command php artisan key:generate

5). change the permission of directory bootstrap/cache/ and storage/ to -R 777

6). run command php artisan migrate --seed

7). run command php artisan serve
