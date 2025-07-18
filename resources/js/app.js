import "bootstrap/dist/js/bootstrap.bundle.min.js";
import flatpickr from "flatpickr";

import Modal from "bootstrap/js/dist/modal";

// Then you can use it like this:
const modalEl = document.getElementById("loadingBackdrop");
const modal = new Modal(modalEl);
modal.show();

document.addEventListener("DOMContentLoaded", function () {
    flatpickr(".flatpickr:not(.flatpickr-initialized)", {
        dateFormat: "yymmdd",
        allowInput: true,
        onReady: function (selectedDates, dateStr, instance) {
            instance.element.classList.add("flatpickr-initialized");
        },
    });
});
