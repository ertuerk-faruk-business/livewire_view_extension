/** Livewire View Extension UpdateBrowserHistory */
document.addEventListener('DOMContentLoaded', function () {

    window.livewire.on('lveUrlChanges', param => {
    if (! param) {
        history.pushState(null, null, `${document.location.pathname}`);

        return;
    }

    history.pushState(null, null, `${document.location.pathname}?${param}`);
    });
});