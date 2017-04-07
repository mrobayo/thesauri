
{{ content() }}


<h4 class="page-header">Administración de Thesaurus</h4>

	<div class="row">
	
		<div class="col-sm-2">
				
			<ul class="nav nav-pills flex-column" role="tablist">
				{% if entidad.id_thesaurus is empty %}
				<li role="presentation" class="nav-item">
		    		<a href="#xlist" class="nav-link {% if items_list is empty %} disabled {% else %} active {% endif %}" aria-controls="xlist" role="tab" data-toggle="tab"> <i class="fa fa-search"></i> Consultar </a>
		    	</li>		    	
		    	<li role="presentation" class="nav-item">
		    		<a href="#xnuevo" class="nav-link {% if items_list is empty %} active {% endif %}" aria-controls="xnuevo" role="tab" data-toggle="tab"> <i class="fa fa-file-o"></i> Crear Nuevo </a>
		    	</li>
		    	{% else %}
		    	<li role="presentation" class="nav-item">
		    		<a href="#xnuevo" class="nav-link active" aria-controls="xnuevo" role="tab" data-toggle="tab"> <i class="fa fa-edit"></i> Editar </a>
		    	</li>		    			    	
		    	<li role="presentation" class="nav-item">
		    		<a href="#xpermisos" class="nav-link" aria-controls="xpermisos" role="tab" data-toggle="tab"> <i class="fa fa-user-circle"></i> Permisos </a>
		    	</li>		    	
		    	<li role="presentation" class="nav-item">
		    		{{ link_to('sistema/admin/thesaurus', 'Cancelar', 'class': 'nav-link') }}		    		
		    	</li>
		    	{% endif %}
			</ul>
					
		</div>
		
		<div class="tab-content col-sm-10">
			
			<div id="xlist" role="tabpanel" class="tab-pane {% if entidad.id_thesaurus is empty %} {% if items_list is empty %} disabled {% else %} active {% endif %} {% endif %}">
				<div class="card" style="min-height: 400px;">
				
					<div class="card-header"> <i class="fa fa-search"></i> Thesaurus </div>
										
					<table class="table table-hover table-bordered table-sm">
					<thead>
						<tr><th>#</th>
							<th>Título</th>						
							<th>Térm.<br> Aprobados</th>
							<th>Térm.<br> Pendientes</th>
							<th>Ultima<br> Actividad</th>														
							<th>Prim.</th>
							<th>Status</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						{% for ckey, row in items_list %}
						<tr>
							<td>{{ loop.index }}</td>
							<td>{{ link_to( row.rdf_uri, row.nombre ) }}</td>							
							<td class="text-center">{{ row.term_aprobados }}</td>
							<td class="text-center">{{ row.term_pendientes }}</td>
							<td class="text-center">{{ row.ultima_actividad }}</td>
							<td class="text-center">
								<i class="fa {% if row.is_primario %} fa-heart text-danger {% else %} {% endif %}" title="Principal"></i>
							</td>
							<td class="text-center">
								<i title="{% if row.is_activo %} {% if row.is_publico %} Activo y Públicado {% else %} No Publicado {% endif %} {% else %} Desactivado {% endif %}" 
								class="fa {% if row.is_activo %} {% if row.is_publico %} fa-check text-success {% else %} fa-ban text-warning {% endif %} {% else %} fa-remove text-danger {% endif %}"></i>						
							</td>
							<td class="text-center">{{ link_to( 'sistema/admin/thesaurus/'~row.id_thesaurus, '<i class="fa fa-edit"></i>' ) }}</td>							
						</tr>
						{% endfor %} 
					</tbody>					
					</table>										
										    
				</div>			
			</div>
			
			<div id="xnuevo" role="tabpanel" class="tab-pane {% if entidad.id_thesaurus is empty %} {% if items_list is empty %} active {% endif %} {% else %} active {% endif %}">
			{{ form('sistema/admin/thesaurus', 'id': 'editForm', 'onsubmit': 'return fnValidateForm(this);', 'autocomplete': 'off', 'novalidate': 'novalidate') }}
				
				<fieldset>
				    {{ form.render('id_thesaurus') }}
				    
				    <div class="card">
				    
				    	<div class="card-header"><i class="fa {% if entidad.id_thesaurus is empty %} fa-file-o {% else %} fa-edit text-danger {% endif %}"></i> {% if entidad.id_thesaurus is empty %} Nuevo {% else %} {{ entidad.nombre }} {% endif %}</div>
					    <div class="card-block">
					
					        <div class="form-group row">
					            {{ form.label('nombre', ['class': 'form-control-label col-sm-12']) }}            
					            <div class="col-sm-8">
					            	{{ form.render('nombre', ['class': 'form-control form-control-success required']) }}					            	
					            </div>
					        </div>
					
							<div class="form-group row">
					            {{ form.label('iso25964_description', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">            
					            	{{ form.render('iso25964_description', ['class': 'form-control form-control-success']) }}
					            </div>
					        </div>
					        
					        <div class="form-group row">
					            {{ form.label('iso25964_creator', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">            
					            	{{ form.render('iso25964_creator', ['class': 'form-control form-control-success']) }}					            	
					            </div>            
					        </div>
					             
					        <div class="form-group row">
					            {{ form.label('iso25964_contributor', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">            
					            	{{ form.render('iso25964_contributor', ['class': 'form-control form-control-success']) }}
					            </div>            
					        </div>
					
					        <div class="form-group row">
					            {{ form.label('iso25964_publisher', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">            
					            	{{ form.render('iso25964_publisher', ['class': 'form-control form-control-success']) }}
					            </div>            
					        </div>
					        
					        <div class="form-group row">
					            {{ form.label('iso25964_coverage', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">            
					            	{{ form.render('iso25964_coverage', ['class': 'form-control form-control-success']) }}
					            </div>            
					        </div>
					        
					        <div class="form-group row">
					            {{ form.label('iso25964_rights', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">            
					            	{{ form.render('iso25964_rights', ['class': 'form-control form-control-success']) }}
					            </div>            
					        </div>
					        
					        <div class="form-group row">
					            {{ form.label('iso25964_license', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">            
					            	{{ form.render('iso25964_license', ['class': 'form-control form-control-success']) }}
					            </div>            
					        </div>					        
					        
					        <div class="form-group row">
					            {{ form.label('iso25964_created', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">    
					            	<div class="input-group date">
					            	<span class="input-group-addon"><i class="fa fa-calendar"></i></span>        
					            	{{ form.render('iso25964_created', ['class': 'form-control form-control-success required']) }}
					            	</div>
					            </div>            
					        </div>
					        
					        <div class="form-group row">
					            {{ form.label('iso25964_subject', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">            
					            	{{ form.render('iso25964_subject', ['class': 'form-control form-control-success']) }}
					            </div>            
					        </div>
					        
					        <div class="form-group row">
					            {{ form.label('iso25964_language', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">					            		            	
					            	{{ form.render('iso25964_language', ['class': 'form-control form-control-success']) }}
					            </div>            
					        </div>
					        
					        <div class="form-group row">
					            {{ form.label('iso25964_source', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">            
					            	{{ form.render('iso25964_source', ['class': 'form-control form-control-success']) }}
					            </div>            
					        </div>					        
					                
					        <div class="form-group row">
					            {{ form.label('iso25964_type', ['class': 'form-control-label col-sm-12']) }}
					            <div class="col-sm-8">            
					            	{{ form.render('iso25964_type', ['class': 'form-control form-control-success']) }}
					            </div>            
					        </div>
				
						</div>
						<div class="card-footer">
							<div class="form-actions"> {{ submit_button('Guardar', 'class': 'btn btn-primary') }} </div>
						</div>	
					</div>
				</fieldset>
				    
			{{ end_form() }}				
			</div>
						
			<div id="xpermisos" role="tabpanel" class="tab-pane {% if entidad.id_thesaurus is empty %} disabled {% endif %}">
			{{ form('sistema/admin/permisos', 'id': 'permisosForm', 'onsubmit': 'return fnValidateForm(this);', 'autocomplete': 'off', 'novalidate': 'novalidate') }}
			<fieldset>
				{{ form.render('id_thesaurus') }}
				<div class="card" style="min-height: 400px;">
					<div class="card-header"><i class="fa fa-edit text-danger"></i> {{ entidad.nombre }}</div>
					<div class="card-block">
					
							<div class="form-group row">
					            {{ form.label('is_activo', ['class': 'form-control-label col-sm-12']) }}            
					            <div class="col-sm-8">
					            	{{ form.render('is_activo', ['class': 'form-control form-control-success']) }}
					            </div>
					        </div>
					        
					        <div class="form-group row">
					            {{ form.label('is_publico', ['class': 'form-control-label col-sm-12']) }}            
					            <div class="col-sm-8">
					            	{{ form.render('is_publico', ['class': 'form-control form-control-success']) }}
					            </div>
					        </div>
					        
					        <div class="form-group row">
					            {{ form.label('is_primario', ['class': 'form-control-label col-sm-12']) }}            
					            <div class="col-sm-8">
					            	{{ form.render('is_primario', ['class': 'form-control form-control-success']) }}
					            </div>
					        </div>
					
					    	<div class="form-group row">
					    		<label class="form-control-label col-sm-12"> Permisos </label>
					        	<div class="col-sm-8">					        	
						        	<table class="table table-condensed">					        	
						        	<tbody>
						        		{% for ckey, cuser in usuarios_list %}
						        		<tr>
						        		<td class=""> <i class="fa {{ (cuser.app_role == 'ADMIN' ? 'fa-user' : 'fa-user-o') }}" title="{{ cuser.app_role }}"></i> </td>
						        		<td class="col-7"> {{ cuser.nombre }} </td>						        		
						        		<td class="col-5">					        							        		
						        			<select name="permisos[{{ cuser.id_usuario }}]" class="form-control">
						        				<option value="">-- </option>
						        				{% for permisoKey, permisoDesc in PERMISOS_TYPES %} 
						        				<option value="{{ permisoKey }}"  {% if cuser.permiso_usuario == permisoKey %} selected {% endif %}>{{ permisoKey }}</option> 
						        				{% endfor %}
						        			</select>
						        		</td></tr>
						        		{% endfor %}
						        	</tbody>
						        	</table>
					        	</div>					        				           
					        </div>
					        
					        <div class="form-group row">
					        <div class="small col-sm-8">
					        	<h5 class="page-header">&nbsp;</h5>						        							        								        		
						        <dl>{% for permisoKey, permisoDesc in PERMISOS_TYPES %} <dt>{{ permisoKey }}</dt><dd>{{ permisoDesc }}</dd> {% endfor %} </dl>						        		
						    </div>
						    </div>
					        
					</div>
					<div class="card-footer">
						<div class="form-actions"> {{ submit_button('Guardar', 'class': 'btn btn-primary') }} </div>
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
		
		$('#iso25964_language').tagsinput({
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
		
		$('.input-group.date').datepicker({
			format: 'yyyy-mm-dd'			
		});
		$('#editForm,#permisosForm')
			.enterAsTab({ 'allowSubmit': true})
			.find(":input:text:visible:not(disabled):not([readonly])").first().focus();
	});		
</script>
