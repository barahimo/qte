<?php

namespace App\Imports;

use App\Fournisseur;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;



class FournisseurImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
            
        $mytime = Carbon::now()->format('Y-m-d H:i:s');
        $array = [];
        $fournisseurs = Fournisseur::get();
        foreach ($fournisseurs as $fournisseur)
            array_push($array, $fournisseur->code);
        /*
        |--------------------------------------------------------------------------
        |                           Start Collection
        |--------------------------------------------------------------------------
        */
        $status = false;
        foreach ($collection as $index => $row) {
            if($index == 0){
                if(
                    $row[0] == 'code' &&  
                    $row[1] == 'nom_fournisseur' &&  
                    $row[2] == 'adresse' && 
                    $row[3] == 'code_postal' &&  
                    $row[4] == 'ville' &&  
                    $row[5] == 'pays' &&  
                    $row[6] == 'tel' &&  
                    $row[7] == 'site' &&  
                    $row[8] == 'email' &&  
                    $row[9] == 'note' && 
                    $row[10] == 'iff' &&  
                    $row[11] == 'ice' &&  
                    $row[12] == 'capital' &&  
                    $row[13] == 'rc' &&  
                    $row[14] == 'patente' &&  
                    $row[15] == 'cnss' &&  
                    $row[16] == 'banque' &&  
                    $row[17] == 'rib'
                )
                $status = true;
            }
        }
        if($status){
            $existe = false;
            $list = array();
            foreach ($collection as $index => $row) {
                array_push($list,$row[0]);
            }
            $tab = array_count_values($list);
            foreach ($tab as $key => $item) {
                if($item>1)
                    $existe = true;
            }
            if($existe){
                abort(404, "Erreur ! un code de fournisseur se répète plusieurs fois !");
            }
            else{
                foreach ($collection as $index => $row) {
                    if($index >= 1) {
                        $code = $row[0];
                        /*/ ************************ /*/
                        if (!in_array($code, $array)) {
                                DB::table('fournisseurs')->insert([
                                    'code' => $code,
                                    'nom_fournisseur' => $row[1],
                                    'adresse' => $row[2],
                                    'code_postal' => $row[3],
                                    'ville' => $row[4],
                                    'pays' => $row[5],
                                    'tel' => $row[6],
                                    'site' => $row[7],
                                    'email' => $row[8],
                                    'note' => $row[9],
                                    'iff' => $row[10],
                                    'ice' => $row[11],
                                    'capital' => $row[12],
                                    'rc' => $row[13],
                                    'patente' => $row[14],
                                    'cnss' => $row[15],
                                    'banque' => $row[16],
                                    'rib' => $row[17],
                                    'user_id' => $user_id,
                                    'created_at' => $mytime,
                                    'updated_at' => $mytime,
                                ]);
                            }
                        else {
                            DB::table('fournisseurs')
                            ->where('code',$code)
                            ->update([
                                'nom_fournisseur' => $row[1],
                                'adresse' => $row[2],
                                'code_postal' => $row[3],
                                'ville' => $row[4],
                                'pays' => $row[5],
                                'tel' => $row[6],
                                'site' => $row[7],
                                'email' => $row[8],
                                'note' => $row[9],
                                'iff' => $row[10],
                                'ice' => $row[11],
                                'capital' => $row[12],
                                'rc' => $row[13],
                                'patente' => $row[14],
                                'cnss' => $row[15],
                                'banque' => $row[16],
                                'rib' => $row[17],
                                'updated_at' => $mytime,
                            ]);
                        }
                    }
                }
            }
        }
        else{
            abort(404, "Erreur lors de l`importation de fichier suivant !");
        }
    }
}