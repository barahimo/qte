<?php

namespace App\Imports;

use App\Client;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;



class ClientImport implements ToCollection
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
        $clients = Client::get();
        foreach ($clients as $client)
            array_push($array, $client->code);
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
                    $row[1] == 'nom_client' &&
                    $row[2] == 'adresse' &&
                    $row[3] == 'telephone' &&
                    $row[4] == 'solde'
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
                abort(404, "Erreur  ! un code de client se répète plusieurs fois !");
            }
            else{
                foreach ($collection as $index => $row) {
                    if($index >= 1) {
                        $code = $row[0];
                        /*/ ************************ /*/
                        if (!in_array($code, $array)) {
                                DB::table('clients')->insert([
                                    'code' => $code,
                                    'nom_client' => $row[1],
                                    'adresse' => $row[2],
                                    'telephone' => $row[3],
                                    'ICE' => Str::slug($row[1], '-'),
                                    'solde' => $row[4],
                                    'user_id' => $user_id,
                                    'created_at' => $mytime,
                                    'updated_at' => $mytime,
                                ]);
                            }
                        else {
                            DB::table('clients')
                            ->where('code',$code)
                            ->update([
                                'nom_client' => $row[1],
                                'adresse' => $row[2],
                                'telephone' => $row[3],
                                'ICE' => Str::slug($row[1], '-'),
                                'solde' => $row[4],
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