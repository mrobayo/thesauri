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
	
	jQuery.parseJSON = function() {		
		return JSON.parse.apply( null, arguments );
	};
	
	
	 /*
	  * $("#form").enterAsTab({ 'allowSubmit': true});
	  * http://stackoverflow.com/questions/2335553/jquery-how-to-catch-enter-key-and-change-event-to-tab
	  */
	 (function( $ ){
	     $.fn.enterAsTab = function( options ) {
	     var settings = $.extend( {
	        'allowSubmit': false
	     }, options);
	     this.find('input, select').on("keypress", {localSettings: settings}, function(event) {
	         if (settings.allowSubmit) {
	         var type = $(this).attr("type");
	         if (type == "submit") { return true; }
	     }
	     if (event.keyCode == 13 ) {
	         var inputs =   $(this).parents("form").eq(0).find(":input:visible:not(disabled):not([readonly])");
	         var idx = inputs.index(this);
	         if (idx == inputs.length - 1) {
	            idx = -1;
	            try {
	            	var nextInputs = $(this).parents("form").eq(0).next("form").eq(0).find(":input:visible:not(disabled):not([readonly])");
	            	if (nextInputs.length >0) nextInputs[0].focus(); // handles submit buttons
	            } catch(err) {
	            }
	        } else {
	            inputs[idx + 1].focus(); // handles submit buttons
	       }
	         try {
	             inputs[idx + 1].select();
	             }
	         catch(err) {
	             // handle objects not offering select
	             }
	         return false;
	     }
	 });
	   return this;
	 };
	 })( jQuery );


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
