<?php
/**
 * Created by PhpStorm.
 * User: Lord
 * Date: 15-Apr-18
 * Time: 1:43 PM
 */

namespace App\Admin\Databases\Website;


use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('admin.database.pages_table'));

        parent::__construct($attributes);
    }
}