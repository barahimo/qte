<?php

namespace App\Exports;

use App\Fournisseur;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FournisseurExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $fournisseurs = Fournisseur::select('code',  'nom_fournisseur',  'adresse', 'code_postal',  'ville',  'pays',  'tel',  'site',  'email',  'note', 'iff',  'ice',  'capital',  'rc',  'patente',  'cnss',  'banque',  'rib')->where('user_id',$user_id)->get();
        return $fournisseurs;
    }
    public function headings(): array
    {
        return ['code',  'nom_fournisseur',  'adresse', 'code_postal',  'ville',  'pays',  'tel',  'site',  'email',  'note', 'iff',  'ice',  'capital',  'rc',  'patente',  'cnss',  'banque',  'rib'];
    }
}