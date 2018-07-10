<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SiteFooter;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Tab;

class FooterController extends Controller
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

            $content->header('Edit Footer');
            $content->description('Modify the fields below');
            $box = new Box(' ');
            $box->content($this->form());
            $content->row($box);
        });
    }

    public function update($id)
    {

    }


    protected function form()
    {
        $form = new \Encore\Admin\Widgets\Form();

        return $form;



    }
}
