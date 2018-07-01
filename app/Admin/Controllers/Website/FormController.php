<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SiteForms;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\Request;

class FormController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('Edit the form');
            $content->description('Build and manage form inputs');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('Create new form');
            $content->description('Build your forms by designing by expanding the form with new button');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(SiteForms::class, function (Grid $grid) {

            $grid->columns('title','table_name');

        });
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(SiteForms::class, function (Form $form) {

            $form->tab('Form Setup',function ($form)
            {
                $form->text('title');
                $form->text('table_name');
                $form->text('submit_button','Submit Button')->default('Submit');
                $form->aceditor('agreement_html','Agreement');
            });

            $form->tab('Form Field Setup',function ($form)
            {
                $form->hasMany('entries', function (Form\NestedForm $form) {
                    $form->select('field_name')->rules('required')->options(function ($id)
                    {
                        return SiteForms::list_fields($this->table_name);
                    });
                    $form->text('field_title')->rules('required');
                    $form->textarea('field_rules');
                    $form->textarea('field_instructions');
                    $form->textarea('field_ivals','Initial Values');
                    $form->select('field_type')->options(array('text'=>"Text",'email'=>'Email','tel'=>'Telephone','url'=>'URL','file'=>'File Upload','textarea'=>"Text Area",'radiobutton'=>"Radio Buttons","checkbox"=>"Checkbox","select"=>"Select"))->rules('required');
                    $form->textarea('field_placeholder');
                });
            });
            $form->tab('Newsletter',function ($form)
            {
                $form->switch('newsletter','Enable?');
                $form->hasMany('newslettersub', 'Shared Fields', function (Form\NestedForm $form) {
                    $form->text('list_field_name');
                    $form->select('form_field_name')->rules('required')->options(function ($id)
                    {
                        return SiteForms::list_fields($this->table_name);
                    });
                    $form->hidden('subscription_type')->value('newsletter');
                });

            });


            $form->tab('Mailchimp Contact',function ($form)
            {
                $form->switch('subscribers','Enable?');
                $form->text('subscribers_confname','Mailchimp List');
                $form->hasMany('subscriptions', 'Shared Fields', function (Form\NestedForm $form) {
                    $form->text('list_field_name');
                    $form->select('form_field_name')->rules('required')->options(function ($id)
                    {
                        return SiteForms::list_fields($this->table_name);
                    });
                    $form->hidden('subscription_type')->value('subscription');
                });

            });

            $form->saving(function ($form)
            {
                $form->newsletter =  $form->newsletter=="off"?'0':'1';
                $form->subscriber =  $form->subscriber=="off"?'0':'1';
            });

        /*    $form->tab('Confirmation Email',function (Form $form)
            {
                $form->switch('mail_user','Enable?');
                $form->select('field_x','Usable fields')->options(function ($id)
                {
                    return SiteForms::list_fields($this->table_name);
                });
                $form->html("<button class='btn btn-primary pull-right insField'>Insert</button>");
                $form->ckeditor('mail_user_content');

            });*/
          /*  $form->tab('Report Back Email',function (Form $form)
            {
                $form->switch('mail_owner','Enable?');
                $form->select('field_x','Usable fields')->options(function ($id)
                {
                    return SiteForms::list_fields($this->table_name);
                });
                $form->html("<button class='btn btn-primary pull-right insField'>Insert</button>");
                $form->ckeditor('mail_owner_content');
                $form->html("<script type='text/javascript'>$(document).ready(function() {
    $('.insField').on('click',function(e) {
     e.preventDefault();

      //console.log(CKEDITOR.instances[]);
     
    })
  
});</script>");
            });*/
        //    $form->ignore('field_x');


        });
    }
}
