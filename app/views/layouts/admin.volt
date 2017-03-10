
<nav class="breadcrumb">
  <a class="breadcrumb-item" href="#">Sistema</a>  
  <a class="breadcrumb-item active">Administración</a>
</nav>


<div class="card panel-default">

    <div class="card-header" style="padding-bottom: 0; border-bottom: 0">
		{{ elements.getTabsAdmin() }}
	</div>

	<div class="card-block">
		<div class="tab-content">
	    {{ content() }}
		</div>
	</div>	
	
</div>