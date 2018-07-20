<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SiteSlider;
use App\Admin\Databases\Website\SiteSliderMeta;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;

class SiteSliderMetaController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */

    public $parent = 0;
    public function index($id)
    {
        $this->parent = $id;
        return Admin::content(function (Content $content) use ($id){

            $content->header(SiteSlider::find($id)->title);
            $content->description('List of the available slides');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id,$slide)
    {
        $this->parent = $id;
        return Admin::content(function (Content $content) use ($slide) {

            $content->header('Edit slide');
            $content->description('Edit the background image and the overlay content');

            $content->body($this->form()->edit($slide));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create($id)
    {
        $this->parent = $id;
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
        $slider = $this->parent;
        return Admin::grid(SiteSliderMeta::class, function (Grid $grid)use ($slider) {
            $grid->model()->where('slider_id','=',$slider);
            $grid->disableExport();
            $grid->column('img','Image')->display(function ($image)
            {
                return "<img src='$image' height='50' width='50'/>";
            });

        });
    }

    public function update($id,$slide)
    {
        return $this->form()->update($slide);
    }


    public function destroy($id,$slide)
    {
        if ($this->form()->destroy($slide)) {
            return response()->json([
                'status'  => true,
                'message' => trans('admin.delete_succeeded'),
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => trans('admin.delete_failed'),
            ]);
        }
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(SiteSliderMeta::class, function (Form $form) {
            $form->hidden('slider_id');
            $form->kcimage('img','Background image');
            $form->ckeditor('content');
            $form->ignore('slider_id');

            $form->saving(function (Form $form) {
                $form->slider_id = \request()->slide;
            });

        });
    }
}
