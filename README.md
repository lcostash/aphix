# Back-End for Aphix

This project was create using Symfony 5 special for Aphix. 

## How to up the project

1) get the project from git on local folder with name `aphix`
2) go to inside of `aphix`, you must to see composer.json file
3) run cmd terminal and run next command `composer install` to install required packages and press enter
4) the next step you must setup in .env file Google Api Key
4) after that you must create virtual host on your Apache server on local machine with name `aphix.one`
5) from cmd execute this line `curl -d '{"find":"php course"}' -H "Content-Type: application/json" -X POST http://aphix.one/find` or use Postman for request


 
