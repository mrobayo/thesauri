
{{ content() }}


<h4 class="page-header">Nuevo Thesaurus</h4>


{{ form('sistema/admin/thesaurus', 'id': 'thesaurusForm', 'onsubmit': 'return fnValidateForm(this);', 'autocomplete': 'off', 'novalidate': 'novalidate') }}

    <fieldset>
    
    <div class="card">
	    <div class="card-block bg-faded">
	
	        <div class="form-group row">
	            {{ form.label('nombre', ['class': 'form-control-label col-sm-12']) }}            
	            <div class="col-sm-8">
	            	{{ form.render('nombre', ['class': 'form-control form-control-success', 'aria-describedby': 'nombreHelp']) }}            
	            	<small id="nombreHelp" class="form-text text-muted">Título del Thesaurus (requerido)</small>
	            </div>
	        </div>
	
			<div class="form-group row">
	            {{ form.label('descripcion', ['class': 'form-control-label col-sm-12']) }}
	            <div class="col-sm-8">            
	            	{{ form.render('descripcion', ['class': 'form-control form-control-success', 'aria-describedby': 'descripcionHelp']) }}            
	            	<small id="descripcionHelp" class="form-text text-muted">(requerido)</small>
	            </div>
	        </div>
	
	        <div class="form-group row">
	            {{ form.label('identifier', ['class': 'form-control-label col-sm-12']) }}
	            <div class="col-sm-8">            
	            	{{ form.render('identifier', ['class': 'form-control form-control-success', 'aria-describedby': 'identifierHelp']) }}            
	            	<small id="identifierHelp" class="form-text text-muted">Identificador del Thesaurus (requerido)</small>
	            </div>
	        </div>
	        
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
	            	{{ form.render('contributor', ['class': 'form-control form-control-success', 'minlength': '8', 'aria-describedby': 'contributorHelp']) }}            
	            	<small id="contributorHelp" class="form-text text-muted">Personas u organizaciones quienes contribuyeron con el Thesaurus</small>
	            </div>            
	        </div>
	
	        <div class="form-group row">
	            {{ form.label('publisher', ['class': 'form-control-label col-sm-12']) }}
	            <div class="col-sm-8">            
	            	{{ form.render('publisher', ['class': 'form-control form-control-success', 'minlength': '8', 'aria-describedby': 'publisherHelp']) }}            
	            	<small id="publisherHelp" class="form-text text-muted">Entidad responsable de la publicación</small>
	            </div>            
	        </div>
	        
	        <div class="form-group row">
	            {{ form.label('coverage', ['class': 'form-control-label col-sm-12']) }}
	            <div class="col-sm-8">            
	            	{{ form.render('coverage', ['class': 'form-control form-control-success', 'minlength': '8', 'aria-describedby': 'coverageHelp']) }}            
	            	<small id="coverageHelp" class="form-text text-muted">Cobertura espacial o temporal del Thesaurus</small>
	            </div>            
	        </div>
	        
	        <div class="form-group row">
	            {{ form.label('rights', ['class': 'form-control-label col-sm-12']) }}
	            <div class="col-sm-8">            
	            	{{ form.render('rights', ['class': 'form-control form-control-success', 'minlength': '8', 'aria-describedby': 'rightsHelp']) }}            
	            	<small id="rightsHelp" class="form-text text-muted">Copyright / Otros Derechos de la Información</small>
	            </div>            
	        </div>
	        
	        <div class="form-group row">
	            {{ form.label('license', ['class': 'form-control-label col-sm-12']) }}
	            <div class="col-sm-8">            
	            	{{ form.render('license', ['class': 'form-control form-control-success', 'minlength': '8', 'aria-describedby': 'licenseHelp']) }}            
	            	<small id="licenseHelp" class="form-text text-muted"></small>
	            </div>            
	        </div>
	        
	        
	        <div class="form-group row">
	            {{ form.label('created', ['class': 'form-control-label col-sm-12']) }}
	            <div class="col-sm-8">            
	            	{{ form.render('created', ['class': 'form-control form-control-success', 'minlength': '8', 'aria-describedby': 'createdHelp']) }}            
	            	<small id="Help" class="form-text text-muted"></small>
	            </div>            
	        </div>
	        
	        <div class="form-group row">
	            {{ form.label('subject', ['class': 'form-control-label col-sm-12']) }}
	            <div class="col-sm-8">            
	            	{{ form.render('subject', ['class': 'form-control form-control-success', 'minlength': '8', 'aria-describedby': 'subjectHelp']) }}            
	            	<small id="Help" class="form-text text-muted">Indice de Términos indicando las materias del contenido</small>
	            </div>            
	        </div>
	        
	        <div class="form-group row">
	            {{ form.label('language', ['class': 'form-control-label col-sm-12']) }}
	            <div class="col-sm-8">            
	            	{{ form.render('language', ['class': 'form-control form-control-success', 'minlength': '8', 'aria-describedby': 'languageHelp']) }}            
	            	<small id="languageHelp" class="form-text text-muted">Idiomas soportados por el Thesaurus</small>
	            </div>            
	        </div>
	        
	        <div class="form-group row">
	            {{ form.label('source', ['class': 'form-control-label col-sm-12']) }}
	            <div class="col-sm-8">            
	            	{{ form.render('source', ['class': 'form-control form-control-success', 'minlength': '8', 'aria-describedby': 'sourceHelp']) }}            
	            	<small id="sourceHelp" class="form-text text-muted">Recursos desde los cuales el Thesaurus fue derivado</small>
	            </div>            
	        </div>
	        
	                
	        <div class="form-group row">
	            {{ form.label('type', ['class': 'form-control-label col-sm-12']) }}
	            <div class="col-sm-8">            
	            	{{ form.render('type', ['class': 'form-control form-control-success', 'minlength': '8', 'aria-describedby': 'typeHelp']) }}            
	            	<small id="typeHelp" class="form-text text-muted">El género del vocabulario</small>
	            </div>            
	        </div>
	               
	        <div class="form-group row">
	            {{ form.label('format', ['class': 'form-control-label col-sm-12']) }}
	            <div class="col-sm-8">            
	            	{{ form.render('format', ['class': 'form-control form-control-success', 'minlength': '8', 'aria-describedby': 'formatHelp']) }}            
	            	<small id="formatHelp" class="form-text text-muted">Formato de Archivo o Medio Físico del Thesaurus</small>
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

