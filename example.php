<?php
require 'vendor/autoload.php';

$updater = new \Kanti\HubUpdater('kanti/xms');
if($updater->able())
{
	if(isset($_GET['update']))
	{
		$updater->update();
		echo '<p>updated :)</p>';
	}
	else
	{
		echo '<a href="?update">Update Me</a>';		
	}
}
else
{
	echo '<p>uptodate :)</p>';
}