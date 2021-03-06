<?php

namespace App\Http\Controllers;

use App\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class WorkersController extends Controller
{
    /**
     * Pass workers into view.
     * Return workers view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getWorkersPage() {
        $workers = Worker::all();

        return view('workers', ['workers' => $workers]);
    }




    /**
     * Import data from loaded excel file to workers table.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function importWorkers(Request $request) {
        if($request->hasFile('import-file')) {
            Excel::load($request->file('import-file')->getRealPath(), function ($reader) {

                foreach ($reader->toArray() as $key => $row) {
                    $data['famimliya'] = $row['famimliya'];
                    $data['imya'] = $row['imya'];
                    $data['otchestvo'] = $row['otchestvo'];
                    $data['god_rozhdeniya'] = $row['god_rozhdeniya'];
                    $data['dolzhnost'] = $row['dolzhnost'];
                    $data['zp_v_god'] = $row['zp_v_god'];

                    if(!empty($data)) {
                        DB::table('workers')->insert($data);
                    }
                }
            });
        }

        return back();
    }




    /**
     * Export workers to excel file.
     * Download exportWorkers.xls file.
     *
     * @return mixed
     */
    public function exportWorkers() {
        $data = Worker::all()->toArray();

        $data = array_map(function($data) {
            return array(
                'Фамилия' => $data['famimliya'],
                'Имя' => $data['imya'],
                'Отчество' => $data['otchestvo'],
                'Год рождения' => $data['god_rozhdeniya'],
                'Должность' => $data['dolzhnost'],
                'Зп в год' => $data['zp_v_god']
            );
        }, $data);

        return Excel::create('exportWorkers', function($excel) use ($data) {
                $excel->sheet('Sheet1', function($sheet) use ($data) {
                    $sheet->fromArray($data);
            });
        })->download('xls');
    }




    /**
     * Create new worker.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createWorker(Request $request) {
        $worker = new Worker;
        $request->all();

        $worker->famimliya = $request->famimliya;
        $worker->imya = $request->imya;
        $worker->otchestvo = $request->otchestvo;
        $worker->god_rozhdeniya = $request->god_rozhdeniya;
        $worker->dolzhnost = $request->dolzhnost;
        $worker->zp_v_god = $request->zp_v_god;
        $worker->save();

        return back();
    }




    /**
     * Edit worker's info.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editWorker(Request $request, $id) {
        $worker = Worker::find($id);
        $request->all();

        $worker->famimliya = $request->famimliya;
        $worker->imya = $request->imya;
        $worker->otchestvo = $request->otchestvo;
        $worker->god_rozhdeniya = $request->god_rozhdeniya;
        $worker->dolzhnost = $request->dolzhnost;
        $worker->zp_v_god = $request->zp_v_god;
        $worker->save();

        return back();
    }




    /**
     * Remove worker from database.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeWorker($id) {
        $worker = Worker::find($id);

        if($worker) {
            $worker->delete($id);
        }

        return back();
    }
}
