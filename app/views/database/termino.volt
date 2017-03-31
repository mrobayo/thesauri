
	<h4 class="card-title" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125); padding-bottom: 4px;"> 
	
		{% if auth['is_admin'] %}
		<a href="{{ url('database/editar/'~entidad.id_termino) }}" class="btn btn-sm btn-primary pull-right"> <i class="fa fa-edit"></i> </a>		
		{% endif %}
		
		{{ entidad.nombre }} 
	
	</h4>	
						
	<table class="table table-striped table-bordered">
		<tbody>
			
			<tr> <td>Definición</td><td>{{ entidad.descripcion }}</td> </tr>
			
			{% for row in relaciones_list %}
			
				<tr> <td> {{ RELATION_TYPES[ row['tipo_relacion'] ] }} </td><td>{{ row['nombre'] }}</td> </tr>
			
			{% endfor %}
			
			<tr> <td>Comentario</td><td> </td> </tr>
			<tr> <td>URI</td><td>{{ link_to( rdf_uri, rdf_uri ) }}</td> </tr>
			
			{% if auth['is_admin']  %}
			<tr> <td>Estado</td><td> {{ entidad.estado_termino }} </td> </tr>
			{% endif %}

		</tbody>
		<tfoot>
			<tr>
			<td colspan="99">				
				<small class="text-muted pull-right">Ultima modificación {{ ultima_mod }}</small>			
			</td>
			</tr>			
		</tfoot>
	</table>
	
