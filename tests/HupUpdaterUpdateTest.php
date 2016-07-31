<?php
namespace Kanti\Test;

use Kanti\HubUpdater;
use Symfony\Component\Filesystem\Filesystem;

class HupUpdaterUpdateTest extends \PHPUnit_Framework_TestCase
{
    protected $back;

    public function goBack()
    {
        chdir($this->back);
        $_SERVER["SCRIPT_FILENAME"] = $this->back . '/index.php';
        $filesystem = new Filesystem;
        $filesystem->remove(__DIR__ . '/tests/empty');
    }

    public function goToEmptyDir()
    {
        $filesystem = new Filesystem;
        $filesystem->remove(__DIR__ . '/tests/empty');
        $filesystem->mkdir(__DIR__ . '/tests/empty');
        $this->back = getcwd();
        chdir(__DIR__ . '/tests/empty');
        $_SERVER["SCRIPT_FILENAME"] = __DIR__ . '/tests/empty/index.php';
    }

    public function testUpdate1()
    {
        $this->goToEmptyDir();
        $hubUpdater = new HubUpdater(array(
            "name" => "Kanti/test",
            "auth" => "kanti:a2a1daee80b428558882ead92d6a8847eab00261",
        ));
        if (!$hubUpdater->able()) {
            $this->fail(
                "empty Dir was not updateable"
                . json_encode($hubUpdater->getAllRelease())
            );
        }
        if (!$hubUpdater->update()) {
            $this->fail(
                "empty Dir was not updated"
                . json_encode($hubUpdater->getAllRelease())
            );
        }
        if ($hubUpdater->update()) {
            $this->fail("updated dir was updated again");
        }
        if (!is_array($hubUpdater->getCurrentInfo())) {
            $this->fail("getCurrentInfo returned nonObject");
        }
        if (!is_array($hubUpdater->getNewestInfo())) {
            $this->fail("getNewestInfo returned nonObject");
        }
        $this->goBack();
    }
}
