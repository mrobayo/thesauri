
<h4 class="card-title" style="margin: 10px 0">{{ myheading }}</h4>

{{ form('sistema/'~router.getControllerName()~'/guardar', 'id': 'ajustesForm', 'onbeforesubmit': 'return false', 'autocomplete': 'off') }}

	<table class="table table-bordered table-striped">
	    <tbody>
	    	
	    </tbody>
	</table>

	<div class="form-actions">
		{{ hidden_field("success_forward", "value": router.getActionName()) }}
        {{ submit_button('Guardar', 'class': 'btn btn-primary') }}
        
    </div>
    
{{ end_form() }}
