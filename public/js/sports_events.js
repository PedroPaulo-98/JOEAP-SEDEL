document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".card").forEach((card) => {
        card.addEventListener("click", () => {
            const modalId = card.getAttribute("data-modal");
            if (modalId) {
                document.getElementById(modalId).style.display = "flex";
            }
        });
    });
    document.querySelectorAll(".modal .close").forEach((btn) => {
        btn.addEventListener("click", (e) => {
            e.target.closest(".modal").style.display = "none";
        });
    });
    window.addEventListener("click", (e) => {
        if (e.target.classList.contains("modal")) {
            e.target.style.display = "none";
        }
    });
});
