<?php
    use function App\Providers\hasPermssion;
?>
<div class="table-responsive">
  @if(count($commandes)>0)
  <table class="table" id="table">
    <thead class="bg-primary text-white">
      <tr>
        <th>Rèf</th>
        <th>Date</th>
        <th>Client</th>
        <th>Montant total</th>
        <th>Montant payer</th>
        <th>Reste à payer</th>
        <th style="display : none">Status</th>
        <th style="display : none">Facture</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($commandes as $key=>$commande)
      <?php
        $index = $commandes->firstItem()+$key;
        // -------------------------------------------------------------------- //
        $url_edit = route('commande.edit',['commande'=>$commande->id]);
        $url_delete1 = route('commande.destroy',['commande'=>$commande->id]);
        $url_show1 = route('commande.show',['commande'=>$commande->id]);
        $url_reg = route('reglement.create3',['commande'=>$commande->id]);
        $url_fac = route('commande.facture',['commande'=>$commande->id]);
        // -------------------------------------------------------------------- //
        $facture = "NF";
        if($commande->facture == "f")
          $facture = "F";
        // -------------------------------------------------------------------- //
        $status = "R";
        if($commande->reste > 0)
          $status = "NR";
        // -------------------------------------------------------------------- //
      ?>
      <tr>
        <td>{{$commande->code}}</td>
        <td>{{$commande->date}}</td>
        <td>{{$commande->client->nom_client}}</td>
        <td>{{number_format($commande->total,2, '.', '')}}</td>
        <td>{{number_format($commande->avance,2, '.', '')}}</td>
        <td>{{number_format($commande->reste,2, '.', '')}}</td>
        <td id="viewStatus{{$index}}" style="display : none">{{$status}}</td>
        <td id="viewFacture{{$index}}" style="display : none">{{$facture}}</td>
        <td>
          @if(in_array('details4',$permission) || Auth::user()->is_admin == 2)
          <i class="fas fa-eye fa-1x"
            id="btnActions{{$index}}"
            data-index="{{$index}}" 
            data-status="false" 
            onclick="handleActions(event)"
            style="cursor: pointer;"
          ></i>
          @endif
        </td>
      </tr>
      <tr id="viewActions{{$index}}" style="display : none;">
        <td colspan="8" class="border border-dark shadow p-3 mb-5 bg-light rounded">
            <!---------------------------- BEGIN Action ---------------------------->
              <div class="row">
                <div class="col-8">
                  <span>[{{$commande->code}}]</span>
                  @if(in_array('create6',$permission) || Auth::user()->is_admin == 2)
                  <button id="btnFacture{{$index}}" 
                    class="btn btn-link" 
                    onclick="window.location.assign('{{$url_fac}}')">
                      <i class="fa fa-plus-square"></i>&nbsp;Facture&nbsp;<i class="fas fa-receipt"></i>
                  </button>
                  @endif
                  @if(in_array('create5',$permission) || Auth::user()->is_admin == 2)
                    <button id="btnStatus{{$index}}" 
                      class="btn btn-link" 
                      onclick="window.location.assign('{{$url_reg}}')">
                        <i class="fa fa-plus-square"></i>&nbsp;Règlement&nbsp;<i class="fas fa-hand-holding-usd"></i>
                    </button>
                  @endif
                  @if(in_array('show4',$permission) || Auth::user()->is_admin == 2)
                  <a class="btn btn-outline-info btn-sm" href="{{$url_show1}}"><i class="fas fa-print"></i></a>
                  @endif
                  @if(in_array('edit4',$permission) || Auth::user()->is_admin == 2)
                    <a class="btn btn-outline-success btn-sm" 
                      id="btnEdit{{$index+1}}" 
                      href={{$url_edit}}>
                        <i class="fas fa-edit"></i>
                    </a>
                  @endif
                  @if(in_array('delete4',$permission) || Auth::user()->is_admin == 2)
                  <a 
                    class="btn btn-outline-danger btn-sm" 
                    id="btnDelete1{{$index+1}}" 
                    data-id="{{$commande->id}}" 
                    data-route="{{$url_delete1}}" 
                    href="javascript:deleteCommande({{$index+1}})">
                      <i class="fas fa-trash-alt fa-0.5px"></i>
                  </a>
                  @endif
                </div>
                <div class="col-4 text-right">
                    @if(in_array('details5',$permission) || Auth::user()->is_admin == 2)
                      <button class="btn btn-outline-success btn-sm"
                        id="btnDetails{{$index}}"
                        data-index="{{$index}}" 
                        data-status="false" 
                        onclick="handleEvent('{{$index}}')"
                      >
                        <i class="fas fa-angle-down"></i><span>&nbsp;Liste des règlements</span>
                      </button>
                    @endif
                </div>
              </div>
            <!---------------------------- END Action ---------------------------->
            <hr>
            <div id="viewDetails{{$index}}" style="display : none;">
              <table class="table">
                <thead class="thead-light">
                  <tr>
                    <th>Rèf</th>
                    <th style="display : none">id</th>
                    <th style="display : none">commande_id</th>
                    <th style="display : none">Nom client</th>
                    <th>Date</th>
                    <th>Montant payer</th>
                    <th>Reste</th>
                    <th>Mode règlement</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($commande->reglements as $i => $reglement)
                      <?php
                        $style = "";
                        $btnAvoir = $reglement->status;
                        ($reglement->reste > 0) ? $style = "color : red" : $style = "color : green";
                        if($reglement->status == 'AV'){
                          $id= "avoir".$reglement->id; 
                          $reg_id= $reglement->id; 
                          $reg_avance = number_format($reglement->avance,2, '.', '') ;
                          $reg_reste = number_format($reglement->reste,2, '.', '');
                          $cmd_id = $commande->id;
                          $cmd_avance = number_format($commande->avance,2, '.', '');
                          $cmd_reste = number_format($commande->reste,2, '.', '');
                          $obj = [
                            'reg_id'=> $reg_id, 
                            'reg_avance'=> $reg_avance, 
                            'reg_reste'=> $reg_reste,
                            'cmd_id'=> $cmd_id, 
                            'cmd_avance'=> $cmd_avance, 
                            'cmd_reste'=> $cmd_reste
                          ];
                          $json = json_encode($obj);
                          $btnAvoir = "<button  
                              id=$id  
                              onclick='avoir($json)'
                              class='btn btn-outline-success btn-sm'>Avoir</button>";
                        }
                        $url_show2 = route('reglement.show',['reglement'=>$reglement->id]);
                        $index_1 = $index+1;
                        $i_1 = $i +1;
                      ?>
                      @if($reglement->avance != 0)
                          <tr style="<?php echo $style; ?>">
                            <td>{{$reglement->code}}</td>
                            <td style='display : none'>{{$reglement->id}}</td>
                            <td style='display : none'>{{$reglement->commande_id}}</td>
                            <td style='display : none'>{{$commande->client->nom_client}}</td>
                            <td>{{$reglement->date}}</td>
                            <td>{{number_format($reglement->avance,2, '.', '')}}</td>
                            <td id='reste$i'>{{number_format($reglement->reste,2, '.', '')}}</td>
                            <td>{{$reglement->mode_reglement}}</td>
                            <td class='text-center'>{!!$btnAvoir!!}</td>
                            <td class='text-center'>
                              @if(in_array('show5',$permission) || Auth::user()->is_admin == 2)
                                <a class="btn btn-outline-info  btn-sm" href="{{$url_show2}}"><i class="fas fa-print"></i></a>
                              @endif
                              @if(in_array('delete5',$permission) || Auth::user()->is_admin == 2)
                                <button 
                                  class='btn btn-outline-danger btn-sm' 
                                  id='btnDelete2{{$index_1}}{{$i_1}}' 
                                  data-id='{{$reglement->id}}' 
                                  data-route="{{route('reglement.delete',['reglement'=>$reglement->id])}}" 
                                  onclick="javascript:deleteReglement('{{$index_1}}{{$i_1}}')">
                                  <i class='fas fa-trash-alt fa-0.5px'></i>
                                </button>
                              @endif
                            </td>
                          </tr>
                      @endif
                    @endforeach
                </tbody>
              </table>
            </div>
        </td>  
      </tr>
      @endforeach
    </tbody>
  </table>
  {{$commandes->links()}}
  @else
  <table class="table">
    <thead class="bg-primary text-white">
      <tr>
        <th>Rèf</th>
        <th>Date</th>
        <th>Client</th>
        <th>Montant total</th>
        <th>Montant payer</th>
        <th>Reste à payer</th>
        <th style="display : none">Status</th>
        <th style="display : none">Facture</th>
        <th>Actions</th>
      </tr>
    </thead>
  </table>
  @endif
</div>