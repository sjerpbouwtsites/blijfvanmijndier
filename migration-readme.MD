# Migreren van staging naar productie in 253 eenvoudige stappen

Mendoo is leuk. Dit staat hier zodat niet het gehele document negatief is. Voila.

Bij acute paniek: Die server kan wel even wachten. Klanten moeten maar even wat anders doen.  
Mocht je een gigantische en/of onbegrijpelijke migratiemiskleun hebben: bel mij (sjerp) op 0616541143. Vlieg me niet in voor alledaagse miskleuntjes... als je dit leest ben ik weg bij Mendoo. Het mag, maar liever niet. Het ergste hebben wij, je voorgangers, reeds voor je geleden, tijdens de upgrades van php, mariadb, enzoverder. In THEORIE zou dit gewoon nu altijd goed moeten gaan! Tot dat iets veranderd, dan is iets in dit stappenplan achterhaald. Zoals euh, laravel.

Je gaat map voor map productie met staging overschrijven. Dit is het stappenplan. Er is een merge script voor staging, maar vanwege de ooit gekozen en nooit op dit vlak gecorrigeerde architectuur was een productie merge script me te complex en foutgevoelig voor wat het opleverde. Dit is ook waarom de *master* tak niet gebruikt wordt. Het idee was dev naar staging en master naar productie. Dit liep stuk op de public folder, zie onder, en hier niet de tijd in willen investeren. Dus... alles met de hand scripten. Doe dit werk als je potentieel urenlang ongestoord door wie dan ook je aandacht er bij kan houden. Ja, dit kan alleen snachts. Niet schrikken als de server omvalt! Dat gebeurd. Je shell wordt halverwege je stress onresponsief. Tijd wachten, mogelijk paar uur, mogelijk dan paniekniveau omhoog bijstellen. Klaar voor? Zin in? Dit wordt leuk! 🤪 

Er zitten hier vele stappen in die overbodig of omslachtig zijn. Zo noteer je de config uit een bestand dat niet gebruikt wordt... denk ik. Veel natte vinger werk.


## 0. Maak backups van staging, productie en beide databases in ~/backups. 
```bash
mkdir ~/backups/[DATUM];
cd ~/backups/[DATUM];
tar -czvf code.tar.gz ~/ifawnl ~/ifawnl_staging;
mysqldump -u phpmyadmin -p bvmd_staging > staging-dump.sql;
mysqldump -u phpmyadmin -p bvmd > productie.sql;
```


## 1. Haal de laatste code op met zwarte magie.
Dit zijn dingen die je op staande voet ontslagen zouden krijgen in een bedrijf. Kijk niet in me merge script plz 😘.
```bash
cd ~/ifawnl_staging; sudo bash merge.sh
```

## 2. Onderzoeken waarden en apart opslaan. Let op verschil staging en productie.
noteer deze waarden: 
config/app.php > development/productie
	       > APP_url
.env 	> APP_KEY
	> database, db usr, db pass

## 3. Alle topmappen uit productie weggooien BEHALVE public en vendor. 
Public want daar zijn afbeeldingen èn de pdfs worden hier opgeslagen. Dat is onhandig maar het is. Vendor weggooien heeft nachten ruzie met de server betekent tijdens eerdere migraties. Dat hoort niet. Dingen zijn raar. 

Dus 
```bash
cd ~/ifawnl; 
sudo rm -rf app bootstrap config database dummystartdb nbproject node_modules resources routes storage tests;
```

## 4. Alle topmappen behalve public en vendor vervangen.
Je vervangt ze met de staging variant. Dit is niet gekozen om efficient te zijn (node_modules bv) maar om begrijpelijk en controleerbaar te zijn.

