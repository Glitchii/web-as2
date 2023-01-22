addEventListener('DOMContentLoaded', () => {
    // Ask for confirmation before doing an action where necessary eg. deleting a job, or account.
    for (const confirmLink of document.querySelectorAll('[data-confirm]'))
        confirmLink.addEventListener('click', e => {
            if (!confirm(confirmLink.dataset.confirm))
                e.preventDefault();
        });

    // Use by jobfilter template to scroll the submit button into view when the filter is opened.
    document.querySelector('details.filter')?.addEventListener('toggle', e => {
        if (e.target.open)
            e.target.closest('.filter')
                .querySelector('input[type="submit"]')
                .scrollIntoView({ behavior: 'smooth' });
    });
});