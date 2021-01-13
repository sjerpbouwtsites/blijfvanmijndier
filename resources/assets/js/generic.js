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
