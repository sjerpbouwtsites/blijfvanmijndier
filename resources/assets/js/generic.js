// unrelated to the map.

document.querySelectorAll('input[type="number"]').forEach((numberInput) => {
  numberInput.addEventListener("keyup", noLettersInNumbers);
  numberInput.addEventListener("change", noLettersInNumbers);
});

function noLettersInNumbers(event) {
  // the french call this
  // le approach fuck you
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


document.querySelectorAll('.kopieer-adres-knop').forEach(knop => {
  knop.addEventListener('click', (e)=>{
    let scrollPositionY = Number(window.pageYOffset);
    let scrollPositionX = Number(window.pageXOffset);
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
  

   window.scroll(scrollPositionX, scrollPositionY);


   return false;
   
  });
});



function copyToClipboard(text) {
var input = document.body.appendChild(document.createElement("input"));
input.value = text;
input.focus();
input.select();
document.execCommand('copy');
input.parentNode.removeChild(input);
}