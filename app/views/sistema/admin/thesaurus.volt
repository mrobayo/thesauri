
{{ content() }}


<h4 class="page-header">Administración de Thesaurus</h4>

	<div class="row">
	
		<div class="col-sm-2">
				
			<ul class="nav nav-pills flex-column" role="tablist">

				<li role="presentation" class="nav-item">
		    		<a href="#xlist" class="nav-link {% if items_list is empty %} disabled {% else %} active {% endif %}" aria-controls="xlist" role="tab" data-toggle="tab"> <i class="fa fa-search"></i> Consultar </a>
		    	</li>
				<li role="presentation" class="nav-item">
		    		<a href="#xnuevo" class="nav-link {% if items_list is empty %} active {% endif %}" aria-controls="xnuevo" role="tab" data-toggle="tab"> <i class="fa fa-file-o"></i> Crear Nuevo </a>
		    	</li>
			</ul>
					
		</div>
		
		<div class="tab-content col-sm-10">
			
			<div id="xlist" role="tabpanel" class="tab-pane {% if items_list is empty %} disabled {% else %} active {% endif %}">
				<div class="card" style="min-height: 400px;">
				
					<div class="card-header"> <i class="fa fa-search"></i> Thesaurus </div>
										
					<table class="table table-hover table-bordered table-sm">
					<thead>
						<tr><th>#</th>
							<th>Titulo</th>						
						
							<th>Términos Aprobados</th><th>Términos Pendientes</th><th>Ultima Actividad</th>
							<th>Url</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						{% for ckey, row in items_list %}
						<tr>
							<td>{{ loop.index }}</td>
							<td>{{ link_to( 'sistema/tesauro/view/'~row.id_thesaurus, row.nombre ) }}</td>
							
							<td></td>
							<td></td>
							<td></td>
							<td>
								{{ link_to( config.rdf.baseUri ~ row.rdf_uri, row.rdf_uri ) }}
							<a href="#" title=""><i class="fa fa-external-link"></i></a></td>
							<td><i class="fa fa-check text-success"></i></td>
						</tr>
						{% endfor %} 
					</tbody>					
					</table>
										
										    
				</div>			
			</div>
			
			<div id="xnuevo" role="tabpanel" class="tab-pane {% if items_list is empty %} active {% endif %}">
			{{ form('sistema/admin/thesaurus', 'id': 'thesaurusForm', 'onsubmit': 'return fnValidateForm(this);', 'autocomplete': 'off', 'novalidate': 'novalidate') }}
				
				<fieldset>
				    {{ form.render('id_thesaurus') }}
				    
				    <div class="card">
				    
				    	<div class="card-header"><i class="fa fa-file-o"></i> Nuevo Thesauri</div>
					    <div class="card-block">
					
					        <div class="form-group row">
					            {{ form.label('nombre', ['class': 'form-control-label col-sm-12']) }}            
					            <div class="col-sm-8">
					            	{{ form.render('nombre', ['class': 'form-control form-control-success required', 'aria-describedby': 'nombreHelp']) }}            
					            	<small id="nombreHelp" class="form-text text-muted">Título del Thesaurus (requerido)</small>
					            </div>
					        </div>
					
							<div class="form-group row">
					            {{ form.label('description', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">            
					            	{{ form.render('description', ['class': 'form-control form-control-success', 'aria-describedby': 'descripcionHelp']) }}            
					            	<small id="descripcionHelp" class="form-text text-muted">(requerido)</small>
					            </div>
					        </div>
					
					        <!-- <div class="form-group row">
					            {{ form.label('identifier', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">            
					            	{{ form.render('identifier', ['class': 'form-control form-control-success', 'aria-describedby': 'identifierHelp']) }}            
					            	<small id="identifierHelp" class="form-text text-muted">Identificador del Thesaurus (requerido)</small>
					            </div>
					        </div> -->
					        
					        <div class="form-group row">
					            {{ form.label('creator', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">            
					            	{{ form.render('creator', ['class': 'form-control form-control-success', 'aria-describedby': 'creatorHelp']) }}            
					            	<small id="creatorHelp" class="form-text text-muted">Persona o entidad principal responsable de la elaboración</small>
					            </div>            
					        </div>
					             
					        <div class="form-group row">
					            {{ form.label('contributor', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">            
					            	{{ form.render('contributor', ['class': 'form-control form-control-success', 'aria-describedby': 'contributorHelp']) }}            
					            	<small id="contributorHelp" class="form-text text-muted">Personas u organizaciones quienes contribuyeron con el Thesaurus</small>
					            </div>            
					        </div>
					
					        <div class="form-group row">
					            {{ form.label('publisher', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">            
					            	{{ form.render('publisher', ['class': 'form-control form-control-success', 'aria-describedby': 'publisherHelp']) }}            
					            	<small id="publisherHelp" class="form-text text-muted">Entidad responsable de la publicación</small>
					            </div>            
					        </div>
					        
					        <div class="form-group row">
					            {{ form.label('coverage', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">            
					            	{{ form.render('coverage', ['class': 'form-control form-control-success', 'aria-describedby': 'coverageHelp']) }}            
					            	<small id="coverageHelp" class="form-text text-muted">Cobertura espacial o temporal del Thesaurus</small>
					            </div>            
					        </div>
					        
					        <div class="form-group row">
					            {{ form.label('rights', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">            
					            	{{ form.render('rights', ['class': 'form-control form-control-success', 'aria-describedby': 'rightsHelp']) }}            
					            	<small id="rightsHelp" class="form-text text-muted">Copyright / Otros Derechos de la Información</small>
					            </div>            
					        </div>
					        
					        <div class="form-group row">
					            {{ form.label('license', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">            
					            	{{ form.render('license', ['class': 'form-control form-control-success', 'aria-describedby': 'licenseHelp']) }}            
					            	<small id="licenseHelp" class="form-text text-muted"> <a href='https://www.gnu.org/licenses/license-list.en.html#OtherLicenses' target="_blank">Licencias para otros trabajos</a></small>
					            </div>            
					        </div>
					        
					        
					        <div class="form-group row">
					            {{ form.label('created', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">    
					            	<div class="input-group">
					            	<span class="input-group-addon"><i class="fa fa-calendar"></i></span>        
					            	{{ form.render('created', ['class': 'form-control form-control-success', 'aria-describedby': 'createdHelp']) }}
					            	</div>            
					            	<small id="Help" class="form-text text-muted"></small>
					            </div>            
					        </div>
					        
					        <div class="form-group row">
					            {{ form.label('subject', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">            
					            	{{ form.render('subject', ['class': 'form-control form-control-success', 'aria-describedby': 'subjectHelp']) }}            
					            	<small id="Help" class="form-text text-muted">Indice de Términos indicando las materias del contenido</small>
					            </div>            
					        </div>
					        
					        <div class="form-group row">
					            {{ form.label('language', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">	            
					            		            	
					            	{{ form.render('language', ['class': 'form-control form-control-success', 'aria-describedby': 'languageHelp']) }}
					            	            
					            	<small id="languageHelp" class="form-text text-muted">Idiomas soportados por el Thesaurus</small>
					            </div>            
					        </div>
					        
					        <div class="form-group row">
					            {{ form.label('source', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">            
					            	{{ form.render('source', ['class': 'form-control form-control-success', 'aria-describedby': 'sourceHelp']) }}            
					            	<small id="sourceHelp" class="form-text text-muted">Recursos desde los cuales el Thesaurus fue derivado</small>
					            </div>            
					        </div>
					        
					                
					        <div class="form-group row">
					            {{ form.label('type', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">            
					            	{{ form.render('type', ['class': 'form-control form-control-success', 'aria-describedby': 'typeHelp']) }}            
					            	<small id="typeHelp" class="form-text text-muted">El género del vocabulario</small>
					            </div>            
					        </div>
					               
					        
				
						</div>
						<div class="card-footer">
							<div class="form-actions">
					            {{ submit_button('Guardar', 'class': 'btn btn-primary') }} <!-- 'onclick': '$("#thesaurusForm").submit();' -->
					        </div>
						</div>	
					</div>
				</fieldset>
				    
			{{ end_form() }}				
			</div>
			
		
		</div>
		
	</div>
	

<script>
	$(function(){
		
		var langcodes = new Bloodhound({
			  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('text'),
			  queryTokenizer: Bloodhound.tokenizers.whitespace,
			  prefetch: '/thesauri/iso-codes.json'
		});
		langcodes.initialize();
		
		$('#language').tagsinput({
		  itemValue: 'value',
	 	  itemText: 'text',
		  typeaheadjs: {
		    name: 'langcodes',
		    displayKey: 'text',
		    source: langcodes.ttAdapter()
		  },
		  allowDuplicates: false,
		  tagClass: function(item) {
		      return 'badge badge-info';
		    },
		});
		$('.bootstrap-tagsinput').addClass('form-control');
		$('#created').datepicker({
			format: 'yyyy-mm-dd'			
		});		
		$('#thesaurusForm')
			.enterAsTab({ 'allowSubmit': true})
			.find(":input:text:visible:not(disabled):not([readonly])").first().focus();
	});		
</script>
