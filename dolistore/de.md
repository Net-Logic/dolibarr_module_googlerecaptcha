---
title: Google reCAPTCHA v3 für Dolibarr
short: Schützt die Anmeldeseite und die öffentlichen Formulare von Dolibarr mit dem unsichtbaren Google reCAPTCHA v3 vor Bots.
---
**Google reCAPTCHA v3** fügt Dolibarr ein unsichtbares Captcha hinzu: Google bewertet jeden Besucher mit einem Score, niemand muss ein Kästchen anklicken oder ein Rätsel lösen.

## Funktionen

- **Dolibarr 21 und neuer**: Ein *Captcha-Handler* schützt die Anmeldeseite, die Seite „Passwort vergessen“ und ab Dolibarr 23 die öffentlichen Formulare mit aktiviertem Captcha (Tickets, Mitglieder, Spenden, Rekrutierung...).
- **Alle Versionen, auch Dolibarr 20**: Eine Anmeldemethode prüft das reCAPTCHA-Token vor dem Passwort, sodass eine Anfrage ohne Token nichts über das Passwort verrät.
- Einstellbarer **Mindest-Score** (standardmäßig 0,7).
- Die Einstellungsseite prüft Ihre Schlüssel und zeigt den Score oder den von Google gelieferten Fehlercode an.

## Installation

Installieren Sie die ZIP-Datei unter **Start → Einstellungen → Module → Externes Modul installieren** und aktivieren Sie das Modul. Erstellen Sie Ihre Schlüssel auf https://www.google.com/recaptcha/admin (reCAPTCHA v3), tragen Sie sie auf der Einstellungsseite des Moduls ein, aktivieren Sie dann das Captcha unter **Einstellungen → Sicherheit → Captcha** und wählen Sie den Handler *googlerecaptcha*.

Kompatibel mit Dolibarr 20 bis 24 (und 25) und PHP 7.4 bis 8.5. Für die Schlüssel ist ein Google-Konto erforderlich.
