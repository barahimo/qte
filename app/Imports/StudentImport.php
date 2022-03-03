<?php

namespace App\Imports;

use App\Classroom;
use App\Level;
use App\Period;
use App\Student;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date;

use function App\Providers\nbGender;

class StudentImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $mytime = Carbon::now()->format('Y-m-d H:i:s');
        $array = [];
        $students = Student::get();
        foreach ($students as $student)
            array_push($array, $student->cin);
        /*
        |--------------------------------------------------------------------------
        |                           Start Collection
        |--------------------------------------------------------------------------
        */
        $status = false;
        foreach ($collection as $index => $row) {
            if($index == 0){
                if(
                    $row[0] == "id" && 
                    $row[1] == "cin" && 
                    $row[2] == "nom" && 
                    $row[3] == "prenom" && 
                    $row[4] == "age"
                )
                $status = true;
            }
        }
        if($status){
            foreach ($collection as $index => $row) {
                if($index >= 1) {
                    $cin = $row[1];
                    // dump($row[1],$row[2],$row[3],$row[4]);
                    /*/ ************************ /*/
                    if (!in_array($cin, $array)) {
                        // dump($cin ." not exist");
                            DB::table('students')->insert([
                                'cin' => $row[1],
                                'nom' => $row[2],
                                'prenom' => $row[3],
                                'age' => $row[4],
                                'created_at' => $mytime,
                                'updated_at' => $mytime,
                            ]);
                        }
                    else {
                        // dump($cin ." exist deja");
                        DB::table('students')
                            ->where('cin',$row[1])
                            ->update([
                                    'cin' => $row[1],
                                    'nom' => $row[2],
                                    'prenom' => $row[3],
                                    'age' => $row[4],
                                    'updated_at' => $mytime,
                            ]);
                    }
                }
            }
        }
        else{
            abort(404, "Erreur lors de l`importation de fichier suivant !");
        }
        // dd("fin"); 
    }
}