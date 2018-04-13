<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;

class HomeController extends Controller
{

    public function index()
    {
        $v = null;

        if(Admin::user()->isRole('administrator'))
        {
            $v = Admin::content(function (Content $content) {

                $content->header('Dashboard');
                $content->description("Have a look at things that are currently happening");

                $content->row(Dashboard::title());

                $content->row(function (Row $row) {

                    $row->column(12, function (Column $column) {
                        $column->append(Dashboard::environment());
                    });

                });
            });
        }
        else
        {
            $v = Admin::content(function (Content $content) {

                $content->header('Dashboard');
                $content->description("Have a look at things that are currently happening");
                $content->row(Dashboard::title());
                $content->row((new Dashboard())->google_analystics());
            });
        }
        return $v;
    }
}
