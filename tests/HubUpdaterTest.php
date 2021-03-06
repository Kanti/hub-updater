<?php
namespace Kanti\Test;

use Kanti\HubUpdater;
use Symfony\Component\Filesystem\Filesystem;

class HubUpdaterTest extends \PHPUnit_Framework_TestCase
{
    protected static $back;

    public static function goBack()
    {
        chdir(static::$back);
    }

    public static function goToEmptyDir()
    {
        $filesystem = new Filesystem;
        $filesystem->remove(__DIR__ . '/tests/empty');
        $filesystem->mkdir(__DIR__ . '/tests/empty');
        static::$back = getcwd();
        chdir(__DIR__ . '/tests/empty');
    }

    public function testStartupArray()
    {
        static::goToEmptyDir();
        $hubUpdater = new HubUpdater(array(
            "name" => "Kanti/test",
            "auth" => "kanti:a2a1daee80b428558882ead92d6a8847eab00261"
        ));
        if (!$hubUpdater->able()) {
            $this->fail("empty Dir was not updateable");
        }
        static::goBack();
    }

    /**
     * @depends testStartupArray
     */
    public function testStartupString()
    {
        static::goToEmptyDir();
        $hubUpdater = new HubUpdater(array(
            "name" => "Kanti/test",
            "auth" => "kanti:a2a1daee80b428558882ead92d6a8847eab00261"
        ));
        unset($hubUpdater);
        $hubUpdater = new HubUpdater("Kanti/test");
        if (!$hubUpdater->able()) {
            $this->fail("empty Dir was not updateable");
        }
        if (!is_null($hubUpdater->getCurrentInfo())) {
            $this->fail("current info should be null in empty Dir");
        }
        static::goBack();
    }

    /**
     * @depends testStartupString
     */
    public function testStartupArrayNotEmptySaveDir()
    {
        static::goToEmptyDir();
        $hubUpdater = new HubUpdater(array(
            "name" => "Kanti/test",
            "save" => "temp/",
            "auth" => "kanti:a2a1daee80b428558882ead92d6a8847eab00261",
        ));
        if (!$hubUpdater->able()) {
            $this->fail("empty Dir was not updateable");
        }
        if (!(file_exists("temp")) && is_dir("temp")) {
            $this->fail("save Dir was not created");
        }

        static::goBack();
    }

    /**
     * @expectedException \Exception
     * @depends testStartupArrayNotEmptySaveDir
     */
    public function testException1()
    {
        static::goToEmptyDir();
        new HubUpdater(array());
        static::goBack();
    }

    /**
     * @expectedException \Exception
     * @depends testException1
     */
    public function testException2()
    {
        static::goToEmptyDir();
        new HubUpdater("");
        static::goBack();
    }

    /**
     * @expectedException \Exception
     * @depends testException2
     */
    public function testException3()
    {
        static::goToEmptyDir();
        new HubUpdater(null);
        static::goBack();
    }

    /**
     * @expectedException \Exception
     * @depends testException3
     */
    public function testException4()
    {
        static::goToEmptyDir();
        $hubUpdater = new HubUpdater(array(
            "name" => "kanti/qwertzuiopasdfghjklyxcvbnm",
        ));
        if ($hubUpdater->able()) {
            static::fail("none existent repo was updateable");
        }
        new HubUpdater(array(
            "name" => "kanti/qwertzuiopasdfghjklyxcvbnm",
            "exceptions" => true,
        ));
        static::goBack();
    }
}
