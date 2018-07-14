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
        //$this->script = " var editor = $('textarea.{$this->getElementClassString()}').ckeditor(); CKFinder.setupCKEditor( editor );";
        $this->script = "var editor = CKEDITOR.replace( '{$this->getElementClassString()}', {
        extraAllowedContent : 'section(*)[*]{*}',
    filebrowserBrowseUrl : '".asset('packages/kcfinder/browse.php?opener=ckeditor&type=files')."',
    filebrowserImageBrowseUrl : '".asset('packages/kcfinder/browse.php?opener=ckeditor&type=images')."',
    filebrowserFlashBrowseUrl : '".asset('packages/kcfinder/browse.php?opener=ckeditor&type=flash')."',
    filebrowserUploadUrl : '".asset('packages/kcfinder/upload.php?opener=ckeditor&type=files')."',
    filebrowserImageUploadUrl : '".asset('packages/kcfinder/upload.php?opener=ckeditor&type=images')."',
    filebrowserFlashUploadUrl : '".asset('packages/kcfinder/upload.php?opener=ckeditor&type=flash')."'
});
";

        return parent::render();
    }
}