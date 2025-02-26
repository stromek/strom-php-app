# Nastavení
- Vytvořit soubor /.env (z .env.example)
- Do .evn zadat údaje
  - PROJECT_NAME
  - DB_MYSQL_*

# Deoploy do AWS
- ``npm run build``
- ```sh
  serverless deploy
  ```

# Vývoj
- PHP
  - php Built-in web server (nejrychlejší pro vývoj)
  - nebo docker
  - nebo Aapache
- MySQL
  - docker
- React
  - watch + MHR: ```npm run start```
  - build: ```npm run build```


## Docker
- ```sh
  docker-compose up db-mysql api -d
  ```
- PHP Api poběží na: [localhost:8001](http://localhost:8001)
  - MySQL poběží na: [localhost:3310](http://localhost:3310)


## PHP Dev server
- Target: ./htdocs
- Router: htdocs/cli-touter.php
- ```sh
  php -S localhost:8000 -t ./htdocs htdocs/cli-router.php
  ```
- PHP Api poběží [localhost:8000](http://localhost:8000)


## Apache
- VirtualHost
- DocumentRoot ``/htdocs``
- povolit ``.htaccess``