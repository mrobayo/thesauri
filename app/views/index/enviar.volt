

<h4 class="page-header">

	<nav class="breadcrumb">
		{% if ! (thesaurus is empty) %}	
		<a class="breadcrumb-item" href="{{ url( thesaurus.rdf_uri ) }}"> {{ thesaurus.nombre }}</a>
		{% endif %}
		<a class="breadcrumb-item active">{{ myheading }} </a>
	</nav>

</h4>

<div class="row">

		
	
		<div class="col-sm-2">
			<ul class="nav nav-pills flex-column" role="tablist">
		    	
				<li role="presentation" class="nav-item">
		    		<a href="#xnuevo" class="nav-link active" aria-controls="xnuevo" role="tab" data-toggle="tab"> 
		    			<i class="fa fa-file-o"></i> Crear Nuevo 
		    		</a>
		    	</li>
		    	
			</ul>
		
		</div>
		<div class="tab-content col-sm-10">
		
			<div id="xnuevo" role="tabpanel" class="tab-pane {% if entidad.id_termino is empty %} {% if items_list is empty %} active {% endif %} {% else %} active {% endif %}">
			{{ form('index/enviar', 'id': 'thisForm', 'onsubmit': 'return fnValidateForm(this);', 'autocomplete': 'off', 'novalidate': 'novalidate') }}
				
				<fieldset>
				    {{  form.render('id_termino') }}
				    {{  form.render('id_thesaurus') }}
				    
				    <div class="card">
				    
				    	<div class="card-header"><i class="fa {% if entidad.id_termino is empty %} fa-file-o {% else %} fa-edit {% endif %}"></i> 
				    		{% if entidad.id_termino is empty %} Nuevo {% else %} {{ entidad.nombre }} {% endif %}
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
					            <label class="form-control-label col-sm-8" for="SIN[]">
					            	<button data-input-name="SIN[]" type="button" class="add-termino-btn btn btn-outline-primary pull-right btn-sm"> <i class="fa fa-plus"></i></button>	
					            	Sinónimos
					            </label>
					            <!-- <div class="col-sm-8">	
					        	<table class="table table-condensed">					        	
					        	<tbody>
					        		<tr> <td class="col-12"> {{ form.render('SIN[]', ['class': 'form-control form-control-success']) }} </td>  </tr>					        		
					        	</tbody>
					        	</table> </div> -->
					        	
					        	 <div class="col-sm-8">            
					            	{{ form.render('SIN[]', ['class': 'form-control form-control-success']) }}
					            </div>					        	
					        </div>	
					        
					        <div class="form-group row">
					            <label class="form-control-label col-sm-8" for="TR[]">
					            	<button data-input-name="TR[]" type="button" class="add-termino-btn btn btn-outline-primary pull-right btn-sm"> <i class="fa fa-plus"></i></button>	
					            	Término relacionado
					            </label>
					            <div class="col-sm-8">            
					            	{{ form.render('TR[]', ['class': 'form-control form-control-success']) }}
					            </div>
					        </div>		        
					        					        
					        <div class="form-group row">
					        	 {{ form.label('dc_source', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">            
					            	{{ form.render('dc_source', ['class': 'form-control form-control-success']) }}
					            </div>
					        </div>

							{# 					        					        
					        <div class="form-group row">
					            {{ form.label('id_thesaurus', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">					            		            	
					            	{{ form.render('id_thesaurus', ['class': 'form-control form-control-success required']) }}
					            </div>            
					        </div> #} 
					        
					        
					        <div class="form-group row">
					            {{ form.label('iso25964_language', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">					            		            	
					            	{{ form.render('iso25964_language', ['class': 'form-control form-control-success']) }}
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
	var vIdiomas = JSON.parse('{{ thesaurus_lang }}');	
	$('#id_thesaurus').change(function(){
		vId = $(this).val();		
		$('#iso25964_language').empty();
		
		if (vId && vIdiomas && vIdiomas[vId]) {			
			$.each(vIdiomas[vId], function(i, v){
				$('#iso25964_language').append('<option value="'+ i +'">'+ v +'</option>');
			});			
		}		
	});
	
	$('#id_thesaurus').change();
		
	$('.add-termino-btn').click(function(e){
		vInput = $('<input>', {
			'name': $(this).data('inputName'),
			'class':'form-control'});
		vTab = $(this).closest('div').find('table');
		
		if (vTab.length == 0) {
			vDiv = $(this).closest('div');			
			vDiv.append( $('<div>', {'class': 'col-sm-8', 'style': 'padding-top: 8px'}).append(vInput).append(vDiv.find('small')));
		}
		else {
			vTab.find('tbody').append( $('<tr>').append( $('<td>').append(vInput)));
		}
		
		vInput.focus();		
	});	
});
</script>