<?php

namespace Kanti;

class HelperClass{
	static public function fileExists($file){
		return file_exists(dirname($_SERVER["SCRIPT_FILENAME"]) . "/" . $file);
	}
}