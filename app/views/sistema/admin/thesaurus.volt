
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
							<td>
								{{ link_to( 'sistema/admin/thesaurus/'~row.id_thesaurus, row.nombre ) }}
								<!-- {{ link_to( 'sistema/thesaurus/view/'~row.id_thesaurus, row.nombre ) }} -->
							</td>
							
							<td></td>
							<td></td>
							<td></td>
							<td>
								{{ link_to( row.rdf_uri, '<i class="fa fa-external-link"></i>' ) }}							
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
					            	<div class="input-group">
					            	<span class="input-group-addon"><i class="fa fa-calendar"></i></span>        
					            	{{ form.render('iso25964_created', ['class': 'form-control form-control-success']) }}
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
		$('#iso25964_created').datepicker({
			format: 'yyyy-mm-dd'			
		});		
		$('#thesaurusForm')
			.enterAsTab({ 'allowSubmit': true})
			.find(":input:text:visible:not(disabled):not([readonly])").first().focus();
	});		
</script>
