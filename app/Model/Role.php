<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Scout\Searchable;

/**
 * @property int $role_id 
 * @property string $role_name 
 * @property string $describe 
 * @property int $is_delete 
 * @property int $create_time 
 * @property int $update_time 
 * @property \Carbon\Carbon $updated_at 
 * @property \Carbon\Carbon $created_at 
 * @property string $deleted_at 
 */
class Role extends Model
{
    use Searchable;
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'role';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $primaryKey = 'role_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['role_id' => 'integer', 'is_delete' => 'integer', 'create_time' => 'integer', 'update_time' => 'integer', 'updated_at' => 'datetime', 'created_at' => 'datetime'];
}