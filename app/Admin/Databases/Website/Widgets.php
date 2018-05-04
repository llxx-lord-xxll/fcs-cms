<?php

namespace App\Admin\Databases\Website;

use Encore\Admin\Form;
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

    public static function form_widget_display(Form &$form,$id,$suffix)
    {
        $tmp = array();
        $wid = Widgets::find($id);
        $widentry = WidgetEntries::where('widget_id','=',$id);


        if($widentry->first() != null)
        {
            $form->html('<h4/>'.$wid->title.'</h4>');
            foreach ($widentry->get() as $row)
            {
                static::form_widget_display($form,$row->field_type,$suffix);
            }
        }
        else
        {
            switch ($wid->slug)
            {
                case 'text':
                    $form->text('cust_wid_'.$wid->slug.$suffix,$wid->title);
                    $form->ignore('cust_wid_'.$wid->slug.$suffix);
                    array_push($tmp,'cust_wid_'.$wid->slug.$suffix);
                    break;
                case 'image':
                    $form->image('cust_wid_'.$wid->slug.$suffix,$wid->title);
                    $form->ignore('cust_wid_'.$wid->slug.$suffix);
                    array_push($tmp,'cust_wid_'.$wid->slug.$suffix);
                    break;
                case 'direct_html':
                    $form->aceditor('cust_wid_'.$wid->slug.$suffix,$wid->title);
                    $form->ignore('cust_wid_'.$wid->slug.$suffix);
                    array_push($tmp,'cust_wid_'.$wid->slug.$suffix);
                    break;
                case 'richtext':
                    $form->textarea('cust_wid_'.$wid->slug.$suffix,$wid->title);
                    $form->ignore('cust_wid_'.$wid->slug.$suffix);
                    array_push($tmp,'cust_wid_'.$wid->slug.$suffix);
                    break;
                case 'column':
                    $form->textarea('cust_wid_'.$wid->slug.$suffix,$wid->title);
                    $form->ignore('cust_wid_'.$wid->slug.$suffix);
                    array_push($tmp,'cust_wid_'.$wid->slug.$suffix);
                    break;
            }
        }

    }
}
