<?php

class SimpleStyleguideController extends Controller
{

    private static $allowed_actions = [
        'index',
    ];

    /**
     * Runs the permissiion checks, and setup of the controller view.
     */
    public function index()
    {
        if (!Director::isDev() && !Permission::check('ADMIN')) {
            return Security::permissionFailure();
        }

        // If the subsite module is installed then set the theme based on the current subsite
        if (class_exists('Subsite') && Subsite::currentSubsite()) {
            Config::inst()->update('SSViewer', 'theme', Subsite::currentSubsite()->Theme);
        }

        $page = Page::get()->first();
        $controller = ModelAsController::controller_for($page);
        $controller->init();

        return $controller
            ->customise($this->getStyleGuideData())
            ->renderWith(['SimpleStyleguideController', 'Page']);
    }

    /**
     * Provides access to any custom function on the controller for use on the template output.
     * @return Array
     */
    public function getStyleguideData()
    {
        $data = new ArrayData([
            'Title' => 'Styleguide',
            'TestForm' => $this->getTestForm(),
            'Content' => $this->getContent(),
        ]);

        // extensions for adding/overriding template data.
        $this->extend('updateStyleguideData', $data);

        return $data;
    }

    /**
     * Return a form with fields to match rendering through controller/template output.
     * @return Form
     */
    public function getTestForm()
    {
        $fields = FieldList::create(
            TextField::create('SimpleText', 'Simple Text Field'),
            TextField::create('SimpleTextGood', 'Simple Text Field (good)')
                ->setError('This is a good message', 'good'),
            TextField::create('SimpleTextWarning', 'Simple Text Field (warning)')
                ->setError('This is a warning message', 'warning'),
            TextField::create('SimpleTextBad', 'Simple Text Field (bad)')
                ->setError('This is an error message', 'bad'),
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

        $required = new RequiredFields(
            'SimpleText',
            'Email',
            'Checkbox',
            'Dropdown'
        );

        $form = new Form($this, 'TestForm', $fields, $actions, $required);
        $form->setMessage('This is a form wide message. See the alerts component for site wide messages.', 'warning');

        return $form;
    }

    /**
     * Emulate an HTMLEditorField output useful for testing shortcodes and output extensions etc.
     * @return HTMLText
     */
    public function getContent()
    {
        $content = '';

        // add file link to html content
        $file = File::get()->filter('ClassName', 'File')->first();
        if ($file) {
            $content .= '<p>This is an internal <a href="[file_link,id=' . $file->ID . ']">link to a file</a> inside content</p>';
        }

        // add external link to html content
        $content .= '<p>This is an external <a href="http://google.com">link to google</a> inside content.</p>';

        return DBField::create_field('HTMLText', $content);
    }
}
