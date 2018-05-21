<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SiteGallery;
use App\Admin\Databases\Website\SiteGalleryAlbums;

use App\Admin\Databases\Website\SiteGalleryAlbumMeta;
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
    private $pid = -1;
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
        $this->pid = $id;
        return Admin::content(function (Content $content) use ($id) {

            $content->header('Edit Album Name');
            $content->description('Album name editing');

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
        return Admin::grid(SiteGalleryAlbums::class, function (Grid $grid) {

            $grid->column('title');
            $grid->column('slug');

            $grid->column('gallery')->display(function ($gallery)
            {
                $ret = '';
                foreach (SiteGalleryAlbumMeta::getGalleries($this->id) as $item)
                {
                    $gallery = SiteGallery::find($item);
                    if ($gallery != null)
                    {
                        $ret .= "<span style='border: 1px;padding:5px;margin: 3px; color: white; background: royalblue;'>$gallery->title</span>";
                    }
                }

                return $ret;
            });

            $grid->disableExport();
            $grid->disableCreateButton();


            $grid->filter(function (Grid\Filter $filter)
            {
               $filter->disableIdFilter();
               $filter->like('title');
                $filter->where(function ($query)
                {
                    foreach ($this->input as $item)
                    {
                        $albums = SiteGalleryAlbumMeta::getAlbums($item);
                        foreach ($albums as $album)
                        {
                            $query->OrWhere('id','=',$album);
                        }

                    }

                    return $query;
                },'Gallery')->multipleSelect(SiteGallery::allNodes());
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

            $form->multipleSelect('gallery')->options(SiteGallery::allNodes())->value(SiteGalleryAlbumMeta::getGalleries($this->pid));
            $form->ignore('gallery');

            $form->saved(function (Form $form)
            {
                SiteGalleryAlbumMeta::where('album_id','=',$form->model()->id)->delete();
                foreach (request('gallery') as $item)
                {
                    if ($item != null)
                    {
                        $meta = new SiteGalleryAlbumMeta();
                        $meta->album_id = $form->model()->id;
                        $meta->gallery_id = $item;
                        $meta->save();
                    }
                }
            });
        });
    }
}
