---
title: Google reCAPTCHA v3 for Dolibarr
short: Protect the login page and the public forms of Dolibarr against bots with the invisible Google reCAPTCHA v3.
---
**Google reCAPTCHA v3** adds an invisible captcha to Dolibarr: Google gives each visitor a score, so nobody has to tick a box or solve a puzzle.

## What it does

- **Dolibarr 21 and later**: a *captcha handler* protects the login page, the password forgotten page and, from Dolibarr 23, the public forms where the captcha is enabled (tickets, members, donations, recruitment...).
- **All versions, including Dolibarr 20**: a login method that checks the reCAPTCHA token before the password, so a request without token learns nothing about the password.
- Configurable **minimum score** (0.7 by default).
- The setup page checks your keys and shows the score or the error code returned by Google.

## Installation

Deploy the zip with **Home → Setup → Modules → Deploy an external module** and enable the module. Create your keys on https://www.google.com/recaptcha/admin (reCAPTCHA v3), enter them in the module setup page, then enable the captcha in **Setup → Security → Captcha** and choose the handler *googlerecaptcha*.

Compatible with Dolibarr 20 to 24 (and 25) and PHP 7.4 to 8.5. A Google account is required to create the keys.
