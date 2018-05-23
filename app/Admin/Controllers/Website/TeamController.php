<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SiteTeams;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Layout\Row;

class TeamController extends Controller
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

            $content->header('Manage Teams');
            $content->description('Team management system');

            $content->row(function (Row $row)
            {
                $row->column(6,$this->grid());
                $row->column(6,$this->form()->setAction(route('team.view.store')));
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

            $content->header('Edit team');
            $content->description('Modify fields to edit the data');

            $content->body($this->form()->edit($id));
        });
    }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(SiteTeams::class, function (Grid $grid) {

           $grid->disableExport();
           $grid->disableCreateButton();
           $grid->column('title');

           $grid->filter(function (Grid\Filter $filter)
           {
              $filter->like('title');
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
        return Admin::form(SiteTeams::class, function (Form $form) {
            $form->text('title');
        });
    }
}
