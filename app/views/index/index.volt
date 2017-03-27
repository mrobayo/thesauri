
<div>	
	
	<h4 class="card-title" style="margin: 10px 0">{{ myheading }}  </h4>
	
	{% if modo_mantenimiento %}  
	
		<div class="card bg-faded">		
			<div class="card-header"> 
				<h1> <i class="fa fa-hand-o-right"></i> Sitio en matenimiento programado, por favor reintente luego. </h1> 
			</div>
		</div>	
	
	{% else %}  
	
				
	
	{% endif %}
		
</div>


