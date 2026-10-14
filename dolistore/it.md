---
title: "Google reCAPTCHA v3 - captcha invisibile per Dolibarr"
short: "Proteggi la pagina di accesso e i moduli pubblici di Dolibarr dai bot con il reCAPTCHA v3 invisibile di Google."
needs:
  - "Un account Google per creare le chiavi reCAPTCHA v3."
---
**Google reCAPTCHA v3** aggiunge a Dolibarr un captcha invisibile: Google assegna un punteggio a ogni visitatore, nessuno deve spuntare una casella o risolvere un enigma.

## Funzionalità

- **Gestore captcha, Dolibarr 21 e successivi**: protegge la pagina di accesso, la pagina di password dimenticata e, da Dolibarr 23, i moduli pubblici in cui il captcha è attivo (ticket, soci, donazioni, selezione del personale...).
- **Controllo all'accesso, tutte le versioni compresa Dolibarr 20**: un metodo di accesso verifica il token reCAPTCHA prima della password, così una richiesta senza token non rivela nulla sulla password.
- **Punteggio minimo**: configurabile, 0,7 per impostazione predefinita.
- **Verifica delle chiavi**: la pagina di configurazione verifica le chiavi e mostra il punteggio o il codice di errore restituito da Google.

## Come funziona

1. Crea le chiavi reCAPTCHA v3 su https://www.google.com/recaptcha/admin.
2. Inseriscile nella pagina di configurazione del modulo.
3. Attiva il captcha in **Impostazioni → Sicurezza → Captcha** e scegli il gestore *googlerecaptcha*.
