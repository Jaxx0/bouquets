<?php
include("bouquet.php");

$bouquets = [];
// echo "White bouquet spec code:\n";
while( $bouquet_spec = readline() ){
	try{
		$bouquets[] = new bouquet($bouquet_spec);
	}catch(Exception $e){
		// echo "Bouquet not added.\n";
		// echo $e->getMessage() . "\n\n";
	}
}

// echo "Input flower:\n";
$flowers = [];
while(true) {
	if (preg_match("/^([a-z])(L|S)$/", readline(), $flower) === 0) {
		// echo "Incorrect input. Use format [a-z](L|S).\n";
	}
	$added = false;
	foreach ($bouquets as $key => $bouquet) {
		if ($bouquet->add_flower($flower[1], $flower[2])) {
			$added = true;
			if ($bouquet->ready()){
				echo $bouquet->get_spec_code() . "\n";
				unset($bouquets[$key]);
			}
			break;
		}
	}
	if (!$added){
		if(count($flowers) > 256){
			echo "Flowers capacity exceeded. Terminating process.";
			exit(1);
		}else{
			$flowers[] = $flower;
		}
	}
}