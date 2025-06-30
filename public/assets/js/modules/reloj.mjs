export default function actualizarReloj() {
    const ahora = new Date();
    const opciones = {
        hour: '2-digit',
        minute: '2-digit',
        // second: '0-digit',
    };
    document.getElementById('reloj').textContent = ahora.toLocaleTimeString('es-PE', opciones);
}

setInterval(actualizarReloj, 1000);
actualizarReloj(); // Primera ejecución inmediata