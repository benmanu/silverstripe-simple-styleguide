<?php

namespace BenManu\SimpleStyleguide;

use BenManu\SimpleStyleguide\SimpleStyleguideController;
use SilverStripe\Dev\SapphireTest;

/**
 * @package simple-styleguide
 * @subpackage tests
 */
class SimpleStyleguideControllerTest extends SapphireTest
{
    protected static $fixture_file = 'SimpleStyleguideControllerTest.yml';

    protected $requiredExtensions = [
        SimpleStyleguideController::class => [
            SimpleStyleguideControllerTest_data::class
        ],
    ];

    public function testGetStyleguideData()
    {
        $controller = SimpleStyleguideController::create();
        $data = $controller->getStyleguideData();

        $this->assertInstanceOf('SilverStripe\View\ArrayData', $data);
        $this->assertEquals('Styleguide', $data->Title);
        $this->assertEquals(
            '<p>This controller is only accessible to developers and admin users.</p>',
            $data->Message->getValue()
        );
        $this->assertInstanceOf('SilverStripe\Forms\Form', $data->TestForm);
        $this->assertInstanceOf('SilverStripe\ORM\FieldType\DBHTMLText', $data->Content);
        $this->assertInstanceOf('SilverStripe\ORM\ArrayList', $data->ColorSwatches);
    }

    public function testGetStyleguideDataExtension()
    {
        SimpleStyleguideController::add_extension(
            SimpleStyleguideControllerTest_data::class
        );

        $controller = SimpleStyleguideController::create();

        $data = $controller->getStyleguideData();
        $this->assertTrue($data->hasField('CustomData'));
    }

    public function testGetTestForm()
    {
        $controller = SimpleStyleguideController::create();
        $form = $controller->getTestForm();

        $this->assertInstanceOf('SilverStripe\Forms\Form', $form);
    }

    public function testGetContent()
    {
        $controller = SimpleStyleguideController::create();
        $content = $controller->getContent();

        $this->assertInstanceOf('SilverStripe\ORM\FieldType\DBHTMLText', $content);
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

        SimpleStyleguideController::config()->remove('color_swatches');
        SimpleStyleguideController::config()->color_swatches = $swatchesFixture;

        $swatches = $controller->getColorSwatches();

        $this->assertInstanceOf('SilverStripe\ORM\ArrayList', $swatches);
        $this->assertEquals(count($swatchesFixture), $swatches->count());
    }
}
