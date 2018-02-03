<?php

namespace BenManu\SimpleStyleguide;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Dev\TestOnly;

class SimpleStyleguideControllerTest_data extends DataExtension implements TestOnly {
    public function updateStyleguideData($data)
    {
        $data->setField('CustomData', 'Test');
    }
}
