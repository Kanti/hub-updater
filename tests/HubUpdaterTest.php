<?php
namespace Kanti\Test;

use Kanti\HubUpdater;
use Symfony\Component\Filesystem\Filesystem;

class HubUpdaterTest extends \PHPUnit_Framework_TestCase
{
    protected $back;

    public function goBack()
    {
        chdir($this->back);
        $_SERVER["SCRIPT_FILENAME"] = $this->back . '/index.php';
    }

    public function goToEmptyDir()
    {
        $filesystem = new Filesystem;
        $filesystem->remove(__DIR__ . 'tests/empty');
        $filesystem->mkdir(__DIR__ . 'tests/empty');
        $this->back = getcwd();
        chdir(__DIR__ . 'tests/empty');
        $_SERVER["SCRIPT_FILENAME"] = __DIR__ . 'tests/empty/index.php';
    }

    public function testStartupArray()
    {
        $this->goToEmptyDir();
        $hubUpdater = new HubUpdater(array(
            "name" => "Kanti/test",
            "auth" => "kanti:a2a1daee80b428558882ead92d6a8847eab00261"
        ));
        if (!$hubUpdater->able()) {
            $this->fail(
                "empty Dir was not updateable"
                . json_encode($hubUpdater->getAllRelease())
            );
        }
        $this->goBack();
    }

    /**
     * @depends testStartupArray
     */
    public function testStartupString()
    {
        $this->goToEmptyDir();
        $hubUpdater = new HubUpdater("Kanti/test");
        $hubUpdater->useAuth("kanti:a2a1daee80b428558882ead92d6a8847eab00261");
        if (!$hubUpdater->able()) {
            $this->fail(
                "empty Dir was not updateable"
                . json_encode($hubUpdater->getAllRelease())
            );
        }
        if (!is_null($hubUpdater->getCurrentInfo())) {
            $this->fail("current info should be null in empty Dir");
        }
        $this->goBack();
    }

    /**
     * @depends testStartupString
     */
    public function testStartupArrayNotEmptySaveDir()
    {
        $this->goToEmptyDir();
        $hubUpdater = new HubUpdater(array(
            "name" => "Kanti/test",
            "save" => "temp/",
            "auth" => "kanti:a2a1daee80b428558882ead92d6a8847eab00261",
        ));
        if (!$hubUpdater->able()) {
            $this->fail(
                "empty Dir was not updateable"
                . json_encode($hubUpdater->getAllRelease())
            );
        }
        if (!(file_exists("temp")) && is_dir("temp")) {
            $this->fail("save Dir was not created");
        }

        $this->goBack();
    }

    /**
     * @expectedException \Exception
     * @depends testStartupArrayNotEmptySaveDir
     */
    public function testException1()
    {
        $this->goToEmptyDir();
        new HubUpdater(array());
        $this->goBack();
    }

    /**
     * @expectedException \Exception
     * @depends testException1
     */
    public function testException2()
    {
        $this->goToEmptyDir();
        new HubUpdater("");
        $this->goBack();
    }

    /**
     * @expectedException \Exception
     * @depends testException2
     */
    public function testException3()
    {
        $this->goToEmptyDir();
        new HubUpdater(null);
        $this->goBack();
    }
}
