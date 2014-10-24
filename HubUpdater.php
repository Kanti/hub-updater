<?php

namespace Kanti;

class HubUpdater {

    protected $cachedInfo = "downloadInfo.json";//safed in cache Folder
    protected $versionFile = "installedVersion.json";//safed in cache Folder (should not be deleted)
    protected $zipFile = "tmpZipFile.zip";//safed in cache Folder
	
    protected $infos = array();
    protected $streamContext = null;
	
	protected $name = '';
	protected $branch = 'master';
	protected $cache = 'cache';
	protected $prerelease = false;
	protected $draft = false;

    public function __construct($option) {
		if(is_array($option))
		{
			if(! isset($option['name']))
			{
				throw new Exception('No Name in Option Set');
			}
			$this->name = $option['name'];
			$this->branch = isset($option['branch']) ? $option['branch'] : 'master';
			$this->cache = isset($option['cache']) ? $option['cache'] : 'cache';
			$this->prerelease = isset($option['prerelease']) ? $option['prerelease'] : false;
			$this->draft = isset($option['draft']) ? $option['draft'] : false;
		}
		else if(is_string($option))
		{
			$this->name = $option;
		}
		else
		{
			throw new Exception('No Option Set');
		}
	
		$this->cache = rtrim($this->cache,'/') . '/';
	
	
        if (!file_exists($this->cache)) {
            mkdir($this->cache);
        }
        $this->cachedInfo = new CacheOneFile($this->cache . $this->cachedInfo);

        $this->streamContext = stream_context_create(
                array(
                    'http' => array(
                        'header' => "User-Agent: Awesome-Update-My-Self-" . $this->name . "\r\n",
                    ),
                    'ssl' => array(
                        'cafile' => dirname(__FILE__) . '/ca_bundle.crt',
                        'verify_peer' => true,
                    )
                )
        );
        $this->infos = $this->getRemoteInfos();
    }

    protected function getRemoteInfos() {
        //$path = "https://api.github.com/repos/" . $this->name ."/releases";
		$path = "http://127.0.0.1/git/hub-updater/cache/offline.json";//DEBUG
        if ($this->cachedInfo->is()) {
            $fileContent = $this->cachedInfo->get();
        } else {
            if (!in_array('https', stream_get_wrappers())) {
                return array();
            }
            $fileContent = @file_get_contents($path, false, $this->streamContext);

            if ($fileContent === false) {
                return array();
            }
            $json = json_decode($fileContent, true);
            $fileContent = json_encode($json, JSON_PRETTY_PRINT);
            $this->cachedInfo->set($fileContent);
            return $json;
        }
        return json_decode($fileContent, true);
    }

    public function able() {
        if (!in_array('https', stream_get_wrappers()))
            return false;
        if (empty($this->infos))
            return false;
		if ($this->infos[0]['prerelease'] == $this->prerelease && $this->infos[0]['draft'] == $this->draft)
			return false;

        if (file_exists($this->cache . $this->versionFile)) {
            $fileContent = file_get_contents($this->cache . $this->versionFile);
            $current = json_decode($fileContent, true);

            if (isset($current['id']) && $current['id'] == $this->infos[0]['id'])
                return false;
            if (isset($current['tag_name']) && $current['tag_name'] == $this->infos[0]['tag_name'])
                return false;
        }
        return true;
    }

    public function update() {
        if ($this->able()) {
            //if ($this->download("https://github.com/" . $this->name . "/archive/" . $this->infos[0]['tag_name'] . ".zip")) {
            if ($this->download("http://127.0.0.1/git/hub-updater/cache/x.zip" )) {
                if ($this->unZip()) {
                    unlink($this->cache . $this->zipFile);
                    file_put_contents($this->cache . $this->versionFile, json_encode(array(
                        "id" => $this->infos[0]['id'],
                        "tag_name" => $this->infos[0]['tag_name']
                                    ), JSON_PRETTY_PRINT));
                    return true;
                }
            }
        }
        return false;
    }

    protected function download($url) {
        $file = @fopen($url, 'r', false, $this->streamContext);
        if ($file == false)
            return false;
        file_put_contents(dirname($_SERVER['SCRIPT_FILENAME']) . "/" . $this->cache . $this->zipFile, $file);
        return true;
    }

    protected function unZip() {
        $path = dirname($_SERVER['SCRIPT_FILENAME']) . "/" . $this->cache . $this->zipFile;

        $zip = new \ZipArchive;
        if ($zip->open($path) === true) {
            $cutLength = strlen($zip->getNameIndex(0));
            for ($i = 1; $i < $zip->numFiles; $i++) {//iterate throw the Zip
                $fileName = $zip->getNameIndex($i);
                $stat = $zip->statIndex($i);
                if ($stat["crc"] == 0) {
                    $dirName = substr($fileName, $cutLength);
                    if (!file_exists($dirName)) {
                        mkdir($dirName);
                    }
                } else {
                    copy("zip://" . $path . "#" . $fileName, substr($fileName, $cutLength));
                }
            }
            $zip->close();
            return true;
        } else {
            return false;
        }
    }/*

    public function printOne() {
        $releases = $this->infos;
        $string = "<h3>Updated to<h3>\n";
        $string .= "<h2>[" . $releases[0]['tag_name'] . "] " . $releases[0]['name'] . "</h2>\n";
        $string .= "<p>" . $releases[0]['body'] . "</p>\n";
        return $string;
    }
    
    public function getInfos(){
        if(empty($this->infos))
            return null;
        return $this->infos[0];
    }
*/
}
?>