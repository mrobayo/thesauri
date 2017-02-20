
{{ content() }}

<div class="page-header">
    <h2>Registrarse en {{ config.application.appTitle }}</h2>
</div>

{{ form('register', 'id': 'registerForm', 'onbeforesubmit': 'return false', 'autocomplete': 'off') }}

    <fieldset>

        <div class="control-group">
            {{ form.label('nombre', ['class': 'control-label']) }}
            <div class="controls">
                {{ form.render('nombre', ['class': 'form-control']) }}
                <p class="help-block">(required)</p>
                <div class="alert alert-warning" id="name_alert">
                    <strong>Alerta!</strong> Por favor ingrese su nombre completo
                </div>
            </div>
        </div>

        <div class="control-group">
            {{ form.label('email', ['class': 'control-label']) }}
            <div class="controls">
                {{ form.render('email', ['class': 'form-control']) }}
                <p class="help-block">(required)</p>
                <div class="alert alert-warning" id="email_alert">
                    <strong>Alerta!</strong> Por favor ingrese su email
                </div>
            </div>
        </div>

        <div class="control-group">
            {{ form.label('clave', ['class': 'control-label']) }}
            <div class="controls">
                {{ form.render('clave', ['class': 'form-control']) }}
                <p class="help-block">(minimo 8 caracteres)</p>
                <div class="alert alert-warning" id="password_alert">
                    <strong>Alerta!</strong> Por favor ingrese su clave valida
                </div>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="repeatPassword">Confirmar Clave</label>
            <div class="controls">
                {{ password_field('repeatPassword', 'class': 'form-control') }}
                <div class="alert" id="repeatPassword_alert">
                    <strong>Alerta!</strong> La clave no coincide
                </div>
            </div>
        </div>

        <div class="form-actions">
            {{ submit_button('Register', 'class': 'btn btn-primary', 'onclick': 'return SignUp.validate();') }}
            <p class="help-block">Al registrarse usted acepta los términos y políticas de privacidad.</p>
        </div>

    </fieldset>
</form>
