<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;

class PageController extends Controller
{
    public function index()
    {




        return Admin::content(function (Content $content) {

            $content->header('Pages');
            $content->description("Manage your pages with ease");

            $content->row(function (Row $row) {

                $row->column(8, function (Column $column) {
                    $column->append("Test");
                });

                $row->column(4, function (Column $column) {
                    $column->row("<h3>Add new page</h3>");
                    $form = new Form();
                    $form->action('post');
                    $form->text('title');
                    $form->url('url');

                    $column->append($form);
                });

            });
        });



    }
}
