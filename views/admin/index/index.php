<?php
	$page_head = array(
		"title" => "Example Plugin",
		"bodyclass" => "primary",
		"content_class" => "horizontal-nav"
	);

	echo head($page_head);
 	echo common('limitedcontributor-nav'); 
 	echo $this->form;
?>

<?php
	echo foot();
?>


