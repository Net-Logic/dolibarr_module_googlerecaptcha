---
title: Google reCAPTCHA v3 per Dolibarr
short: Proteggi la pagina di accesso e i moduli pubblici di Dolibarr dai bot con il reCAPTCHA v3 invisibile di Google.
---
**Google reCAPTCHA v3** aggiunge a Dolibarr un captcha invisibile: Google assegna un punteggio a ogni visitatore, nessuno deve spuntare una casella o risolvere un enigma.

## Funzionalità

- **Dolibarr 21 e successivi**: un *gestore captcha* protegge la pagina di accesso, la pagina di password dimenticata e, da Dolibarr 23, i moduli pubblici in cui il captcha è attivo (ticket, soci, donazioni, selezione del personale...).
- **Tutte le versioni, compresa Dolibarr 20**: un metodo di accesso verifica il token reCAPTCHA prima della password, così una richiesta senza token non rivela nulla sulla password.
- **Punteggio minimo** configurabile (0,7 per impostazione predefinita).
- La pagina di configurazione verifica le chiavi e mostra il punteggio o il codice di errore restituito da Google.

## Installazione

Installa lo zip da **Home → Impostazioni → Moduli → Installa un modulo esterno** e attiva il modulo. Crea le chiavi su https://www.google.com/recaptcha/admin (reCAPTCHA v3), inseriscile nella pagina di configurazione del modulo, poi attiva il captcha in **Impostazioni → Sicurezza → Captcha** e scegli il gestore *googlerecaptcha*.

Compatibile con Dolibarr da 20 a 24 (e 25) e PHP da 7.4 a 8.5. Serve un account Google per creare le chiavi.
