<nav class="navbar navbar-toggleable-md navbar-light bg-faded fixed-top">
  
	<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
  
	<a class="navbar-brand" href="#">
		{% set th_logo = image('favicon.png', 'style':'margin-top: -6px; width: 30px; height: 30px', 'title':'thesauri - UEES') %}
		{{ th_logo }}
		<span class="hidden-sm-down">{{ config.application.appTitle }}</span> 
	</a>
  
  	{{ elements.getMenu() }}
  
</nav>



<div class="container">
		
    {{ flash.output() }}
    {{ content() }}
    
</div>

<footer id="main-footer" class="bg-faded"><!-- fixed-bottom -->	
	<div class="container-fluid">
		<p class="text-muted" style="padding-top: 1rem">&copy; {{ config.application.appPartner }} {{ date('Y') }}.</p>
	</div>    	
</footer>