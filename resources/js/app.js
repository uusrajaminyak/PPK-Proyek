document.addEventListener('click', (event) => {
    const openButton = event.target.closest('[data-open-rejection-dialog], [data-open-cancellation-dialog]');
    if (openButton) {
        const dialogSelector = openButton.matches('[data-open-cancellation-dialog]')
            ? '[data-cancellation-dialog]'
            : '[data-rejection-dialog]';
        const dialog = document.querySelector(dialogSelector);
        dialog?.showModal();
        dialog?.querySelector('textarea')?.focus();
    }

    const closeButton = event.target.closest('[data-close-rejection-dialog], [data-close-cancellation-dialog]');
    if (closeButton) {
        closeButton.closest('dialog')?.close();
    }
});

const ajaxListSelector = '[data-ajax-list]';
let activeListRequest = null;

async function loadListPage(url, { updateHistory = true } = {}) {
    const currentList = document.querySelector(ajaxListSelector);

    if (!currentList) {
        window.location.assign(url);
        return;
    }

    activeListRequest?.abort();
    activeListRequest = new AbortController();
    currentList.setAttribute('aria-busy', 'true');

    try {
        const response = await fetch(url, {
            headers: {
                Accept: 'text/html',
                'X-Requested-With': 'XMLHttpRequest',
            },
            signal: activeListRequest.signal,
        });

        if (!response.ok || new URL(response.url).origin !== window.location.origin) {
            throw new Error('Daftar gagal dimuat.');
        }

        const page = new DOMParser().parseFromString(await response.text(), 'text/html');
        const updatedList = page.querySelector(ajaxListSelector);

        if (!updatedList) {
            window.location.assign(response.url);
            return;
        }

        currentList.innerHTML = updatedList.innerHTML;
        currentList.removeAttribute('aria-busy');
        document.title = page.title;

        if (updateHistory) {
            window.history.pushState({ ajaxList: true }, '', response.url);
        }

        currentList.dispatchEvent(new CustomEvent('ajax:list-updated', { bubbles: true }));
    } catch (error) {
        if (error.name === 'AbortError') return;

        currentList.removeAttribute('aria-busy');
        window.location.assign(url);
    }
}

document.addEventListener('submit', (event) => {
    if (event.defaultPrevented) return;

    const form = event.target;

    if (!(form instanceof HTMLFormElement) || form.method.toUpperCase() !== 'GET') return;
    if (!form.closest(ajaxListSelector)) return;

    event.preventDefault();
    const url = new URL(form.action || window.location.href, window.location.href);
    url.search = new URLSearchParams(new FormData(form)).toString();
    loadListPage(url.toString());
});

document.addEventListener('click', (event) => {
    if (event.defaultPrevented) return;

    const link = event.target.closest(`${ajaxListSelector} a`);

    if (!(link instanceof HTMLAnchorElement)) return;

    const isFilterLink = link.hasAttribute('data-ajax-filter-link');
    const isPaginationLink = link.closest('.officer-pagination, .pagination, nav[role="navigation"]');
    if (!isFilterLink && !isPaginationLink) return;
    if (link.target || link.hasAttribute('download')) return;

    const url = new URL(link.href, window.location.href);
    if (url.origin !== window.location.origin) return;

    event.preventDefault();
    loadListPage(url.toString());
});

window.addEventListener('popstate', () => {
    if (document.querySelector(ajaxListSelector)) {
        loadListPage(window.location.href, { updateHistory: false });
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
