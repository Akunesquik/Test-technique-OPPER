# Test Technique 

## Objectif 
Implémenter une API REST avec la dernière version de Symfony en mettant en place les services en GET, PUT, POST et DELETE

## Installation du programme

La première étape est de cloner le dépot :

```git clone https://github.com/Akunesquik/Test-technique-OPPER.git```

Il faut ensuite initialiser et préparer le 'container' :

``` docker-compose build ```

``` docker-compose up -d ```

Connectez vous au 'container' :

``` docker exec -it php8 bash ```

Vous atterrirez au chemin : "/var/www/html"

Le projet se trouve dans "new-project"

``` cd new-project ```

Installez maintenant les dépendances Symfony en exécutant :

``` composer install ```

Le projet est installé !!


## Utilisation de l'API

Pour lancer l'api :

``` symfony serve -d --allow-all-ip ``` 
(-d : pour lancer en arrière-plan, --allow-all-ip : afin qu'il n'y ai aucun problème de connexion lors du test)

le site sera disponible à :

[http://127.0.0.1:9000/](http://127.0.0.1:9000/)

## Jeu de données
Des contacts et des produits ont été crées et possèdent des ID allant de 1 à 3.

Je vous conseille d'exécuter ces requètes dans cet ordre afin de vivre de la création à la suppression de votre souscription.

```
POST : 127.0.0.1:9000/subscription
JSON : 
{
    "contact": 1,
    "product": 2,
    "beginDate": "2024-03-01",
    "endDate": "2024-06-01"
}
```
```
GET : 127.0.0.1:9000/subscription/{idContact}
```

```
PUT : http://localhost:9000/subscription/{idSubscription}
JSON :
{
    "product": 1,
    "endDate": "2024-07-01"
}
```
```
GET : 127.0.0.1:9000/subscription/{idContact}
```
```
DELETE : http://localhost:9000/subscription/{idSubscription}
```
```
GET : 127.0.0.1:9000/subscription/{idContact}
```







