
{{ flash.output() }}
{{ content() }}


<div class="card">

	<div class="card-header">
	    <h4>Reiniciar Clave</h4>
	</div>
	
	 <div class="card-block">
	
	{{ form('', 'id': 'resetForm', 'onsubmit': 'return fnValidateForm(this);', 'autocomplete': 'off', 'novalidate': 'novalidate') }}
	
	    <fieldset>
	
	        <div class="form-group row">
	            Estimado {{ entidad.nombre}}, por favor ingresa tu nueva clave: 
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
	            {{ submit_button('Reiniciar', 'class': 'btn btn-primary') }}	            
	        </div>
	
	    </fieldset>
	    
	{{ end_form() }}
	</div>

</div>

<script>
	$(function() { $('#clave').focus(); });
</script>
