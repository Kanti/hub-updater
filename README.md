hubupdater
==========

Simpel Github Updater for Web Projects [PHP]

Check for an update [simple]
==========
```php
<?php
require 'vendor/autoload.php';

$updater = new \Kanti\HubUpdater('kanti/test');
$updater->update();
```

Check for an update [complete]
==========
```php
<?php
require 'vendor/autoload.php';

$updater = new \Kanti\HubUpdater([
  "name" => 'kanti/test',
	'branch' => 'master',
	'cache' => 'cache/',
	'save' => 'save/',
	'prerelease' => false,
]);
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
```