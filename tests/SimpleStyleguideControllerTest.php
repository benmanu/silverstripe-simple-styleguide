<?php
/**
 * @package simple-styleguide
 * @subpackage tests
 */
class SimpleStyleguideControllerTest extends SapphireTest
{
    protected static $fixture_file = 'SimpleStyleguideControllerTest.yml';

    protected $requiredExtensions = [
        'SimpleStyleguideController' => ['SimpleStyleguideControllerTest_data'],
    ];

    public function testGetStyleguideData()
    {
        $controller = SimpleStyleguideController::create();
        $data = $controller->getStyleguideData();

        $this->assertInstanceOf('ArrayData', $data);
        $this->assertEquals('Styleguide', $data->Title);
        $this->assertEquals(
            '<p>This controller is only accessible to developers and admin users.</p>',
            $data->Message->getValue()
        );
        $this->assertInstanceOf('Form', $data->TestForm);
        $this->assertInstanceOf('HTMLText', $data->Content);
        $this->assertInstanceOf('ArrayList', $data->ColorSwatches);
    }

    public function testGetStyleguideDataExtension()
    {
        $controller = SimpleStyleguideController::create();
        $data = $controller->getStyleguideData();
        $this->assertTrue($data->hasField('CustomData'));
    }

    public function testGetTestForm()
    {
        $controller = SimpleStyleguideController::create();
        $form = $controller->getTestForm();

        $this->assertInstanceOf('Form', $form);
    }

    public function testGetContent()
    {
        $controller = SimpleStyleguideController::create();
        $content = $controller->getContent();

        $this->assertInstanceOf('HTMLText', $content);
        $this->assertNotNull($content->getValue());
    }

    public function testGetColorSwatches()
    {
        $controller = SimpleStyleguideController::create();
        $swatchesFixture = [
            [
                'Name' => 'Black',
                'Description' => 'This color is rather dark',
                'CSSColor' => '#000000',
                'TextColor' => '#ffffff',
            ],
            [
                'Name' => 'Grey',
                'Description' => 'This color is grey',
                'CSSColor' => '#666666',
                'TextColor' => '#000000',
            ],
        ];

        Config::inst()->remove('SimpleStyleguideController', 'color_swatches');
        Config::inst()->update('SimpleStyleguideController', 'color_swatches', $swatchesFixture);

        $swatches = $controller->getColorSwatches();

        $this->assertInstanceOf('ArrayList', $swatches);
        $this->assertEquals(count($swatchesFixture), $swatches->count());
    }
}

class SimpleStyleguideControllerTest_data extends DataExtension implements TestOnly {
    public function updateStyleguideData($data)
    {
        $data->setField('CustomData', 'Test');
    }
}
