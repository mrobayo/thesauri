<nav class="navbar navbar-toggleable-md navbar-light bg-faded fixed-top">
  
	<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
  
	<a class="navbar-brand" href="#">
		<img src="favicon.png" style="margin-top: -6px; width: 32px; height: 32px"/> <span class="hidden-sm-down">{{ config.application.appTitle }}</span> 
	</a>
  
  	{{ elements.getMenu() }}
  
</nav>



<div class="container">
    {{ flash.output() }}
    {{ content() }}
    <hr>
    <footer>
        <p>&copy; {{ config.application.appPartner }} {{ date('Y') }}</p>
    </footer>
</div>
