<?php
/**
 * Created by PhpStorm.
 * User: Lord
 * Date: 17-Apr-18
 * Time: 1:27 AM
 */

namespace App\Admin\Extension\Form;
use Encore\Admin\Form\Field;


class KCImage extends Field
{
    public static $js = [];

    protected $view = 'admin.form.kcimage';

    public function __construct($column, array $arguments = [])
    {
        parent::__construct($column, $arguments);
        return $this->render();
    }

    public function render()
    {
        //$this->script = " var editor = $('textarea.{$this->getElementClassString()}').ckeditor(); CKFinder.setupCKEditor( editor );";
        $this->script = '$( "form" ).on( "click", ".'.$this->getElementClassString().'",function() {
  openKCFinder(this);
});' . "   function openKCFinder(field) {
           // console.log(field);
    window.KCFinder = {
        callBack: function(url) {
            field.value = url;
            window.KCFinder = null;
        }
    };
    window.open('".asset('packages/kcfinder/browse.php?type=images')."', 'kcfinder_textbox',
        'status=0, toolbar=0, location=0, menubar=0, directories=0, ' +
        'resizable=1, scrollbars=0, width=800, height=600'
    );
}";

        return parent::render();
    }
}