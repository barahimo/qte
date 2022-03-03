<?php

namespace App\Http\Controllers;

use App\Imports\ClientImport;
use App\Exports\ClientExport;
use App\Exports\FournisseurExport;
use App\Imports\FournisseurImport;
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

    public function excel()
    {
        return view('managements.files.excel');
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
    // ********************************************************************* //
    public function fournisseurExcel()
    {
        return view('managements.files.fournisseurExcel');
    }

    public function fournisseurImport(Request $request)
    {
        try{
            $request->validate([
                'file' => 'required',
            ]);
            $file = $request->file;
            Excel::import(new FournisseurImport, $file);
            return redirect()->back()->withStatus("Le fichier est inséré avec succès");
        }
        catch(Exception $e){
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function fournisseurExport()
    {
        $name = 'Fournisseurs_' . now()->format('Ymd_His') . '.xls';
        return Excel::download(new FournisseurExport, $name);
    }
    // ********************************************************************* //
    
    /********************************************** FILES **********************************************/
}