<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SitePages;

use App\Admin\Databases\Website\SitePagesMeta;
use App\Admin\Databases\Website\SiteTemplates;
use App\Admin\Databases\Website\SiteTemplatesMeta;
use App\Admin\Databases\Website\Widgets;
use Carbon\Carbon;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use function foo\func;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    private $id;
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('Pages');
            $content->description('Manage your pages with ease');

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
        $this->id = $id;
        return Admin::content(function (Content $content) use ($id) {

            $content->header('Edit page attributes');
            $content->description('Page attributes are really easy to add');

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

            $content->header('Create a page');
            $content->description('Create new page by just filling the widget data you organized in template');
            $content->body($this->form());

        });
    }

    public function destroy($id)
    {
        SitePagesMeta::where('pages_id','=',$id)->delete();
        SitePages::find($id)->delete();
        return response(['message'=>"Delete succeeded !",'status' => true]);
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(SitePages::class, function (Grid $grid) {

            $states = [
                '1'  => ['value' => 1, 'text' => 'YES', 'color' => 'primary'],
                '0' => ['value' => 0, 'text' => 'NO', 'color' => 'default'],
            ];

            $grid->id('ID')->sortable();
            $grid->column('title');
            $grid->column('template_id','Template')->display(function ($template)
            {
              return SiteTemplates::find($template)->title;
            });

            $grid->column('slug');
            $grid->column('permalink')->display(function ($permalink){
                return  "<a href='" . env('SITE_URL') . $permalink . "'>".  $permalink . "</a>" ;
            });
            $grid->column('author')->display(function ($author)
            {
                return Administrator::find($author)->name;
            });
            $grid->column('visibility')->select([
                0 => 'Hidden',
                1 => 'Visible',
            ]);
            $grid->updated_at()->display(function ($updated_at){
                return Carbon::parse($updated_at)->format("d M, Y");
            });
            $grid->actions(function (Grid\Displayers\Actions $actions)
            {
                $actions->prepend('<a href="pages/'.$actions->getKey().'/data" title="Edit Data"> <span class="fa fa-gears" ></span> </a>');
            });
        });
    }

    public function show($id)
    {
        return redirect()->route('pages.edit', ['page' => $id]);
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(SitePages::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text('title');
            $form->select('template_id','Template')->options(SiteTemplates::selectOptions())->default(SiteTemplates::first()->id);
            $form->text('slug');
            $form->text('permalink');
            $form->hidden('author');
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
            $form->switch('visibility',"Visible?")->states([
                '1'  => ['value' => 1, 'text' => 'YES', 'color' => 'primary'],
                '0' => ['value' => 0, 'text' => 'NO', 'color' => 'default'],
            ]);

            $form->aceditor('page_data')->default(function (Form $form)
            {
                if(Storage::disk("site_pages")->exists($form->model()->slug.".blade.php"))
                {
                    return Storage::disk("site_pages")->get($form->model()->slug.".blade.php");
                }
                return '';
            });
            $form->ignore('page_data');
            $form->saving(function (Form $form) {
                $form->author = Admin::user()->id;
            });


            $form->saved(function (Form $form) {
                foreach(SiteTemplatesMeta::where('templates_id','=',$form->template_id)->get() as $row)
                {
                    $widget_type = Widgets::find($row->widgets_id);

                    if ($widget_type != null)
                    {
                        $widget_type = $widget_type->slug;
                    }

                    switch ($widget_type)
                    {
                        case 'test':

                            break;

                        default:
                            if( SitePagesMeta::where('pages_id','=',$form->model()->id)->where('meta_type','=','data')->where('meta_key','=','input_'. $row->id)->first() == null)
                            {
                                $pageMeta = new SitePagesMeta();
                                $pageMeta->pages_id = $form->model()->id;
                                $pageMeta->meta_type = 'data';
                                $pageMeta->meta_key = 'input_'. $row->id;
                                $pageMeta->meta_value = '';
                                $pageMeta->save();
                            }
                            break;
                    }

                }

                if (request('page_data')){
                    Storage::disk("site_pages")->put($form->model()->slug.".blade.php",request('page_data'));
                }
            });

        });
    }

    protected function form_edit()
    {
        return Admin::form(SitePages::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text('title');
            $form->select('template_id','Template')->options(SiteTemplates::selectOptions())->default(SiteTemplates::first()->id);
            $form->text('permalink');
            $form->hidden('author');
            SitePages::formOptions($form,$this->id);

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');

        });
    }
}
