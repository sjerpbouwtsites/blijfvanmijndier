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