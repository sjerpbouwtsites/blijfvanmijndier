// unrelated to the map.

document.querySelectorAll('input[type="number"]').forEach((numberInput) => {
  numberInput.addEventListener("keyup", noLettersInNumbers);
  numberInput.addEventListener("change", noLettersInNumbers);
});

function noLettersInNumbers(event) {
  event.target.value = Number(event.target.value);
}

if(location.port.includes('80')){ // dev 8000, staging 8080

    document.body.setAttribute('data-locatie', 
    location.port === '8000' ? 'development' : 'staging'
    );
  document.getElementById('marquee-holder').innerHTML = `<marquee class='staging-marquee' direction="left" >
  Je bent op de staging.
</marquee>`
} else {
  document.body.setAttribute('data-locatie', 'live');
}

switch (location.port) {
  case "8000":
    break;
  case "8080":
    
    break;
  default:
}

const dontScrollOnKlik = {
  _x: null,
  _y: null,
  init (){
    this._x = Number(window.pageXOffset);
    this._y = Number(window.pageYOffset);
    
  },
  backToEarlierScroll(){
    if (this._x === null || this._y === null) {
      throw Error('scroll klik prevent data not saved / unretrieveable');
    }
    window.scroll(this._x, this._y);

  }
}

document.querySelectorAll('.kopieer-adres-knop').forEach(knop => {
  knop.addEventListener('click', (e)=>{
    dontScrollOnKlik.init();
    e.preventDefault();
    e.stopPropagation();
    const target = e.target;
    
    const knop = target.className.includes('kopieer-adres-knop-ikoon') 
      ? target.parentNode 
      : target;
  
   const teKopieeren = `${knop.dataset.street} ${knop.dataset.house_number} \n${knop.dataset.postal_code} ${knop.dataset.city}`;
    
   copyToClipboard(teKopieeren)
   knop.classList.add('enable-button-blink');
   setTimeout(()=>{
       knop.classList.add('button-blink');
   }, 50)            
   setTimeout(()=>{
       knop.classList.remove('button-blink');
   }, 1000)    
  

   dontScrollOnKlik.backToEarlierScroll();

   return false;
   
  });
});


if (document.getElementById('animal-grid')) {
  document.getElementById('animal-grid').addEventListener('click', (e)=>{

    if (e.target.className.includes('fa')) {
        dontScrollOnKlik.init();
        e.preventDefault();
        e.stopPropagation();
    }

  
    let knop;
    if (e.target.classList.contains('fake-anchor')) {
        knop = e.target;
    } else if (e.target.parentNode.classList.contains('fake-anchor')) {
        knop = e.target.parentNode;
    } else {
        return
    }
    
  
   const r = knop.getAttribute('data-href');
  
   if (knop.classList.contains('clipboard')) {
    copyToClipboard(r)
    knop.classList.add('enable-button-blink');
    setTimeout(()=>{
        knop.classList.add('button-blink');
    }, 50)            
    setTimeout(()=>{
        knop.classList.remove('button-blink');
    }, 1000)    
  
   } else {
       location.href = r;
   }
   dontScrollOnKlik.backToEarlierScroll();
   return false;
   
  });
}


function copyToClipboard(text) {
var input = document.body.appendChild(document.createElement("input"));
input.value = text;
input.focus();
input.select();
document.execCommand('copy');
input.parentNode.removeChild(input);
}