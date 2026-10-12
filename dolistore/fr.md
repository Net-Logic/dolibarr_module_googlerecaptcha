---
title: Google reCAPTCHA v3 pour Dolibarr
short: Protégez la page de connexion et les formulaires publics de Dolibarr contre les robots avec le reCAPTCHA v3 invisible de Google.
---
**Google reCAPTCHA v3** ajoute un captcha invisible à Dolibarr : Google attribue un score à chaque visiteur, personne n'a de case à cocher ni d'énigme à résoudre.

## Fonctionnalités

- **Dolibarr 21 et suivants** : un *gestionnaire de captcha* protège la page de connexion, la page de mot de passe oublié et, à partir de Dolibarr 23, les formulaires publics où le captcha est activé (tickets, adhérents, dons, recrutement...).
- **Toutes les versions, dont Dolibarr 20** : une méthode de connexion qui vérifie le jeton reCAPTCHA avant le mot de passe, ainsi une requête sans jeton n'apprend rien sur le mot de passe.
- **Score minimum** configurable (0,7 par défaut).
- La page de configuration vérifie vos clés et affiche le score ou le code d'erreur renvoyé par Google.

## Installation

Déployez le zip avec **Accueil → Configuration → Modules → Déployer un module externe** et activez le module. Créez vos clés sur https://www.google.com/recaptcha/admin (reCAPTCHA v3), saisissez-les dans la page de configuration du module, puis activez le captcha dans **Configuration → Sécurité → Captcha** et choisissez le gestionnaire *googlerecaptcha*.

Compatible avec Dolibarr 20 à 24 (et 25) et PHP 7.4 à 8.5. Un compte Google est nécessaire pour créer les clés.
