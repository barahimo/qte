@extends('layout.dashboard')
@section('contenu')
{{-- ################## --}}
<!-- Content Header (Page header) -->
<div class="content-header sty-one">
    <h1>Modification d'utilisateur</h1>
    <ol class="breadcrumb">
        <li><a href="{{route('app.home')}}">Home</a></li>
        <li><i class="fa fa-angle-right"></i> Utlisateur</li>
    </ol>
</div>
{{-- ################## --}}
<!-- Main content -->
<div class="content">
    {{-- ---------------- --}}
    <div class="row m-t-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-blue">
                    <h5 class="text-white m-b-0">Formulaire d'utilisateur</h5>
                </div>
                <div class="card-body">
                    <form action="{{route('user.update',['user'=> $user->id])}}" method="POST">
                        @csrf 
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                <label class="control-label" for="name">Nom d'utilisateur</label>
                                <input class="form-control" placeholder="Nom d'utilisateur" type="text" name="name" id="name"  value="{{ old('name', $user->name ?? null) }}">
                                <span class="fa fa-user form-control-feedback" aria-hidden="true"></span> </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                <label class="control-label" for="email">E-Mail</label>
                                <input class="form-control" placeholder="E-Mail" type="email" id="email1" value="{{ old('email', $user->email ?? null) }}" disabled>
                                <input class="form-control" placeholder="E-Mail" type="hidden" name="email" id="email2" value="{{ old('email', $user->email ?? null) }}">
                                <span class="badge badge-danger" id="erreur"></span>
                                <span class="fa fa-envelope-o form-control-feedback" aria-hidden="true"></span> </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-danger btn-sm text-white" name="changePasse">Changer le mot de passe</button>
                            </div>
                            <div class="col-md-12">
                                &nbsp;&nbsp;
                            </div>
                            <input type="hidden" name="is_pass" id="is_pass" value='no'>
                            <div class="col-12" id="pass" style="display : none">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group has-feedback">
                                            <label class="control-label" for="password">Nouveau mot de passe</label>
                                            <input class="form-control" placeholder="Mot de passe" type="password" name="password" id="password">
                                            <span class="fa fa-lock form-control-feedback" aria-hidden="true"></span> 
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group has-feedback">
                                            <label class="control-label" for="password_confirmation">Confirmer le mot de passe</label>
                                            <input class="form-control" placeholder="Confirmer le mot de passe" type="password" name="password_confirmation" id="password_confirmation">
                                            <span class="fa fa-lock form-control-feedback" aria-hidden="true"></span> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <label class="control-label" for="is_admin">Type d'utilisateur</label>
                                    <select class="form-control" id="is_admin" name="is_admin">
                                        <option value="">-- Type d'utilisateur --</option>
                                        <option value="0"  @if ($user->is_admin == old('is_admin',0 ?? null)) selected="selected" @endif> User</option>
                                        <option value="1"  @if ($user->is_admin == old('is_admin',1 ?? null)) selected="selected" @endif> Admin</option>
                                    </select>
                                </div>
                            </div> --}}
                            <input type="hidden" name="visibility" id="visibility" value="{{$visibility}}">
                            @if($visibility)
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <label class="control-label" for="status">Status d'utilisateur</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="">-- Status d'utilisateur --</option>
                                        <option value="1"  @if ($user->status == old('status',1 ?? null)) selected="selected" @endif> Active</option>
                                        <option value="0"  @if ($user->status == old('status',0 ?? null)) selected="selected" @endif> InActive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="control-label" for="permission"><strong>Permissions</strong></label>
                                {{-- Begin - Fournisseur --}}
                                <div class="form-group has-feedback">
                                    <label class="control-label" for="permission1_2" id="permission1_2" onclick="check1_2()"><u>Gestions des Fournisseurs</u>&nbsp;:&nbsp;</label>
                                    <div class="checkbox">
                                        <label><input name="permission10_2" id="permission10_2" value="list1_2" type="checkbox" @if (in_array('list1_2',$permission)) checked="checked" @endif>&nbsp;List</label>
                                        <label><input name="permission11_2" id="permission11_2" value="show1_2" type="checkbox" @if (in_array('show1_2',$permission)) checked="checked" @endif>&nbsp;Affichage</label>
                                        <label><input name="permission12_2" id="permission12_2" value="create1_2" type="checkbox" @if (in_array('create1_2',$permission)) checked="checked" @endif>&nbsp;Création</label>
                                        <label><input name="permission13_2" id="permission13_2" value="edit1_2" type="checkbox" @if (in_array('edit1_2',$permission)) checked="checked" @endif>&nbsp;Modification</label>
                                        <label><input name="permission14_2" id="permission14_2" value="delete1_2" type="checkbox" @if (in_array('delete1_2',$permission)) checked="checked" @endif>&nbsp;Suppression</label>
                                    </div>
                                </div>
                                {{-- Begin - Client --}}
                                <div class="form-group has-feedback">
                                    <label class="control-label" for="permission1" id="permission1" onclick="check1()"><u>Gestions des Clients</u>&nbsp;:&nbsp;</label>
                                    <div class="checkbox">
                                        <label><input name="permission10" id="permission10" value="list1" type="checkbox" @if (in_array('list1',$permission)) checked="checked" @endif>&nbsp;List</label>
                                        <label><input name="permission11" id="permission11" value="show1" type="checkbox" @if (in_array('show1',$permission)) checked="checked" @endif>&nbsp;Affichage</label>
                                        <label><input name="permission12" id="permission12" value="create1" type="checkbox" @if (in_array('create1',$permission)) checked="checked" @endif>&nbsp;Création</label>
                                        <label><input name="permission13" id="permission13" value="edit1" type="checkbox" @if (in_array('edit1',$permission)) checked="checked" @endif>&nbsp;Modification</label>
                                        <label><input name="permission14" id="permission14" value="delete1" type="checkbox" @if (in_array('delete1',$permission)) checked="checked" @endif>&nbsp;Suppression</label>
                                    </div>
                                </div>
                                {{-- Begin - Catégorie --}}
                                <div class="form-group has-feedback">
                                    <label class="control-label" for="permission2" id="permission2" onclick="check2()"><u>Gestions des Catégories</u>&nbsp;:&nbsp;</label>
                                    <div class="checkbox">
                                        <label><input name="permission20" id="permission20" value="list2" type="checkbox" @if (in_array('list2',$permission)) checked="checked" @endif>&nbsp;List</label>
                                        <label><input name="permission21" id="permission21" value="show2" type="checkbox" @if (in_array('show2',$permission)) checked="checked" @endif>&nbsp;Affichage</label>
                                        <label><input name="permission22" id="permission22" value="create2" type="checkbox" @if (in_array('create2',$permission)) checked="checked" @endif>&nbsp;Création</label>
                                        <label><input name="permission23" id="permission23" value="edit2" type="checkbox" @if (in_array('edit2',$permission)) checked="checked" @endif>&nbsp;Modification</label>
                                        <label><input name="permission24" id="permission24" value="delete2" type="checkbox" @if (in_array('delete2',$permission)) checked="checked" @endif>&nbsp;Suppression</label>
                                    </div>
                                </div>
                                {{-- Begin - Produit --}}
                                <div class="form-group has-feedback">
                                    <label class="control-label" for="permission3" id="permission3" onclick="check3()"><u>Gestions des Produits</u>&nbsp;:&nbsp;</label>
                                    <div class="checkbox">
                                        <label><input name="permission30" id="permission30" value="list3" type="checkbox" @if (in_array('list3',$permission)) checked="checked" @endif>&nbsp;List</label>
                                        <label><input name="permission31" id="permission31" value="show3" type="checkbox" @if (in_array('show3',$permission)) checked="checked" @endif>&nbsp;Affichage</label>
                                        <label><input name="permission32" id="permission32" value="create3" type="checkbox" @if (in_array('create3',$permission)) checked="checked" @endif>&nbsp;Création</label>
                                        <label><input name="permission33" id="permission33" value="edit3" type="checkbox" @if (in_array('edit3',$permission)) checked="checked" @endif>&nbsp;Modification</label>
                                        <label><input name="permission34" id="permission34" value="delete3" type="checkbox" @if (in_array('delete3',$permission)) checked="checked" @endif>&nbsp;Suppression</label>
                                    </div>
                                </div>
                                {{-- Begin - Demande --}}
                                <div class="form-group has-feedback">
                                    <label class="control-label" for="permission4_2" id="permission4_2" onclick="check4_2()"><u>Gestions des achats</u>&nbsp;:&nbsp;</label>
                                    <div class="checkbox">
                                        <label><input name="permission40_2" id="permission40_2" value="list4_2" type="checkbox" @if (in_array('list4_2',$permission)) checked="checked" @endif>&nbsp;List</label>
                                        <label><input name="permission41_2" id="permission41_2" value="show4_2" type="checkbox" @if (in_array('show4_2',$permission)) checked="checked" @endif>&nbsp;Affichage</label>
                                        <label><input name="permission42_2" id="permission42_2" value="create4_2" type="checkbox" @if (in_array('create4_2',$permission)) checked="checked" @endif>&nbsp;Création</label>
                                        <label><input name="permission43_2" id="permission43_2" value="edit4_2" type="checkbox" @if (in_array('edit4_2',$permission)) checked="checked" @endif>&nbsp;Modification</label>
                                        <label><input name="permission44_2" id="permission44_2" value="delete4_2" type="checkbox" @if (in_array('delete4_2',$permission)) checked="checked" @endif>&nbsp;Suppression</label>
                                        <label><input name="permission45_2" id="permission45_2" value="print4_2" type="checkbox" @if (in_array('print4_2',$permission)) checked="checked" @endif>&nbsp;Impression</label>
                                        <label><input name="permission46_2" id="permission46_2" value="details4_2" type="checkbox"  @if (in_array('details4_2',$permission)) checked="checked" @endif>&nbsp;Détails</label>
                                    </div>
                                </div>
                                {{-- Begin - Commande --}}
                                <div class="form-group has-feedback">
                                    <label class="control-label" for="permission4" id="permission4" onclick="check4()"><u>Gestions des ventes</u>&nbsp;:&nbsp;</label>
                                    <div class="checkbox">
                                        <label><input name="permission40" id="permission40" value="list4" type="checkbox" @if (in_array('list4',$permission)) checked="checked" @endif>&nbsp;List</label>
                                        <label><input name="permission41" id="permission41" value="show4" type="checkbox" @if (in_array('show4',$permission)) checked="checked" @endif>&nbsp;Affichage</label>
                                        <label><input name="permission42" id="permission42" value="create4" type="checkbox" @if (in_array('create4',$permission)) checked="checked" @endif>&nbsp;Création</label>
                                        <label><input name="permission43" id="permission43" value="edit4" type="checkbox" @if (in_array('edit4',$permission)) checked="checked" @endif>&nbsp;Modification</label>
                                        <label><input name="permission44" id="permission44" value="delete4" type="checkbox" @if (in_array('delete4',$permission)) checked="checked" @endif>&nbsp;Suppression</label>
                                        <label><input name="permission45" id="permission45" value="print4" type="checkbox" @if (in_array('print4',$permission)) checked="checked" @endif>&nbsp;Impression</label>
                                        <label><input name="permission46" id="permission46" value="details4" type="checkbox"  @if (in_array('details4',$permission)) checked="checked" @endif>&nbsp;Détails</label>
                                    </div>
                                </div>
                                {{-- Begin - Payements --}}
                                <div class="form-group has-feedback">
                                    <label class="control-label" for="permission5_2" id="permission5_2" onclick="check5_2()"><u>Gestions des Règlements Fournisseurs</u>&nbsp;:&nbsp;</label>
                                    <div class="checkbox">
                                        <label><input name="permission50_2" id="permission50_2" value="list5_2" type="checkbox" @if (in_array('list5_2',$permission)) checked="checked" @endif>&nbsp;List</label>
                                        <label><input name="permission51_2" id="permission51_2" value="show5_2" type="checkbox" @if (in_array('show5_2',$permission)) checked="checked" @endif>&nbsp;Affichage</label>
                                        <label><input name="permission52_2" id="permission52_2" value="create5_2" type="checkbox" @if (in_array('create5_2',$permission)) checked="checked" @endif>&nbsp;Création</label>
                                        <label><input name="permission53_2" id="permission53_2" value="edit5_2" type="checkbox" @if (in_array('edit5_2',$permission)) checked="checked" @endif>&nbsp;Modification</label>
                                        <label><input name="permission54_2" id="permission54_2" value="delete5_2" type="checkbox" @if (in_array('delete5_2',$permission)) checked="checked" @endif>&nbsp;Suppression</label>
                                        <label><input name="permission55_2" id="permission55_2" value="print5_2" type="checkbox" @if (in_array('print5_2',$permission)) checked="checked" @endif>&nbsp;Impression</label>
                                        <label><input name="permission56_2" id="permission56_2" value="details5_2" type="checkbox"  @if (in_array('details5_2',$permission)) checked="checked" @endif>&nbsp;Détails</label>
                                    </div>
                                </div>
                                {{-- Begin - Règlement --}}
                                <div class="form-group has-feedback">
                                    <label class="control-label" for="permission5" id="permission5" onclick="check5()"><u>Gestions des Règlements Clients</u>&nbsp;:&nbsp;</label>
                                    <div class="checkbox">
                                        <label><input name="permission50" id="permission50" value="list5" type="checkbox" @if (in_array('list5',$permission)) checked="checked" @endif>&nbsp;List</label>
                                        <label><input name="permission51" id="permission51" value="show5" type="checkbox" @if (in_array('show5',$permission)) checked="checked" @endif>&nbsp;Affichage</label>
                                        <label><input name="permission52" id="permission52" value="create5" type="checkbox" @if (in_array('create5',$permission)) checked="checked" @endif>&nbsp;Création</label>
                                        <label><input name="permission53" id="permission53" value="edit5" type="checkbox" @if (in_array('edit5',$permission)) checked="checked" @endif>&nbsp;Modification</label>
                                        <label><input name="permission54" id="permission54" value="delete5" type="checkbox" @if (in_array('delete5',$permission)) checked="checked" @endif>&nbsp;Suppression</label>
                                        <label><input name="permission55" id="permission55" value="print5" type="checkbox" @if (in_array('print5',$permission)) checked="checked" @endif>&nbsp;Impression</label>
                                        <label><input name="permission56" id="permission56" value="details5" type="checkbox"  @if (in_array('details5',$permission)) checked="checked" @endif>&nbsp;Détails</label>
                                    </div>
                                </div>
                                {{-- Begin - Facture --}}
                                <div class="form-group has-feedback">
                                    <label class="control-label" for="permission6" id="permission6" onclick="check6()"><u>Gestions des Factures</u>&nbsp;:&nbsp;</label>
                                    <div class="checkbox">
                                        <label><input name="permission60" id="permission60" value="list6" type="checkbox" @if (in_array('list6',$permission)) checked="checked" @endif>&nbsp;List</label>
                                        <label><input name="permission61" id="permission61" value="show6" type="checkbox" @if (in_array('show6',$permission)) checked="checked" @endif>&nbsp;Affichage</label>
                                        <label><input name="permission62" id="permission62" value="create6" type="checkbox" @if (in_array('create6',$permission)) checked="checked" @endif>&nbsp;Création</label>
                                        <label><input name="permission63" id="permission63" value="edit6" type="checkbox" @if (in_array('edit6',$permission)) checked="checked" @endif>&nbsp;Modification</label>
                                        <label><input name="permission64" id="permission64" value="delete6" type="checkbox" @if (in_array('delete6',$permission)) checked="checked" @endif>&nbsp;Suppression</label>
                                        <label><input name="permission65" id="permission65" value="print6" type="checkbox" @if (in_array('print6',$permission)) checked="checked" @endif>&nbsp;Impression</label>
                                    </div>
                                </div>
                                {{-- Begin - Mouvement --}}
                                <div class="form-group has-feedback">
                                    <label class="control-label" for="permission7" id="permission7" onclick="check7()"><u>Gestions des Mouvements</u>&nbsp;:&nbsp;</label>
                                    <div class="checkbox">
                                        <label><input name="permission70" id="permission70" value="list7" type="checkbox" @if (in_array('list7',$permission)) checked="checked" @endif>&nbsp;List</label>
                                        <label><input name="permission71" id="permission71" value="show7" type="checkbox" @if (in_array('show7',$permission)) checked="checked" @endif>&nbsp;Affichage</label>
                                        <label><input name="permission75" id="permission75" value="print7" type="checkbox" @if (in_array('print7',$permission)) checked="checked" @endif>&nbsp;Impression</label>
                                    </div>
                                </div>
                                {{-- Begin - Inventaire --}}
                                <div class="form-group has-feedback">
                                    <label class="control-label" for="permission7_2" id="permission7_2" onclick="check7_2()"><u>Gestions des Inventaires</u>&nbsp;:&nbsp;</label>
                                    <div class="checkbox">
                                        <label><input name="permission70_2" id="permission70_2" value="list7_2" type="checkbox" @if (in_array('list7_2',$permission)) checked="checked" @endif>&nbsp;List</label>
                                        <label><input name="permission71_2" id="permission71_2" value="show7_2" type="checkbox" @if (in_array('show7_2',$permission)) checked="checked" @endif>&nbsp;Affichage</label>
                                        <label><input name="permission75_2" id="permission75_2" value="print7_2" type="checkbox" @if (in_array('print7_2',$permission)) checked="checked" @endif>&nbsp;Impression</label>
                                    </div>
                                </div>
                                @if(Auth::user()->is_admin == 2)
                                {{-- Begin - Utilisateur --}}
                                <div class="form-group has-feedback">
                                    <label class="control-label" for="permission8" id="permission8" onclick="check8()"><u>Gestions des Utilisateurs</u>&nbsp;:&nbsp;</label>
                                    <div class="checkbox">
                                        <label><input name="permission80" id="permission80" value="list8" type="checkbox" @if (in_array('list8',$permission)) checked="checked" @endif>&nbsp;List</label>
                                        <label><input name="permission81" id="permission81" value="show8" type="checkbox" @if (in_array('show8',$permission)) checked="checked" @endif>&nbsp;Affichage</label>
                                        <label><input name="permission82" id="permission82" value="create8" type="checkbox" @if (in_array('create8',$permission)) checked="checked" @endif>&nbsp;Création</label>
                                        <label><input name="permission83" id="permission83" value="edit8" type="checkbox" @if (in_array('edit8',$permission)) checked="checked" @endif>&nbsp;Modification</label>
                                        <label><input name="permission84" id="permission84" value="delete8" type="checkbox" @if (in_array('delete8',$permission)) checked="checked" @endif>&nbsp;Suppression</label>
                                    </div>
                                </div>
                                @endif
                                {{-- Begin - Import|Export --}}
                                <div class="form-group has-feedback">
                                    <label class="control-label" for="permission9_2" id="permission9_2" onclick="check9_2()"><u>Import | Export</u>&nbsp;:&nbsp;</label>
                                    <div class="checkbox">
                                        <label><input name="permission97_2" id="permission97_2" value="import9_2" type="checkbox" @if (in_array('import9_2',$permission)) checked="checked" @endif>&nbsp;Import des fichiers</label>
                                        <label><input name="permission98_2" id="permission98_2" value="export9_2" type="checkbox" @if (in_array('export9_2',$permission)) checked="checked" @endif>&nbsp;Export des fichiers</label>
                                    </div>
                                </div>
                                {{-- Begin - Paramètres --}}
                                <div class="form-group has-feedback">
                                    <label class="control-label" for="permission9" id="permission9" onclick="check9()"><u>Paramètres</u>&nbsp;:&nbsp;</label>
                                    <div class="checkbox">
                                        <label><input name="permission90" id="permission90" value="list9" type="checkbox" @if (in_array('list9',$permission)) checked="checked" @endif>&nbsp;Informations de compte</label>
                                        <label><input name="permission92" id="permission92" value="create9" type="checkbox" @if (in_array('create9',$permission)) checked="checked" @endif>&nbsp;Création Formulaire</label>
                                        <label><input name="permission93" id="permission93" value="edit9" type="checkbox" @if (in_array('edit9',$permission)) checked="checked" @endif>&nbsp;Modification Formulaire</label>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-warning text-white" name="updateUser">Modifier</button>
                                &nbsp;
                                <a href="{{action('UserController@index')}}" class="btn btn-info">Retour</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- ---------------- --}}
