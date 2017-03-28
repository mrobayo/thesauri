<div class="page-header">
	{% if entidad is empty %}		
		<h2>Exploración de Vocabularios</h2>
	{% else %}
    	<h2> {{ TYPES[ entidad.iso25964_type ] }} </h2>
	{% endif %}    
</div>
	
	{% if entidad is empty %}
	
	<div class="card">
	
		<div class="card-header">
			<i class="fa fa-bank"></i> Thesaurus
		</div>
		
		<table class="table table-hover table-bordered table-sm">
			<thead>
				<tr><th>#</th>
					<th class="col-3">Título</th>						
					<th class="col-8">Descripción</th>
					<th class="col-1">Ultima<br> Actividad</th>					
				</tr>
			</thead>
			<tbody>
				{% for ckey, row in items_list %}
				<tr>
					<td>{{ loop.index }}</td>
					<td>{{ link_to( row.rdf_uri, row.nombre ) }}</td>							
					<td class="">{{ row.iso25964_description }}</td>					
					<td class="text-center">{{ row.ultima_actividad }}</td>
					
					
				</tr>
				{% endfor %} 
			</tbody>					
		</table>
	
	
	</div>
	
	{% else %}
	
	
	<div class="card">
		
		<div class="card-header">
			<i class="fa fa-bank"></i> {{ entidad.nombre }}
		</div>
		<div class="card-block">
			<p>{{ entidad.iso25964_description }}</p>
			
			<div class="row">
			
				<div class="col-sm-6 sidebar">
					<h4 class="card-title" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125); padding-bottom: 4px;">Explorar</h4>					
					
					<div class="card panel-default" style="min-height:720px;">
					    <div class="card-header" style="padding-bottom: 0; border-bottom: 0">
							<ul class="nav nav-tabs" role="tablist">
								<li role="presentation" class="nav-item">
						    		<a href="#xlist" class="nav-link active" aria-controls="xlist" role="tab" data-toggle="tab"> <i class="fa fa-search"></i> Alfabético </a>
						    	</li>						    	
							</ul>
						</div>					
						<div class="card-block">
							<div class="tab-content">
						    <div id="xlist" role="tabpanel" class="tab-pane active">						    		
						    		
						    		<!-- Terminos Aprobados -->
									<table class="table table-hover table-bordered table-sm">
										<thead>
											<tr><th>#</th>
												<th class="col-3">Término</th>						
																													
											</tr>
										</thead>
										<tbody>
											{% for ckey, row in terms_list %}
											<tr>
												<td class="">{{ loop.index }}</td>
												<td class="col-12">{{ link_to( row.rdf_uri, row.nombre ) }}</td>												
											</tr>
											{% endfor %} 
										</tbody>					
									</table>				    		
						    								    								    
						    </div>
							</div>
						</div>							
					</div>	
					
								
				</div>
				
				<div class=" col-sm-6">
					<h4 class="card-title" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125); padding-bottom: 4px;"> {{ TYPES[ entidad.iso25964_type ] }} </h4>					
					<table class="table table-striped table-bordered">
						<tbody>
							<tr> <td class="col-2">Titulo</td><td>{{ entidad.nombre }}</td> </tr>
							<tr> <td class="col-2">Identificador</td><td>{{ entidad.iso25964_identifier }}</td> </tr>					
							<tr> <td>URI</td><td>{{ link_to( entidad.rdf_uri, entidad.rdf_uri ) }}</td> </tr>
							<tr> <td>Editor</td><td>{{ entidad.iso25964_publisher }}</td> </tr>
							<tr> <td>Derechos/Copyright</td><td>{{ entidad.iso25964_rights }}</td> </tr>							
							<tr> <td>Licencia</td><td>{{ entidad.iso25964_license }}</td> </tr>
							<tr> <td>Cobertura/Alcance</td><td>{{ entidad.iso25964_coverage }}</td> </tr>
							<tr> <td>Fecha creación</td><td>{{ entidad.iso25964_created }}</td> </tr>							
							<tr> <td>Temática/Contenido</td><td>{{ entidad.iso25964_subject }}</td> </tr>
							<tr> <td>Idiomas soportados</td><td>{{ entidad.iso25964_language }}</td> </tr>
							<tr> <td>Fuentes</td><td>{{ entidad.iso25964_source }}</td> </tr>											
							<tr> <td>Creador</td><td>{{ entidad.iso25964_creator }}</td> </tr>
							<tr> <td>Colaboradores</td><td>{{ entidad.iso25964_contributor }}</td> </tr>
							<tr> <td>Género del Vocabulario</td><td>{{ entidad.iso25964_type }}</td> </tr>					
						</tbody>
					</table>				
				</div>
				
			</div>
						
		</div>
		
		
		
		
		
		
		
		<div class="card-footer">
		
			<div class="pull-right">
			{% if entidad.ultima_actividad is empty %}
			
			<p>No ha registrado ninguna actividad.</p>
			
			{% else %}
			
			<p>Última Actividad: {{ entidad.ultima_actividad }}.</p>
	
			{% endif %}
			</div>
		
			<p>Descargar este {{ TYPES[ entidad.iso25964_type ] }} en formato:</p>
			
			<a href="#" title="RDF/XML">			  
				<img class="img-thumbnail rounded float-left" src="{{ url('img/rdf-doc.64.png') }}" alt="RDF - Resource Description Framework"/>				
			</a>
			
			<a href="#" title="XML/ISO-25964">			  
				<img class="img-thumbnail rounded float-left" src="{{ url('img/xml-doc.64.png') }}" alt="XML - ISO.25964-1"/>				
			</a>
			
			<div class="clearfix"></div>
			
			
					
		</div>
		
		
	</div>
	
	{% endif %}
	
	 


