<?php

namespace BenManu\SimpleStyleguide;

use SilverStripe\Core\Extension;
use SilverStripe\Dev\TestOnly;

class SimpleStyleguideControllerTest_data extends Extension implements TestOnly {
    public function updateStyleguideData($data)
    {
        $data->setField('CustomData', 'Test');
    }
}
