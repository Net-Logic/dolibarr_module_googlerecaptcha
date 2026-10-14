---
title: "Google reCAPTCHA v3 - unsichtbares Captcha für Dolibarr"
short: "Schützt die Anmeldeseite und die öffentlichen Formulare von Dolibarr mit dem unsichtbaren Google reCAPTCHA v3 vor Bots."
needs:
  - "Ein Google-Konto zum Erstellen der reCAPTCHA-v3-Schlüssel."
---
**Google reCAPTCHA v3** fügt Dolibarr ein unsichtbares Captcha hinzu: Google bewertet jeden Besucher mit einem Score, niemand muss ein Kästchen anklicken oder ein Rätsel lösen.

## Funktionen

- **Captcha-Handler, Dolibarr 21 und neuer**: schützt die Anmeldeseite, die Seite „Passwort vergessen“ und ab Dolibarr 23 die öffentlichen Formulare mit aktiviertem Captcha (Tickets, Mitglieder, Spenden, Rekrutierung...).
- **Prüfung bei der Anmeldung, alle Versionen einschließlich Dolibarr 20**: Eine Anmeldemethode prüft das reCAPTCHA-Token vor dem Passwort, sodass eine Anfrage ohne Token nichts über das Passwort verrät.
- **Mindest-Score**: einstellbar, standardmäßig 0,7.
- **Schlüsselprüfung**: Die Einstellungsseite prüft Ihre Schlüssel und zeigt den Score oder den von Google gelieferten Fehlercode an.

## So funktioniert es

1. Erstellen Sie Ihre reCAPTCHA-v3-Schlüssel auf https://www.google.com/recaptcha/admin.
2. Tragen Sie sie auf der Einstellungsseite des Moduls ein.
3. Aktivieren Sie das Captcha unter **Einstellungen → Sicherheit → Captcha** und wählen Sie den Handler *googlerecaptcha*.
