
<h4 class="card-title" style="margin: 10px 0">{{ myheading }}</h4>

{{ form('sistema/'~router.getControllerName()~'/guardar', 'id': 'ajustesForm', 'onbeforesubmit': 'return false', 'autocomplete': 'off') }}

	<table class="table table-bordered table-striped">
	    <tbody>
	    	{% for ckey, cvalue in config_items %}
	        <tr class="row">
	        	<th class="col-4">{{ t[ckey] }} <small class="form-text text-muted"> {{ t[ckey~'_desc'] }}  </small> </th>
	        	<td class="col-8">{{ config_tag(ckey, cvalue) }}</td>        	
	        </tr>
	        {% endfor %}
	    </tbody>
	</table>

	<div class="form-actions">
		{{ hidden_field("success_forward", "value": router.getActionName()) }}
        {{ submit_button('Guardar', 'class': 'btn btn-primary') }}
        
    </div>
    
{{ end_form() }}

