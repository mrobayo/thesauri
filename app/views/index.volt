<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
        {{ get_title() }}
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
		<link rel="SHORTCUT ICON" href="{{ config.application.baseUri }}favicon.png" />

		{{ stylesheet_link("css/bootstrap-tagsinput.css") }} <!-- vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.css -->
		{{ stylesheet_link("css/bootstrap-tagsinput-typeahead.css") }} <!-- vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput-typeahead.css -->	
		{{ stylesheet_link("css/bootstrap-datepicker3.min.css") }} <!-- vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css -->

		<!-- vendor/components-font-awesome/css/font-awesome.min.css -->		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
		
		<style type="text/css">
			html {
  				position: relative;
  				min-height: 100%;
			}
			body {
				min-height: 15rem;
				padding-top: 4.5rem;
				padding-bottom: 2rem;
                margin-bottom: 4rem;				
			}			
			footer#main-footer {
				position: absolute; bottom: 0; right: 0;
				width: 100%;
				z-index: -1;
				border-top: 1px solid #e3e3e3;				
			}
			footer#main-footer p {
				text-align: right;
				padding-right: 1rem;
				padding-top: 1rem;
			}
			.page-header {
				border-bottom: 1px solid #eee;   
    			margin-bottom: 1rem;
    			padding-bottom: 0.5rem;
			}
			.page-header > .breadcrumb {
				background-color: transparent; 
				margin-bottom: 0; 
				padding: 0.75rem 0 0 0
			}
			.sidebar {
    			  position: -webkit-sticky;
				  position: -moz-sticky;
				  position: -o-sticky;
				  position: -ms-sticky;
				  position: sticky;
				  top: 100px;
				  z-index: 1;
			}
			blockquote.blockquote {
				padding: 0 1rem;
			}
			blockquote.blockquote.callout-warning {
			    border-left-color: #f0ad4e;
			} 
			blockquote.blockquote p {
				padding-top: 0;
				padding-bottom: 0;
			}
		</style>
		
		<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>		
    </head>
    <body>
    	<script>
    		var gvTerminosBh = {}; // variable global de terminos
    		
    		function fnValidaTerminoYaExiste(){ // Valida que el nombre sea unico
    			vThis = $(this);
    			iThesaurus = vThis.closest('form').find('#id_thesaurus').val();    			 		
    			$.post('{{ url("database/terminoYaExiste/") }}'+iThesaurus, {'nombre': vThis.val()}, function(result) {
    				if (result != 'true') {				
    					vError = $('<em>', {'id': "nombre-error", 'class': 'error', 'html': result})				
    					$('em:visible', vThis.closest('div.form-group')).hide(300, function() {
    						$(this).remove(); 
    					}); 
    					vError.addClass('form-control-feedback col-sm-2').appendTo( vThis.closest('div.form-group') );    					
    				}
    				else {
    					vThis.closest('div.form-group').find('em').remove();
    				}			
    			}, 'json');		
    		}
    		
    		// Typeahead
        	function fnBindTypeAhead(vInputFelds, iThesaurusId) {
        		var terminosBh = null;
        		if (iThesaurusId in gvTerminosBh) {
        			terminosBh = gvTerminosBh[ iThesaurusId ];
        		}
        		else {
        			// instantiate bloodhound suggestion engine
        			terminosBh = new Bloodhound({
        			  datumTokenizer: function(item) { return Bloodhound.tokenizers.whitespace(item.name); }, //Bloodhound.tokenizers.whitespace,
        			  queryTokenizer: Bloodhound.tokenizers.whitespace,
        			  identify: function(item) { return item.id; },
        			  prefetch: {
        			        url: '{{ url("database/json/") }}'+iThesaurusId,
        			        filter: function(response) {	
        			        	return $.map( response.result, function( aData, nId ) { return {'id': nId, 'name': aData[0]}; });
        			        }
        			    }
        			});			
        			// initialize bloodhound suggestion engine
        			terminosBh.clearPrefetchCache();
        			terminosBh.initialize();						
        			gvTerminosBh[ iThesaurusId ] = terminosBh;
        		}	
        				
        		$.each(vInputFelds, function(i, o) {			
        			inputField = $(this);
        			
        			oTmpl = $( $('#tmpl-valida-termino').html() ).prependTo( inputField.parent() );		
        			inputField.prependTo(oTmpl);
        			oTmpl.find('em').appendTo( oTmpl.parent().parent() );
        			oTmpl.find('i').show();
        			
        			inputField.typeahead({
        				  items: 'all',
        				  minLength: 0,
        				  highlight: true,
        				  source: terminosBh.ttAdapter(),
        				  display: 'name'
        				});	
        			
        			inputField.change(function() {
        				vThis = $(this);
        				vParent = vThis.closest('div.input-group')
        				var current = vThis.typeahead("getActive");
        				
        				bIsOk = current && current.name == vThis.val();
        				
        				if (current && current.name == vThis.val()) {			
        					vThis.attr('data-id', current.id);
        				}
        				else {
        					vThis.attr('data-id', ''); 
        				}
        				
        				vThis.toggleClass('form-control-success', bIsOk);
        				vParent.toggleClass('has-success', bIsOk);
        				
        				vParent.parent().next('em.text-warning').toggle( !bIsOk && (vThis.val()) );
        			});			
        		});
        		
        		return vInputFelds;
        	}
    		
    		function fnRemoveTermino(e) {
    			$(this).closest('div.form-group').remove();
    			console.log('eliminado');
    		}
    		        		
    		
    		function fnAddTermino(e) {  
    			vBtn = $(this); 
    			iThesaurus = vBtn.closest('form').find('#id_thesaurus').val();        			
    			
        		vInput = $('<input>', {
        			'name': vBtn.data('inputName'),
        			'class':'form-control'});       		
        		
        		vDiv = $('<div>', {'class': 'form-group row'})
        			.append('<label class="form-control-label col-sm-3"> </label>')
        			.append( $('<div>', {'class': 'col-sm-7', 'style': 'padding-top: 8px'}).append(vInput));
        		
        		vBtn.closest('div.form-group').after(vDiv);
        		
        		fnBindTypeAhead(vInput, iThesaurus);
        		
        		vInput.next().show().find('button').click(fnRemoveTermino);        		
        		vInput.focus();
        		
        		vBtn.closest('form').off('keypress').enterAsTab({ 'allowSubmit': true});        		
			}	
    		
        	$(function() {        		
        		$('.add-termino-btn').click(fnAddTermino);        		
        	});
        	
    	</script>
    
        {{ content() }}
                
        <!-- bootstrap -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    	
    	<!-- tagsinput -->    	    	
    	<!-- vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js -->
    	{{ javascript_include('js/vendor/bootstrap-tagsinput.min.js') }}
    	
    	<!-- vendor/typeahead.js/dist/typeahead.bundle.js -->
    	{{ javascript_include('js/vendor/typeahead.bundle.min.js') }} 
    	
    	{{ javascript_include('js/vendor/bootstrap3-typeahead.min.js') }} 
    	
    	<!-- datepicker -->
    	<!-- vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js -->
    	{{ javascript_include('js/vendor/bootstrap-datepicker.min.js') }} 
    	    	
    	<!-- jqueryvalidation -->
    	<!-- vendor/jquery-validation/dist/jquery.validate.min.js -->
    	{{ javascript_include('js/vendor/jquery.validate.min.js') }}  
    	{{ javascript_include('js/form-validate.js') }}  	
    	
    	<!-- arbor: http://arborjs.org/
    	{{ javascript_include('js/vendor/arbor.js') }} -->
    	    	
    	<script id="tmpl-valida-termino" type="text/template">
		<div class="input-group">
		<span class="input-group-btn" style="display: none;">
			<button class="btn btn-secondary" type="button" title="Eliminar este término"> <i class="fa fa-remove text-danger"></i> </button>			
		</span>
		<em class="text-warning form-control-feedback col-sm-2" style="display: none">Termino nuevo, deberá ser aprobado.</em>
		</div>	
		</script>
		     
    </body>
</html>
