
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

			<li role="presentation" class="nav-item"><a href="#xnuevo"
				class="nav-link active" aria-controls="xnuevo" role="tab" data-toggle="tab"> <i class="fa fa-file-o"></i> Crear Nuevo
			</a></li>

		</ul>

	</div>
	<div class="tab-content col-sm-10">

		<div id="xnuevo" role="tabpanel"
			class="tab-pane {% if entidad.id_termino is empty %} {% if items_list is empty %} active {% endif %} {% else %} active {% endif %}">
			{{ form('index/enviar', 'id': 'thisForm', 'onsubmit': 'return fnValidateForm(this);', 'autocomplete': 'off', 'novalidate': 'novalidate') }}

			<fieldset>
				{{ form.render('id_termino') }} {{ form.render('id_thesaurus') }}

				<div class="card">

					<div class="card-header">
						<i class="fa {% if entidad.id_termino is empty %} fa-file-o {% else %} fa-edit {% endif %}"></i>
						{% if entidad.id_termino is empty %} Nuevo {% else %} {{
						entidad.nombre }} {% endif %}
					</div>

					<div class="card-block">

						<div class="form-group row">
							{{ form.label('nombre', ['class': 'form-control-label col-sm-12']) }}
							<div class="col-sm-8">
								{{ form.render('nombre', ['class': 'form-control
								form-control-success required' ]) }}
								<!-- 'remote': url("database/terminoYaExiste/"~entidad.id_thesaurus) -->
							</div>
						</div>

						<div class="form-group row">
							{{ form.label('descripcion', ['class': 'form-control-label col-sm-12']) }}
							<div class="col-sm-8">{{ form.render('descripcion', ['class': 'form-control form-control-success']) }}</div>
						</div>

						<div class="form-group row">
							{{ form.label('TG', ['class': 'form-control-label col-sm-12']) }}
							<div id="tahTG" class="col-sm-8">
								<div class="input-group">
								<!-- {{ form.render('TG', ['class': 'termino_typeahead form-control form-control-success']) }} -->								
								<input id="TG" class="termino_typeahead form-control form-control-success" type="text" aria-describedby="TGHelp" name="TG">								
								<span class="input-group-addon" style="background-color: transparent;"> 
									<i class="fa fa-check text-success" style="display:none;"></i> 
									<i class="fa fa-exclamation text-warning" style="display:none;"></i> 
								</span>
								</div>								
							</div>
							<em class="text-warning form-control-feedback col-sm-4" style="display: none"> Termino es nuevo, deberá ser aprobado. </em>
						</div>

						<div class="form-group row">
							<label class="form-control-label col-sm-8" for="SIN[]">
								<button data-input-name="SIN[]" type="button" class="add-termino-btn btn btn-outline-primary pull-right btn-sm">
									<i class="fa fa-plus"></i>
								</button> Sinónimos
							</label>

							<div class="col-sm-8">{{ form.render('SIN[]', ['class': 'termino_typeahead form-control form-control-success']) }}</div>
						</div>

						<div class="form-group row">
							<label class="form-control-label col-sm-8" for="TR[]">
								<button data-input-name="TR[]" type="button"
									class="add-termino-btn btn btn-outline-primary pull-right btn-sm">
									<i class="fa fa-plus"></i>
								</button> Término relacionado
							</label>
							<div class="col-sm-8">{{ form.render('TR[]', ['class': 'termino_typeahead form-control form-control-success']) }}</div>
						</div>

						<div class="form-group row">
							{{ form.label('dc_source', ['class': 'form-control-label col-sm-12']) }}
							<div class="col-sm-8">{{ form.render('dc_source', ['class': 'form-control form-control-success']) }}</div>
						</div>

						{#
						<div class="form-group row">
							{{ form.label('id_thesaurus', ['class': 'form-control-label col-sm-12']) }}
							<div class="col-sm-8">{{ form.render('id_thesaurus', ['class': 'form-control form-control-success required']) }}</div>
						</div>
						#}


						<div class="form-group row">
							{{ form.label('iso25964_language', ['class': 'form-control-label col-sm-12']) }}
							<div class="col-sm-8">{{ form.render('iso25964_language', ['class': 'form-control form-control-success']) }}</div>
						</div>


					</div>
					<div class="card-footer">
						<div class="form-actions">{{ submit_button('Guardar', 'class': 'btn btn-primary') }}</div>
					</div>
				</div>
			</fieldset>

			{{ end_form() }}
		</div>


	</div>

</div>

<script>

$(function() {
	
	// instantiate bloodhound suggestion engine
	var terminosBh = new Bloodhound({
	  datumTokenizer: function(item) { return Bloodhound.tokenizers.whitespace(item.name); }, //Bloodhound.tokenizers.whitespace,
	  queryTokenizer: Bloodhound.tokenizers.whitespace,
	  identify: function(item) { return item.id; },
	  prefetch: {
	        url: '{{ url("database/json/"~entidad.id_thesaurus) }}',
	        filter: function(response) {	
	        	return $.map( response.result, function( aData, nId ) { return {'id': nId, 'name': aData[0]}; });
	        }
	    }
	});
	
	// initialize bloodhound suggestion engine
	terminosBh.clearPrefetchCache();
	terminosBh.initialize();
	
	// Typeahead
	function fnBindTypeAhead(inputField) {
		inputField.typeahead({
			  items: 'all',
			  minLength: 0,
			  highlight: true,
			  source: terminosBh.ttAdapter(),
			  display: 'name'
			});	
		inputField.change(function() {
			vThis = $(this);
			var current = vThis.typeahead("getActive");
			
			bIsOk = current && current.name == vThis.val();
			
			if (current && current.name == vThis.val()) {			
				inputField.attr('data-id', current.id);								
			}
			else {
				vThis.attr('data-id', ''); // val('');
			}
			
			vThis.closest('div.form-group').find('i.fa-check').toggle( bIsOk );
			vThis.closest('div.form-group').find('i.fa-exclamation').toggle( !bIsOk );
			vThis.closest('div.form-group').find('em.text-warning').toggle( !bIsOk );
		});	
	}	
	
	// Valida que el nombre se unico
	 
	$('#nombre').change(function(){		
		vNombre = $('#nombre'); 		
		$.post('{{ url("database/terminoYaExiste/"~entidad.id_thesaurus) }}', {'nombre': $(this).val()}, function(result) {
		
			if (result != 'true') {				
				vError = $('<em>', {'id': "nombre-error", 'class': 'error form-control-feedback col-sm-4', 'html': result})				
				if ($('em:visible', vNombre.closest('div.form-group')).length == 0) vError.addClass('form-control-feedback col-sm-4').appendTo( vNombre.closest('div.form-group') );	
			}
			else {
				vNombre.closest('div.form-group').find('em').remove();
			}
			
		}, 'json');		
	});
	
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
		
		fnBindTypeAhead(vInput);
		vInput.focus();		
	});
		
	
	// Bind Typeahead	
	fnBindTypeAhead( $(".termino_typeahead") );
		
});
</script>