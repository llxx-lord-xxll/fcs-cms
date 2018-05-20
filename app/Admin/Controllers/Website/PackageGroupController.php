<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SitePackageGroup;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Layout\Row;

class PackageGroupController extends Controller
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

            $content->header('Manage package groups');
            $content->description('Package group management is very easy in this way');
            $content->row(function (Row $row)
            {
                $row->column(6,$this->grid());
                $row->column(6,$this->form()->setAction("/packs/groups"));
            });
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

            $content->header('Edit package group');
            $content->description('Edit the group information');

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

            $content->header('Create new package group');
            $content->description('Package group creation on the go');

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
        return Admin::grid(SitePackageGroup::class, function (Grid $grid) {

            $grid->column('title')->sortable();
            $grid->column('slug');
            $grid->disableCreateButton();
            $grid->disableExport();
            $grid->disableFilter();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(SitePackageGroup::class, function (Form $form) {

            $form->text('title');
            $form->text('slug');
            $form->textarea('description');
            $form->icon('icon');
        });
    }
}
