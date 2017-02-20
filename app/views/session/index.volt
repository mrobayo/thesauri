
{{ content() }}

<div class="row">

    <div class="col-md-6">
        <div class="page-header">
            <h2>Ingreso</h2>
        </div>
        {{ form('session/start', 'role': 'form') }}
            <fieldset>
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="controls">
                        {{ text_field('email', 'class': "form-control") }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Clave</label>
                    <div class="controls">
                        {{ password_field('clave', 'class': "form-control") }}
                    </div>
                </div>
                <div class="form-group">
                    {{ submit_button('Ingresar', 'class': 'btn btn-primary btn-large') }}
                </div>
            </fieldset>
        </form>
    </div>

    <div class="col-md-6">

        <div class="page-header">
            <h2>Necesita registrarse?</h2>
        </div>

        <p>Crear una cuenta requiere de los siguientes pasos:</p>
        <ol>
            <li>Registrar formulario de datos</li>            
            <li>Validar direccion de correo</li>
            <li>Autorizacion de role y acceso</li>            
        </ol>

        <div class="clearfix center">
            {{ link_to('register', 'Registrarse', 'class': 'btn btn-primary btn-large btn-success') }}
        </div>
    </div>

</div>
