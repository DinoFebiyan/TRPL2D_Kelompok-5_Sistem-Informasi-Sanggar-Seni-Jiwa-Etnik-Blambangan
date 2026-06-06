/**
 * BACK BUTTON HANDLER v2 – Sanggar Tari JEB
 * public/js/back-button-handler.js
 */

document.addEventListener("DOMContentLoaded", function () {
    const isFormPage = document.body.classList.contains("page-form");

    // Push state supaya ada "halaman sebelumnya" yang bisa kita intersep
    history.pushState({ jeb: "blocked" }, "", window.location.href);

    function handlePopstate(e) {
        // Kembalikan state supaya user tidak benar-benar pergi
        history.pushState({ jeb: "blocked" }, "", window.location.href);

        if (isFormPage) {
            if (typeof showPopupBatal === "function") showPopupBatal();
        } else {
            if (typeof showPopupLogout === "function") showPopupLogout();
        }
    }

    window.addEventListener("popstate", handlePopstate);

    // Override batalkanForm agar bisa benar-benar pergi
    window.batalkanForm = function () {
        window.removeEventListener("popstate", handlePopstate);
        if (typeof hidePopupBatal === "function") hidePopupBatal();
        history.go(-2);
    };
});
