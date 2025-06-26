<?php

namespace BenManu\SimpleStyleguide;

use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\Core\Manifest\ModuleResourceLoader;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Model\List\ArrayList;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Security;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\CMS\Controllers\ModelAsController;
use SilverStripe\View\Requirements;
use SilverStripe\Model\ArrayData;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\CheckboxSetField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\Form;
use SilverStripe\Assets\File;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\Validation\RequiredFieldsValidator;
use SilverStripe\Subsites\Model\Subsite;

/**
 * @package simple-styleguide
 */
class SimpleStyleguideController extends Controller
{
    /**
     * @config
     * @var array
     */
    private static $color_swatches = [];

    /**
     * @config
     * @var string
     */
    private static $placeholder_image_url = 'benmanu/silverstripe-simple-styleguide: images/placeholder.png';

    /**
     * @var array
     */
    private static $allowed_actions = [
        'index',
    ];

    private static $url_segment = '_styleguide';

    /**
     * Runs the permissiion checks, and setup of the controller view.
     */
    public function index()
    {
        if (!Director::isDev() && !Permission::check('ADMIN')) {
            return Security::permissionFailure();
        }

        // If the subsite module is installed then set the theme based on the current subsite
        if (class_exists(Subsite::class) && Subsite::currentSubsite()) {
            Config::modify()->set('SilverStripe\View\SSViewer', 'theme', Subsite::currentSubsite()->Theme);
        }

        $page = Injector::inst()->create(SiteTree::class);
        $controller = ModelAsController::controller_for($page);
        $controller->init();

        // requirements
        Requirements::css('benmanu/silverstripe-simple-styleguide: css/styleguide.css');
        Requirements::javascript('benmanu/silverstripe-simple-styleguide: js/styleguide.js');

        return $controller
            ->customise($this->getStyleGuideData())
            ->renderWith(['SimpleStyleguideController', 'Page']);
    }

    /**
     * Provides access to any custom function on the controller for use on the template output.
     */
    public function getStyleguideData(): ArrayData
    {
        $data = ArrayData::create([
            'Title' => 'Styleguide',
            'Message' => DBField::create_field(
                'HTMLText',
                '<p>This controller is only accessible to developers and admin users.</p>'
            ),
            'TestForm' => $this->getTestForm(),
            'Content' => $this->getContent(),
            'ColorSwatches' => $this->getColorSwatches(),
            'PlaceholderImageURL' => $this->getPlaceholderImageURL(),
        ]);

        // extensions for adding/overriding template data.
        $this->extend('updateStyleguideData', $data);

        return $data;
    }

    /**
     * Return a form with fields to match rendering through controller/template output.
     */
    public function getTestForm(): Form
    {
        $fields = FieldList::create(
            TextField::create('SimpleText', 'Simple Text Field'),
            TextField::create('SimpleTextGood', 'Simple Text Field (good)'),
            TextField::create('SimpleTextWarning', 'Simple Text Field (warning)'),
            TextField::create('SimpleTextBad', 'Simple Text Field (bad)'),
            NumericField::create('Number', 'Number Field'),
            EmailField::create('Email', "Email Field"),
            DropdownField::create('Dropdown', 'Normal dropdown', [
                '1' => 'One option',
                '2' => 'Two option',
            ]),
            CheckboxField::create('Checkbox', 'Checkbox'),
            CheckboxSetField::create('CheckboxSet', 'Checkbox set', [
                '1' => 'One option',
                '2' => 'Two option',
                '3' => 'Three option',
            ]),
            OptionsetField::create('Option', 'Option', [
                '1' => 'One option',
            ]),
            OptionsetField::create('OptionSet', 'Option set', [
                '1' => 'One option',
                '2' => 'Two option',
                '3' => 'Three option',
            ]),
            TextField::create('Text', 'Text')
                ->setDescription('This is a description')
        );

        $actions = FieldList::create(
            FormAction::create('doForm', 'Submit')
        );

        $required = RequiredFieldsValidator::create(
            'SimpleText',
            'Email',
            'Checkbox',
            'Dropdown'
        );

        $form = Form::create($this, 'TestForm', $fields, $actions, $required);
        $form->setMessage('This is a form wide message. See the alerts component for site wide messages.', 'warning');

        $this->extend('updateForm', $form);

        return $form;
    }

    /**
     * Emulate an HTMLEditorField output useful for testing shortcodes and output extensions etc.
     */
    public function getContent(): DBField
    {
        $content = '';

        // add file link to html content
        $file = File::get()->filter('ClassName', 'File')->first();
        if ($file) {
            $content .= '<p>This is an internal <a href="[file_link,id=' . $file->ID . ']">link to a file</a> inside content</p>';
        }

        // add external link to html content
        $content .= '<p>This is an external <a href="http://google.com">link to google</a> inside content.</p>';

        $this->extend('updateContent', $content);

        return DBField::create_field('HTMLText', $content);
    }

    public function getColorSwatches(): ArrayList
    {
        $list = ArrayList::create();
        $colors = $this->config()->color_swatches;

        if ($colors) {
            foreach ($colors as $color) {
                $list->push(ArrayData::create($color));
            }
        }

        $this->extend('updateColorSwatches', $list);

        return $list;
    }

    public function getPlaceholderImageURL(): ?string
    {
        $url = $this->config()->placeholder_image_url;
        $url = ModuleResourceLoader::singleton()->resolveURL($url);

        $this->extend('updatePlaceholderImageURL', $url);

        return $url;
    }
}
