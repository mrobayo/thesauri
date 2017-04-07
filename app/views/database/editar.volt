
<h4 class="page-header">{{ myheading }} </h4>

<div class="row">
	
		<div class="col-sm-2">
			<ul class="nav nav-pills flex-column" role="tablist">
		    	
				<li role="presentation" class="nav-item">
		    		<a href="#xnuevo" class="nav-link active" aria-controls="xnuevo" role="tab" data-toggle="tab"> 
		    			<i class="fa fa-edit"></i> Editar 
		    		</a>
		    	</li>
		    	
			</ul>
		
		</div>
		<div class="tab-content col-sm-10">
		
			<div id="xnuevo" role="tabpanel" class="tab-pane active">
			{{ form( 'database/editar/' ~ entidad.id_termino, 'id': 'thisForm', 'onsubmit': 'return fnValidateForm(this);', 'autocomplete': 'off', 'novalidate': 'novalidate') }}
				
				<fieldset>
				    {{ form.render('id_termino') }}
				    
				    <div class="card">
				    
				    	<div class="card-header"><i class="fa {% if entidad.id_termino is empty %} fa-file-o {% else %} fa-edit {% endif %}"></i> 
				    		Editar {{ entidad.nombre }}
				    	</div>
				    	
					    <div class="card-block">
					
					        <div class="form-group row">
					            {{ form.label('nombre', ['class': 'form-control-label col-sm-12']) }}            
					            <div class="col-sm-8">
					            	{{ form.render('nombre', ['class': 'form-control form-control-success required']) }}					            	
					            </div>
					        </div>
					
							<div class="form-group row">
					            {{ form.label('descripcion', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">            
					            	{{ form.render('descripcion', ['class': 'form-control form-control-success']) }}
					            </div>
					        </div>
					        					       
					        <div class="form-group row">
					            {{ form.label('TG', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">            
					            	{{ form.render('TG', ['class': 'form-control form-control-success']) }}
					            </div>
					        </div> 
					        					        	
					        <div class="form-group row">
					            {{ form.label('SIN[]', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">	
					        	<table class="table table-condensed">					        	
					        	<tbody>
					        		<!-- <td> <button type="button" class="btn btn-outline-danger"> <i class="fa fa-minus"></i></button> </td> -->
					        		<tr> <td class="col-12"> {{ form.render('SIN[]', ['class': 'form-control form-control-success']) }} </td>  </tr>					        		
					        	</tbody>
					        	</table>

					        	<small id="SINHelp" class="form-text text-muted">Término relacionados</small>
					        	<button id="addSinonimoBtn" type="button" class="btn btn-outline-primary"> <i class="fa fa-plus"></i></button>				     					        	
					        	</div>
					        </div>
					        
					        <div class="form-group row">
					        	 {{ form.label('dc_source', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">            
					            	{{ form.render('dc_source', ['class': 'form-control form-control-success']) }}
					            </div>
					        </div>
					        
					        <div class="form-group row">
					        	<label class="form-control-label col-sm-12"> Nota </label>
					        	<div class="col-sm-8">
					        	
					        	<table class="table table-condensed">					        	
					        	<tbody>
					        		<tr><td class="col-12">					        		
					        			<textarea rows="" cols="" class="form-control"></textarea> 
					        		</td></tr>					        		
					        	</tbody>
					        	</table>

					        	<small id="SINHelp" class="form-text text-muted">Término relacionados</small>
					        	<button id="addSinonimoBtn" type="button" class="btn btn-outline-primary"> <i class="fa fa-plus"></i></button>					        	
					        	
					        	</div>
					        
					        </div>
					        					        					        
					        <div class="form-group row">
					            {{ form.label('id_thesaurus', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">					            		            	
					            	<input class="form-control" disabled value="{{ thesaurus.nombre }}">
					            </div>            
					        </div>
					        
					        <div class="form-group row">
					            {{ form.label('iso25964_language', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">					            		            	
					            	{{ form.render('iso25964_language', ['class': 'form-control form-control-success']) }}
					            </div>            
					        </div>
					        					        
					        <div class="form-group row">
					            {{ form.label('estado_termino', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">					            		            	
					            	{{ form.render('estado_termino', ['class': 'form-control form-control-success']) }}
					            </div>            
					        </div>
				
						</div>
						
						<div class="card-footer">
							<div class="form-actions">
					            {{ submit_button('Guardar', 'class': 'btn btn-primary') }} 
					        </div>
						</div>
							
					</div>
				</fieldset>
				    
			{{ end_form() }}				
			</div>
					
		
		</div>		
		
</div>		

<script>
$(function() {
	
	$('#addSinonimoBtn').click(function(e){
		vInput = $('<input>', {
			'name': 'SIN[]',
			'class':'form-control'});
		$(this).parent().find('table').find('tbody').append( $('<tr>').append( $('<td>').append(vInput)))
		
		vInput.focus();		
	});
	
});

</script>
