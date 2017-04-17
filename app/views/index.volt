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
		</style>
		
		<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>		
    </head>
    <body>
        {{ content() }}
                
        <!-- bootstrap -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    	
    	<!-- tagsinput -->    	    	
    	{{ javascript_include('js/vendor/bootstrap-tagsinput.min.js') }} <!-- vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js -->
    	{{ javascript_include('js/vendor/typeahead.bundle.min.js') }} <!-- vendor/typeahead.js/dist/typeahead.bundle.js -->
    	
    	<!-- datepicker -->
    	{{ javascript_include('js/vendor/bootstrap-datepicker.min.js') }} <!-- vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js -->
    	    	
    	<!-- jqueryvalidation -->
    	{{ javascript_include('js/vendor/jquery.validate.min.js') }} <!-- vendor/jquery-validation/dist/jquery.validate.min.js --> 
    	{{ javascript_include('js/form-validate.js') }}
    	
    	
    	<!-- arbor: http://arborjs.org/ -->
    	{{ javascript_include('js/vendor/arbor.js') }}     
    </body>
</html>
