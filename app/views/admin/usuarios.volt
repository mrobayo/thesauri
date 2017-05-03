
{{ content() }}


<h4 class="page-header">Administración de Usuarios</h4>

	<div class="row">
	
		<div class="col-sm-2">
				
			<ul class="nav nav-pills flex-column" role="tablist">

				{% if entidad.id_usuario is empty %}								
				<li role="presentation" class="nav-item">
		    		<a href="#xlist" class="nav-link {% if items_list is empty %} disabled {% else %} active {% endif %}" aria-controls="xlist" role="tab" data-toggle="tab"> <i class="fa fa-search"></i> Consultar </a>
		    	</li>
		    	{% endif %}
		    	
				<li role="presentation" class="nav-item">
		    		<a href="#xnuevo" class="nav-link {% if entidad.id_usuario is empty %} {% if items_list is empty %} active {% endif %} {% else %} active {% endif %}" aria-controls="xnuevo" role="tab" data-toggle="tab"> 
		    			<i class="fa {% if entidad.id_usuario is empty %} fa-file-o {% else %} fa-edit {% endif %}"></i> {% if entidad.id_usuario is empty %} Crear Nuevo {% else %} Editar {% endif %} 
		    		</a>
		    	</li>
		    	
		    	{% if not(entidad.id_usuario is empty) %}
		    	<li role="presentation" class="nav-item">
		    		{{ link_to('sistema/admin/usuarios', 'Cancelar', 'class': 'nav-link') }}
		    		
		    		<!-- {{ link_to('#', 'Cancelar', ['class': 'nav-link'])  }} <a href="#" class="nav-link"> Cancelar</a> -->
		    	</li>
		    	{% endif %}
			</ul>
					
		</div>
		
		<div class="tab-content col-sm-10">
			
			<div id="xlist" role="tabpanel" class="tab-pane {% if entidad.id_usuario is empty %} {% if items_list is empty %} disabled {% else %} active {% endif %} {% endif %}">
				<div class="card" style="min-height: 400px;">
				
					<div class="card-header"> <i class="fa fa-search"></i> Usuarios </div>
										
					<table class="table table-hover table-bordered table-sm">
					<thead>
						<tr><th>#</th>
							<th>Nombre</th>						
							<th>Email</th>
							<th>Role</th>
							<th>Recibir<br> Avisos</th>
							<th>Ultimo Login</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						{% for ckey, row in items_list %}
						<tr>
							<td>{{ loop.index }}</td>
							<td>{{ link_to( 'sistema/admin/usuarios/'~row.id_usuario, row.nombre ) }}</td>							
							<td class="">{{ row.email }}</td>
							<td class=""> <i class="fa {{ (row.app_role == 'ADMIN' ? 'fa-user' : 'fa-user-o') }}" title="{{ row.app_role }}"></i> {{ ROLE_TYPES[ row.app_role ] }}</td>
							<td class="text-center">{{ row.recibir_avisos }}</td>
							<td class="text-center">{{ row.login_history }}</td>															
							<td class="text-center"> <i class="fa {{ (row.is_activo ? 'fa-check text-success' : 'fa-times text-danger') }}"></i> </td>
						</tr>
						{% endfor %} 
					</tbody>					
					</table>
										
										    
				</div>			
			</div>
			
			<div id="xnuevo" role="tabpanel" class="tab-pane {% if entidad.id_usuario is empty %} {% if items_list is empty %} active {% endif %} {% else %} active {% endif %}">
			{{ form('sistema/admin/usuarios', 'id': 'thisForm', 'onsubmit': 'return fnValidateForm(this);', 'autocomplete': 'off', 'novalidate': 'novalidate') }}
				
				<fieldset>
				    {{ form.render('id_usuario') }}
				    
				    <div class="card">
				    
				    	<div class="card-header"><i class="fa {% if entidad.id_usuario is empty %} fa-file-o {% else %} fa-edit {% endif %}"></i> 
				    		{% if entidad.id_usuario is empty %} Nuevo {% else %} {{ entidad.nombre }} {% endif %}
				    	</div>
				    	
					    <div class="card-block">
					
					        <div class="form-group row">
					            {{ form.label('nombre', ['class': 'form-control-label col-sm-3']) }}            
					            <div class="col-sm-7"> {{ form.render('nombre', ['class': 'form-control form-control-success required']) }} </div>
					        </div>					        
					        <div class="form-group row">
					            {{ form.label('email', ['class': 'form-control-label col-sm-3']) }}            
					            <div class="col-sm-7"> {{ form.render('email', ['class': 'form-control form-control-success required email']) }} </div>
					        </div>
					        <div class="form-group row">
					            {{ form.label('app_role', ['class': 'form-control-label col-sm-3']) }}            
					            <div class="col-sm-7"> {{ form.render('app_role', ['class': 'form-control form-control-success']) }} </div>
					        </div>					        
					        <div class="form-group row">
					            {{ form.label('recibir_avisos', ['class': 'form-control-label col-sm-3']) }}            
					            <div class="col-sm-7"> {{ form.render('recibir_avisos', ['class': 'form-control form-control-success']) }} </div>
					        </div>					        
					        {% if entidad.id_usuario is empty %}					        
					        <div class="form-group row">
					            {{ form.label('is_activo', ['class': 'form-control-label col-sm-3']) }}            
					            <div class="col-sm-7"> SI </div>
					        </div>					        	
					        {% else %}
					        <div class="form-group row">
					            {{ form.label('is_activo', ['class': 'form-control-label col-sm-3']) }}            
					            <div class="col-sm-7"> {{ form.render('is_activo', ['class': 'form-control form-control-success']) }} </div>
					        </div>
					        {% endif %}
				
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
	$(function(){		
		$('.bootstrap-tagsinput').addClass('form-control');				
		$('#thisForm')
			.enterAsTab({ 'allowSubmit': true})
			.find(":input:text:visible:not(disabled):not([readonly])").first().focus();
	});		
</script>
