1- get into any folder then open a terminal and paste this: git clone git@github.com:ebtsam-1/content-scheduling-management.git
2- rename .env.example file to be .env then add your database credentails to the file
3- open a terminal in the project folder and run composer i
4- then run php artisan key:generate in the terminal
5- then run php artisan migrate --seed
6- php artisan storage:link
then run php artisan serve
7- open anoher terminal and run php artisan schedule:work to run the scheduled posts job
8- I have attached postman collection for the requested endpoints => Tasks.postman_collection.json, feel free to update the server link => to your server data
9- after Login, I update the auth token variable to be the new token then please check for tasks filterations in the tasks index endpoint => you will find it in the params tab

The project handles api side and blade side
