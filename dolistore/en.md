---
title: "Google reCAPTCHA v3 - invisible captcha for Dolibarr"
short: "Protect the login page and the public forms of Dolibarr against bots with the invisible Google reCAPTCHA v3."
needs:
  - "A Google account to create the reCAPTCHA v3 keys."
---
**Google reCAPTCHA v3** adds an invisible captcha to Dolibarr: Google gives each visitor a score, so nobody has to tick a box or solve a puzzle.

## Features

- **Captcha handler, Dolibarr 21 and later**: protects the login page, the password forgotten page and, from Dolibarr 23, the public forms where the captcha is enabled (tickets, members, donations, recruitment...).
- **Login check, every version including Dolibarr 20**: a login method checks the reCAPTCHA token before the password, so a request without token learns nothing about the password.
- **Minimum score**: configurable, 0.7 by default.
- **Key check**: the setup page checks your keys and shows the score or the error code returned by Google.

## How it works

1. Create your reCAPTCHA v3 keys on https://www.google.com/recaptcha/admin.
2. Enter them in the module setup page.
3. Enable the captcha in **Setup → Security → Captcha** and choose the handler *googlerecaptcha*.
