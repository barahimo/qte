<?php
    use function App\Providers\hasPermssion;
?>
<div class="table-responsive">
  @if(count($demandes)>0)
  <table class="table" id="table">
    <thead class="bg-primary text-white">
      <tr>
        <th>Rèf</th>
        <th>Date</th>
        <th>Fournisseur</th>
        <th>Facture</th>
        <th>Montant total</th>
        <th>Montant payer</th>
        <th>Reste à payer</th>
        <th style="display : none">Status</th>
        <th style="display : none">Facture</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($demandes as $key=>$demande)
      <?php
        $index = $demandes->firstItem()+$key;
        // -------------------------------------------------------------------- //
        $facture = '';
        if($demande->facture)
          $facture = $demande->facture;
        else if(!$demande->facture || $demande->facture =  '')
          $facture = '';
        // -------------------------------------------------------------------- //
        $url_edit = route('demande.edit',['demande'=>$demande->id]);
        $url_delete1 = route('demande.destroy',['demande'=>$demande->id]);
        $url_show1 = route('demande.show',['demande'=>$demande->id]);
        $url_reg = route('payement.create3',['demande'=>$demande->id]);
        // -------------------------------------------------------------------- //
        $status = "R";
        if($demande->reste > 0)
          $status = "NR";
        // -------------------------------------------------------------------- //
      ?>
      <tr>
        <td>{{$demande->code}}</td>
        <td>{{$demande->date}}</td>
        <td>{{$demande->fournisseur->nom_fournisseur}}</td>
        <td>{{$facture}}</td>
        <td>{{number_format($demande->total,2, '.', '')}}</td>
        <td>{{number_format($demande->avance,2, '.', '')}}</td>
        <td>{{number_format($demande->reste,2, '.', '')}}</td>
        <td id="viewStatus{{$index}}" style="display : none">{{$status}}</td>
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
                  <span>[{{$demande->code}}]</span>
                  @if(in_array('create5',$permission) || Auth::user()->is_admin == 2)
                    <button id="btnStatus{{$index}}" 
                      class="btn btn-link" 
                      onclick="window.location.assign('{{$url_reg}}')">
                        <i class="fa fa-plus-square"></i>&nbsp;Règlement&nbsp;<i class="fas fa-hand-holding-usd"></i>
                    </button>
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
                    data-id="{{$demande->id}}" 
                    data-route="{{$url_delete1}}" 
                    href="javascript:deleteDemande({{$index+1}})">
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
                    <th style="display : none">Demande_id</th>
                    <th style="display : none">Nom fournisseur</th>
                    <th>Date</th>
                    <th>Montant payer</th>
                    <th>Reste</th>
                    <th>Mode règlement</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($demande->payements as $i => $payement)
                      <?php
                        $style = "";
                        $btnAvoir = $payement->status;
                        ($payement->reste > 0) ? $style = "color : red" : $style = "color : green";
                        if($payement->status == 'AV'){                        
                          $reg_id= $payement->id; 
                          $reg_avance = number_format($payement->avance,2, '.', '') ;
                          $reg_reste = number_format($payement->reste,2, '.', '');
                          $cmd_id = $demande->id;
                          $cmd_avance = number_format($demande->avance,2, '.', '');
                          $cmd_reste = number_format($demande->reste,2, '.', '');
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
                              onclick='avoir($json)'
                              class='btn btn-outline-success btn-sm'>Avoir</button>";
                        }
                        $url_show2 = "{{route('payement.show',['payement'=>$payement->id])}}";
                        $index_1 = $index+1;
                        $i_1 = $i +1;
                      ?>
                      @if($payement->avance != 0)
                          <tr style="<?php echo $style; ?>">
                            <td>{{$payement->code}}</td>
                            <td style='display : none'>{{$payement->id}}</td>
                            <td style='display : none'>{{$payement->demande_id}}</td>
                            <td style='display : none'>{{$demande->fournisseur->nom_fournisseur}}</td>
                            <td>{{$payement->date}}</td>
                            <td>{{number_format($payement->avance,2, '.', '')}}</td>
                            <td id='reste$i'>{{number_format($payement->reste,2, '.', '')}}</td>
                            <td>{{$payement->mode_payement}}</td>
                            <td class='text-center'>{!!$btnAvoir!!}</td>
                            <td class='text-center'>
                              @if(in_array('delete5',$permission) || Auth::user()->is_admin == 2)
                                <button 
                                  class='btn btn-outline-danger btn-sm' 
                                  id='btnDelete2{{$index_1}}{{$i_1}}' 
                                  data-id='{{$payement->id}}' 
                                  data-route="{{route('payement.delete',['payement'=>$payement->id])}}" 
                                  onclick="javascript:deletePayement('{{$index_1}}{{$i_1}}')">
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
  {{$demandes->links()}}
  @else
  <table class="table">
    <thead class="bg-primary text-white">
      <tr>
        <th>Rèf</th>
        <th>Date</th>
        <th>Fournisseur</th>
        <th>Facture</th>
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