<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SiteTemplateMetaValues;
use App\Admin\Databases\Website\SiteTemplatesMeta;

use App\Admin\Databases\Website\Widgets;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Layout\Row;
use Encore\Admin\Tree;
use Mockery\Exception;

class TemplatesMetaController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    private $tid;
    private $metum;
    public function index($tid)
    {
        $this->tid = $tid;
        return Admin::content(function (Content $content){
            $content->header("Template Widgets");
            $content->description("Organize your widgets in the template");

            $content->row(function (Row $row){
                $row->column(6, $this->treeView()->render());

                $row->column(6, $this->form()->setAction(admin_base_path('appearance/templates/'.$this->tid.'/meta')));
            });
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($tid,$metum)
    {
        $this->tid = $tid;
        $this->metum = $metum;
        return Admin::content(function (Content $content) {

            $content->header('Template Widget Editor');
            $content->description('Edit your template widget');
            $content->body($this->form2()->edit($this->metum)->setAction(admin_base_path('appearance/templates/'.$this->tid.'/meta/'.$this->metum)));
        });
    }

    public function update($tid,$metum)
    {
        $this->tid = $tid;
        $this->metum = $metum;

        return $this->form2()->update($this->metum);
    }

    public function clone_branch($tid,$metum)
    {
        SiteTemplatesMeta::clone_item($metum);
        return redirect()->route('meta.index',['tid'=>$tid]);
    }

    public function destroy($tid,$metum)
    {
        $this->tid = $tid;
        $this->metum = $metum;
        try{
            SiteTemplateMetaValues::where('template_meta_id','=',$metum)->delete();
            SiteTemplatesMeta::find($metum)->delete();
        }catch (Exception $e)
        {
            return response(json_encode(['status'=>false,'message'=>"Delete unsucceeded !"]));
        }

        return response(json_encode(['status'=>true,'message'=>"Delete succeeded !"]))->header('Content-Type', 'application/json');
    }

    /**
     * Make a treeView builder.
     *
     * @return Tree
     */
    protected function treeView()
    {
        $tid = $this->tid;
        return SiteTemplatesMeta::tree(function (Tree $tree) use ($tid){
            $tree->query(function ($model) {
                return $model->where('templates_id','=',$this->tid);
            });

            $tree->disableCreate();

            $tree->branch(function ($branch) use ($tid){
                $widget = Widgets::find($branch['widgets_id'])->title;
                $keyid = $branch['id'];
                $payload = "<i class='fa '></i>&nbsp;<strong>{$branch['title']} &nbsp; &nbsp; ({$widget}) </strong>" . '<span class="pull-right dd-nodrag" style="position: absolute;top: 8px;right: 42px;"> <a href="'. route('meta.clone',['tid'=>$tid,'metum'=>$keyid]) .'" title="Clone this"><i class="fa fa-clone"></i></a> </span>';

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
        return Admin::form(SiteTemplatesMeta::class, function (Form $form) {

            $form->text('title');
            $form->display('id', 'ID');
            $form->hidden('templates_id')->value($this->tid);
            $form->select('parent_id','Parent Widget')->options(SiteTemplatesMeta::selectedOptions2($this->tid));
            $form->select('widgets_id','Widget')->options(Widgets::selectOptions())->default(key(Widgets::selectOptions()));
            $form->hasMany('templates_meta_values','Widget Properties',function (Form\NestedForm $form){
                $form->select('meta_key','Property Name')->options(array('style'=>'Style','innserstyle'=>'InnerStyle','class'=>'Class','innerclass'=>'InnerClass','id'=>"ID"));
                $form->text('meta_value','Value');
            });

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }

    protected function form2()
    {
        return Admin::form(SiteTemplatesMeta::class, function (Form $form) {
            $form->text('title');
            $form->display('id', 'ID');
            $form->hidden('templates_id')->value($this->tid);
            $form->select('parent_id','Parent Widget')->options(SiteTemplatesMeta::selectedOptions2($this->tid));
            $form->select('widgets_id','Widget')->options(Widgets::selectOptions())->default(key(Widgets::selectOptions()));
            $form->hasMany('templates_meta_values','Widget Properties',function (Form\NestedForm $form){
                $form->select('meta_key','Property Name')->options(array('style'=>'Style','innserstyle'=>'InnerStyle','class'=>'Class','innerclass'=>'InnerClass','id'=>"ID"));
                $form->text('meta_value','Value');
            });

            $form->display('created_at', 'Created');
            $form->display('updated_at', 'Updated');


        });
    }


}
