
{{ flash.output() }}
{{ content() }}


<div class="card">

	<div class="card-header">
	    <h4>Registrarse en {{ config.application.appTitle }}</h4>
	</div>
	
	 <div class="card-block">
	
	{{ form('register', 'id': 'registerForm', 'onsubmit': 'return fnValidateForm(this);', 'autocomplete': 'off', 'novalidate': 'novalidate') }}
	
	    <fieldset>
	
	        <div class="form-group row">
	            {{ form.label('nombre', ['class': 'form-control-label col-sm-12']) }}            
	            <div class="col-sm-8">
	            	{{ form.render('nombre', ['class': 'form-control form-control-success required', 'aria-describedby': 'nombreHelp']) }}            
	            	<small id="nombreHelp" class="form-text text-muted">(required)</small>
	            </div>
	        </div>
	
	        <div class="form-group row">
	            {{ form.label('email', ['class': 'form-control-label col-sm-12']) }}
	            <div class="col-sm-8">            
	            	{{ form.render('email', ['class': 'form-control form-control-success required email', 'aria-describedby': 'emailHelp']) }}            
	            	<small id="emailHelp" class="form-text text-muted">(required)</small>
	            </div>
	        </div>
	
	        <div class="form-group row">
	            {{ form.label('clave', ['class': 'form-control-label col-sm-12']) }}
	            <div class="col-sm-8">            
	            	{{ form.render('clave', ['class': 'form-control form-control-success required', 'minlength': '8', 'aria-describedby': 'claveHelp']) }}            
	            	<small id="claveHelp" class="form-text text-muted">(minimo 8 caracteres)</small>
	            </div>            
	        </div>
	
	        <div class="form-group row">
	            <label class="control-label col-sm-12" for="repeatPassword">Confirmar Clave</label>
	            <div class="col-sm-8">                        
	            	{{ password_field('repeatPassword', 'class': 'form-control form-control-success', 'data-rule-equalTo': "#clave") }}
	            </div>                        
	        </div>
	
	        <div class="form-actions">
	            {{ submit_button('Registrarse', 'class': 'btn btn-primary') }}
	            <p class="form-text text-muted">Al registrarse usted acepta los términos y políticas de privacidad.</p>
	        </div>
	
	    </fieldset>
	    
	{{ end_form() }}
	</div>

</div>