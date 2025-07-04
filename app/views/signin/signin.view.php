<div class="container vh-100 d-flex align-items-center justify-content-center">
    <div class="col-12 col-sm-8 col-md-6 col-lg-4 bg-white p-4 rounded shadow">
        <form action="/public/signin/authentication" method="post">
            <div class="mb-3">
                <label for="emailName" class="form-label">Correo</label>
                <input type="email" class="form-control" id="emailName" name="email" aria-describedby="emailHelp" required>
                <div id="emailHelp" class="form-text">
                    Ingresa tu correo.
                </div>
            </div>
            <div class="mb-3">
                <label for="inputPassword" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="inputPassword" aria-describedby="passwordHelp" name="password" required>
                <div id="passwordHelp" class="form-text">
                    Nunca compartas tu contraseña.
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Recordar</label>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="#" class="small">Olvidaste tu contraseña?</a>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success">Iniciar sesion</button>
            </div>
        </form>
    </div>
</div>