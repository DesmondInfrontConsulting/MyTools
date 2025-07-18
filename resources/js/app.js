import $ from "jquery";
import "bootstrap/dist/js/bootstrap.bundle.min.js";
import flatpickr from "flatpickr";

document.addEventListener("DOMContentLoaded", function () {
    flatpickr(".flatpickr:not(.flatpickr-initialized)", {
        dateFormat: "yymmdd",
        allowInput: true,
        onReady: function (selectedDates, dateStr, instance) {
            instance.element.classList.add("flatpickr-initialized");
        },
    });
});
