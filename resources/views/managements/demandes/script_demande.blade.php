<script type="text/javascript">
  $(document).ready(function(){
    $(document).on('click','.pagination a',function(event)
    {
      event.preventDefault();
      var page = $(this).attr('href').split('page=')[1];
      fetch_demande(page,fetch().facture,fetch().status,fetch().fournisseur,fetch().search,fetch().from,fetch().to);
    });

    function fetch_demande(page,facture,status,fournisseur,search,from,to)
    {
        $.ajax({
            type:'GET',
            url:"{{route('demande.fetch_demande')}}" + "?page=" + page+ "&facture=" + facture + "&status=" + status + "&fournisseur=" + fournisseur + "&search=" + search+ "&from=" + from+ "&to=" + to,
            success:function(data){
                $('#demandes_data').html(data);
                actionButton();
            },
            error:function(){
                console.log([]);    
            }
        });
    }

    function fetch()
    {
        var date1 = $('#date1').val();
        var date2 = $('#date2').val();
        // -------------------------------------------------- //
        var f=$('#f');
        var nf=$('#nf');
        var r=$('#r');
        var nr=$('#nr');
        var facture = '';
        var status = '';
        var fournisseur = '';
        var search = '';
        // -----------------------------------
        if($('#search').val() != ""){
          search = $('#search').val();
        }
        // -----------------------------------
        if($('#fournisseur').val()){
          fournisseur = $('#fournisseur').val(); 
          var create  = $('#create');
          var text = $("#fournisseur option:selected" ).text();
          var msg = `<i class="fa fa-plus"></i>&nbsp;Règlements [ ${text} ]` ;
          create.html(msg);
          var url = "{{route('payement.create2',['fournisseur'=>'val'])}}";
          url = url.replace('val', fournisseur);
          create.attr('href',url);
        }
        else {
          var create  = $('#create');
          var text = $("#fournisseur option:selected" ).text();
          var msg = `<i class="fa fa-plus"></i>&nbsp;Règlements` ;
          create.html(msg);
          var url = "{{route('payement.create2')}}";
          create.attr('href',url);
        }
        // -----------------------------------
        if(r.prop("checked") && nr.prop("checked"))
          status = 'all';
        else if(r.prop("checked") && !nr.prop("checked"))
          status = 'r';
        else if(!r.prop("checked") && nr.prop("checked"))
          status = 'nr';
        // -----------------------------------
        if(f.prop("checked") && nf.prop("checked"))
          facture = 'all';
        else if(f.prop("checked") && !nf.prop("checked"))
          facture = 'f';
        else if(!f.prop("checked") && nf.prop("checked"))
          facture = 'nf';
        // -------------------------------------------------- //
        return {
          'facture':facture,
          'status':status,
          'fournisseur':fournisseur,
          'search':search,
          'from':date1,
          'to':date2,
        };
    }
    // search();
    // -----------Change Select_Facturée--------------//
    $(document).on('change','#f',function(){
      // $('#search').val('');
      // search();
      fetch_demande(1,fetch().facture,fetch().status,fetch().fournisseur,fetch().search,fetch().from,fetch().to);
    });
    // -----------End Select_Facturée--------------//
    // -----------Change Select_NonFacturée--------------//
    $(document).on('change','#nf',function(){
      // $('#search').val('');
      // search();
      fetch_demande(1,fetch().facture,fetch().status,fetch().fournisseur,fetch().search,fetch().from,fetch().to);
    });
    // -----------End Select_NonFacturée--------------//
    // -----------Change Select_Reglée--------------//
    $(document).on('change','#r',function(){
      // $('#search').val('');
      // search();
      fetch_demande(1,fetch().facture,fetch().status,fetch().fournisseur,fetch().search,fetch().from,fetch().to);
    });
    // -----------End Select_Reglée--------------//
    // -----------Change Select_NonReglée--------------//
    $(document).on('change','#nr',function(){
      // $('#search').val('');
      // search();
      fetch_demande(1,fetch().facture,fetch().status,fetch().fournisseur,fetch().search,fetch().from,fetch().to);
    });
    // -----------End Select_NonReglée--------------//
    // -----------Change Select_Fournisseur--------------//
    $(document).on('change','#fournisseur',function(){
      // $('#search').val('');
      // search();
      fetch_demande(1,fetch().facture,fetch().status,fetch().fournisseur,fetch().search,fetch().from,fetch().to);
    });
    // -----------End Select_Fournisseur--------------//
    // -----------KeyUp searchDemandes--------------//
    $(document).on('keyup','#search',function(){
      // search();
      fetch_demande(1,fetch().facture,fetch().status,fetch().fournisseur,fetch().search,fetch().from,fetch().to);
    });
    $(document).on('change','#date1',function(){
      fetch_demande(1,fetch().facture,fetch().status,fetch().fournisseur,fetch().search,fetch().from,fetch().to);
    });
    $(document).on('change','#date2',function(){
      fetch_demande(1,fetch().facture,fetch().status,fetch().fournisseur,fetch().search,fetch().from,fetch().to);
    });
    // -----------KeyUp searchDemandes--------------//
  });
  // -----------My function--------------//
  function searchSelect(param1,param2,param3,param4){
    var table=$('#table');
    $.ajax({
      type:'get',
      url:"{!!Route('demande.getDemandes5')!!}",
      data:{'fournisseur':param1,'facture':param2,'status':param3,'search':param4},
      success:function(data){
        var lignes = '';
        // ------------------ //
        table.find('tbody').html("");
        var details = ''; 
        // ------------------ //
        var demandes = data;
        demandes.forEach((demande,index) => {
          // ************************ //
          var url_edit = "{{route('demande.edit',['demande'=>':id'])}}".replace(':id', demande.id);
          var url_delete1 = "{{route('demande.destroy',['demande'=>':demande'])}}".replace(':demande', demande.id);
          var url_show1 = "{{route('demande.show',['demande'=>':id'])}}".replace(':id', demande.id);
          var url_reg = "{{route('payement.create3',['demande'=>'demande_id'])}}".replace('demande_id', demande.id);
          // var url_fac = "{{route('demande.facture',['demande'=>"demande_id"])}}".replace('demande_id', demande.id);
          // var facture = "NF";
          // if(demande.facture == "f")
            // facture = "F";
          var status = "R";
          if(demande.reste > 0)
            status = "NR";
          // ************************ //
          details = ''; 
            // ------ begin payements ------
            demande.payements.forEach((payement,i) => {
              var style = "";
              var btnAvoir = payement.status;
              (payement.reste > 0) ? style = "color : red" : style = "color : green";
              if(payement.status == 'AV'){
                btnAvoir = `<button  
                    onclick="avoir({
                      reg_id: ${payement.id}, 
                      reg_avance: ${parseFloat(payement.avance).toFixed(2)}, 
                      reg_reste: ${parseFloat(payement.reste).toFixed(2)},
                      cmd_id: ${demande.id}, 
                      cmd_avance: ${parseFloat(demande.avance).toFixed(2)}, 
                      cmd_reste: ${parseFloat(demande.reste).toFixed(2)}
                    })"
                    class="btn btn-outline-success btn-sm">Avoir</button>`;
              }
              var url_show2 = "{{route('payement.show',['payement'=>':id'])}}".replace(':id', payement.id);
              var url_delete2 = "{{route('payement.delete',['payement'=>':payement'])}}".replace(':payement', payement.id);
              // var btnDelete2 = `<a class="btn btn-outline-danger  btn-sm" href=${url_delete2}><i class="fas fa-trash"></i></a>`;
              var btnDelete2 = `<button 
                  class="btn btn-outline-danger btn-sm" 
                  id="btnDelete2${index+1}${i+1}" 
                  data-id="${payement.id}" 
                  data-route="${url_delete2}" 
                  onclick="javascript:deletePayement(${index+1}${i+1})">
                    <i class="fas fa-trash-alt fa-0.5px"></i>
                </button>`;
              if(payement.avance != 0) 
                details+=`<tr style="${style}">
                      <td>${payement.code}</td>
                      <td style="display : none">${payement.id}</td>
                      <td style="display : none">${payement.demande_id}</td>
                      <td style="display : none">${demande.fournisseur.nom_fournisseur}</td>
                      <td>${payement.date}</td>
                      <td>${parseFloat(payement.avance).toFixed(2)}</td>
                      <td id="reste${i}">${parseFloat(payement.reste).toFixed(2)}</td>
                      <td>${payement.mode_payement}</td>
                      <td class="text-center">${btnAvoir}</td>
                      <td class="text-center">
                        @if(in_array('delete5',$permission) || Auth::user()->is_admin == 2)
                        ${btnDelete2}
                        @endif
                      </td>
                    </tr>`;
            }) ;
            // ------ end payements ------
          // ************************ //
          // <button id="btnDelete1${index}" class="btn btn-outline-danger btn-sm" onclick="window.location.assign('${url_delete1}')"><i class="fas fa-trash-alt fa-0.5px"></i></button>
          actions = `
            <div class="row">
              <div class="col-8">
                <span>[${demande.code}]</span>
                @if(in_array('create5',$permission) || Auth::user()->is_admin == 2)
                <button id="btnStatus${index}" class="btn btn-link" onclick="window.location.assign('${url_reg}')"><i class="fa fa-plus-square"></i>&nbsp;Règlement&nbsp;<i class="fas fa-hand-holding-usd"></i></button>
                @endif
                @if(in_array('edit4',$permission) || Auth::user()->is_admin == 2)
                <a class="btn btn-outline-success btn-sm" id="btnEdit${index+1}" href=${url_edit}><i class="fas fa-edit"></i></a>
                @endif
                @if(in_array('delete4',$permission) || Auth::user()->is_admin == 2)
                <a 
                  class="btn btn-outline-danger btn-sm" 
                  id="btnDelete1${index+1}" 
                  data-id="${demande.id}" 
                  data-route="${url_delete1}" 
                  href="javascript:deleteDemande(${index+1})">
                    <i class="fas fa-trash-alt fa-0.5px"></i>
                </a>
                @endif
              </div>
              <div class="col-4 text-right">
                  @if(in_array('details5',$permission) || Auth::user()->is_admin == 2)
                  <button class="btn btn-outline-success btn-sm"
                    id="btnDetails${index}"
                    data-index="${index}" 
                    data-status="false" 
                    onclick="handleEvent(${index})">
                    <i class="fas fa-angle-down"></i><span>&nbsp;Liste des règlements</span>
                  </button>
                  @endif
              </div>
            </div>
          `;
              // <td id="viewFacture${index}" style="display : none">${facture}</td>
          facture = '';
          if(demande.facture)
            facture = demande.facture;
          else if(!demande.facture || Object.is(demande.facture, ''))
            facture = '';
          lignes += `
            <tr>
              <td>${demande.code}</td>
              <td>${demande.date}</td>
              <td>${demande.fournisseur.nom_fournisseur}</td>
              <td>${facture}</td>
              <td>${parseFloat(demande.total).toFixed(2)}</td>
              <td>${parseFloat(demande.avance).toFixed(2)}</td>
              <td>${parseFloat(demande.reste).toFixed(2)}</td>
              <td id="viewStatus${index}" style="display : none">${status}</td>
              <td>
                @if(in_array('details4',$permission) || Auth::user()->is_admin == 2)
                <i class="fas fa-eye fa-1x"
                  id="btnActions${index}"
                  data-index="${index}" 
                  data-status="false" 
                  onclick="handleActions(event)"
                  style="cursor: pointer;"
                ></i>
                @endif
              </td>
            </tr>
            <tr id="viewActions${index}" style="display : none;">
              <td colspan="8" class="border border-dark shadow p-3 mb-5 bg-light rounded">
                  ${actions}
                  <hr>
                  <div id="viewDetails${index}" style="display : none;">
                    <table class="table">
                      <thead class="thead-light">
                        <tr>
                          <th>#</th>
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
                      <tbody>${details}</tbody>
                    </table>
                  </div>
              </td>  
            </tr>`;
        });
        table.find('tbody').html("");
        table.find('tbody').append(lignes);
        actionButton();    
      },
      error:function(){
        console.log([]);    
      }
    });
  }
  function search(){
    var f=$('#f');
    var nf=$('#nf');
    var r=$('#r');
    var nr=$('#nr');
    var facture = null;
    var status = null;
    var fournisseur = null;
    var search = null;
    // -----------------------------------
    if($('#search').val() != ""){
      search = $('#search').val();
    }
    // -----------------------------------
    if($('#fournisseur').val()){
      fournisseur = $('#fournisseur').val(); 
      // fournisseurHTML = $('#fournisseur').html(); 
      var create  = $('#create');
      var text = $("#fournisseur option:selected" ).text();
      var msg = `<i class="fa fa-plus"></i>&nbsp;Règlements [ ${text} ]` ;
      create.html(msg);
      var url = "{{route('payement.create2',['fournisseur'=>'val'])}}";
      url = url.replace('val', fournisseur);
      create.attr('href',url);
    }
    else {
      var create  = $('#create');
      var text = $("#fournisseur option:selected" ).text();
      var msg = `<i class="fa fa-plus"></i>&nbsp;Règlements` ;
      create.html(msg);
      var url = "{{route('payement.create2')}}";
      create.attr('href',url);
    }
    // -----------------------------------
    if(r.prop("checked") && nr.prop("checked"))
      status = 'all';
    else if(r.prop("checked") && !nr.prop("checked"))
      status = 'r';
    else if(!r.prop("checked") && nr.prop("checked"))
      status = 'nr';
    // -----------------------------------
    if(f.prop("checked") && nf.prop("checked"))
      facture = 'all';
    else if(f.prop("checked") && !nf.prop("checked"))
      facture = 'f';
    else if(!f.prop("checked") && nf.prop("checked"))
      facture = 'nf';
    // -----------------------------------
    // searchSelect(fournisseur,facture,status);
    // searchSelect(fournisseur,facture,status,search);
  }

  function actionButton(){
    table = $('#table');
    list = table.find('tbody').find('tr');
    for (let index = 0; index < list.length; index++) {
      // var item = list.eq(index).find('td');
      // var status = item.eq(6);
      // var facture = item.eq(7);
      var status = $('#viewStatus'+index);
      // var facture = $('#viewFacture'+index);
      // var action = item.eq(8).find('button');
      // var btnFacture = action.eq(0);
      // var btnStatus = action.eq(1);
      var btnStatus = $('#btnStatus'+index);
      var btnEdit = $('#btnEdit'+(index+1));
      var btnDelete1 = $('#btnDelete1'+(index+1));
  
      (status.html() == 'R') ? btnStatus.attr('disabled',true): btnStatus.attr('disabled',false);
      // #################################################### //
      listAction = $('#viewActions'+index).find('table').find('tbody').find('tr');
      var existe = false;
      for (let indexAction = 0; indexAction < listAction.length; indexAction++) {
        var code = listAction.eq(indexAction).find('td').eq(0).html();
        if(code.substring(0, 3) === 'AVF'){
          existe = true;
          break;
        }
      }
      if(existe) {
        btnEdit.attr('style',"color : grey;cursor: default !important;pointer-events: none;");
        btnEdit.attr('onclick',"return false;");
      }  
      else{
        btnEdit.attr('style',"");
        btnEdit.attr('onclick',"");
      }
      for (let indexAction = 0; indexAction < listAction.length; indexAction++) {
        var code = listAction.eq(indexAction).find('td').eq(0).html();
        var btnDelete2 = $('#btnDelete2'+(index+1)+(indexAction+1));
        if(code.substring(0, 3) !== 'AVF'){
          if(existe) {
            btnDelete2.attr('style',"color : grey;cursor: default !important;pointer-events: none;");
            btnDelete2.attr('onclick',"return false;");
          }  
          else{
            btnDelete2.attr('style',"");
            btnDelete2.attr('onclick',"javascript:deletePayement("+(index+1)+(indexAction+1)+")");
          }
        }
      }
      // #################################################### //
    }
  }
  function actionButton_fordelete(){
    table = $('#table');
    list = table.find('tbody').find('tr');
    for (let index = 0; index < list.length; index++) {
      // var item = list.eq(index).find('td');
      // var status = item.eq(6);
      // var facture = item.eq(7);
      var status = $('#viewStatus'+index);
      // var facture = $('#viewFacture'+index);
      // var action = item.eq(8).find('button');
      // var btnFacture = action.eq(0);
      // var btnStatus = action.eq(1);
      // var btnFacture = $('#btnFacture'+index);
      var btnStatus = $('#btnStatus'+index);
      var btnEdit = $('#btnEdit'+(index+1));
      var btnDelete1 = $('#btnDelete1'+(index+1));
      // if(facture.html() == 'F'){
      //   btnFacture.attr('disabled',true);
      //   btnEdit.attr('style',"color : grey;cursor: default !important;pointer-events: none;");
      //   btnEdit.attr('onclick',"return false;");

      //   btnDelete1.attr('style',"color : grey;cursor: default !important;pointer-events: none;");
      //   btnDelete1.attr('onclick',"return false;");
      // }
      // else{
      //   btnFacture.attr('disabled',false);
      //   btnEdit.attr('style',"");
      //   btnEdit.attr('onclick',"");

      //   btnDelete1.attr('style',"");
      //   btnDelete1.attr('onclick',"");
      // }
      (status.html() == 'R') ? btnStatus.attr('disabled',true): btnStatus.attr('disabled',false);
    }
  }
  // ------------------------------------ //
  function handleEvent(index){
    btnDetails = $('#btnDetails'+index);
    var item = 'btnDetails'+index;
    var status = btnDetails.data('status');
    if(status == 'true'){
      btnDetails.data('status','false');
      btnDetails.parent().parent().parent().find('#viewDetails'+index).prop('style','display: none;');
      btnDetails.find('i').prop('class','fas fa-angle-down');
    }
    else{
      btnDetails.data('status','true');
      btnDetails.parent().parent().parent().find('#viewDetails'+index).prop('style','display: contents;');
      btnDetails.find('i').prop('class','fas fa-angle-up');
    }
  }
  function handleActions(e){
    var item = e.target.getAttribute('id');
    var index = e.target.getAttribute('data-index');
    var status = e.target.getAttribute('data-status');
    if(status == 'true'){
      e.target.setAttribute('data-status','false');
      $('#'+item).parent().parent().parent().find('#viewActions'+index).prop('style','display: none;');
      $('#'+item).prop('class','fas fa-eye');
    }
    else{
      e.target.setAttribute('data-status','true');
      $('#'+item).parent().parent().parent().find('#viewActions'+index).prop('style','display: contents;');
      $('#'+item).prop('class','fas fa-eye-slash');
    }
  }
  function avoir(obj){
    var avoir = $('#avoir'+obj['reg_id']);
    avoir.prop('disabled',true);
    $('#loading').prop('style','display : block');
    $.ajax({
        type:'post',
        url:"{!!Route('payement.avoir')!!}",
        data:{
          _token : $('input[name=_token]').val(),
          obj : obj,
        },
        success: function(data){
          // Swal.fire(data.message);
          message(data.status,'',data.message);
          // search();
          if(data.status == "success"){
            setTimeout(() => {
              window.location.assign("{{route('demande.index')}}")
            }, 2000);
          }
          else{
            avoir.prop('disabled',false);
            $('#loading').prop('style','display : none');
          }
        },
        error:function(err){
          // (err.status === 500) ? Swal.fire(err.statusText):Swal.fire("Erreur !!!") ;
          (err.status === 500) ? message('error','',err.statusText):message('error','',"Erreur !!!") ;
        },
      });
  }
  function deleteDemande(index){
    var btnDelete = $('#btnDelete1'+index);
    var action = btnDelete.data('route');
    Swal.fire({
      title: "Une demande est sur le point d'être détruite",
      text: "Est-ce que vous êtes d'accord ?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Annuler',
      confirmButtonText: 'Oui, supprimez-le!'
    }).then((result) => {
      // begin destroy
      if (result.isConfirmed) {
        // var action = current_object.attr('data-action');
        var token = jQuery('meta[name="csrf-token"]').attr('content');
        // var id = current_object.attr('data-id');
        // $('body').find('.remove-form').append('<input name="id" type="hidden" value="'+id+'">');
        $('body').html("<form class='form-inline remove-form' method='post' action='"+action+"'></form>");
        $('body').find('.remove-form').append('<input name="_method" type="hidden" value="delete">');
        $('body').find('.remove-form').append('<input name="_token" type="hidden" value="'+token+'">');
        $('body').find('.remove-form').submit();
      }
      //end destroy
    })
    // end swal2
  }
  function deletePayement(index){
    var btnDelete = $('#btnDelete2'+index);
    var action = btnDelete.data('route');
    var id = btnDelete.data('id');
    Swal.fire({
      title: "Un règlement est sur le point d'être détruite",
      text: "Est-ce que vous êtes d'accord ?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Annuler',
      confirmButtonText: 'Oui, supprimez-le!'
    }).then((result) => {
      // begin destroy
      if (result.isConfirmed) {
        // var action = current_object.attr('data-action');
        var token = jQuery('meta[name="csrf-token"]').attr('content');
        // var id = current_object.attr('data-id');
        // $('body').find('.remove-form').append('<input name="id" type="hidden" value="'+id+'">');
        $('body').html("<form class='form-inline remove-form' method='post' action='"+action+"'></form>");
        $('body').find('.remove-form').append('<input name="_method" type="hidden" value="delete">');
        $('body').find('.remove-form').append('<input name="_token" type="hidden" value="'+token+'">');
        $('body').find('.remove-form').submit();
      }
      //end destroy
    })
    // end swal2
  }
  function message(icon,title,text){
    Swal.fire({
      title: title,
      text: text,
      icon: icon,
      showCancelButton: false,
      showConfirmButton : true,
      confirmButtonColor: '#007BFF',
    });
  }
</script>