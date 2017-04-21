
<h4 class="page-header">

	<nav class="breadcrumb">		
		<a class="breadcrumb-item" href="/"> Inicio </a>
		<a href="#" class="breadcrumb-item active"> Recuperar Clave </a>
	</nav>

</h4>

<div class="">

	{{ form('', 'id': 'thisForm', 'onsubmit': 'return fnValidateForm(this);', 'autocomplete': 'off', 'novalidate': 'novalidate') }}
	
		<fieldset>		
			    <div class="card">			    
			    	<div class="card-header"><i class="fa "></i>  Recuperar clave </div>			    	
				    <div class="card-block">
				    
				    		<div class="form-group row">
					            {{ form.label('email', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">            
					            	{{ form.render('email', ['class': 'form-control form-control-success email required']) }}
					            </div>
					        </div>
					        
					        <div class="form-group row">
					            {{ form.label('captcha', ['class': 'form-control-label col-sm-12']) }}					            
					            <div class="col-sm-8">
						            <div class="input-group">
						            	<span class="input-group-addon"><img src="{{ url('session/captcha') }}"></span>
						            	{{ form.render('captcha', ['class': 'form-control form-control-success required digits']) }}
						            </div>
						            <small class="form-text text-muted">Ingrese el resultado de operación matemática.</small>
					            </div>					            
					        </div>
				    
				    		<div class="card-footer">
								<div class="form-actions">
						            {{ submit_button('Enviar', 'class': 'btn btn-primary') }} 
						        </div>
							</div>
											    				    
					</div>					    
				</div>				
		</fieldset>    
				    
	{{ end_form() }}					    					  

</div>

<script>
	$(function() { $('#email').focus(); });
</script>