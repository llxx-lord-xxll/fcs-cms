<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SiteTimeline;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use function foo\func;

class TimelineController extends Controller
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

            $content->header('Manage FCS History');
            $content->description('List the history and display them in any pages');

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

            $content->header('Edit the event');
            $content->description('Editing the fcs event');

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

            $content->header('Add a new event to the list');
            $content->description('Show a new event by filling up the form');

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
        return Admin::grid(SiteTimeline::class, function (Grid $grid) {
            $grid->disableExport();
            $grid->disableFilter();
            $grid->columns(['title'=>'Title','subtitle'=>'Subtitle','event_date' => 'Date']);
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(SiteTimeline::class, function (Form $form) {
            $form->text('title');
            $form->textarea('subtitle');

            $form->date('event_date','Date');
            $form->image('image');
        });
    }
}
