const texts = {
  dieren: {
    subtitle: "Scroll door de dierenlijst, filter met het zoekveld, vind dieren en hun relaties",
    title:
      "Dieren direct zoeken",
    body: `<p class='bvmd-popup__paragraph'>Boven in de zijbalk vind je de diereninterface. Alle dieren in het project staan in licht getinte lijst. Het zijn er meer dan kunnen passen dus wellicht moet je even scrollen voor je de jouwe tegenkomt.<br>Dik gedrukt zie je de dierennamen, met daaronder de naam van de eigenaar en daaronder weer de huidige verblijfplaats. Klik op &eacute;&eacute;n van deze links om de kaart te activeren en de bijbehorende info in de kaart te zien.<br><br><strong>Geen zin om te scrollen?</strong> <br>Gebruik het zoekvenster. Tijdens het tikken wordt gezocht op je zoekterm tussen deze gegevens van de dieren:</p>
    <ol class='bvmd-popup__list bvmd-popup__list--ordered'>
    <li class='bvmd-popup__list-item bvmd-popup__list-item--ordered'>Naam dier; </li>
    <li class='bvmd-popup__list-item bvmd-popup__list-item--ordered'>naam eigenaar; </li>
    <li class='bvmd-popup__list-item bvmd-popup__list-item--ordered'>naam verblijfplaats; </li>
    <li class='bvmd-popup__list-item bvmd-popup__list-item--ordered'>diertype of </li>
    <li class='bvmd-popup__list-item bvmd-popup__list-item--ordered'>diersoort</li>
  </ol>`,
  },
  filter: {
    subtitle: "Maak een selectie naar wil",
    title: "Filter markers op de kaart",
    body: `
    <h4 class="bvmd-popup__header-title bvmd-popup__header-title--small">
      <span class="bvmd-popup__header-title-inner bvmd-popup__header-title-inner--small">Type filter: grofste filterwerk.</span>
    </h4>
    <p class='bvmd-popup__paragraph'>Met de type filter kan je verschillende soorte locaties / personen in zijn geheel verstoppen of tonen.</p>

    <h4 class="bvmd-popup__header-title bvmd-popup__header-title--small">
      <span class="bvmd-popup__header-title-inner bvmd-popup__header-title-inner--small">De filters werken samen.</span>
    </h4>
    <p class='bvmd-popup__paragraph'>
    Indien je alleen w&egrave;l de pensions wilt zien met m&eacute;&eacute;r dan 1 dier, combineer je de 'aantal opgevangen dieren' en 'type' filter. Klikvolgorde maakt niet uit.</p>

    <h4 class="bvmd-popup__header-title bvmd-popup__header-title--small">
      <span class="bvmd-popup__header-title-inner bvmd-popup__header-title-inner--small">De filters be&iuml;nvloeden elkaar.</span>
    </h4>
    <p class='bvmd-popup__paragraph'>    
    Een dierenarts vangt nooit een dier op. Als je de dierenartsen aan zet, springt het 'aantal dieren opgevangen' filter naar 'negeer'. Omgekeerd, als je dierenartsen aan heb staan, maar klikt op 'een of meer dieren' bij 'aantal dieren opgevangen', dan springt dierenartsen als optie uit.</p>
    
    <h4 class="bvmd-popup__header-title bvmd-popup__header-title--small">
      <span class="bvmd-popup__header-title-inner bvmd-popup__header-title-inner--small">Multifilters voor diervoorkeur, gedrag, woonstijl</span>
    </h4>
    <p class='bvmd-popup__paragraph'>    
      Filter de gastgezinnen op wat voor willekeurige combinatie aan meerdere mogelijkheden dan ook. 
    </p>  

    
    `,
  },
  algemeen: {
    subtitle: "Alles is gerelateerd",
    title: "De Maya kaart",
    body: `<p class='bvmd-popup__paragraph'><strong>Welkom!</strong></br>Door de app heen vind je paarse rondjes met een i. Klik hierop voor uitleg bij dat onderdeel. Kom je er nog niet uit of zie je een fout, stuur dan een bericht naar Renilde of je coordinator zodat dit doorgezet kan worden.</p>
    <p class='bvmd-popup__paragraph'><strong>Boeien</strong></br>Voor je staat een kaart van Nederland met daarop boeien. Die boeien refereren allen naar clienten, vrijwilligers of partners van Mendoo: </p>
    <table class='bvmd-popup__table'>
      <tr class='bvmd-popup__table-row'>
        <th class='bvmd-popup__table-head-cell'>Locatie</th>
        <th class='bvmd-popup__table-head-cell'>Dieren aanwezig</th>
        <th class='bvmd-popup__table-head-cell'>Geen dieren aanwezig</th>
      </tr>
      <tr class='bvmd-popup__table-row'>
        <td class='bvmd-popup__table-cell'>Gastgezezin</td>
        <td class='bvmd-popup__table-cell'>groen</td>
        <td class='bvmd-popup__table-cell'>groenblauw</td>
      </tr>
      <tr class='bvmd-popup__table-row'>
        <td class='bvmd-popup__table-cell'>Eigenaar</td>
        <td class='bvmd-popup__table-cell'>diepblauw (fel)</td>
        <td class='bvmd-popup__table-cell'>diepblauw</td>
      </tr>
      <tr class='bvmd-popup__table-row'>
        <td class='bvmd-popup__table-cell'>Pension</td>
        <td class='bvmd-popup__table-cell'>magenta (fel)</td>
        <td class='bvmd-popup__table-cell'>magenta</td>
      </tr>
      <tr class='bvmd-popup__table-row'>
        <td class='bvmd-popup__table-cell'>Opvang</td>
        <td class='bvmd-popup__table-cell'>lichtblauw (fel)</td>
        <td class='bvmd-popup__table-cell'>lichtblauw</td>
      </tr>
      <tr class='bvmd-popup__table-row'>
        <td class='bvmd-popup__table-cell'>Dierenarts</td>
        <td class='bvmd-popup__table-cell'>rood</td>
        <td class='bvmd-popup__table-cell'>rood</td>
      </tr>                        
    </table>
    <p class='bvmd-popup__paragraph'><strong>Popups</strong></br>De app werkt met veel popups. Die sluiten zodra je een nieuwe opent, wanneer je op het kruisje of ergens buiten de popup drukt of met de escape toets. Klik op een boeien en zie hoe eigenaren, pensions en dieren aan elkaar relateren. Ga direct naar de Maya bewerk pagina. Of gebruik de instant-zoekfunctie rechtsboven (zie de i). Rechts filter je ook de boeien.</p>

    `,
  },  
};

module.exports = texts;
