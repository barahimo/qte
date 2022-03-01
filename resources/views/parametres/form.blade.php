@extends('layout.dashboard')
@section('contenu')
{{-- ################## --}}
<!-- Content Header (Page header) -->
<div class="content-header sty-one">
    <h1>Paramètres</h1>
    <ol class="breadcrumb">
        <li><a href="{{route('app.home')}}">Home</a></li>
        <li><i class="fa fa-angle-right"></i> Paramètres</li>
    </ol>
</div>
{{-- ################## --}}
<br>
{{-- ################## --}}
<!-- Main content -->
<div class="content">
    <div class="row m-t-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-blue">
                    <h5 class="text-white m-b-0">Formulaire</h5>
                </div>
                <div class="card-body">
                    <form  method="POST" action="{{$route}}" enctype="multipart/form-data">
                        @csrf 
                        @if($view == 'edit')
                        @method('PUT')
                        @endif
                        <!-- ########################################## -->
                        <div id="form">
                            <div id="form1" data-view="on" style="display : content">
                                <!-- BEGIN Nom/Raison && logo -->
                                <div class="form-row">
                                    <!-- BEGIN Nom/Raison -->
                                    <div class="form-group col-md-6">
                                        <label for="nom">Nom/Raison sociale : :</label>
                                        <div class="input-group">
                                            <input type="text" value="{{old('nom',$company->nom ?? null )}}" id="nom" name="nom" class="form-control" placeholder="Nom/Raison sociale" />
                                        </div>
                                    </div>
                                    <!-- END Nom/Raison -->
                                    <!-- BEGIN logo -->
                                    <div class="form-group col-md-6">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label for="logo">Logo :</label>
                                                <div class="input-group">
                                                    <input type="file" id="logo" name="logo" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                @if($view == 'edit' && $company->logo)
                                                <img src="{{Storage::url($company->logo ?? null)}}"  alt="logo" style="width:80px;height:80px" class="img-fluid border rounded-circle">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END logo -->
                                </div>
                                <!-- END Nom/Raison && logo -->
                                <!-- BEGIN Adresse -->
                                <div class="form-group">
                                    <label for="adresse">Adresse :</label>
                                    <textarea id="adresse" name="adresse" class="form-control" rows="3" cols="3">{{old('adresse',$company->adresse ?? null )}}</textarea>
                                </div>
                                <!-- END Adresse -->
                                <!-- BEGIN code_postal &&  ville -->
                                <div class="form-row">
                                    <!-- BEGIN code_postal -->
                                    <div class="form-group col-md-6">
                                        <label for="code_postal">Code postal :</label>
                                        <div class="input-group">
                                            <input type="text" value="{{old('code_postal',$company->code_postal ?? null )}}"  id="code_postal" name="code_postal"  class="form-control" placeholder="Code postal"/>
                                        </div>
                                    </div>
                                    <!-- END code_postal -->
                                    <!-- BEGIN ville -->
                                    <div class="form-group col-md-6">
                                        <label for="ville">Ville :</label>
                                        <div class="input-group">
                                            <input type="text" value="{{old('ville',$company->ville ?? null )}}"  id="ville" name="ville"  class="form-control" placeholder="Ville"/>
                                        </div>
                                    </div>
                                    <!-- END ville -->
                                </div>
                                <!-- END code_postal &&  ville -->
                                <!-- BEGIN pays &&  tel -->
                                <div class="form-row">
                                    <!-- BEGIN pays -->
                                    <div class="form-group col-md-6">
                                        <label for="pays">Pays :</label>
                                        <div class="input-group">
                                            <input type="text" value="{{old('pays',$company->pays ?? null )}}"  id="pays" name="pays"  class="form-control" placeholder="Pays"/>
                                        </div>
                                    </div>
                                    <!-- END pays -->
                                    <!-- BEGIN tel -->
                                    <div class="form-group col-md-6">
                                        <label for="tel">Téléphone :</label>
                                        <div class="input-group">
                                            <input type="text" value="{{old('tel',$company->tel ?? null )}}"  id="tel" name="tel"  class="form-control" placeholder="Téléphone"/>
                                        </div>
                                    </div>
                                    <!-- END tel -->
                                </div>
                                <!-- END pays &&  tel -->
                                <!-- BEGIN site && email -->
                                <div class="form-row">
                                    <!-- BEGIN site -->
                                    <div class="form-group col-md-6">
                                        <label for="site">Site web :</label>
                                        <div class="input-group">
                                            <input type="text" value="{{old('site',$company->site ?? null )}}"  id="site" name="site"  class="form-control" placeholder="Site web"/>
                                        </div>
                                    </div>
                                    <!-- END site -->
                                    <!-- BEGIN email -->
                                    <div class="form-group col-md-6">
                                        <label for="email">Email :</label>
                                        <div class="input-group">
                                            <input type="text" value="{{old('email',$company->email ?? null )}}" id="email" name="email"  class="form-control" placeholder="Email"/>
                                        </div>
                                    </div>
                                    <!-- END email -->
                                </div>
                                <!-- END site && email -->
                            </div>
                            <div id="form2" data-view="off" style="display : none">
                                <!-- BEGIN IF &&  ice -->
                                <div class="form-row">
                                    <!-- BEGIN IF -->
                                    <div class="form-group col-md-6">
                                        <label for="iff">Identifiant fiscal :</label>
                                        <div class="input-group">
                                            <input type="text" value="{{old('iff',$company->iff ?? null )}}"  id="iff" name="iff"  class="form-control" placeholder="Identifiant fiscal"/>
                                        </div>
                                    </div>
                                    <!-- END IF -->
                                    <!-- BEGIN ice -->
                                    <div class="form-group col-md-6">
                                        <label for="ice">Identifiant Commun (ICE) :</label>
                                        <div class="input-group">
                                            <input type="text" value="{{old('ice',$company->ice ?? null )}}"  id="ice" name="ice"  class="form-control" placeholder="ICE"/>
                                        </div>
                                    </div>
                                    <!-- END ice -->
                                </div>
                                <!-- END IF &&  ice -->
                                <!-- BEGIN capital &&  rc -->
                                <div class="form-row">
                                    <!-- BEGIN capital -->
                                    <div class="form-group col-md-6">
                                        <label for="capital">Capital :</label>
                                        <div class="input-group">
                                            <input type="text" value="{{old('capital',$company->capital ?? null )}}"  id="capital" name="capital"  class="form-control" placeholder="Capital"/>
                                        </div>
                                    </div>
                                    <!-- END capital -->
                                    <!-- BEGIN rc -->
                                    <div class="form-group col-md-6">
                                        <label for="rc">Registre du commerce :</label>
                                        <div class="input-group">
                                            <input type="text" value="{{old('rc',$company->rc ?? null )}}"  id="rc" name="rc"  class="form-control" placeholder="Registre du commerce"/>
                                        </div>
                                    </div>
                                    <!-- END rc -->
                                </div>
                                <!-- END capital &&  rc -->
                                <!-- BEGIN patente &&  cnss -->
                                <div class="form-row">
                                    <!-- BEGIN patente -->
                                    <div class="form-group col-md-6">
                                        <label for="patente">Patente :</label>
                                        <div class="input-group">
                                            <input type="text" value="{{old('patente',$company->patente ?? null )}}"  id="patente" name="patente"  class="form-control" placeholder="Patente"/>
                                        </div>
                                    </div>
                                    <!-- END patente -->
                                    <!-- BEGIN cnss -->
                                    <div class="form-group col-md-6">
                                        <label for="cnss">CNSS :</label>
                                        <div class="input-group">
                                            <input type="text" value="{{old('cnss',$company->cnss ?? null )}}"  id="cnss" name="cnss"  class="form-control" placeholder="CNSS"/>
                                        </div>
                                    </div>
                                    <!-- END cnss -->
                                </div>
                                <!-- END patente &&  cnss -->
                                <!-- BEGIN banque &&  rib -->
                                <div class="form-row">
                                    <!-- BEGIN banque -->
                                    <div class="form-group col-md-6">
                                        <label for="banque">Banque :</label>
                                        <div class="input-group">
                                            <input type="text" value="{{old('banque',$company->banque ?? null )}}"  id="banque" name="banque"  class="form-control" placeholder="Banque"/>
                                        </div>
                                    </div>
                                    <!-- END banque -->
                                    <!-- BEGIN rib -->
                                    <div class="form-group col-md-6">
                                        <label for="rib">Relevé d'Identité Bancaire (RIB) :</label>
                                        <div class="input-group">
                                            <input type="text" value="{{old('rib',$company->rib ?? null )}}"  id="rib" name="rib"  class="form-control" placeholder="RIB"/>
                                        </div>
                                    </div>
                                    <!-- END rib -->
                                </div>
                                <!-- END banque &&  rib -->
                                <!-- BEGIN note -->
                                <div class="form-group">
                                    <label for="note">Note :</label>
                                    <textarea id="note" name="note" class="form-control" rows="3" cols="3" >{{old('note',$company->note ?? null )}}</textarea>
                                </div>
                                <!-- END note -->
                            </div>
                        </div>
                        <!-- ########################################## -->
                        <div class="form-group">
                            <button class="btn btn-info btn-rounded" type="submit">Valider</button>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <button id="btnView" class="btn btn-success btn-rounded" onclick="myform(event)">Suivant</button>
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
@if(session('status'))               
    <script>
        Swal.fire('{{ session('status')}}')
    </script>
@endif

<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click','#image',function(e){
            console.log($(this).val());
            return;
            var _token=$('input[name=_token]'); //Envoi des information via method POST
            $.ajax({
                type:'post',
                url:'{!!URL::to('saveImage')!!}',
                data:{
                    _token : _token.val(),
                    image : $(this).val(),
                },
                success: function(data){
                    console.log(data);
                } ,
                error:function(err){}
            });
        });
    });
    function myform(e){
        e.preventDefault();
        view1 = $('#form1').data('view');
        view2 = $('#form2').data('view');
        if(view1 == 'on' && view2 == 'off'){
            $('#btnView').html('Retour');
            $('#form1').prop('style','display : none');
            $('#form2').prop('style','display : content');
            $('#form1').data('view','off');
            $('#form2').data('view','on');
        }
        else if(view2 == 'on' && view1 == 'off'){
            $('#btnView').html('Suivant');
            $('#form2').prop('style','display : none');
            $('#form1').prop('style','display : content');
            $('#form1').data('view','on');
            $('#form2').data('view','off');
        }
    }
</script>
@endsection