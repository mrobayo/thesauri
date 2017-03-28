
	<h4 class="card-title" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125); padding-bottom: 4px;"> {{ entidad.nombre }} </h4>	
						
	<table class="table table-striped table-bordered">
		<tbody>
			<tr> <td class="col-2">Término</td><td>{{ entidad.nombre }}</td> </tr>
			<tr> <td class="col-2">Definición</td><td>{{ entidad.descripcion }}</td> </tr>					
			<tr> <td>Términos Relacionados</td><td> </td> </tr>
			<tr> <td>Comentario</td><td> </td> </tr>
			<tr> <td>URI</td><td>{{ url( entidad.rdf_uri ) }}</td> </tr>							
								
		</tbody>
		<tfoot>
			<tr>
			<td colspan="99"> 
				
				<small class="text-muted pull-right">Ultima modificación {{ ultima_mod }}</small>
			
			</td>
			</tr>			
		</tfoot>
	</table>
	
