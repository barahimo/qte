<?php

namespace App\Http\Controllers;

use App\Categorie;
use App\Client;
use App\Commande;
use App\Company;
use App\Facture;
use App\Http\Middleware\SuperAdmin;
use App\Lignecommande;
use App\Produit;
use App\Reglement;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use phpDocumentor\Reflection\Types\Boolean;
use Illuminate\Support\Str;
use function App\Providers\get_limit_pagination;


use function PHPUnit\Framework\isEmpty;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        // $this->middleware('superAdmin');
        $this->middleware('statususer');
    }

    /**     
    *--------------------------------------------------------------------------
    * Mes fonctions
    *--------------------------------------------------------------------------
    **/
    public function getPermssion($string){
        $list = [];
        $array = explode("','",$string);
        foreach ($array as $value) 
            foreach (explode("['",$value) as $val) 
                if($val != '')
                    array_push($list, $val);
        $array = $list;
        $list = [];
        foreach ($array as $value) 
            foreach (explode("']",$value) as $val) 
                if($val != '')
                    array_push($list, $val);
        return $list;
    }

    public function hasPermssion($string){
        $permission = $this->getPermssion(Auth::user()->permission);
        $permission_ = $this->getPermssion(User::find(Auth::user()->user_id)->permission);
        $result = 'no';
        if(
            (Auth::user()->is_admin == 2) ||
            (Auth::user()->is_admin == 1 && in_array($string,$permission)) ||
            (Auth::user()->is_admin == 0 && in_array($string,$permission) && in_array($string,$permission_))
        )
        $result = 'yes';
        return $result;
    }

    public function storePermssion(Request $request){
        $permission10 = $request['permission10'];
        $permission11 = $request['permission11'];
        $permission12 = $request['permission12'];
        $permission13 = $request['permission13'];
        $permission14 = $request['permission14'];
        
        $permission10_2 = $request['permission10_2'];
        $permission11_2 = $request['permission11_2'];
        $permission12_2 = $request['permission12_2'];
        $permission13_2 = $request['permission13_2'];
        $permission14_2 = $request['permission14_2'];
        
        $permission20 = $request['permission20'];
        $permission21 = $request['permission21'];
        $permission22 = $request['permission22'];
        $permission23 = $request['permission23'];
        $permission24 = $request['permission24'];
        
        $permission30 = $request['permission30'];
        $permission31 = $request['permission31'];
        $permission32 = $request['permission32'];
        $permission33 = $request['permission33'];
        $permission34 = $request['permission34'];
        
        $permission40 = $request['permission40'];
        $permission41 = $request['permission41'];
        $permission42 = $request['permission42'];
        $permission43 = $request['permission43'];
        $permission44 = $request['permission44'];
        $permission45 = $request['permission45'];
        $permission46 = $request['permission46'];
        
        $permission40_2 = $request['permission40_2'];
        $permission41_2 = $request['permission41_2'];
        $permission42_2 = $request['permission42_2'];
        $permission43_2 = $request['permission43_2'];
        $permission44_2 = $request['permission44_2'];
        $permission45_2 = $request['permission45_2'];
        $permission46_2 = $request['permission46_2'];
        
        $permission50 = $request['permission50'];
        $permission51 = $request['permission51'];
        $permission52 = $request['permission52'];
        $permission53 = $request['permission53'];
        $permission54 = $request['permission54'];
        $permission55 = $request['permission55'];
        $permission56 = $request['permission56'];

        $permission50_2 = $request['permission50_2'];
        $permission51_2 = $request['permission51_2'];
        $permission52_2 = $request['permission52_2'];
        $permission53_2 = $request['permission53_2'];
        $permission54_2 = $request['permission54_2'];
        $permission55_2 = $request['permission55_2'];
        $permission56_2 = $request['permission56_2'];
        
        $permission60 = $request['permission60'];
        $permission61 = $request['permission61'];
        $permission62 = $request['permission62'];
        $permission63 = $request['permission63'];
        $permission64 = $request['permission64'];
        $permission65 = $request['permission65'];

        $permission70 = $request['permission70'];
        $permission71 = $request['permission71'];
        $permission75 = $request['permission75'];

        $permission70_2 = $request['permission70_2'];
        $permission71_2 = $request['permission71_2'];
        $permission75_2 = $request['permission75_2'];

        $permission80 = $request['permission80'];
        $permission81 = $request['permission81'];
        $permission82 = $request['permission82'];
        $permission83 = $request['permission83'];
        $permission84 = $request['permission84'];

        $permission90 = $request['permission90'];
        $permission92 = $request['permission92'];
        $permission93 = $request['permission93'];

        $array = [];
        if($permission10) array_push($array,$permission10);
        if($permission11) array_push($array,$permission11);
        if($permission12) array_push($array,$permission12);
        if($permission13) array_push($array,$permission13);
        if($permission14) array_push($array,$permission14);

        if($permission10_2) array_push($array,$permission10_2);
        if($permission11_2) array_push($array,$permission11_2);
        if($permission12_2) array_push($array,$permission12_2);
        if($permission13_2) array_push($array,$permission13_2);
        if($permission14_2) array_push($array,$permission14_2);

        if($permission20) array_push($array,$permission20);
        if($permission21) array_push($array,$permission21);
        if($permission22) array_push($array,$permission22);
        if($permission23) array_push($array,$permission23);
        if($permission24) array_push($array,$permission24);

        if($permission30) array_push($array,$permission30);
        if($permission31) array_push($array,$permission31);
        if($permission32) array_push($array,$permission32);
        if($permission33) array_push($array,$permission33);
        if($permission34) array_push($array,$permission34);

        if($permission40) array_push($array,$permission40);
        if($permission41) array_push($array,$permission41);
        if($permission42) array_push($array,$permission42);
        if($permission43) array_push($array,$permission43);
        if($permission44) array_push($array,$permission44);
        if($permission45) array_push($array,$permission45);
        if($permission46) array_push($array,$permission46);

        if($permission40_2) array_push($array,$permission40_2);
        if($permission41_2) array_push($array,$permission41_2);
        if($permission42_2) array_push($array,$permission42_2);
        if($permission43_2) array_push($array,$permission43_2);
        if($permission44_2) array_push($array,$permission44_2);
        if($permission45_2) array_push($array,$permission45_2);
        if($permission46_2) array_push($array,$permission46_2);

        if($permission50) array_push($array,$permission50);
        if($permission51) array_push($array,$permission51);
        if($permission52) array_push($array,$permission52);
        if($permission53) array_push($array,$permission53);
        if($permission54) array_push($array,$permission54);
        if($permission55) array_push($array,$permission55);
        if($permission56) array_push($array,$permission56);

        if($permission50_2) array_push($array,$permission50_2);
        if($permission51_2) array_push($array,$permission51_2);
        if($permission52_2) array_push($array,$permission52_2);
        if($permission53_2) array_push($array,$permission53_2);
        if($permission54_2) array_push($array,$permission54_2);
        if($permission55_2) array_push($array,$permission55_2);
        if($permission56_2) array_push($array,$permission56_2);

        if($permission60) array_push($array,$permission60);
        if($permission61) array_push($array,$permission61);
        if($permission62) array_push($array,$permission62);
        if($permission63) array_push($array,$permission63);
        if($permission64) array_push($array,$permission64);
        if($permission65) array_push($array,$permission65);

        if($permission70) array_push($array,$permission70);
        if($permission71) array_push($array,$permission71);
        if($permission75) array_push($array,$permission75);

        if($permission70_2) array_push($array,$permission70_2);
        if($permission71_2) array_push($array,$permission71_2);
        if($permission75_2) array_push($array,$permission75_2);

        if($permission80) array_push($array,$permission80);
        if($permission81) array_push($array,$permission81);
        if($permission82) array_push($array,$permission82);
        if($permission83) array_push($array,$permission83);
        if($permission84) array_push($array,$permission84);
        
        if($permission90) array_push($array,$permission90);
        if($permission92) array_push($array,$permission92);
        if($permission93) array_push($array,$permission93);
        $permission = "[";
        foreach ($array as $key => $value) {
            $permission.="'".$value."'"; 
            if($key != count($array)-1)
                $permission.=","; 
        }
        $permission .= "]";
        return $permission;
    }

    public function rememberToken(Request $request){
        return $this->guard()->attempt(
            $this->credentials($request), $request->has('remember')
        );
    }

    /**     
    *--------------------------------------------------------------------------
    * editUser
    *--------------------------------------------------------------------------
    **/
    public function editUser($id){
        $user = User::where('id',Auth::user()->id)->findOrFail($id);
        $permission = $this->getPermssion(Auth::user()->permission);
        if($this->hasPermssion('list9') == 'yes')
        return view('managements.users.edit')->with([
            "user" => $user,
            "visibility" => false,
            "permission" => $permission
        ]);
        else
        return redirect()->back();
    }
    /**     
    *--------------------------------------------------------------------------
    * findEmail
    *--------------------------------------------------------------------------
    **/
    public function findEmail(Request $request){
        $user_id = Auth::user()->id;
        $user=User::where('email',$request->email)->where('user_id',$user_id)->first();
        $existe = false;
        if($user || $request->email===Auth::user()->email) $existe = true;
        return $existe;
	}
    /**     
    *--------------------------------------------------------------------------
    * Ressources
    *--------------------------------------------------------------------------
    **/
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $users = User::where([['is_admin','!=',2],['user_id',Auth::user()->id]])
        ->orderBy('id','desc')
        ->paginate(get_limit_pagination());
        $permission = $this->getPermssion(Auth::user()->permission);
        if($this->hasPermssion('list8') == 'yes')
        return view('managements.users.index', compact('users','permission'));
        else
        return redirect()->back();
    }

    function fetch_user(Request $request){
        if($request->ajax()){
            $users = User::where([['is_admin','!=',2],['user_id',Auth::user()->id]])
            ->orderBy('id','desc')
            ->paginate(get_limit_pagination());
            return view('managements.users.index_data',compact('users'))->render();
        }
    }
    public function index_save(){
        $users = User::where([['is_admin','!=',2],['user_id',Auth::user()->id]])->orderBy('id','desc')->get();
        $permission = $this->getPermssion(Auth::user()->permission);
        if($this->hasPermssion('list8') == 'yes')
        return view('managements.users.index', compact('users','permission'));
        else
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        if($this->hasPermssion('create8') == 'yes')
        return view('managements.users.create');
        else
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $permission = $this->storePermssion($request);
        $name = $request['name'];
        $email = $request['email'];
        $password = Hash::make($request['password']);

        $status = $request['status'];
        $is_admin = 0;
        if(Auth::user()->is_admin == 2)
            $is_admin = 1;
        $remember_token = Str::random(60);
        $user_id = Auth::user()->id;

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = $password;
        $user->is_admin = $is_admin;
        $user->status = $status;
        $user->remember_token = $remember_token;
        $user->user_id = $user_id;
        $user->permission = $permission;
        $user->save();

        $request->session()->flash('status','Utilisateur a été bien enregistré !');
        return redirect()->route('user.index');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
        $user_id = Auth::user()->user_id;
        $user = User::where('user_id',$user_id)->findOrFail($id);
        $permission = $this->getPermssion($user->permission);
        if($this->hasPermssion('edit8') == 'yes')
        return view('managements.users.edit')->with([
            "user" => $user,
            "visibility" => true,
            "permission" => $permission
        ]);
        else
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $is_pass = $request['is_pass'];
        $permission = $this->storePermssion($request);
        $visibility = $request['visibility'];
        $name = $request['name'];
        $email = $request['email'];

        $status = $request['status'];
        $is_admin = 0;
        if(Auth::user()->is_admin == 2)
        $is_admin = 1;
        $user_id = Auth::user()->id;
        $password = Hash::make($request['password']);
        
        if ($visibility) {
            $user = User::where('user_id',Auth::user()->id)->find($id);
            $user->status = $status;
            $user->is_admin = $is_admin;
            $user->user_id = $user_id;
            $user->permission = $permission;
        }
        else{
            $user = User::where('id',Auth::user()->id)->find($id);
            $is_admin = Auth::user()->is_admin;
        }
        $user->name = $name;
        $user->email = $email;

        if($is_pass == 'yes')
            $user->password = $password;
        
        $user->save();

        if($visibility){
            $request->session()->flash('status',"Utlisateur a été bien modifié !");
            return redirect()->route('user.index');
        }
        else{
            $request->session()->flash('status',"Les informations sont bien modifiés !");
            return redirect()->route('app.home');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $msg = "Utilisateur est supprimés avec succès !";
        $user_id = $id;
        $user = User::find($id);
        ####################################################
        if($user->is_admin == 1)
        {
            $factures = Facture::where('user_id',$user_id)->get();
            $reglements = Reglement::where('user_id',$user_id)->get();
            $lignecommandes = Lignecommande::where('user_id',$user_id)->get();
            $categories = Categorie::where('user_id',$user_id)->get();
            $produits = Produit::where('user_id',$user_id)->get();
            $commandes = Commande::where('user_id',$user_id)->get();
            $clients = Client::where('user_id',$user_id)->get();
            $companies = Company::where('user_id',$user_id)->get();
            $users = User::where('user_id',$user_id)->get();
            ####################################################
            if($factures->count() != 0){
                foreach ($factures as $facture) {
                    $facture->delete();
                }
            }
            if($reglements->count() != 0){
                foreach ($reglements as $reglement) {
                    $reglement->delete();
                }
            }
            if($lignecommandes->count() != 0){
                foreach ($lignecommandes as $lignecommande) {
                    $lignecommande->delete();
                }
            }
            if($categories->count() != 0){
                foreach ($categories as $categorie) {
                    $categorie->delete();
                }
            }
            if($produits->count() != 0){
                foreach ($produits as $produit) {
                    $produit->delete();
                }
            }
            if($commandes->count() != 0){
                foreach ($commandes as $commande) {
                    $commande->delete();
                }
            }
            if($clients->count() != 0){
                foreach ($clients as $client) {
                    $client->delete();
                }
            }
            if($companies->count() != 0){
                foreach ($companies as $companie) {
                    $companie->delete();
                }
            }
            if($users->count() != 0){
                foreach ($users as $user) {
                    $user->delete();
                }
            }
            $msg = "Utilisateur et ses composants sont supprimés avec succès !";
        }
        ####################################################
        $user->delete();
        return redirect()->route('user.index')->with(["status" => $msg]); 
    }
}
