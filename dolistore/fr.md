---
title: "Google reCAPTCHA v3 - captcha invisible pour Dolibarr"
short: "Protégez la connexion et les formulaires publics de Dolibarr contre les robots avec le reCAPTCHA v3 invisible de Google."
needs:
  - "Un compte Google pour créer les clés reCAPTCHA v3."
---
**Google reCAPTCHA v3** ajoute un captcha invisible à Dolibarr : Google attribue un score à chaque visiteur, personne n'a de case à cocher ni d'énigme à résoudre.

## Fonctionnalités

- **Gestionnaire de captcha, Dolibarr 21 et suivants** : il protège la page de connexion, la page de mot de passe oublié et, à partir de Dolibarr 23, les formulaires publics où le captcha est activé (tickets, adhérents, dons, recrutement...).
- **Contrôle à la connexion, toutes versions dont Dolibarr 20** : une méthode de connexion vérifie le jeton reCAPTCHA avant le mot de passe, ainsi une requête sans jeton n'apprend rien sur le mot de passe.
- **Score minimum** : configurable, 0,7 par défaut.
- **Vérification des clés** : la page de configuration vérifie vos clés et affiche le score ou le code d'erreur renvoyé par Google.

## Fonctionnement

1. Créez vos clés reCAPTCHA v3 sur https://www.google.com/recaptcha/admin.
2. Saisissez-les dans la page de configuration du module.
3. Activez le captcha dans **Configuration → Sécurité → Captcha** et choisissez le gestionnaire *googlerecaptcha*.
