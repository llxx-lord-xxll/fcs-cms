<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SiteFormContact;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use function foo\func;
use Illuminate\Support\Facades\Mail;

class ContactFormController extends Controller
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

            $content->header('Contact Form Submissions');
            $content->description('See the submissions and reply them from here');

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

            $content->header('Reply to the contact');
            $content->description('Reply to the message sent by the visitor');

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

            $content->header('header');
            $content->description('description');

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
        return Admin::grid(SiteFormContact::class, function (Grid $grid) {
            //$grid->seen();
            $grid->model()->orderBy('created_at','desc');
            $grid->column('name')->display(function ($name)
            {
                if ($this->seen == 0)
                {
                    return "<a href='".route('contact.edit',['contact'=>$this->id])."' class='text-primary' title='Not yet replied'>$name</a>";
                }
                else
                {
                    return $name;
                }
            });
            $grid->columns(array('email'=>'Email','mob'=>'Mobile','message'=>'Message'));
            $grid->column('country','From')->display(function ($from){
                return ucfirst($from);
            });
            $grid->column('created_at','Time')->sortable();
            $grid->actions(function (Grid\Displayers\Actions $actions)
            {
                $actions->disableEdit();
                $actions->prepend('<a href="'.route('contact.edit',['contact'=>$actions->getKey()]).'"><i class="fa fa-reply"></i></a>');
            });
            $grid->disableCreateButton();

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(SiteFormContact::class, function (Form $form) {

            $form->display('name');
            $form->display('message','Message sent by visitor');
            $form->display('created_at', 'Sent at');
            $form->display('email');
            $form->display('mob','Mobile');
            $form->divider();
            $form->ckeditor('reply_msg','Your message')->placeholder('Send a reply message to the user');
            $form->ignore('reply_msg');
            $form->disableReset();
            $form->disableSubmit();
            $form->html('<button type="submit" class="btn btn-info pull-right" data-loading-text="<i class=\'fa fa-spinner fa-spin \'></i> Save"> <span class="fa fa-reply"></span> Reply</button>');


            $form->saving(function (Form $form)
            {
                if (request('reply_msg') != null || empty(request('reply_msg')) == false)
                {
                    $data = ['email' => $form->model()->email,'name'=> $form->model()->name,'msg'=>$form->model()->message, 'reply_msg'=> request('reply_msg')];
                    $form->model()->seen = 1;
                    Mail::send('mails.mailTmp1', $data, function ($m) use ($data) {
                        $m->from('community@futurecitysummit.org', 'Future City Summit');
                        $m->replyTo('community@futurecitysummit.org', 'Future City Summit');
                        $m->to($data['email'], $data['name'])->subject('Contact form submit reply | Future City Summit');
                    });
                }

            });
        });
    }
}
