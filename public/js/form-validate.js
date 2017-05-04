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
	  * Return spinner
	  * @returns {String}
	  */
	 function fnSpinnerIcon() {
		 return '<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>';
	 }
	 
	 /**
	  * Nano Templates (Tomasz Mazur, Jacek Becela)
	  * Ej.
	  *      tmpl = '<tmpl>Hello {user.firstName} {user.lastName}</tmpl>'
	  *      text = nano(tmpl, {user: {firstName: 'Mario', lastName: 'Robayo'}})
	  *
	  * @param template
	  * @param data
	  * @returns texto: template+data
	  */
	 function fnNano(template, data) {
	   return template.replace(/\{([\w\.]*)\}/g, function(str, key) {
	     var keys = key.split("."), v = data[keys.shift()];
	     for (var i = 0, l = keys.length; i < l; i++) v = v[keys[i]];
	     return (typeof v !== "undefined" && v !== null) ? v : "";
	   });
	 }

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
				if ($('em:visible', element.closest('div.form-group')).length == 0) error.addClass('form-control-feedback col-sm-2 small').appendTo( element.closest('div.form-group') );			  
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
			oValidator = $(vForm).validate(valOptions);
			if ($(vForm).length > 0 && oValidator.form()) {
				return true; //formulario validado exitosamente
			}
			else {
				oValidator.focusInvalid();
				try {
					$('html, body').animate({ scrollTop: $('.has-danger').first().offset().top-60 }, 700);	
				} catch(e) {
					//ignore
				}				
											
			}
		} catch(vErr) {			
			console.log(vErr);			
		}		
		return false;
	}
	
	/**
	 * Confirm callback
	 * @param aConfig options Array
	 * @returns oDialogModal
	 */
	function fnConfirmBox(aConfig) {
		aConfig = $.extend({titulo: 'Confirmar', mensaje: 'Confirmar?'}, aConfig);
		var vInput = false;
		if (aConfig['data-input'] && aConfig['data-input'].indexOf('#') === 0) {
			vInput = $(aConfig['data-input']);
			if (vInput.length > 0) {
				vInput = vInput.first().clone().attr('type', 'text').attr('id','');
				aConfig['mensaje'] = aConfig['mensaje'] + "<div id='confirmBox'></div>";
			}
			else {
				vInput = false;
			}
		}
	    var dlgHtml = $(fnNano($("#dlgConfirmMsg").html(), aConfig)).modal({ show:false, backdrop:false, keyboard: false });
	    if (vInput) {
	    	dlgHtml.find('#confirmBox').append(vInput);
	    }
	    dlgHtml.find('button[data-handler]').click(function() {
	    	if (vInput && vInput.val().length == 0) {
	    		vInput.parent().addClass('has-error').focus();
	    		return;
	    	}
	    	else {
	    		dlgHtml.modal('hide');
	    		if (vInput) $(aConfig['data-input']).val(vInput.val());
	    		var fnCallBack = aConfig['data-callback'];
	    		if ($.isFunction(fnCallBack)) {
	    			try {
	    				var vResult = false;
	    				if (vInput) vResult = { name: vInput.attr('name'), value: vInput.val() };	    				
	    				fnCallBack(vResult, $(this).attr('data-handler'));
	    			}
	    			catch(err) {
	    				//console.log("fnConfirm fallo llamada: --> " + err);
	    			}
	    		}
	    	}
	    });
		return dlgHtml.modal('show').on('shown.bs.modal', function(e){ if (vInput) vInput.focus(); })
	}
