---
title: "Google reCAPTCHA v3 - captcha invisible para Dolibarr"
short: "Proteja el inicio de sesión y los formularios públicos de Dolibarr contra bots con el reCAPTCHA v3 invisible de Google."
needs:
  - "Una cuenta de Google para crear las claves reCAPTCHA v3."
---
**Google reCAPTCHA v3** añade a Dolibarr un captcha invisible: Google asigna una puntuación a cada visitante, nadie tiene que marcar una casilla ni resolver un acertijo.

## Funcionalidades

- **Gestor de captcha, Dolibarr 21 y posteriores**: protege la página de inicio de sesión, la de contraseña olvidada y, desde Dolibarr 23, los formularios públicos con el captcha activado (tickets, socios, donaciones, reclutamiento...).
- **Control al iniciar sesión, todas las versiones incluida Dolibarr 20**: un método de inicio de sesión comprueba el token reCAPTCHA antes de la contraseña, así una petición sin token no revela nada sobre la contraseña.
- **Puntuación mínima**: configurable, 0,7 por defecto.
- **Comprobación de las claves**: la página de configuración comprueba sus claves y muestra la puntuación o el código de error devuelto por Google.

## Cómo funciona

1. Cree sus claves reCAPTCHA v3 en https://www.google.com/recaptcha/admin.
2. Introdúzcalas en la página de configuración del módulo.
3. Active el captcha en **Configuración → Seguridad → Captcha** y elija el gestor *googlerecaptcha*.
