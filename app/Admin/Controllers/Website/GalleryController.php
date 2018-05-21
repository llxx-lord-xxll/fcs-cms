<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SiteGallery;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Layout\Row;
use Mockery\Exception;

class GalleryController extends Controller
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

            $content->header('Gallery');
            $content->description('Manage your image gallery');

            $content->row(function (Row $row)
            {
                $row->column(6,$this->grid());
                $row->column(6,$this->form()->setAction(route('view.store')));
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

            $content->header('Edit Gallery Information');
            $content->description('Gallery information ');

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
        return Admin::grid(SiteGallery::class, function (Grid $grid) {

            $grid->column('title');
            $grid->column('description');
            $grid->disableExport();
            $grid->disableCreateButton();
            
            $grid->filter(function (Grid\Filter $filter)
            {
                $filter->disableIdFilter();
                $filter->like('title');
                $filter->like('description');
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
        return Admin::form(SiteGallery::class, function (Form $form) {
            $form->text('title');
            $form->text('description');
        });
    }
}
