<?php

namespace App\Admin\Databases\Website;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Menu.
 *
 * @property int $id
 *
 * @method where($parent_id, $id)
 */
class Widgets extends Model
{


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $fillable = ['id','widget_name','slug'];
    protected $primaryKey = 'id';
    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */

    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('admin.database.site_widgets_table'));

        parent::__construct($attributes);
    }


    public function allNodes()
    {
        $orderColumn = DB::getQueryGrammar()->wrap($this->orderColumn);
        $byOrder = $orderColumn.' = 0,'.$orderColumn;
        return static::all()->toArray();
    }

    /**
     * Detach models from the relationship.
     *
     * @return void
     */

    public static function selectOptions()
    {
        $options = array();
        foreach( static::all()->toArray() as $key => $value )
        {
            $options[$value['id']] = $value['title'];
        }

        //$options = (new static())->buildSelectOptions();
        return collect($options)->all();
    }

    public function widget_entries()
    {
        return $this->hasMany(WidgetEntries::class, 'widget_id');
    }




}
