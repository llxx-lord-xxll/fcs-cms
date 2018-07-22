<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SiteSchedule;

use App\Admin\Databases\Website\SiteTeamPeople;
use App\Admin\Databases\Website\SiteTeams;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class ScheduleController extends Controller
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

            $content->header('Schedules');
            $content->description('The list of the schedule days');

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

            $content->header('Edit a schedule');
            $content->description('Modify the fields of this form to edit');

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

            $content->header('Add new schedule day');
            $content->description('Fill the form for schedule entry');

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
        return Admin::grid(SiteSchedule::class, function (Grid $grid) {

            $grid->columns('title','event_date');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(SiteSchedule::class, function (Form $form) {

            $form->text('title');
            $form->date('event_date');

            $form->hasMany('events',function (Form\NestedForm $form)
            {
                $form->text('title');
                $form->text('subtitle');
                $form->select('speakers','Select Speakers Team')->options(collect(SiteTeams::allNodes())->prepend("None",0)->all());
                $form->select('moderator')->options(collect(SiteTeamPeople::allNodes())->prepend('None',0)->all());
                $form->timeRange('time_period_start','time_period_end','Time Period');
                $form->text('host');
                $form->text('location');
            });

        });
    }
}
