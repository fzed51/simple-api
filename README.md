# simple-api

Simple-api permet de construire une API simplement avec un fichier de configuration minimal.

## TODO

- [X] Mise en place du socle Slimv4
- [X] Mise en place d'une Entité avec API key
- [X] Création d'un utilisateur non actif
- [ ] Activation d'un utilisateur
- [ ] Connexion d'un utilisateur
- [ ] Modification d'un utilisateur
- [ ] Demande de modification du mot de passe avec envoie d'e-mail et redirection
- [ ] Modification du mot de passe
- [ ] Gestion des données suplémentaire pour un utilisateur et paramétrage
- [ ] Création des ressources et paramétrage

## Usage

### Installation

Installation à partir de composer

```shell
composer create-project fzed51/simple-api path-to-my-api
```

Installation à partir de github

```shell
git clone https://github.com/fzed51/simple-api.git path-to-my-api
cd path-to-my-api
composer install
```

Après avoir installer les source, créer un fichier de configuration dans le dossier config

### Fichier de configuration

Schema :

```json
[
  {
    "uuid": "b80ad7f1-7136-4e27-b5f0-beb6374f2477",
    "title": "nom de mon api",
    "ressources": []
  }
]
```

## Fichier de configuration

Le fichier de configuration est au format json. C'est un tableau d'entité.

### Une entité

Une entité est un objet json composé de :

- une `uid` : chaine unique permettant de différencier 2 entité
- un `title` : nom synthétique de l'entité (10 caractères max)
- des `ressources` : tableau de ressources

Exemple :

```json
{
  "uuid": "b80ad7f1-7136-4e27-b5f0-beb6374f2477",
  "title": "nom_entite",
  "ressources": []
}
```

Elle correspond à une application, à une base de donnée.

### Une ressource

Une ressource est un objet json composé de :

- un `name` : chaine identifant la ressource. Il est unique dans l'entité et contient uniquement des caractères ascii sans espaces.
- des `fields` : un tableau de chaine identifiant les champs de la ressource.

Exemple :

```json
{
  "name" : "ressource",
  "fields" : ["champ1", "champ2"]
}
```

Elle correspond à une table de donnée.

### configuration d'une entité

### configuration des user

### configuration des ressources
