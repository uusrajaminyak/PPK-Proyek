document.addEventListener('click', (event) => {
    const openButton = event.target.closest('[data-open-rejection-dialog]');
    if (openButton) {
        const dialog = document.querySelector('[data-rejection-dialog]');
        dialog?.showModal();
        dialog?.querySelector('textarea')?.focus();
    }

    if (event.target.closest('[data-close-rejection-dialog]')) {
        document.querySelector('[data-rejection-dialog]')?.close();
    }
});

const textFieldSelector = [
    'input[type="text"]',
    'input[type="search"]',
    'input[type="email"]',
    'input[type="tel"]',
    'input[type="url"]',
    'input[type="password"]',
    'input[type="date"]',
    'input[type="number"]',
    'input[type="month"]',
    'input[type="time"]',
    'select',
    'textarea',
].join(', ');

const syncTextFieldState = (field) => {
    field.classList.toggle('has-value', field.value.trim().length > 0);
};

document.querySelectorAll(textFieldSelector).forEach(syncTextFieldState);
document.addEventListener('input', (event) => {
    if (event.target.matches(textFieldSelector)) syncTextFieldState(event.target);
});
document.addEventListener('change', (event) => {
    if (event.target.matches(textFieldSelector)) syncTextFieldState(event.target);
});
