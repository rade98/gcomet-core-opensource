<!DOCTYPE html>
<html>
	<head>
		<title>Test Plugin System</title>
	</head>
	<body>
	<?php
		require( "../Include/Plugins.php" );

		$pTest = new Plugins( );
		$pTest->RunHook( "IndexHeader" );
		echo "<hr />";
		$pTest->RunHook( "IndexContent" );
		echo "<hr />";
		$pTest->RunHook( "IndexFooter" );
	?>
	</body>
	
	<!-- OUTPUT -->
	<!-- Image: https://goo.gl/9xAgQN -->
	<!-- END OF OUTPUT -->
</html>
