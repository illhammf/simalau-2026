import "./bootstrap";

function showToast(message, type = "success") {
    const oldToast = document.querySelector("[data-toast]");
    if (oldToast) {
        oldToast.remove();
    }

    const toast = document.createElement("div");
    toast.dataset.toast = "true";

    const typeClass = {
        success: "toast-success",
        error: "toast-error",
        info: "toast-info",
    };

    toast.className = `${typeClass[type] ?? typeClass.info} animate-slide-up`;
    toast.textContent = message;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = "0";
        toast.style.transform = "translateY(-8px)";
        toast.style.transition = "all 250ms ease";

        setTimeout(() => {
            toast.remove();
        }, 250);
    }, 3500);
}

function setupMobileMenu() {
    const button = document.querySelector("[data-mobile-menu-button]");
    const menu = document.querySelector("[data-mobile-menu]");

    if (!button || !menu) {
        return;
    }

    button.addEventListener("click", () => {
        menu.classList.toggle("is-open");
    });
}

function setupFlashMessages() {
    const flash = document.querySelector("[data-flash]");

    if (!flash) {
        return;
    }

    setTimeout(() => {
        flash.style.opacity = "0";
        flash.style.transform = "translateY(-8px)";
        flash.style.transition = "all 250ms ease";

        setTimeout(() => {
            flash.remove();
        }, 250);
    }, 4000);
}

function setupSubmitLoading() {
    document.addEventListener("submit", (event) => {
        const form = event.target;
        const button = form.querySelector("button[type='submit']");

        if (!button || button.dataset.loading === "false") {
            return;
        }

        button.dataset.originalText = button.innerHTML;
        button.innerHTML = "Memproses...";
        button.disabled = true;
    });
}

function setupBackToTop() {
    const button = document.querySelector("[data-back-to-top]");

    if (!button) {
        return;
    }

    window.addEventListener("scroll", () => {
        if (window.scrollY > 400) {
            button.classList.remove("hidden");
        } else {
            button.classList.add("hidden");
        }
    });

    button.addEventListener("click", () => {
        window.scrollTo({
            top: 0,
            behavior: "smooth",
        });
    });
}

document.addEventListener("DOMContentLoaded", () => {
    setupMobileMenu();
    setupFlashMessages();
    setupSubmitLoading();
    setupBackToTop();

    window.LaundryApp = {
        toast: showToast,
    };

    console.log("Laundry frontend loaded");
});