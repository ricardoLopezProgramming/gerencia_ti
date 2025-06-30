import imageform from "./modules/imageForm.mjs";
import modal from "./modules/modal.mjs";
import actualizarReloj from "./modules/reloj.mjs";

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById("inputImage") && document.getElementById("previewImage")) {
        imageform();
    }
    modal();
    actualizarReloj();

});