# AddSecure

Instrukcja uruchomienia projektu:

1. tworzymy kontenery aplikacji:
`docker-compose up -d`

2. Wchodzimy do kontenera i wykonujemy migrację:
`docker-compose exec php-fpm bash` i `./bin/console d:m:m`

3. Instalujemy yarna w kontenerze:
`docker-compose exec php-fpm bash` i `apt-get install yarn` oraz `yarn dev`

Aplikacja powinna uruchomić się pod adresem:
`http://localhost:9993/vehicles`
