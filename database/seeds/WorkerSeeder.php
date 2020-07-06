<?php

use Illuminate\Database\Seeder;

class WorkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $positions = [];
        $positions[] = App\Position::create(['name'=>'Генеральный директор','lvl'=>0]);
        $positions[] = App\Position::create(['name'=>'Исполнительный директор','lvl'=>1]);
        $positions[] = App\Position::create(['name'=>'Директор','lvl'=>2]);
        $positions[] = App\Position::create(['name'=>'Руководитель проекта','lvl'=>3]);
        $positions[] = App\Position::create(['name'=>'Тестировщик','lvl'=>4]);
        $positions[] = App\Position::create(['name'=>'Дизайнер','lvl'=>4]);
        $positions[] = App\Position::create(['name'=>'Разработчик','lvl'=>4]);
//
//        $positions[] = App\Position::create(['name'=>'Должность 1','lvl'=>0]);
//        $positions[] = App\Position::create(['name'=>'Должность 2','lvl'=>1]);
//        $positions[] = App\Position::create(['name'=>'Должность 3','lvl'=>2]);
//        $positions[] = App\Position::create(['name'=>'Должность 4','lvl'=>3]);
//        $positions[] = App\Position::create(['name'=>'Должность 5','lvl'=>4]);
        $count = 0;
        $index = 1;
        foreach ($positions as $position){
            switch ($position->lvl){
                case 0:
                    $count = 20/$index;
                    break;
                case 1:
                    $count = 240/$index;
                    break;
                case 2:
                    $count = 1920/$index;
                    break;
                case 3:
                    $count = 15360/$index;
                    break;
                case 4:
                    $count = 32460/$index;
                    break;
            }

            factory(\App\Worker::class,'worker',$count)->create()->each(function ($worker) use($position){
                if($position->lvl !== 0){
                    $workerChief = \App\Worker::select('workers.id')
                        ->join('positions','positions.id','=','workers.position_id')
                        ->where('positions.lvl','=',$position->lvl-1)->get()->random();
                    if(isset($workerChief)) {
                        $worker->parent_id = $workerChief->id;
                    }
                }
                $worker->position_id = $position->id;
                $worker->save();
            });
        }

    }
}
