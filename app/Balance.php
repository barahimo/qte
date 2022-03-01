<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use function App\Providers\get_limit_pagination;

class Balance extends Model
{
    public static function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
    
    public static function sorting($array,$type,$link,$order)
    {
        $array = json_decode(json_encode($array),true);
        usort($array, function ($a, $b) use($type,$link,$order) {
            if($type == 'string'){
              if($order == 'asc'){
                $objA = $a[$link];   
                $objB = $b[$link];
              }
              else{
                $objA = $b[$link];   
                $objB = $a[$link];
              }
              return ($objA < $objB) ? -1 : 1;  
              return ($objA > $objB) ? -1 : 1;  
              return 0;  
            }
            elseif($type == 'date'){
              $dateA = $a[$link];
              $dateB = $b[$link];
              if($order == 'asc'){
                $objA = strtotime($dateA);   
                $objB = strtotime($dateB);
              }
              else{
                $objA = strtotime($dateB);   
                $objB = strtotime($dateA);
              }
              return $objA - $objB;
            }
            else{
              if($order == 'asc'){
                $objA = $a[$link];   
                $objB = $b[$link];
              }
              else{
                $objA = $b[$link];   
                $objB = $a[$link];
              }
              return $objA - $objB;
            }
        });
        return json_decode(json_encode($array));
    }

    public static function dataMouvements($from,$to)
    {
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $commandes = Commande::with('client')
            ->whereBetween('date', [$from, $to])
            ->where('user_id',$user_id)
            ->get();
        $reglements = Reglement::with(['commande' => function($query){$query->with('client');}])
            ->whereBetween('date', [$from, $to])
            ->where('user_id',$user_id)
            ->get();
        $demandes = Demande::with('fournisseur')
            ->whereBetween('date', [$from, $to])
            ->where('user_id',$user_id)
            ->get();
        $payements = Payement::with(['demande' => function($query){$query->with('fournisseur');}])
            ->whereBetween('date', [$from, $to])
            ->where('user_id',$user_id)
            ->get();
        // ########################### //
        $obj = [];
        $total_debit = 0;
        $total_credit = 0;
        foreach ($commandes as $key => $value) {
            $total_debit = $total_debit + $value->total;
            $json = [
                'date'=>$value->date,
                'nom'=>$value->client->nom_client,
                'nature'=>'BL',
                'code'=>$value->code,
                'debit'=>$value->total,
                'credit'=>null,
            ];
            array_push($obj,$json);
        }
        foreach ($reglements as $key => $value) {
            $total_credit = $total_credit + $value->avance;
            $json = [
                'date'=>$value->date,
                'nom'=>$value->commande->client->nom_client,
                'nature'=>'RC',
                'code'=>$value->code,
                'debit'=>null,
                'credit'=>$value->avance,
            ];
            array_push($obj,$json);
        }
        foreach ($demandes as $key => $value) {
            $total_credit = $total_credit + $value->total;
            $json = [
                'date'=>$value->date,
                'nom'=>$value->fournisseur->nom_fournisseur,
                'nature'=>'BA',
                'code'=>$value->code,
                'debit'=>null,
                'credit'=>$value->total,
            ];
            array_push($obj,$json);
        }
        foreach ($payements as $key => $value) {
            $total_debit = $total_debit + $value->avance;
            $json = [
                'date'=>$value->date,
                'nom'=>$value->demande->fournisseur->nom_fournisseur,
                'nature'=>'RF',
                'code'=>$value->code,
                'debit'=>$value->avance,
                'credit'=>null,
            ];
            array_push($obj,$json);
        }
        $data = json_decode(json_encode($obj));
        return compact('total_debit','total_credit','data');
    }

    public static function getDataMouvements($from,$to)
    {
        $data = Balance::dataMouvements($from,$to)['data'];
        $data = Balance::paginate($data,get_limit_pagination());
        return $data;
    }
    
    public static function fetchDataMouvements($from,$to,$type,$link,$order)
    {
        $data = Balance::dataMouvements($from,$to)['data'];
        $data = Balance::sorting($data,$type,$link,$order);
        $data = Balance::paginate($data,get_limit_pagination());
        return $data;
    }

    public static function getMouvementPrint($from,$to,$type,$link,$order)
    {
        $data = Balance::dataMouvements($from,$to)['data'];
        $data = Balance::sorting($data,$type,$link,$order);
        return $data;
    }

