<?php

namespace App\Http\Controllers;

use App\Imports\StudentImport;
use App\Exports\StudentExport;
use App\Imports\ClientImport;
use App\Exports\ClientExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class FileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /****************************************************************************************************************/
    /**
     * ****************************** IMPORT EXPORT ******************************************
     */
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('statususer');
    }

    // ********************************************************************* //
    public function clientExcel()
    {
        return view('managements.files.clientExcel');
    }

    public function clientImport(Request $request)
    {
        try{
            $request->validate([
                'file' => 'required',
            ]);
            $file = $request->file;
            Excel::import(new ClientImport, $file);
            return redirect()->back()->withStatus("Le fichier est inséré avec succès");
        }
        catch(Exception $e){
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function clientExport()
    {
        $name = 'Clients_' . now()->format('Ymd_His') . '.xls';
        return Excel::download(new ClientExport, $name);
    }
    // ********************************************************************* //
    public function studentExcel()
    {
        return view('files.studentExcel');
    }

    public function studentImport(Request $request)
    {
        try{

            $request->validate([
                'file' => 'required',
            ]);
            $file = $request->file;
            Excel::import(new StudentImport, $file);
            return redirect()->back()->withStatus("Inserted succefully");
        }
        catch(Exception $e){
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function studentExport()
    {
        $name = 'Students_' . now()->format('Ymd_His') . '.xls';
        return Excel::download(new StudentExport, $name);
    }
    /********************************************** FILES **********************************************/
}