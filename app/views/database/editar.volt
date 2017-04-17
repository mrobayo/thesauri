
<h4 class="page-header">
	<nav class="breadcrumb">
		{% if ! (thesaurus is empty) %}	
		<a class="breadcrumb-item" href="{{ url( thesaurus.rdf_uri ) }}"> {{ thesaurus.nombre }}</a>
		{% endif %}
		<a class="breadcrumb-item active">{{ myheading }} </a>
	</nav>
</h4>

<div class="row">
	
		<div class="col-sm-3">
			<ul class="nav nav-pills flex-column" role="tablist">
		    	
				<li role="presentation" class="nav-item">
		    		<a href="#xnuevo" class="nav-link active" aria-controls="xnuevo" role="tab" data-toggle="tab">  <i class="fa fa-edit"></i> Editar </a>
		    	</li>
		    	
		    	<li role="presentation" class="nav-item">
		    		<a href="#xnotas" title="Aprobación &amp; Notas" class="nav-link" aria-controls="xnotas" role="tab" data-toggle="tab">  <i class="fa fa-thumbs-o-up"></i> Aprobación &amp; Notas </a>
		    	</li>
		    	
			</ul>
		
		</div>
		<div class="tab-content col-sm-9">
		
			<div id="xnotas" role="tabpanel" class="tab-pane">
			{{ form( 'database/aprobar/' ~ entidad.id_termino, 'id': 'thisForm', 'onsubmit': 'return fnValidateForm(this);', 'autocomplete': 'off', 'novalidate': 'novalidate') }}
				
				<fieldset>
				    {{ form.render('id_termino') }}
				     <div class="card">
				    
				    	<div class="card-header"><i class="fa fa-thumbs-o-up"></i> 
				    		{{ entidad.nombre }} <span class="text-primary"> (Aprobación &amp; Notas Técnicas) </span>
				    	</div>
				    	
					    <div class="card-block">
					    
					    	<div class="form-group row">
					            {{ form.label('estado_termino', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">					            		            	
					            	{{ form.render('estado_termino', ['class': 'form-control form-control-success']) }}
					            </div>            
					        </div>
					        
					        
					        <div class="form-group row">					        	
					        	{{ form.label('notas_tecnicas', ['class': 'form-control-label col-sm-12']) }}				      				        
					        	<div class="col-sm-8">            
					            	{{ form.render('notas_tecnicas', ['class': 'form-control form-control-success']) }}
					            </div>
					        </div>
					        					        
					        <div class="form-group row">
					        	<label class="form-control-label col-sm-12">Historia del Término</label>
					        	
					        	{% for kcom, rcom in comentarios %}
					        	<div class="col-sm-8">
						        	<blockquote class="blockquote {% if rcom.id_ingreso == auth['id'] %} callout-warning {% endif %}">             
							        	<p class="form-control-static"> {{ rcom.contenido }}
							        		
							        	 </p>
						        	 </blockquote>
					        	 </div>					        						        						        					        
					        	{% endfor %}
					        
					        </div>
					        
					        <div class="form-group row">
					        	<label class="form-control-label col-sm-12">Nuevo Comentario</label>					        				        
					        	<div class="col-sm-8">
						        	<div class="input-group">	
							        	<input class="form-control">					        	
							        	<span class="input-group-btn">					        	
							        		<button id="add-comentario-btn" type="button" class="btn btn-outline-primary pull-right btn-sm"> <i class="fa fa-plus"></i></button>
							        	</span>					        	
						        	</div>
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
		
			<div id="xnuevo" role="tabpanel" class="tab-pane active">
			{{ form( 'database/editar/' ~ entidad.id_termino, 'id': 'thisForm', 'onsubmit': 'return fnValidateForm(this);', 'autocomplete': 'off', 'novalidate': 'novalidate') }}
				
				<fieldset>
				    {{ form.render('id_termino') }}
				    {{  form.render('id_thesaurus') }}
				    
				    <div class="card">
				    
				    	<div class="card-header">
				    		<span class="pull-right badge badge-default {% if entidad.estado_termino == 'CANDIDATO' %} badge-danger {% endif %} {% if entidad.estado_termino == 'APROBADO' %} badge-primary {% endif %} ">
				    			{{ ESTADO_LIST[ entidad.estado_termino ] }}
				    		</span>
				    						    	
				    		<i class="fa {% if entidad.id_termino is empty %} fa-file-o {% else %} fa-edit text-danger {% endif %}"></i> 
				    		{{ entidad.nombre }} <span class="text-danger"> (Editar) </span>
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
					        
					        <!-- 
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

					        	<small id="SINHelp" class="form-text text-muted">Añadir nota</small>
					        	<button id="addSinonimoBtn" type="button" class="btn btn-outline-primary"> <i class="fa fa-plus"></i></button>					        	
					        	
					        	</div>					        
					        </div> -->
					        					        	
					        {#
					        <div class="form-group row">
					            {{ form.label('id_thesaurus', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">					            		            	
					            	<input class="form-control" disabled value="{{ thesaurus.nombre }}">
					            </div>            
					        </div>
					        #}
					        
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
