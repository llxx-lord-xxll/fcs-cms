<?php

namespace App\Admin\Databases\Website;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class SitePagesMeta extends Model
{
    use AdminBuilder,ModelTree;
    protected $table = 'site_pages_meta';

    public function pages()
    {
        return $this->belongsTo(SitePages::class, 'pages_id');
    }
}
