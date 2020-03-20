# Correction du TP 20 mars

La correction du TP du 20 mars sera détaillée dans ce fichier. L'idée étant de garder une trace de toute la réflexion qu'on peut avoir quand on doit réaliser ce genre de travaux.

Pour votre projet, vous allez voir cette même reflexion... Mais ce sera à vous de faire le sujet du TP :)

## Installation du projet ?

La partie la plus simple est de créer le projet Symfony. On ouvre un terminal et on se déplace dans le dossier contenant nos projets

```bash
# A vous de vous déplacer dans le bon dossier
cd C:\xampp\htdocs
symfony new immobiliare
cd immobiliare
```

On peut maintenant lancer le projet

```bash
symfony serve
```

Pour la partie annonce, on aura un ```AnnonceController``` avec 5 routes :

- Page de liste des annonces -> /annonces
- Page pour voir une annonce (Au clique sur celle-ci) -> /annonces/1
- Page pour créer une annonce -> /annonces/creer
- Page pour modifier une annonce -> /annonces/modifier/1
- Page pour supprimer une annonce -> /annonces/supprimer/1

Pour la page d'accueil, on fait un controlleur à part ```AccueilController``` :

- Page d'accueil -> /

On installe les annotations et le maker :

```bash
composer require annotations
composer require symfony/maker-bundle --dev
composer require --dev symfony/debug-pack
```

On génére les controlleurs :

```bash
php bin/console make:controller AccueilController
php bin/console make:controller AnnonceController
```

Ensuite, on prépare toutes les routes dans ```AnnonceController```. On attaque ensuite la partie BDD. On va installer Doctrine :

```bash
composer require symfony/orm-pack
```

Pour rappel, si vous créez votre projet avec ```symfony new immobiliare --full```, les ```composer require``` sont "déjà faits".

Maintenant on attaque la base. On oublie pas le ```.env.local``` pour configurer la BDD :

```bash
DATABASE_URL=mysql://root:@127.0.0.1:3306/immobiliare?serverVersion=5.7
```

Je crée la BDD :

```bash
php bin/console doctrine:database:create
```

On crée l'entité Annonce :

```bash
php bin/console make:entity Annonce
```

On oublie pas de créer le schéma SQL (Faire le CREATE TABLE) :

```bash
# Pour debug, le dump
php bin/console doctrine:schema:update --dump-sql
# Pour exécuter la requête
php bin/console doctrine:schema:update --force
```
