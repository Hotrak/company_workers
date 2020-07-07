<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkerRequest;
use App\Worker;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Psy\Util\Str;

class WorkerController extends Controller
{
    public function index(Request $request)
    {
        $workerQuery = Worker::query();

        if($request->filled('search'))
        {
            $searchColumns = ['workers.name','workers.surname','workers.patronymic','positions.name','workers.salary'];
            foreach ($searchColumns as  $item)
            {
                $workerQuery->orWhere($item,'like','%'.$request->search.'%');
            }

            $workerQuery->orWhere(DB::raw(
                'CONCAT(workers.surname," ",workers.name," ",workers.patronymic)'
            ),'like','%'.$request->search.'%');

            $d = DateTime::createFromFormat('Y-m-d', $request->search);
            if($d && $d->format('Y-m-d') == $request->search)
            {
                $workerQuery->orWhereDate('employment_date','=',$request->search);
            }
        }

        if($request->filled('employment_date'))
        {
            $workerQuery->whereDate('workers.employment_date','=',$request->employment_date);

        }

        if($request->filled('sort_by'))
        {
            for($i=0;$i<count($request->sort_by);$i++)
            {
                if($request->sort_desc[$i] == 'false')
                {
                    $workerQuery->orderByDesc($request->sort_by[$i]);
                }else
                    $workerQuery->orderBy($request->sort_by[$i]);
            }
        }

        $workers = $workerQuery->withPosition()
            ->leftjoin('workers as boss','boss.id','=','workers.parent_id')
            ->select('workers.*',
                'positions.name as position_name',
                'boss.surname as boss_surname',
                'boss.name as boss_name'
            )
            ->paginate($request->limit);
        return ['workers' => $workers];
    }
    public function tree(Request $request)
    {
        return ['workers' => Worker::treeWorkers()];
    }

    public function treeChildren($workerId)
    {
        return ['workers' => Worker::treeWorkers($workerId)];
    }

    public function show($id)
    {
        $worker = Worker::find($id);
        return ['worker' => $worker];
    }

    public function store(WorkerRequest $request)
    {
        $path = $request->file('img_url')->store('uploads','public');
        $request['img'] = $path;
        $request['name'] = mb_convert_case($request->name, MB_CASE_TITLE, "UTF-8");
        $request['surname'] = mb_convert_case($request->surname, MB_CASE_TITLE, "UTF-8");
        $request['patronymic'] = mb_convert_case($request->patronymic, MB_CASE_TITLE, "UTF-8");

        $worker = Worker::create($request->all());
        return ['worker' => $worker];
    }

    public function update(Request $request, $id)
    {
        $worker = Worker::find($id);

        if($request->hasFile('img_url')){
            Storage::delete($worker->img);
            $request['img'] = $request->file('img_url')->store('uploads','public');
        }

        if(!$request->has('parent_id'))
            $request['parent_id'] = null;

        $request['name'] = mb_convert_case($request->name, MB_CASE_TITLE, "UTF-8");
        $request['surname'] = mb_convert_case($request->surname, MB_CASE_TITLE, "UTF-8");
        $request['patronymic'] = mb_convert_case($request->patronymic, MB_CASE_TITLE, "UTF-8");

        $worker->fill($request->all());
        $worker->save();
        return ['worker' => $worker];
    }

    public function updateBoss(Request $request)
    {
        Worker::where('parent_id','=',$request->old_boss_id)
            ->update(['parent_id'=>$request->boss_id]);

        return "OK";
    }

    public function destroy($id)
    {
        $worker = Worker::find($id);

        Worker::where('parent_id','=',$worker->id)
            ->update(['parent_id'=>$worker->parent_id]);

        $worker->delete();
        return Response("OK",200);
    }
}
