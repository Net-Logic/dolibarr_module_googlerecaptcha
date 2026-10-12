---
title: Google reCAPTCHA v3 para Dolibarr
short: Proteja la página de inicio de sesión y los formularios públicos de Dolibarr contra bots con el reCAPTCHA v3 invisible de Google.
---
**Google reCAPTCHA v3** añade a Dolibarr un captcha invisible: Google asigna una puntuación a cada visitante, nadie tiene que marcar una casilla ni resolver un acertijo.

## Funciones

- **Dolibarr 21 y posteriores**: un *gestor de captcha* protege la página de inicio de sesión, la de contraseña olvidada y, desde Dolibarr 23, los formularios públicos con el captcha activado (tickets, socios, donaciones, reclutamiento...).
- **Todas las versiones, incluida Dolibarr 20**: un método de inicio de sesión comprueba el token reCAPTCHA antes de la contraseña, así una petición sin token no revela nada sobre la contraseña.
- **Puntuación mínima** configurable (0,7 por defecto).
- La página de configuración comprueba sus claves y muestra la puntuación o el código de error devuelto por Google.

## Instalación

Despliegue el zip desde **Inicio → Configuración → Módulos → Desplegar un módulo externo** y active el módulo. Cree sus claves en https://www.google.com/recaptcha/admin (reCAPTCHA v3), introdúzcalas en la página de configuración del módulo, active el captcha en **Configuración → Seguridad → Captcha** y elija el gestor *googlerecaptcha*.

Compatible con Dolibarr 20 a 24 (y 25) y PHP 7.4 a 8.5. Se necesita una cuenta de Google para crear las claves.
