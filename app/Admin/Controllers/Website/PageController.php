<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Controllers\ModelForm;
use Encore\Admin\Auth\Database\Administrator;
use App\Http\Controllers\Controller;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Form;
use Encore\Admin\Widgets\Table;
use Illuminate\Http\Request;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Grid;

class PageController extends Controller
{
use ModelForm;

    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header("Pages");
            $content->description("Manage your pages with ease");
            $content->body($this->grid()->render());
        });
    }


    public function grid()
    {
        return Administrator::grid(function (Grid $grid) {
            $grid->id('ID')->sortable();

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                if ($actions->getKey() == 1) {
                    $actions->disableDelete();
                }
            });

            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                    $actions->disableDelete();
                });
            });
        });

    }
}
