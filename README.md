# Projet SpeakEasy

Bienvenue sur le projet SpeakEasy, une plateforme complète conçue pour améliorer la communication et l'interaction sociale au sein des communautés, des groupes et des individus. Ce document fournit un aperçu du projet, y compris ses fonctionnalités, le guide d'installation, les instructions d'utilisation et les informations d'accès.

## Vue d'ensemble

SpeakEasy est une application web qui facilite la communication sans couture à travers des interactions textuelles, vocales et vidéo. Elle offre une variété de fonctionnalités visant à fournir aux utilisateurs une expérience riche et engageante. Que vous cherchiez à vous connecter avec des amis, à rejoindre des groupes communautaires ou à participer à des discussions, SpeakEasy offre une plateforme polyvalente pour répondre à vos besoins.

## Fonctionnalités

- **Profils Utilisateurs** : Personnalisez votre profil personnel avec une bio, une photo de profil et plus encore.
- **Système d'amis** : Ajoutez d'autres utilisateurs comme amis pour rester facilement en contact.
- **Groupes** : Rejoignez ou créez des groupes pour discuter de sujets ou d'intérêts spécifiques.
- **Canaux au sein des groupes** : Organisez des conversations avec des canaux spécifiques au sein des groupes.
- **Messagerie** : Envoyez et recevez des messages en temps réel avec des amis ou au sein de canaux de groupe.
- **Appels vocaux et vidéo** : Connectez-vous avec des amis et des groupes par des appels vocaux et vidéo.
- **Paramètres de confidentialité** : Contrôlez qui peut voir votre profil et vos publications avec des paramètres de confidentialité complets.

## Accès en Ligne

SpeakEasy est accessible en ligne à l'adresse suivante : [http://speakeasy.go.yj.fr/](http://speakeasy.go.yj.fr/). Connectez-vous dès maintenant pour explorer les fonctionnalités et commencer à interagir avec d'autres utilisateurs.

## Guide d'installation

### Prérequis

- PHP 7.4 ou supérieur
- Base de données MySQL
- Serveur Web (Apache/Nginx)

### Étapes

1. **Cloner le dépôt** : Clonez le dépôt du projet SpeakEasy sur votre machine locale ou serveur.

    `git clone https://github.com/VotreNomUtilisateur/SpeakEasy.git`
    
2. **Configuration de la base de données** : Importez le fichier `speakeasy.sql` dans votre base de données MySQL pour configurer les tables nécessaires.
3. **Configuration** : Naviguez vers le répertoire `includes` et mettez à jour le fichier `inc-db-connect.php` avec les détails de connexion à votre base de données.
4. **Configuration du serveur Web** : Configurez votre serveur web pour pointer vers le répertoire racine du projet.
5. **Dépendances** : Installez les dépendances PHP requises en utilisant Composer.
    
    `composer install`
    
6. **Permissions** : Assurez-vous que le répertoire `assets/pictures/userPictures/` est accessible en écriture par le serveur web.

## Instructions d'utilisation

- **Accéder à SpeakEasy** : Ouvrez votre navigateur web et naviguez vers l'URL où SpeakEasy est hébergé.
- **Inscription et connexion** : Commencez par vous inscrire à un compte et vous connecter.
- **Personnalisation du profil** : Éditez votre profil pour ajouter une touche personnelle.
- **Établir des connexions** : Recherchez d'autres utilisateurs à ajouter comme amis ou rejoignez des groupes existants correspondant à vos intérêts.
- **Communication** : Commencez à envoyer des messages, à passer des appels ou à participer à des discussions de groupe.

## Contribution

SpeakEasy est un projet open-source, et les contributions sont les bienvenues. N'hésitez pas à forker le dépôt, à apporter vos modifications et à soumettre une pull request.

## Support

Si vous rencontrez des problèmes ou avez des questions, veuillez ouvrir un problème sur le dépôt GitHub.