<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
        {{ get_title() }}
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
		<link rel="SHORTCUT ICON" href="{{ config.application.baseUri }}favicon.png" />
		
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
    			padding-bottom: 9px;
			}
		</style>		
    </head>
    <body>
        {{ content() }}
        
                
        <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
   	
    	
    	{{ javascript_include('js/vendor/jquery.validate.min.js') }}
    	{{ javascript_include('js/form-validate.js') }}        
    </body>
</html>