```bash
cp -rp ~/ifawnl_staging/app ~/ifawnl/app; 
cp -rp ~/ifawnl_staging/bootstrap ~/ifawnl/bootstrap; 
cp -rp ~/ifawnl_staging/config ~/ifawnl/config; 
cp -rp ~/ifawnl_staging/database ~/ifawnl/database; 
cp -rp ~/ifawnl_staging/dummystartdb ~/ifawnl/dummystartdb; 
cp -rp ~/ifawnl_staging/nbproject ~/ifawnl/nbproject; 
cp -rp ~/ifawnl_staging/node_modules ~/ifawnl/node_modules; 
cp -rp ~/ifawnl_staging/resources ~/ifawnl/resources;  
cp -rp ~/ifawnl_staging/routes ~/ifawnl/routes; 
cp -rp ~/ifawnl_staging/storage ~/ifawnl/storage; 
cp -rp ~/ifawnl_staging/tests ~/ifawnl/tests;
```

## 5. specifieke dingen uit public weggooien. 
PAS ENORM OP! dus public/img bevat public/img/markers, die moet je vervangen... maar public/img bevat ook de dierenfoto's... alles is door elkaar. Het blijft verwarrend, je zal fouten maken. 
```bash 
cd ~/ifawnl/public; sudo rm -rf css font-awesome fonts img/markers js;
```
Hier staat ook los frut tussen, controleer met de hand of dat geupdate moet 💀 Wie plaatst hier nu het mendoo logo 🤭

## 6. Specifieke dingen uit public vervangen. 
👉PAS OPNIEUW OP!👈 🙏 

```bash 
cp -rp ~/ifawnl_staging/public/css ~/ifawnl/public/css;
cp -rp ~/ifawnl_staging/public/font-awesome ~/ifawnl/public/font-awesome;
cp -rp ~/ifawnl_staging/public/fonts ~/ifawnl/public/fonts; 
cp -rp ~/ifawnl_staging/public/img/markers ~/ifawnl/public/img/markers;
cp -rp ~/ifawnl_staging/public/js ~/ifawnl/public/js;
```

## 7. Database migraties. 

Laravel heeft van die coole migraties. Maar wij zijn cooler en doen monkeypatches op de DB 🙇‍🔫. Je vind ze onder 'database', hier staan door elkaar varia migraties. Ik hoop voor je dat je geen grote livegang hebt want dan ben je waarschijnlijk vergeten welke migraties nu net hier bij hoorden. Kijk maar vooral naar de data. Tip: gebruik wel laravels migraties.

## 8. Pielen aan configs
Als je niet de relevante configs over hebt getikt, dan mag je gaan graven in je backups. 
Deze waarden moet je controleren / corrigeren
config/app.php > development/productie
	       > APP_url
.env 	> APP_KEY
	> database, db usr, db pass

## 9. Gulp
Waar het gebeurd is, geen idee, maar je bent CSS kwijt. Die zou toch meer gekopieerd moeten zijn? Nou ja.
Gulp production is defect en wordt niet gebruikt. Draai gulp watch en ctrl-c
```bash
cd ~/ifawnl; gulp watch; 
```

## EINDCHECK
1. controleer of dierenfoto's er nog zijn.
2. controleer of de document-pdfs nog functioneren.
3. zoek en god en bid er naar.
4. Zo ben jij blij met die backups no way dat je dat in een keer goed krijgt ✌
5. Doet ie het nog niet? Shit. Misschien is je app-key naar z'n grootje. 
```bash
cd ~/ifawnl; 
php artisan key:generate;
``` 
6. Heb je niet per ongeluk gekopieerd en eigenaar naar ubuntu gezet, evt. her en der? Of rechten veranderd?
7. Kijk... echte programmeurs... kopieeren gewoon eindeloos foutcodes naar google en doen maar wat. Good luck!
sjerp: 0616541143 

### Hoe goed is deze lijst nu echt?
Deze lijst is zo goed, vind ook de schrijver van dit stuk. 
Hij kijkt momenteel evenwel tegen een productie aan die niet is wat hij verwachtte. Totaal niet.
De twijfel slaat toe.
Enige minuten later is het eea. bijgetrokken. 
Kusje er op en gaan!