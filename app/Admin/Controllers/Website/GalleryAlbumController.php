<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SiteGalleryAlbums;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Layout\Row;
use function foo\func;
use Mockery\Exception;

class GalleryAlbumController extends Controller
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

            $content->header('Albums');
            $content->description('Manage your albums');

            $content->row(function (Row $row)
            {
                $row->column(6,$this->grid());
                $row->column(6,$this->form()->setAction(route('albums.store')));
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

            $content->header('Edit Album Name');
            $content->description('Album name editing');

            $content->body($this->form()->edit($id));
        });
    }

    public function store()
    {
        try
        {
            $slug = str_slug(request('title'));
            $album = new SiteGalleryAlbums();
            $album->title = request('title');
            $album->slug = $slug;
            $album->save();
        }
        catch (Exception $exception)
        {
            Throw new \Exception($exception->getMessage());
        }

        return redirect()->route('albums.index');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(SiteGalleryAlbums::class, function (Grid $grid) {

            $grid->column('title');
            $grid->column('slug');
            $grid->disableExport();
            $grid->disableCreateButton();


            $grid->filter(function (Grid\Filter $filter)
            {
               $filter->disableIdFilter();
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
        return Admin::form(SiteGalleryAlbums::class, function (Form $form) {
            $form->text('title');
        });
    }
}
