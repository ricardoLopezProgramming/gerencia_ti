export default function modal() {
    const modal = document.getElementById('modalEliminar');
    if (!modal) return; // ⛔️ Evita error si no existe

    modal.addEventListener('show.bs.modal', e => {
        const boton = e.relatedTarget;
        const nombre = boton.getAttribute('data-nombre');
        const id = boton.getAttribute('data-id');
        document.getElementById('modalNombreUsuario').textContent = nombre;
        document.getElementById('btnConfirmarEliminar').href = `/public/usuario/eliminar?id=${id}`;
    });
}
