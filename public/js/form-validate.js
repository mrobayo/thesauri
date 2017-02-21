	// Avoid `console` errors in browsers that lack a console.
	(function() {
	    var method;
	    var noop = function () {};
	    var methods = [
	        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
	        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
	        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
	        'timeStamp', 'trace', 'warn'
	    ];
	    var length = methods.length;
	    var console = (window.console = window.console || {});
	
	    while (length--) {
	        method = methods[length];
	
	        // Only stub undefined methods.
	        if (!console[method]) {
	            console[method] = noop;
	        }
	    }
	}());
	

	/**
	 * Valida un formulario (jqueryvalidation)
	 * https://jqueryvalidation.org/documentation/#link-list-of-built-in-validation-methods
	 */
	function fnValidateForm(vForm, vOptions) {				
		var valOptions = {
			lang: 'sp',
			errorElement: "em",
			errorPlacement: function(error, element) {
				//valida que el msg de error aparezca solo una vez
				if ($('em:visible', element.closest('div.form-group')).length == 0) error.addClass('form-control-feedback col-sm-4').appendTo( element.closest('div.form-group') );			  
			},
			highlight: function(element) {				
				$(element).closest('div.form-group').addClass('has-danger');
		    },
		    unhighlight: function(element) {
		    	$(element).closest('div.form-group').removeClass('has-danger');
		    }
		};
		$.extend(valOptions, vOptions);
		
		if (typeof $.validator === 'undefined') {
			window.console && console.log('jQuery Validation Plugin - No Disponible');
			return false; //No esta definido validador
		}
		
		try {						
			if ($(vForm).length > 0 && $(vForm).validate(valOptions).form()) {
				return true; //formulario validado exitosamente
			}
		} catch(vErr) {			
			console.log(vErr);			
		}		
		return false;
	}
