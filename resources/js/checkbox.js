export function handleCheckboxClick(clickedCheckbox) {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    console.log(checkboxes);
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    clickedCheckbox.checked = true;
}