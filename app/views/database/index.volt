<h4 class="page-header">
	{% if entidad is empty %}		
		<h2><!-- Exploración --></h2>
	{% else %}    	   	
    	<nav class="breadcrumb">
			{% if ! (entidad is empty) %}	
				<a class="breadcrumb-item active" href="{{ url( entidad.rdf_uri ) }}"> {{ entidad.nombre }}</a>
			{% endif %}		
		</nav>    	    	
	{% endif %}    
</h4>
	
	{% if entidad is empty %}
	
	<div class="card">
	
		<div class="card-header">
			<i class="fa fa-bank"></i> Thesaurus
		</div>
		
		<div class="card-block">
		
		{% for ckey, row in items_list %}
			<div class="card float-left" style="width: 20rem; margin: 1rem;">
			  <!-- <img class="card-img-top" src="..." alt="Card image cap"> -->
			  <div class="card-block" style="min-height: 8rem;">
			    <h4 class="card-title">{{ link_to( row.rdf_uri, row.nombre ) }}</h4>
			    <p class="card-text">{{ row.iso25964_description }}</p>
			  </div>
			  <ul class="list-group list-group-flush">
			  	<!-- <li class="list-group-item justify-content-between">Tipo <span class="badge badge-default badge-pill">{{ row.iso25964_type }}</span></li> -->
			    <li class="list-group-item justify-content-between bg-faded">Número de Términos <span class="badge badge-default badge-pill">{{ row.term_aprobados }}</span></li>			    
			  </ul>
			  <div class="card-block">			    
			    <p>
			    	<a href="{{ url( row.rdf_uri ) }}" class="btn btn-primary">Explorar</a>
			    	<br><br>
			    	<small class="pull-right"> Ultima actualización: 23/02/2017</small>
			    </p>
			  </div>
			</div>
		{% endfor %} 
		
		</div>
	
	</div>
	
	{% else %}
	
	
	<div class="card1">
				
		<div class="card-block1">
			<!-- <p>{{ entidad.iso25964_description }}</p> -->
			
			<div class="row">
			
				<div class="col-sm-5 sidebar">
					<h4 class="card-title" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125); padding-bottom: 4px;">
												
						<div class="btn-group pull-right" role="group">						
							<a id="refrescarAlfabetoBtn" href="{{ url(entidad.rdf_uri) }}" title="Refrescar listado" class="btn btn-sm btn-secondary"> 
								<i class="fa fa-refresh"></i> 
							</a>							
							{% if permiso_enviar %}
							<a href="{{ url('index/enviar/'~entidad.id_thesaurus) }}" title="Nuevo Término" class="btn btn-sm btn-primary"> 
								<i class="fa fa-plus"></i> 
							</a>
							{% endif %}						
						</div>
						
						Explorar
					</h4>					
					
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
						    
						    	<div class="text-center" style="padding: 0em 2em 1em 2em;">
						    		{% for letra, num in letras_list %}
						    		
							    		{% if num is empty %}		
											<span class="text-muted">{{ letra }}</span>
										{% else %}
									    	<a href="{{ url('database/json/'~entidad.id_thesaurus~'/'~letra) }}" title="{{ num }} términos" class="alfabetoByJson">{{ letra }}</a>
										{% endif %}
										
										&nbsp;																    		
						    		{% endfor %}
						    	</div>					    		
						    		
						    		<!-- Terminos Aprobados -->
									<table id="terminosTable" class="table table-hover table-sm">										
										<tbody>
											{% for ckey, row in terms_list %}
											<tr>												
												<td>
													<a href={{ url( row.rdf_uri ) }} class="verTerminoLink">{{ row.nombre }}</a>
												</td>												
											</tr>
											{% endfor %} 
										</tbody>					
									</table>				    		
						    								    								    
						    </div>
							</div>
						</div>							
					</div>	
					
								
				</div>
				
				<div id="infoDetalle" class="col-sm-7">
					<h4 class="card-title" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125); padding-bottom: 4px;"> 
						
						<div class="btn-group pull-right" role="group" aria-label="Opciones">
						
						{% if auth['is_admin'] %}				
							<a href="{{ url('sistema/admin/thesaurus/'~entidad.id_thesaurus) }}" title="Editar Thesaurus" class="btn btn-sm btn-secondary"> <i class="fa fa-edit"></i> </a>
						{% endif %}
											
						<a href="{{ url('database/arbor/'~entidad.iso25964_identifier) }}" title="Visualizar árbol de relaciones" class="btn btn-sm btn-primary "> 
							<i class="fa fa-code-fork fa-rotate-90"></i> 
						</a>
						
						</div>						
						
						{{ entidad.nombre }}
						
					
					</h4>					
					<table class="table table-striped table-bordered">
						<tbody>
							<tr><td colspan="2"> {{ entidad.iso25964_description }} </td></tr>		
							<tr><td style="width:20%" class="col-2">Identificador</td>
								<td style="width:80%" class="col-10">{{ entidad.iso25964_identifier }}</td> 
							</tr>					
							<tr> <td>URI</td><td>{{ link_to( entidad.rdf_uri, entidad.rdf_uri ) }}</td> </tr>
							<tr> <td>Editor</td><td>{{ entidad.iso25964_publisher }}</td> </tr>
							<tr> <td>Derechos/Copyright</td><td>{{ entidad.iso25964_rights }}</td> </tr>							
							<tr> <td>Licencia</td><td>{{ RIGHTS[ entidad.iso25964_license ] }}</td> </tr>
							<tr> <td>Cobertura/Alcance</td><td>{{ entidad.iso25964_coverage }}</td> </tr>
							<tr> <td>Fecha creación</td><td>{{ entidad.iso25964_created }}</td> </tr>							
							<tr> <td>Temática/Contenido</td><td>{{ entidad.iso25964_subject }}</td> </tr>
							<tr> <td>Idiomas soportados</td><td>{{ entidad.iso25964_language }}</td> </tr>
							<tr> <td>Fuentes</td><td>{{ entidad.iso25964_source }}</td> </tr>											
							<tr> <td>Creador</td><td>{{ entidad.iso25964_creator }}</td> </tr>
							<tr> <td>Colaboradores</td><td>{{ entidad.iso25964_contributor }}</td> </tr>
							<tr> <td>Género del Vocabulario</td><td>{{ TYPES[ entidad.iso25964_type ] }}</td> </tr>					
						</tbody>
					</table>				
				</div>
				
			</div>
						
		</div>
		
		
		
		
		
		
		
		<div class="card-footer">
		
			<div class="pull-right">
			{% if entidad.ultima_actividad is empty %}
			
			<p class="text-muted">No ha registrado ninguna actividad.</p>
			
			{% else %}
			
			<small class="text-muted">Última Actividad: {{ entidad.ultima_actividad }}.</small>
	
			{% endif %}
			</div>
		
			<p>Descargar este {{ TYPES[ entidad.iso25964_type ] }} en formato:</p>
			
			<a href="{{ url("index/download/"~entidad.id_thesaurus) }}" 
			   download="data-{{ entidad.id_thesaurus }}.json" title="RDF/XML">			  
				<img class="img-thumbnail rounded float-left" src="{{ url('img/rdf-doc.64.png') }}" alt="RDF - Resource Description Framework"/>				
			</a>
			
			<!-- 
			<a href="#" title="XML/ISO-25964">			  
				<img class="img-thumbnail rounded float-left" src="{{ url('img/xml-doc.64.png') }}" alt="XML - ISO.25964-1"/>				
			</a> -->
			
			<div class="clearfix"></div>					
					
		</div>
		
		
	</div>
	
	{% endif %}
	


	<script>
	function fnVerTermino(e){
		e.preventDefault();			
		$('#infoDetalle').empty().append(fnSpinnerIcon());
		
		$.post($(this).attr('href'), function(data){			
			$('#infoDetalle').empty().html(data);
			try {
				$('html, body').animate({ scrollTop: $('#infoDetalle').first().offset().top-80 }, 700);	
			} catch(e) { /*ignore*/ }
		});
	}
	
	function fnEditTermino(e) {
		e.preventDefault();
		$('#infoDetalle').empty().append(fnSpinnerIcon());
		
		$.get($(this).attr('href'), function(data){
			$('#infoDetalle').empty().html(data);
			try {
				$('html, body').animate({ scrollTop: $('#infoDetalle').first().offset().top-80 }, 700);	
			} catch(e) { /*ignore*/ }
		});
	}
	
	$(function() {
	
		$('.verTerminoLink').click(fnVerTermino);
		
		$('#refrescarAlfabetoBtn').click(function(e){
			e.preventDefault();
			$('.alfabetoByJson.bg-faded:first').click();	
		});
		
		$('.alfabetoByJson').click(function(e) {
			e.preventDefault();			
			
			tBody = $('#terminosTable tbody').empty().append(fnSpinnerIcon());
			
			$.get($(this).attr('href'), function(data){			
				tBody.empty();
				
				$.each(data.result, function(key, value){
					//vObs = (value.estado_termino == 'CANDIDATO') ? ' <span class="text-muted text-italic">(pendiente aprobación)</span>' : '';					
					vObs = '<span class="badge badge-primary badge-pill pull-right"><i class="fa fa-edit"></i></span>';					
					vObs = $('<a>', {'href': '{{ url("database/editar/") }}' + value.id_termino + "/inline" }).append(vObs);
					vObs.click(fnEditTermino);
					
					if (value.rdf_uri) 
					{
						vLink = $('<a>', {'href': value.rdf_uri, 'text': value.nombre}).click( fnVerTermino );
					}
					else 
					{
						vLink = '<span>'+ value.nombre +' </span> ';
					}
					tBody.append( $('<tr>').append( $('<td>').append(vLink).append(vObs) ));					
				});
				
			}, 'json');
			
			$(this).addClass('bg-faded').siblings().removeClass('bg-faded');
		});
		
		$('#infoDetalle').on( "click", ".editarTermino", fnEditTermino);		
	})
	
	</script>


