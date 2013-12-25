<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
	</head>

	<body>
		<div id="response"></div>
		<div id="pictures"></div>
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
		<script type="text/javascript">
			/* TEST USER *
			$.get(
				"user.php",
				{
					id:500
				},
				function(data){
					if(data.error===false)
						$("#response").html("id=500\n" + data.username);
					else
						$("#response").html("id=500\n" + data.stack_trace);
				}
			);

			$.get(
				"user.php",
				{
					id:5
				},
				function(data){
					if(data.error===false)
						$("#response").html("id=5\n" + data.username);
					else
						$("#response").html("id=5\n" + data.stack_trace);
				}
			);

			$.get(
				"user.php",
				{
					
				},
				function(data){
					if(data.error===false)
						$("#response").html("null\n" + data.username);
					else
						$("#response").html("null\n" + data.stack_trace);
				}
			);
			/**/
			
			/* TEST CIRCUS *
			$.get(
				"circus.php",
				{
					id:500
				},
				function(data){
					if(data.error===false)
						$("#response").html("id=500\n" + data.name);
					else
						$("#response").html("id=500\n" + data.stack_trace);
				}
			);

			$.get(
				"circus.php",
				{
					id:5
				},
				function(data){
					if(data.error===false)
						$("#response").html("id=5\n" + data.name);
					else
						$("#response").html("id=5\n" + data.stack_trace);
				}
			);

			$.get(
				"circus.php",
				{
					
				},
				function(data){
					if(data.error===false)
						$("#response").html("null\n" + data.name);
					else
						$("#response").html("null\n" + data.stack_trace);
				}
			);
			/**/
			
			/* TEST CIRCUS SEARCH */
			$.get(
				"circus_search.php",
				{
					name:"cirque"
				},
				function(data){
					if(data.error===false){
						for(c in data.circuses){
							$("#response").html($("#response").html() + '<br>' + data.circuses[c].name);
							$("#pictures").html($("#pictures").html() + '<br>' + '<img src="' + data.circuses[c].picture + '">');
						}
					}else{ $("#response").html(data.stack_trace);	}
				}
			);
			/**/
		</script>
	</body>
</html>