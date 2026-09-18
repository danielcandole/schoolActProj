const form = document.querySelector("#gradeForm");
const gradeInputs = [...document.querySelectorAll('input[type="number"]')];
const averageOutput = document.querySelector("#liveAverage");

function updateAverage() {
  let total = 0;

  for (let i = 0; i < gradeInputs.length; i++) {
    const value = gradeInputs[i].value.trim();

    if (value === "" || value < 0 || value > 100) {
      averageOutput.innerHTML = '<small> / 100</small>';
      return;
    }

    total += Number(value);
  }

  const average = total / gradeInputs.length;
  averageOutput.innerHTML = `${average.toFixed(2)}<small> / 100</small>`;
}

function handleReset() {
  setTimeout(updateAverage, 0);
}

function handleSubmit(event) {
  for (let i = 0; i < gradeInputs.length; i++) {
    const input = gradeInputs[i];
    const value = input.value;

    if (value === "" || Number(value) < 0 || Number(value) > 100) {
      event.preventDefault();
      input.focus();
      input.reportValidity();
      return;
    }
  }
}

for (let i = 0; i < gradeInputs.length; i++) {
  gradeInputs[i].addEventListener("input", updateAverage);
}

if (form) {
  form.addEventListener("reset", handleReset);
  form.addEventListener("submit", handleSubmit);
}

updateAverage();