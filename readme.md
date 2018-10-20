# WOWS Tools

## Clean install in a new developing environment
1) Checkout Repository
2) Create DB Schema + DB User
3) make a .env file based on .env.example
4) composer update 
5) php artisan migrate:fresh
6) If on WAMP, configure cURL <br />
-> Download this file: http://curl.haxx.se/ca/cacert.pem <br />
-> Place this file in the C:\wamp64\bin\php\php7.1.9 folder<br />
-> Open php.iniand find this line: <br />
-> ;curl.cainfo <br />
-> Change it to: <br />
-> curl.cainfo = "C:\wamp64\bin\php\php7.1.9\cacert.pem" <br />