</div>
<!-- /.content --> 
{{-- ################## --}}
<script type="text/javascript">
    function check1(){
        if(
            $('#permission10').prop('checked') == true &&
            $('#permission11').prop('checked') == true &&
            $('#permission12').prop('checked') == true &&
            $('#permission13').prop('checked') == true &&
            $('#permission14').prop('checked') == true
        ){
            $('#permission10').prop('checked',false);
            $('#permission11').prop('checked',false);
            $('#permission12').prop('checked',false);
            $('#permission13').prop('checked',false);
            $('#permission14').prop('checked',false);
        }
        else{
            $('#permission10').prop('checked',true);
            $('#permission11').prop('checked',true);
            $('#permission12').prop('checked',true);
            $('#permission13').prop('checked',true);
            $('#permission14').prop('checked',true);
        }
    }
    function check1_2(){
        if(
            $('#permission10_2').prop('checked') == true &&
            $('#permission11_2').prop('checked') == true &&
            $('#permission12_2').prop('checked') == true &&
            $('#permission13_2').prop('checked') == true &&
            $('#permission14_2').prop('checked') == true
        ){
            $('#permission10_2').prop('checked',false);
            $('#permission11_2').prop('checked',false);
            $('#permission12_2').prop('checked',false);
            $('#permission13_2').prop('checked',false);
            $('#permission14_2').prop('checked',false);
        }
        else{
            $('#permission10_2').prop('checked',true);
            $('#permission11_2').prop('checked',true);
            $('#permission12_2').prop('checked',true);
            $('#permission13_2').prop('checked',true);
            $('#permission14_2').prop('checked',true);
        }
    }
    function check2(){
        if(
            $('#permission20').prop('checked') == true && 
            $('#permission21').prop('checked') == true && 
            $('#permission22').prop('checked') == true && 
            $('#permission23').prop('checked') == true && 
            $('#permission24').prop('checked') == true
        ){
            $('#permission20').prop('checked',false);
            $('#permission21').prop('checked',false);
            $('#permission22').prop('checked',false);
            $('#permission23').prop('checked',false);
            $('#permission24').prop('checked',false);
        }
        else{
            $('#permission20').prop('checked',true);
            $('#permission21').prop('checked',true);
            $('#permission22').prop('checked',true);
            $('#permission23').prop('checked',true);
            $('#permission24').prop('checked',true);
        }
    }
    function check3(){
        if(
            $('#permission30').prop('checked') == true && 
            $('#permission31').prop('checked') == true && 
            $('#permission32').prop('checked') == true && 
            $('#permission33').prop('checked') == true && 
            $('#permission34').prop('checked') == true   
        ){
            $('#permission30').prop('checked',false);
            $('#permission31').prop('checked',false);
            $('#permission32').prop('checked',false);
            $('#permission33').prop('checked',false);
            $('#permission34').prop('checked',false);
        }
        else{
            $('#permission30').prop('checked',true);
            $('#permission31').prop('checked',true);
            $('#permission32').prop('checked',true);
            $('#permission33').prop('checked',true);
            $('#permission34').prop('checked',true);
        }
    }
    function check4(){
        if(
            $('#permission40').prop('checked') == true &&
            $('#permission41').prop('checked') == true &&
            $('#permission42').prop('checked') == true &&
            $('#permission43').prop('checked') == true &&
            $('#permission44').prop('checked') == true &&
            $('#permission45').prop('checked') == true &&
            $('#permission46').prop('checked') == true
        ){
            $('#permission40').prop('checked',false);
            $('#permission41').prop('checked',false);
            $('#permission42').prop('checked',false);
            $('#permission43').prop('checked',false);
            $('#permission44').prop('checked',false);
            $('#permission45').prop('checked',false);
            $('#permission46').prop('checked',false);
        }
        else{
            $('#permission40').prop('checked',true);
            $('#permission41').prop('checked',true);
            $('#permission42').prop('checked',true);
            $('#permission43').prop('checked',true);
            $('#permission44').prop('checked',true);
            $('#permission45').prop('checked',true);
            $('#permission46').prop('checked',true);
        }
    }
    function check4_2(){
        if(
            $('#permission40_2').prop('checked') == true &&
            $('#permission41_2').prop('checked') == true &&
            $('#permission42_2').prop('checked') == true &&
            $('#permission43_2').prop('checked') == true &&
            $('#permission44_2').prop('checked') == true &&
            $('#permission45_2').prop('checked') == true &&
            $('#permission46_2').prop('checked') == true
        ){
            $('#permission40_2').prop('checked',false);
            $('#permission41_2').prop('checked',false);
            $('#permission42_2').prop('checked',false);
            $('#permission43_2').prop('checked',false);
            $('#permission44_2').prop('checked',false);
            $('#permission45_2').prop('checked',false);
            $('#permission46_2').prop('checked',false);
        }
        else{
            $('#permission40_2').prop('checked',true);
            $('#permission41_2').prop('checked',true);
            $('#permission42_2').prop('checked',true);
            $('#permission43_2').prop('checked',true);
            $('#permission44_2').prop('checked',true);
            $('#permission45_2').prop('checked',true);
            $('#permission46_2').prop('checked',true);
        }
    }
    function check5(){
        if(
            $('#permission50').prop('checked') == true &&
            $('#permission51').prop('checked') == true &&
            $('#permission52').prop('checked') == true &&
            $('#permission53').prop('checked') == true &&
            $('#permission54').prop('checked') == true &&
            $('#permission55').prop('checked') == true &&
            $('#permission56').prop('checked') == true
        ){
            $('#permission50').prop('checked',false);
            $('#permission51').prop('checked',false);
            $('#permission52').prop('checked',false);
            $('#permission53').prop('checked',false);
            $('#permission54').prop('checked',false);
            $('#permission55').prop('checked',false);
            $('#permission56').prop('checked',false);
        }
        else{
            $('#permission50').prop('checked',true);
            $('#permission51').prop('checked',true);
            $('#permission52').prop('checked',true);
            $('#permission53').prop('checked',true);
            $('#permission54').prop('checked',true);
            $('#permission55').prop('checked',true);
            $('#permission56').prop('checked',true);
        }
    }
    function check6(){
        if(
            $('#permission60').prop('checked') == true &&
            $('#permission61').prop('checked') == true &&
            $('#permission62').prop('checked') == true &&
            $('#permission63').prop('checked') == true &&
            $('#permission64').prop('checked') == true &&
            $('#permission65').prop('checked') == true
        )
        {
            $('#permission60').prop('checked',false);
            $('#permission61').prop('checked',false);
            $('#permission62').prop('checked',false);
            $('#permission63').prop('checked',false);
            $('#permission64').prop('checked',false);
            $('#permission65').prop('checked',false);
        }
        else{
            $('#permission60').prop('checked',true);
            $('#permission61').prop('checked',true);
            $('#permission62').prop('checked',true);
            $('#permission63').prop('checked',true);
            $('#permission64').prop('checked',true);
            $('#permission65').prop('checked',true);
        }
    }
    function check7(){
        if(
            $('#permission70').prop('checked') == true &&
            $('#permission71').prop('checked') == true &&
            $('#permission75').prop('checked') == true
        ){
            $('#permission70').prop('checked',false);
            $('#permission71').prop('checked',false);
            $('#permission75').prop('checked',false);
        }
        else{
            $('#permission70').prop('checked',true);
            $('#permission71').prop('checked',true);
            $('#permission75').prop('checked',true);
        }
    }
    function check7_2(){
        if(
            $('#permission70_2').prop('checked') == true &&
            $('#permission71_2').prop('checked') == true &&
            $('#permission75_2').prop('checked') == true
        ){
            $('#permission70_2').prop('checked',false);
            $('#permission71_2').prop('checked',false);
            $('#permission75_2').prop('checked',false);
        }
        else{
            $('#permission70_2').prop('checked',true);
            $('#permission71_2').prop('checked',true);
            $('#permission75_2').prop('checked',true);
        }
    }
    function check8(){
        if(
            $('#permission80').prop('checked') == true &&
            $('#permission81').prop('checked') == true &&
            $('#permission82').prop('checked') == true &&
            $('#permission83').prop('checked') == true &&
            $('#permission84').prop('checked') == true 
        ){
            $('#permission80').prop('checked',false);
            $('#permission81').prop('checked',false);
            $('#permission82').prop('checked',false);
            $('#permission83').prop('checked',false);
            $('#permission84').prop('checked',false);
        }
        else{
            $('#permission80').prop('checked',true);
            $('#permission81').prop('checked',true);
            $('#permission82').prop('checked',true);
            $('#permission83').prop('checked',true);
            $('#permission84').prop('checked',true);
        }
    }
    function check9(){
        if(
            $('#permission90').prop('checked') == true &&
            $('#permission92').prop('checked') == true &&
            $('#permission93').prop('checked') == true 
        ){
            $('#permission90').prop('checked',false);
            $('#permission92').prop('checked',false);
            $('#permission93').prop('checked',false);
        }
        else{
            $('#permission90').prop('checked',true);
            $('#permission92').prop('checked',true);
            $('#permission93').prop('checked',true);
        }
    }
    function check9_2(){
        if(
            $('#permission97_2').prop('checked') == true &&
            $('#permission98_2').prop('checked') == true 
        ){
            $('#permission97_2').prop('checked',false);
            $('#permission98_2').prop('checked',false);
        }
        else{
            $('#permission97_2').prop('checked',true);
            $('#permission98_2').prop('checked',true);
        }
    }
    $(document).on('click','button[name=changePasse]',function(e){
        e.preventDefault();
        pass = $('#pass');
        is_pass = $('#is_pass');
        isDisplay = pass.prop('style').display;
        if(isDisplay == 'none'){
            pass.prop('style','display : content');
            is_pass.val('yes');
            var btn = $('button[name=updateUser]');
            btn.prop('disabled','yes');
            var password = $('#password');
            var password_confirmation = $('#password_confirmation');
            password.val('');
            password_confirmation.val('');
        }
        else{
            pass.prop('style','display : none');
            is_pass.val('no');
        }
        myFunction();
    })
    $(document).on('keyup','#name',function(){
        myFunction();
    })
    $(document).on('keyup','#password',function(){
        myFunction();
    })
    $(document).on('keyup','#password_confirmation',function(){
        myFunction();
    })
    // $(document).on('click','#is_admin',function(){
    //     myFunction();
    // })
    $(document).on('click','#status',function(){
        myFunction();
    })
    function myFunction() {
        var name = $('#name').val();
        var password = $('#password').val();
        var password_confirmation = $('#password_confirmation').val();
        // var is_admin = $('#is_admin').val();
        var status = $('#status').val();
        var btn = $('button[name=updateUser]');
        var visibility = "{{$visibility}}";
        pass = $('#pass');
        isDisplay = pass.prop('style').display;
        if(visibility){
            if(isDisplay == 'none'){
                if(
                    (!name && name=='') || 
                    (!status && status=='')
                    // (!is_admin && is_admin=='')
                ) {
                    btn.prop('disabled',true);
                }
                else{
                    btn.prop('disabled',false);
                }
            }
            else{
                if(
                    (!name && name=='') || 
                    (!password && password=='') || 
                    (password_confirmation!==password) || 
                    (!status && status=='')
                    // (!is_admin && is_admin=='')
                ) {
                    btn.prop('disabled',true);
                }
                else{
                    btn.prop('disabled',false);
                }
            }
        }   
        else{
            if(isDisplay == 'none'){
                if(
                    (!name && name=='')
                ) {
                    btn.prop('disabled',true);
                }
                else{
                    btn.prop('disabled',false);
                }
            }
            else{
                if(
                    (!name && name=='') || 
                    (!password && password=='') || 
                    (password_confirmation!==password) 
                ) {
                    btn.prop('disabled',true);
                }
                else{
                    btn.prop('disabled',false);
                }
            }
        }
    }
</script>
{{-- ################## --}}
@endsection
