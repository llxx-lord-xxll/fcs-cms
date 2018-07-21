<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SiteBlogCategory;
use App\Admin\Databases\Website\SiteForms;
use App\Admin\Databases\Website\SiteGallery;
use App\Admin\Databases\Website\SitePackageGroup;
use App\Admin\Databases\Website\SitePages;
use App\Admin\Databases\Website\SitePagesMeta;

use App\Admin\Databases\Website\SiteSlider;
use App\Admin\Databases\Website\SiteTeams;
use App\Admin\Databases\Website\SiteTemplatesMeta;
use App\Admin\Databases\Website\Widgets;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Tree;

class PageDataController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    private $data;
    private $pid;
    private $page_meta_id;
    public function index($pid)
    {
        $this->pid = $pid;
        return Admin::content(function (Content $content) {

            $content->header('Page Data Setup');
            $content->description('Setup your page information');

            $content->body($this->treeView());
        });
    }

    public function show($pid,$data)
    {
        $this->pid = $pid;
        $this->data = $data;
        return redirect()->route('data.edit', ['pid' => $pid,'data'=>$data]);
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id,$data)
    {
        $this->pid = $id;
        $this->data = $data;
        $this->page_meta_id =  SitePagesMeta::where('pages_id','=',$id)->where('meta_type','=','data')->where('meta_key','=','input_'. $data)->first()->id;

        return Admin::content(function (Content $content)  {

            $content->header('Update data');
            $content->description('Fill up the data for this widget');
            $content->body($this->form()->edit($this->page_meta_id));
        });
    }

    public function update($id,$data)
    {
        $this->pid = $id;
        $this->data = $data;

        $this->page_meta_id =  SitePagesMeta::where('pages_id','=',$id)->where('meta_type','=','data')->where('meta_key','=','input_'. $data)->first()->id;

        return $this->form()->update($this->page_meta_id);
    }

    public function destroy($id)
    {
        return false;
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
        return Admin::grid(SitePagesMeta::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->created_at();
            $grid->updated_at();
        });
    }
    /**
     * Make a treeView builder.
     *
     * @return Tree
     */
    protected function treeView()
    {
        SiteTemplatesMeta::deleting(function ($menu) {
         return false;
        });

        return SiteTemplatesMeta::tree(function (Tree $tree) {
            $tree->query(function ($model) {
                return $model->where('templates_id','=',SitePages::find($this->pid)->template_id);
            });

            $tree->disableCreate();
            $tree->disableSave();

            $tree->branch(function ($branch) {
                $payload = "<i class='fa '></i>&nbsp;<strong>{$branch['title']}</strong>";

                return $payload;
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
        $pid = $this->pid;
        $data = $this->data;


        return Admin::form(SitePagesMeta::class, function (Form $form) use ($pid,$data){
            $form->display('meta_key','Key');

            $form->setAction(route('data.update',['pid'=>$this->pid,'data'=>$this->data]));
            if($data != null)
            {
                switch (Widgets::find(SiteTemplatesMeta::find($data)->widgets_id)->slug)
                {
                    case 'image':
                        $form->image('meta_value',SiteTemplatesMeta::find($data)->title)->uniqueName();
                        break;
                    case 'text':
                        $form->text('meta_value',SiteTemplatesMeta::find($data)->title);
                        break;
                    case 'html':
                        $form->aceditor('meta_value',SiteTemplatesMeta::find($data)->title);
                        break;
                    case 'vhtml':
                        $form->ckeditor('meta_value',SiteTemplatesMeta::find($data)->title);
                        break;
                    case 'textarea':
                        $form->textarea('meta_value',SiteTemplatesMeta::find($data)->title);
                        break;
                    case 'images':
                        $form->multipleImage('meta_value',SiteTemplatesMeta::find($data)->title)->uniqueName();
                        break;
                    case 'files':
                        $form->multipleFile('meta_value',SiteTemplatesMeta::find($data)->title)->uniqueName();
                        break;
                    case 'file':
                        $form->file('meta_value',SiteTemplatesMeta::find($data)->title);
                        break;
                    case 'date_time':
                        $form->datetime('meta_value',SiteTemplatesMeta::find($data)->title);
                        break;
                    case 'date_time_range':
                        $form->datetimeRange('meta_value',SiteTemplatesMeta::find($data)->title);
                        break;
                    case 'date':
                        $form->date('meta_value',SiteTemplatesMeta::find($data)->title);
                        break;
                    case 'time':
                        $form->time('meta_value',SiteTemplatesMeta::find($data)->title);
                        break;
                    case 'pricing_table':
                        $form->select('meta_value','Select Package Group')->options(SitePackageGroup::getAllPackageGroups());
                        break;
                    case 'gallery':
                        $form->select('meta_value','Select Gallery')->options(SiteGallery::allNodes());
                        break;
                    case 'a':
                        $form->text('meta_value','Hyperlink');
                        break;
                    case 'people':
                        $form->select('meta_value','Select Team to display people')->options(SiteTeams::allNodes());
                        break;
                    case 'forms':
                        $form->select('meta_value','Select Team to display people')->options(SiteForms::allNodes());
                        break;
                    case 'slider':
                    case 'venue_slider':
                    case 'img_slider':
                        $form->select('meta_value','Select Slider to display')->options(SiteSlider::allNodes());
                        break;
                    case  'accordion_faq':
                        $form->aceditor('meta_value','List of accordions');
                        break;
                    case 'i':
                        $form->icon('meta_value','Select Icon');
                        break;
                    case 'delegate_handbook':
                        $form->aceditor('meta_value','Structural Values');
                        break;
                    default:
                        $form->html('<p class="form-control text-warning">Nothing to edit</p>');
                        break;
                }
            }






        });
    }
}
