<style>
  :root {
    --sidebar-width: 280px;
    --sidebar-width-collapsed: 90px;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
}

.d-flex {
    display: flex;
}

.sidebar {
    width: var(--sidebar-width-collapsed);
    height: 100vh;
    background: linear-gradient(135deg, #1a1c2e 0%, #16181f 100%);
    transition: width 0.3s ease;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    position: relative;
}

.sidebar:hover {
    width: var(--sidebar-width);
    opacity: 1;
}

.sidebar-link {
    color: #a0a3bd;
    transition: all 0.2s ease;
    border-radius: 8px;
    margin: 4px 16px;
    white-space: nowrap;
    overflow: hidden;
    text-decoration: none;
    padding: 1rem;
    display: flex;
    align-items: center;
}

.sidebar-link:hover {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.1);
    transform: translateX(5px);
}

.sidebar-link.active {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.1);
}

.hide-on-collapse {
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.sidebar:hover .hide-on-collapse {
    opacity: 1;
    visibility: visible;
}

.logo-text {
    background: linear-gradient(45deg, #6b8cff, #8b9fff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    transition: opacity 0.3s ease;
}

.profile-section {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    margin-top: auto;
    padding: 1rem;
    /* margin-inline: auto; */
}

.profile-info {
    margin-left: 0.75rem;
    transition: opacity 0.3s ease;
    opacity: 0;
    visibility: hidden;
}

.sidebar:hover .profile-info {
    opacity: 1;
    visibility: visible;
}

.profile-info h6,
.profile-info small {
    margin: 0;
    color: white;
}

.nav {
    display: flex;
    flex-direction: column;
}

.logo-text {
    font-size: 1.25rem;
    font-weight: bold;
    white-space: nowrap;
    overflow: hidden;
}
</style>

<nav class="sidebar">
        <div class="p-4">
            <h4 class="logo-text fw-bold mb-0">MyD TareoApp</h4>
            <p class="text-muted small hide-on-collapse">Dashboard</p>
        </div>

        <div class="nav">
            <a href="#" class="sidebar-link active">
                <i class="fas fa-home me-3"></i>
                <span class="hide-on-collapse">Dashboard</span>
            </a>
            <a href="#" class="sidebar-link">
                <i class="fas fa-chart-bar me-3"></i>
                <span class="hide-on-collapse">Horas</span>
            </a>
            <a href="/public/proyecto/control" class="sidebar-link">
                <i class="fas fa-users me-3"></i>
                <span class="hide-on-collapse">Proyectos</span>
            </a>
            <a href="#" class="sidebar-link">
                <i class="fas fa-box me-3"></i>
                <span class="hide-on-collapse">Tickets</span>
            </a>
            <a href="/public/usuario/control" class="sidebar-link">
                <i class="fas fa-gear me-3"></i>
                <span class="hide-on-collapse">Usuarios</span>
            </a>
            <a href="#" class="sidebar-link">
                <i class="fas fa-gear me-3"></i>
                <span class="hide-on-collapse">Salir</span>
            </a>
        </div>
        <!-- imagenes random de perfil https://randomuser.me/api/portraits/women/70.jpg -->
        <div class="profile-section">
            <div class="d-flex align-items-center">
                <img src="/../../public/assets/image/default-profile.svg" style="height:60px" class="rounded-circle" alt="Profile">
                <div class="profile-info">
                    <h6 class="mb-0">Alex Morgan</h6>
                    <small>Admin</small>
                </div>
            </div>
        </div>
    </nav>