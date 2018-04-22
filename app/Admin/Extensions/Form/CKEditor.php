<?php
/**
 * Created by PhpStorm.
 * User: Lord
 * Date: 17-Apr-18
 * Time: 1:27 AM
 */

namespace App\Admin\Extension\Form;
use Encore\Admin\Form\Field;


class CKEditor extends Field
{
    public static $js = [
        'packages/ckeditor/ckeditor.js',
        'packages/ckeditor/adapters/jquery.js',
    ];

    protected $view = 'admin.ckeditor';

    public function render()
    {
        $this->script = "$('textarea.{$this->getElementClassString()}').ckeditor();";

        return parent::render();
    }
}