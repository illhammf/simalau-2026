import "./bootstrap";

/* =========================
   TOAST SYSTEM
========================= */
function createToast(message, type = "success") {
    const toast = document.createElement("div");

    const baseStyle =
        "fixed top-5 right-5 z-50 px-4 py-3 rounded-xl text-white shadow-lg text-sm fade-in";

    const colors = {
        success: "bg-green-600",
        error: "bg-red-600",
        info: "bg-blue-600",
    };

    toast.className = `${baseStyle} ${colors[type] || colors.info}`;
    toast.innerText = message;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 3000);
}

/* =========================
   LOADING BUTTON STATE
========================= */
function setButtonLoading(button, isLoading = true) {
    if (!button) return;

    if (isLoading) {
        button.dataset.originalText = button.innerHTML;
        button.innerHTML = "Loading...";
        button.disabled = true;
    } else {
        button.innerHTML = button.dataset.originalText;
        button.disabled = false;
    }
}

/* =========================
   AUTO HIDE FLASH MESSAGE
========================= */
document.addEventListener("DOMContentLoaded", () => {
    const flash = document.querySelector("[data-flash]");

    if (flash) {
        setTimeout(() => {
            flash.style.transition = "0.3s";
            flash.style.opacity = "0";
            setTimeout(() => flash.remove(), 300);
        }, 4000);
    }

    console.log("Frontend Laundry System Loaded");
});

/* =========================
   FORM SUBMIT UX ENHANCEMENT
========================= */
document.addEventListener("submit", function (e) {
    const button = e.target.querySelector("button[type='submit']");
    if (button) {
        setButtonLoading(button, true);
    }
});

/* =========================
   GLOBAL EXPORT (optional)
========================= */
window.LaundryApp = {
    toast: createToast,
    loading: setButtonLoading,
};