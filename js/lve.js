document.addEventListener('DOMContentLoaded', function () {
  window.livewire.on('livewire_view_extension_url_changes', function (param) {
    if (!param) {
      history.pushState(null, null, "".concat(document.location.pathname));
      return;
    }

    history.pushState(null, null, "".concat(document.location.pathname, "?").concat(param));
  });
});