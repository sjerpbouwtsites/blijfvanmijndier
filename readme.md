## Blijf van mijn dier @ Sjerpbouwtsites

Dit is de versie werkend bij sjerpbouwtsites draaiend met:

- WSL 2
- Ubuntu 18.04
- MySQL 8.0.21
- PHP 7.4.3

### aanpassing in laravel

Vanwege een mysql engine conflict is de instelling sql_mode "NO_AUTO_CREATE_USER" verwijderd uit de mysql pdo settings.
Dit is de default vanaf 5.7.x oid en is niet langer instelbaar.
Zoek in laravel op "NO_AUTO_CREATE_USER".
