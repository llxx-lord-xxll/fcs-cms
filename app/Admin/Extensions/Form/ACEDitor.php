<?php
/**
 * Created by PhpStorm.
 * User: Lord
 * Date: 17-Apr-18
 * Time: 1:27 AM
 */

namespace App\Admin\Extension\Form;
use Encore\Admin\Form\Field;


class ACEDitor extends Field
{
    public static $js = [
        'packages/aceditor/build/src/ace.js',
        'packages/aceditor/build/src/ext-static_highlight.js',
        'packages/aceditor/build/src/ext-language_tools.js',
    ];

    protected $view = 'admin.aceditor';

    public function render()
    {

        //$this->script = "$('textarea.{$this->getElementClassString()}').ckeditor();";
        $this->script = "var textarea = $('textarea.".$this->getElementClassString()."text'); var aceditor = ace.edit('".$this->getElementClassString()."' ,{        
        theme: 'ace/theme/tomorrow_night_blue',
        mode: 'ace/mode/php_laravel_blade',
        autoScrollEditorIntoView: true,
        maxLines: 30,
        enableBasicAutocompletion: true,
        enableSnippets: true,
        enableLiveAutocompletion: false,
        minLines: 2}); aceditor.setValue(textarea.val()); aceditor.on('change', function(){
          $('textarea.".$this->getElementClassString()."text').val(aceditor.getValue());
          console.log(aceditor.getValue());
});";

        return parent::render();
    }
}