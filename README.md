# simple-api

Simple-api permet de construire une API simplement avec un fichier de configuration minimal.

## TODO

- [X] Mise en place du socle Slimv4
- [ ] Mise en place d'une Entité avec API key
- [ ] Création d'un utilisateur non actif
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
    "uuid" : "b80ad7f1-7136-4e27-b5f0-beb6374f2477",
    "title" : "nom de mon api",
    "ressources" : []
  }
]
```

## Fichier de configuration

### configuration d'une entité

### configuration des user

### configuration des ressources
