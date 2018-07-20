<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SiteSlider;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class SliderController extends Controller
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

            $content->header('Sliders');
            $content->description('List of the sliders');

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

            $content->header('Slider settings');
            $content->description('Edit a slider settings');

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

            $content->header('Create slider');
            $content->description('Create a new slider');

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
        return Admin::grid(SiteSlider::class, function (Grid $grid) {
            $grid->columns('title','height');
            $grid->actions(function (Grid\Displayers\Actions $actions)
            {
                $actions->prepend('<a href="slider/'.$actions->getKey().'/meta" title="Edit slides"> <span class="fa fa-angle-double-right" ></span> </a>');
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
        return Admin::form(SiteSlider::class, function (Form $form) {
            $form->text('title');
            $form->textarea('description');
            $form->number('height');
        });
    }
}
