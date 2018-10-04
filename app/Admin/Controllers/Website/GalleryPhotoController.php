<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SiteGalleryAlbums;
use App\Admin\Databases\Website\SiteGalleryPhotoMeta;
use App\Admin\Databases\Website\SiteGalleryPhotos;

use Carbon\Carbon;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Layout\Row;
use Illuminate\Support\Facades\Storage;

class GalleryPhotoController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    private $pid = -1;
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('Manage photos');
            $content->description('Gallery management means the tips of your fingers');

            $content->row(function (Row $row)
            {
                $row->column(6,$this->grid());
                $row->column(6,$this->form()->setAction(route('photos.store')));
            });


        });
    }

    public function destroy($id)
    {
        try{
            SiteGalleryPhotoMeta::where('photo_id','=',$id)->delete();
            SiteGalleryPhotos::find($id)->delete();

        }
        catch (\Exception $exception)
        {
            return response(json_encode(['status'=>false,'message'=>"Delete failed !"]))->header('Content-Type', 'application/json');
        }
        return response(json_encode(['status'=>true,'message'=>"Delete succeeded !"]))->header('Content-Type', 'application/json');
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

            $content->header('Edit photo attributes');
            $content->description('Modify the uploaded item');

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
        return Admin::grid(SiteGalleryPhotos::class, function (Grid $grid) {

            $grid->disableCreateButton();

            $grid->disableExport();
            $grid->filter(function (Grid\Filter $filter)
            {
                $filter->disableIdFilter();
                $filter->like('caption');
                $filter->where(function ($query)
                {
                    foreach ($this->input as $item)
                    {
                        $photos = SiteGalleryPhotoMeta::getPhotos($item);
                        foreach ($photos as $photo)
                        {
                            $query->orWhere('id','=',$photo);
                        }
                    }

                    return $query;
                },'Album')->multipleSelect(SiteGalleryAlbums::allNodes());
            });

            $grid->column('caption');
            $grid->column('image')->display(function ($image)
            {
                return "<img src='$image' height='50' width='50'/>";
            });

            $grid->column('created_at','Uploaded on')->display(function ($uploaded)
            {
                return Carbon::parse($uploaded)->format('d M, Y @ h:m a');
            });

            $grid->column('albums')->display(function ($album)
            {
                $ret = '';
                foreach (SiteGalleryPhotoMeta::getAlbums($this->id) as $item)
                {
                   $album = SiteGalleryAlbums::find($item);
                   if ($album != null)
                   {
                       $ret .= "<span style='border: 1px;padding:5px;margin: 3px; color: white; background: royalblue;'>$album->title</span>";
                   }
                }

                return $ret;
            });
        });
    }

    /**1
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(SiteGalleryPhotos::class, function (Form $form) {
            $form->text('caption');
            $form->kcimage('image');
            $form->number('height')->default(151);
            $form->number('width')->default(240);
            $form->multipleSelect('albums')->options(SiteGalleryAlbums::allNodes())->value(SiteGalleryPhotoMeta::getAlbums($this->pid));
            $form->ignore('albums');

            $form->saved(function (Form $form)
            {
                SiteGalleryPhotoMeta::where('photo_id','=',$form->model()->id)->delete();
                foreach (request('albums') as $item)
                {
                    if ($item != null)
                    {
                        $meta = new SiteGalleryPhotoMeta();
                        $meta->photo_id = $form->model()->id;
                        $meta->album_id = $item;
                        $meta->save();
                    }
                }
            });
        });
    }
}
