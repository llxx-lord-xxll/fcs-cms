<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SitePackageGroup;
use App\Admin\Databases\Website\SitePackages;

use Carbon\Carbon;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class PackageController extends Controller
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

            $content->header('Create new package');
            $content->description('Fill up the form to create new package');

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

            $content->header('Edit Package');
            $content->description('Modify your changes and tap on save button');

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

            $content->header('Create a new package');
            $content->description('Package creations at it\'s easiest way ');

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
        return Admin::grid(SitePackages::class, function (Grid $grid) {
            $grid->disableExport();
            $grid->column('title');
            $grid->column('price','Price( $ )');
            $grid->column('deadline')->display(function ($deadline)
            {
                $deadline = Carbon::parse($deadline);
                return $deadline->format("d M Y");
            })->sortable();
            $grid->column('package_group_id','Package Group')->display(function ($groupid)
            {
                $groupid = SitePackageGroup::find($groupid);
                if ($groupid != null)
                {
                    return $groupid->title;
                }
                else{
                    return 'No group assigned';
                }
            });

            $grid->filter(function (Grid\Filter $filter)
            {
                $filter->disableIdFilter();
               $filter->in('package_group_id','Package Group')->multipleSelect(SitePackageGroup::getAllPackageGroups());
               $filter->like('price');
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
        return Admin::form(SitePackages::class, function (Form $form) {

            $form->select('package_group_id','Package Group')->options(SitePackageGroup::getAllPackageGroups());
            $form->text('title');
            $form->icon('icon');
            $form->currency('price');
            $form->date('deadline');
            $form->divider();
            $form->hasMany('package_meta','Features',function (Form\NestedForm $form) {
                $form->text('title');
                $form->textarea('description');
            });
            $form->html('<h4>Click new to add package features</h4>');
        });
    }
}
