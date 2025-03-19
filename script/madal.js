document.addEventListener("DOMContentLoaded", function () {
    let modals = document.querySelectorAll(".modal");
    modals.forEach((modal) => {
        modal.addEventListener("shown.bs.modal", function () {
            modal.classList.add("animate__animated", "animate__fadeInUp");
        });
    });
});
