<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SiteBlogCategory;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class BlogCategoryController extends Controller
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

            $content->header('Blog Categories');
            $content->description('Listed below are the categories for your blog');

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

            $content->header('Edit category');
            $content->description('Modify the fields and submit');

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

            $content->header('Create a new blog category');
            $content->description('Need to group the posts with a new category? Fill the form and submit');

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
        return Admin::grid(SiteBlogCategory::class, function (Grid $grid) {

            $grid->columns(['category_name'=>'Name','category_slug'=>'Slug']);

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(SiteBlogCategory::class, function (Form $form) {

            $form->text('category_name','Name');
            $form->hidden('category_slug');
            $form->saving(function (Form $form)
            {
                $form->category_slug = str_slug($form->category_name,'_');
            });
        });
    }
}
