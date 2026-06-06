/**
 * BACK BUTTON HANDLER v3 – Sanggar Tari JEB
 * public/js/button-handler.js
 */

window.addEventListener("load", function () {
    const isFormPage = document.body.classList.contains("page-form");

    // Tunggu sedikit lalu push state
    // agar browser sempat mencatat halaman ini di history
    setTimeout(function () {
        history.pushState({ jeb: "blocked" }, "", window.location.href);
    }, 100);

    window.addEventListener("popstate", function (e) {
        // Push ulang supaya tidak benar-benar pergi
        history.pushState({ jeb: "blocked" }, "", window.location.href);

        if (isFormPage) {
            if (typeof showPopupBatal === "function") {
                showPopupBatal();
            }
        } else {
            if (typeof showPopupLogout === "function") {
                showPopupLogout();
            }
        }
    });

    // Override batalkanForm agar bisa benar-benar pergi
    window.batalkanForm = function () {
        window.onpopstate = null;
        window.removeEventListener("popstate", arguments.callee);
        hidePopupBatal();
        history.go(-2);
    };
});
