export default function imageform() {
    const inputImage = document.getElementById("inputImage");
    const previewImage = document.getElementById("previewImage");

    inputImage.addEventListener("change", (event) => {
        const file = event.target.files[0];
        if (file && file.type.startsWith("image/")) {
            const tempUrl = URL.createObjectURL(file);
            previewImage.src = tempUrl;
        }
    });
}