    public static function dataInventaires($from,$to,$category_id,$produit_id)
    {
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        if($category_id == 0) {
            $lignesdemandes = Lignedemande::with(['demande'=>
                    function ($query) {$query->with('fournisseur');},'produit'])
                ->whereHas('demande',function ($query) use($from,$to) {
                    $query->whereBetween('date', [$from, $to]);
                })
                ->where('user_id',$user_id)
                ->get();
            $lignescommandes = Lignecommande::with(['commande'=>
                    function ($query) {$query->with('client');},'produit'])
                ->whereHas('commande',function ($query) use($from,$to) {
                    $query->whereBetween('date', [$from, $to]);
                })
                ->where('user_id',$user_id)
                ->get();
        }
        elseif($category_id > 0) {
            if($produit_id > 0){
                $lignesdemandes = Lignedemande::with(['demande'=>
                    function ($query) {$query->with('fournisseur');},'produit'])
                ->whereHas('demande',function ($query) use($from,$to) {
                    $query->whereBetween('date', [$from, $to]);
                })
                ->where('produit_id',$produit_id)
                ->where('user_id',$user_id)
                ->get();
                $lignescommandes = Lignecommande::with(['commande'=>
                    function ($query) {$query->with('client');},'produit'])
                ->whereHas('commande',function ($query) use($from,$to) {
                    $query->whereBetween('date', [$from, $to]);
                })
                ->where('produit_id',$produit_id)
                ->where('user_id',$user_id)
                ->get();
            }
            else{
                $category_id > 0;
                $lignesdemandes = Lignedemande::with(['demande'=>
                    function ($query) {$query->with('fournisseur');},'produit'])
                ->whereHas('demande',function ($query) use($from,$to) {
                    $query->whereBetween('date', [$from, $to]);
                })
                ->whereHas('produit',function ($query) use($category_id) {
                    $query->where('categorie_id', $category_id);
                })
                ->where('user_id',$user_id)
                ->get();
                $lignescommandes = Lignecommande::with(['commande'=>
                    function ($query) {$query->with('client');},'produit'])
                ->whereHas('commande',function ($query) use($from,$to) {
                    $query->whereBetween('date', [$from, $to]);
                })
                ->whereHas('produit',function ($query) use($category_id) {
                    $query->where('categorie_id', $category_id);
                })
                ->where('user_id',$user_id)
                ->get();
            }
        }
        $obj = [];
        $quantite_entree = 0;
        $quantite_sortie = 0;
        $total_entree = 0;
        $total_sortie = 0;
        foreach ($lignesdemandes as $key => $value) {
            $quantite_entree = $total_entree + $value->quantite;
            $total_entree = $total_entree + $value->total_produit;
            $json = [
                'id'=>$value->id,
                'date'=>$value->demande->date,
                'produit_id'=>$value->produit_id,
                'ref_produit'=>$value->produit->code_produit,
                'nom_produit'=>$value->produit->nom_produit,
                'type'=>'Entrée',
                'nom'=>$value->demande->fournisseur->nom_fournisseur,
                'prix'=>$value->prix,
                'quantite'=>$value->quantite,
                'total'=>$value->total_produit,
            ];
            array_push($obj,$json);
        }
        foreach ($lignescommandes as $key => $value) {
            $quantite_sortie = $total_sortie + $value->quantite;
            $total_sortie = $total_sortie + $value->total_produit;
            $json = [
                'id'=>$value->id,
                'date'=>$value->commande->date,
                'produit_id'=>$value->produit_id,
                'ref_produit'=>$value->produit->code_produit,
                'nom_produit'=>$value->produit->nom_produit,
                'type'=>'Sortie',
                'nom'=>$value->commande->client->nom_client,
                'prix'=>$value->prix,
                'quantite'=>$value->quantite,
                'total'=>$value->total_produit,
            ];
            array_push($obj,$json);
        }
        $data = json_decode(json_encode($obj));
        return compact('quantite_entree','quantite_sortie','total_entree','total_sortie','data');
    }

    public static function getDataInventaires($from,$to,$category_id,$produit_id)
    {
        $data = Balance::dataInventaires($from,$to,$category_id,$produit_id)['data'];
        $data = Balance::paginate($data,get_limit_pagination());
        return $data;
    }

    public static function fetchDataInventaires($from,$to,$category_id,$produit_id,$type,$link,$order)
    {
        $data = Balance::dataInventaires($from,$to,$category_id,$produit_id)['data'];
        $data = Balance::sorting($data,$type,$link,$order);
        $data = Balance::paginate($data,get_limit_pagination());
        return $data;
    }

    public static function getInventairesPrint($from,$to,$category_id,$produit_id,$type,$link,$order)
    {
        $data = Balance::dataInventaires($from,$to,$category_id,$produit_id)['data'];
        $data = Balance::sorting($data,$type,$link,$order);
        return $data;
    }
}
