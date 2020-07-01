<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Worker extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name','surname','patronymic','position_id','employment_date','salary','parent_id','img'
    ];

    public function position(){
        return $this->hasOne('\App\Position','id','position_id');
    }

    public function children(){
        return $this->hasMany('\App\Worker','parent_id','id')->with('position');
    }

    public function boss(){
        return $this->hasOne('\App\Worker','id','parent_id');
    }


    public function scopeWithPosition($query){
        $query->join('positions','positions.id','=','workers.position_id');
    }

    public static function treeWorkers($parentId = null){
        $workerQuery = Worker::query();

        $workerQuery->with('children')
            ->with('position');

        if(!isset($parentId)){
            return $workerQuery->whereNull('workers.parent_id')
                ->paginate(10);
        }

        return $workerQuery->where('parent_id','=',$parentId)
            ->get();

    }
}
