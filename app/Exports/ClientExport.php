<?php

namespace App\Exports;

use App\Client;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClientExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $clients = Client::select('code','nom_client','adresse','telephone','solde')->where('user_id',$user_id)->get();
        return $clients;
    }
    public function headings(): array
    {
        return ['code','nom_client','adresse','telephone','solde'];
    }
}