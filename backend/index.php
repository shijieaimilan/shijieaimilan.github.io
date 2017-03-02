<?php
	class Thing
	{
		public $title;
		public $description;
		public $url;
	}


    if (isset($_POST['name']) && isset($_POST['info']))
    {
        echo "<strong>Post received.</strong> <br/> <br/> <strong>Name:</strong> " . $_POST['name'] . "<br/><strong>Info:</strong> " . $_POST['info'];
    }
    else
    {
		$t1 = new Thing();
		$t1->title = 'plancha';
		$t2 = new Thing();
		$t2->title = 'savana';
		$array = array(
         $t1,$t2
		 );
	
        echo json_encode($array);
    }
?>