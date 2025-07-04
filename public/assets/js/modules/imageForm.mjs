export default function imageform() {
    const avatar = document.getElementById("avatar");
    const previewImage = document.getElementById("previewImage");

    if (!avatar || !previewImage) return;

    avatar.addEventListener("change", (event) => {
        const file = event.target.files[0];
        if (file && file.type.startsWith("image/")) {
            const tempUrl = URL.createObjectURL(file);
            previewImage.src = tempUrl;
        }
    });
}
