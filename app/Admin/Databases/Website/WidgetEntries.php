<?php

namespace App\Admin\Databases\Website;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WidgetEntries extends Model
{
    use AdminBuilder, ModelTree {
        ModelTree::boot as treeBoot;
    }

    protected $fillable = ['title', 'field_type','parent_id','rules'];
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('admin.database.site_widget_entries'));

        parent::__construct($attributes);
    }



    public function widgets()
    {
        return $this->belongsTo(Widgets::class, 'widget_id');
    }



    protected static function boot()
    {
        static::treeBoot();


    }
    public function allNodes()
    {
        $orderColumn = DB::getQueryGrammar()->wrap($this->orderColumn);
        $byOrder = $orderColumn.' = 0,'.$orderColumn;

        return static::all()->toArray();
    }


}
