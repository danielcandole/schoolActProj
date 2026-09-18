const form = document.querySelector("#gradeForm");
const gradeInputs = [...document.querySelectorAll('input[type="number"]')];
const averageOutput = document.querySelector("#liveAverage");

function updateAverage() {
    const values = gradeInputs.map((input) => input.value.trim());
    const allFilled = values.every((value) => value !== "");
    const valid = values.every((value) => Number.isFinite(Number(value)) && Number(value) >= 0 && Number(value) <= 100);

    if (!allFilled || !valid) {
        averageOutput.innerHTML = '—<small> / 100</small>';
        return;
    }

    const average = values.reduce((sum, value) => sum + Number(value), 0) / values.length;
    averageOutput.innerHTML = `${average.toFixed(2)}<small> / 100</small>`;
}

gradeInputs.forEach((input) => input.addEventListener("input", updateAverage));

form?.addEventListener("reset", () => {
    window.setTimeout(updateAverage, 0);
});

form?.addEventListener("submit", (event) => {
    const invalidGrade = gradeInputs.find((input) => input.value === "" || Number(input.value) < 0 || Number(input.value) > 100);
    if (invalidGrade) {
        event.preventDefault();
        invalidGrade.focus();
        invalidGrade.reportValidity();
    }
});

updateAverage();
