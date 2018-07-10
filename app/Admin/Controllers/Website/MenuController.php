<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Controllers\ModelForm;
use App\Admin\Databases\Website\Menu;

use App\Admin\Databases\Website\SiteMenus;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Tree;
use Encore\Admin\Widgets\Box;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MenuController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public $mid = null;

    public function index($mid)
    {
        $this->mid = $mid;
        return Admin::content(function (Content $content) use ($mid){
            $content->header(trans('admin.menu') );
            $content->description(trans('admin.list') . ' for ' . SiteMenus::find($mid)->title);

            $content->row(function (Row $row) use ($mid){
                $row->column(6, $this->treeView($mid)->render());

                $row->column(6, function (Column $column) use ($mid){

                    $column->append($this->form()->setAction(admin_base_path('appearance/menu/' . $mid . '/data')));
                });
            });
        });
    }


    /**
     * Redirect to edit page.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($mid,$data_id)
    {
        return redirect()->route('menu', ['id' => $data_id]);
    }

    public function update($mid,$data_id)
    {
        return $this->form()->update($data_id);
    }

    /**
     * @return \Encore\Admin\Tree
     */
    protected function treeView($mid)
    {


        return Menu::tree(function (Tree $tree) use ($mid){
            $tree->disableCreate();

            $tree->query(function ($model) use ($mid){
                return $model->where('menu_id','=',$mid);
            });

            $tree->branch(function ($branch) {
                $payload = "<strong>{$branch['title']}</strong>";

                if (!isset($branch['children'])) {
                    if (url()->isValidUrl($branch['uri'])) {
                        $uri = $branch['uri'];
                    } else {
                        $uri = admin_base_path($branch['uri']);
                    }

                    $payload .= "&nbsp;&nbsp;&nbsp;<a href=\"$uri\" class=\"dd-nodrag\">$uri</a>";
                }

                return $payload;
            });
        });
    }

    public function destroy($mid,$data_id)
    {
        try{
            Menu::find($data_id)->delete();
        }catch (\Exception $e)
        {
            return response(json_encode(['status'=>false,'message'=>"Delete unsucceeded !"]));
        }

        return response(json_encode(['status'=>true,'message'=>"Delete succeeded !"]))->header('Content-Type', 'application/json');
    }

    /**
     * Edit interface.
     *
     * @param string $id
     *
     * @return Content
     */
    public function edit($mid,$data_id)
    {
        return Admin::content(function (Content $content) use ($data_id) {
            $content->header(trans('admin.menu'));
            $content->description(trans('admin.edit'));
            $content->row($this->form()->edit($data_id));
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Menu::form(function (Form $form) {
            $form->select('parent_id', trans('admin.parent_id'))->options(Menu::selectOptions());
            $form->text('title', trans('admin.title'))->rules('required');
            $form->text('uri', trans('admin.uri'));
            $form->hidden('menu_id')->default($this->mid);
            $form->saving(function ($form)
            {
                $form->menu_id = $this->mid;
            });

        });
    }

    /**
     * Help message for icon field.
     *
     * @return string
     */
    protected function iconHelp()
    {
        return 'For more icons please see <a href="http://fontawesome.io/icons/" target="_blank">http://fontawesome.io/icons/</a>';
    }
}
