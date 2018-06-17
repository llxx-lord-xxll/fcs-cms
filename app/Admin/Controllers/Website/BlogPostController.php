<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SiteBlogCategory;
use App\Admin\Databases\Website\SiteBlogPost;

use App\User;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use function foo\func;
use Illuminate\Support\Facades\Storage;

class BlogPostController extends Controller
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

            $content->header('Manage articles');
            $content->description('Manage your articles with ease');

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

            $content->header('Edit article');
            $content->description('Edit the article');

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

            $content->header('Write a new article');
            $content->description('Write the article and submit!');

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
        return Admin::grid(SiteBlogPost::class, function (Grid $grid) {
            $grid->column('post_thumb','Thumbnail')->display(function ($thumb) {
                return "<img src='$thumb' style='height: 75px;'>";
            });
            $grid->column('post_title','Title');
            $grid->column('category_ID','Category')->display(function ($category)
            {
            return SiteBlogCategory::find($category)->category_name;
            });
            $grid->column('author_ID','Author')->display(function ($author)
            {
                return Administrator::find($author)->name;
            });

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(SiteBlogPost::class, function (Form $form) {
            $form->select('post_status',"Post visiblity")->options(['Visible','Invisible']);
            $form->text('post_title','Title')->rules('required');
            $form->kcimage('post_thumb','Thumbnail');
            $form->select('category_ID','Category')->options(SiteBlogCategory::allCats());
            $form->ckeditor('post_content','Write your article');
            $form->hidden('post_type');
            $form->hidden('author_ID');
            $form->hidden('post_slug');

            $form->saving(function (Form $form)
            {
                $form->post_slug = str_slug($form->post_title);
                $form->author_ID = Admin::user()->getAuthIdentifier();
                $form->post_type = 'article';
            });
        });
    }
}
