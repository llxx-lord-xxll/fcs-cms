<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SiteMenus;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class MenuHolderController extends Controller
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

            $content->header('Menu');
            $content->description('List');

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

            $content->header('Edit menu');
            $content->description('Modify the values in the form');

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

            $content->header('Create a menu');
            $content->description('Create a new menu holder');

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
        return Admin::grid(SiteMenus::class, function (Grid $grid) {
            $grid->column('title');
            $grid->actions(function (Grid\Displayers\Actions $actions)
            {
                $actions->prepend('<a href="menu/'.$actions->getKey().'/data" title="Edit Data"> <span class="fa fa-gears" ></span> </a>');
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
        return Admin::form(SiteMenus::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text('title');
        });
    }
}
