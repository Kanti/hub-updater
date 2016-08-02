<?php
namespace Kanti\Test;

use Kanti\HubUpdater;
use Symfony\Component\Filesystem\Filesystem;

class HupUpdaterUpdateTest extends \PHPUnit_Framework_TestCase
{
    public function testUpdate1()
    {
        HubUpdaterTest::goToEmptyDir();
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
            $this->fail("getCurrentInfo returned nonArray");
        }
        if (!is_array($hubUpdater->getNewestInfo())) {
            $this->fail("getNewestInfo returned nonArray");
        }
        if (!is_array($hubUpdater->getOptions())) {
            $this->fail("getOptions returned nonArray");
        }
        if (!is_resource($hubUpdater->getStreamContext())) {
            $this->fail("getStreamContext returned nonResource");
        }
        if (!is_array($hubUpdater->getAllRelease())) {
            $this->fail("getAllRelease returned nonArray");
        }
        HubUpdaterTest::goBack();
    }
}
