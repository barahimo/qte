<script type="text/javascript">
  $(document).ready(function(){
    $(document).on('click','.pagination a',function(event)
    {
      event.preventDefault();
      var page = $(this).attr('href').split('page=')[1];
      fetch_commande(page,fetch().facture,fetch().status,fetch().client,fetch().search,fetch().from,fetch().to);
    });

    function fetch_commande(page,facture,status,client,search,from,to)
    {
        $.ajax({
            type:'GET',
            url:"{{route('commande.fetch_commande')}}" + "?page=" + page+ "&facture=" + facture + "&status=" + status + "&client=" + client + "&search=" + search+ "&from=" + from+ "&to=" + to,
            success:function(data){
                $('#commandes_data').html(data);
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
        var client = '';
        var search = '';
        // -----------------------------------
        if($('#search').val() != ""){
          search = $('#search').val();
        }
        // -----------------------------------
        if($('#client').val()){
          client = $('#client').val(); 
          // clientHTML = $('#client').html(); 
          var create  = $('#create');
          var text = $("#client option:selected" ).text();
          var msg = `<i class="fa fa-plus"></i>&nbsp;Règlements [ ${text} ]` ;
          create.html(msg);
          var url = "{{route('reglement.create2',['client'=>'val'])}}";
          url = url.replace('val', client);
          create.attr('href',url);
        }
        else{
          var create  = $('#create');
          var msg = `<i class="fa fa-plus"></i>&nbsp;Règlements` ;
          create.html(msg);
          var url = "{{route('reglement.create2')}}";
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
        return {
          'facture':facture,
          'status':status,
          'client':client,
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
      fetch_commande(1,fetch().facture,fetch().status,fetch().client,fetch().search,fetch().from,fetch().to);
    });
    // -----------End Select_Facturée--------------//
    // -----------Change Select_NonFacturée--------------//
    $(document).on('change','#nf',function(){
      // $('#search').val('');
      // search();
      fetch_commande(1,fetch().facture,fetch().status,fetch().client,fetch().search,fetch().from,fetch().to);
    });
    // -----------End Select_NonFacturée--------------//
    // -----------Change Select_Reglée--------------//
    $(document).on('change','#r',function(){
      // $('#search').val('');
      // search();
      fetch_commande(1,fetch().facture,fetch().status,fetch().client,fetch().search,fetch().from,fetch().to);
    });
    // -----------End Select_Reglée--------------//
    // -----------Change Select_NonReglée--------------//
    $(document).on('change','#nr',function(){
      // $('#search').val('');
      // search();
      fetch_commande(1,fetch().facture,fetch().status,fetch().client,fetch().search,fetch().from,fetch().to);
    });
    // -----------End Select_NonReglée--------------//
    // -----------Change Select_Client--------------//
    $(document).on('change','#client',function(){
      // $('#search').val('');
      // search();
      fetch_commande(1,fetch().facture,fetch().status,fetch().client,fetch().search,fetch().from,fetch().to);
    });
    // -----------End Select_Client--------------//
    // -----------KeyUp searchCommandes--------------//
    $(document).on('keyup','#search',function(){
      // search();
      fetch_commande(1,fetch().facture,fetch().status,fetch().client,fetch().search,fetch().from,fetch().to);
    });
    $(document).on('change','#date1',function(){
      fetch_commande(1,fetch().facture,fetch().status,fetch().client,fetch().search,fetch().from,fetch().to);
    });
    $(document).on('change','#date2',function(){
      fetch_commande(1,fetch().facture,fetch().status,fetch().client,fetch().search,fetch().from,fetch().to);
    });
    // -----------KeyUp searchCommandes--------------//
  });
  // -----------My function--------------//
  function searchSelect(param1,param2,param3,param4){
    var table=$('#table');
    $.ajax({
      type:'get',
      url:"{!!Route('commande.getCommandes5')!!}",
      data:{'client':param1,'facture':param2,'status':param3,'search':param4},
      success:function(data){
        var lignes = '';
        // ------------------ //
        table.find('tbody').html("");
        var details = ''; 
        // ------------------ //
        var commandes = data;
        commandes.forEach((commande,index) => {
          // ************************ //
          var url_edit = "{{route('commande.edit',['commande'=>':id'])}}".replace(':id', commande.id);
          var url_delete1 = "{{route('commande.destroy',['commande'=>':commande'])}}".replace(':commande', commande.id);
          var url_show1 = "{{route('commande.show',['commande'=>':id'])}}".replace(':id', commande.id);
          var url_reg = "{{route('reglement.create3',['commande'=>'commande_id'])}}".replace('commande_id', commande.id);
          var url_fac = "{{route('commande.facture',['commande'=>'commande_id'])}}".replace('commande_id', commande.id);
          var facture = "NF";
          if(commande.facture == "f")
            facture = "F";
          var status = "R";
          if(commande.reste > 0)
            status = "NR";
          // ************************ //
          details = ''; 
            // ------ begin reglements ------
            commande.reglements.forEach((reglement,i) => {
              var style = "";
              var btnAvoir = reglement.status;
              (reglement.reste > 0) ? style = "color : red" : style = "color : green";
              if(reglement.status == 'AV'){
                btnAvoir = `<button
                    id="avoir${reglement.id}"  
                    onclick="avoir({
                      reg_id: ${reglement.id}, 
                      reg_avance: ${parseFloat(reglement.avance).toFixed(2)}, 
                      reg_reste: ${parseFloat(reglement.reste).toFixed(2)},
                      cmd_id: ${commande.id}, 
                      cmd_avance: ${parseFloat(commande.avance).toFixed(2)}, 
                      cmd_reste: ${parseFloat(commande.reste).toFixed(2)}
                    })"
                    class="btn btn-outline-success btn-sm">Avoir</button>`;
              }
              var url_show2 = "{{route('reglement.show',['reglement'=>':id'])}}".replace(':id', reglement.id);
              var url_delete2 = "{{route('reglement.delete',['reglement'=>':reglement'])}}".replace(':reglement', reglement.id);
              var btnPrint = `<a class="btn btn-outline-info  btn-sm" href=${url_show2}><i class="fas fa-print"></i></a>`;
              // var btnDelete2 = `<a class="btn btn-outline-danger  btn-sm" href=${url_delete2}><i class="fas fa-trash"></i></a>`;
              var btnDelete2 = `<button 
                  class="btn btn-outline-danger btn-sm" 
                  id="btnDelete2${index+1}${i+1}" 
                  data-id="${reglement.id}" 
                  data-route="${url_delete2}" 
                  onclick="javascript:deleteReglement(${index+1}${i+1})">
                    <i class="fas fa-trash-alt fa-0.5px"></i>
                </button>`;
              if(reglement.avance != 0) 
                details+=`<tr  style="${style}">
                      <td>${reglement.code}</td>
                      <td style="display : none">${reglement.id}</td>
                      <td style="display : none">${reglement.commande_id}</td>
                      <td style="display : none">${commande.client.nom_client}</td>
                      <td>${reglement.date}</td>
                      <td>${parseFloat(reglement.avance).toFixed(2)}</td>
                      <td id="reste${i}">${parseFloat(reglement.reste).toFixed(2)}</td>
                      <td>${reglement.mode_reglement}</td>
                      <td class="text-center">${btnAvoir}</td>
                      <td class="text-center">
                        @if(in_array('show5',$permission) || Auth::user()->is_admin == 2)
                        ${btnPrint}
                        @endif
                        @if(in_array('delete5',$permission) || Auth::user()->is_admin == 2)
                        ${btnDelete2}
                        @endif
                      </td>
                    </tr>`;
            }) ;
            // ------ end reglements ------
          // ************************ //
          // <button id="btnDelete1${index}" class="btn btn-outline-danger btn-sm" onclick="window.location.assign('${url_delete1}')"><i class="fas fa-trash-alt fa-0.5px"></i></button>
          actions = `
            <div class="row">
              <div class="col-8">
                <span>[${commande.code}]</span>
                @if(in_array('create6',$permission) || Auth::user()->is_admin == 2)
                <button id="btnFacture${index}" class="btn btn-link" onclick="window.location.assign('${url_fac}')"><i class="fa fa-plus-square"></i>&nbsp;Facture&nbsp;<i class="fas fa-receipt"></i></button>
                @endif
                @if(in_array('create5',$permission) || Auth::user()->is_admin == 2)
                <button id="btnStatus${index}" class="btn btn-link" onclick="window.location.assign('${url_reg}')"><i class="fa fa-plus-square"></i>&nbsp;Règlement&nbsp;<i class="fas fa-hand-holding-usd"></i></button>
                @endif
                @if(in_array('show4',$permission) || Auth::user()->is_admin == 2)
                <a class="btn btn-outline-info btn-sm" href=${url_show1}><i class="fas fa-print"></i></a>
                @endif
                @if(in_array('edit4',$permission) || Auth::user()->is_admin == 2)
                <a class="btn btn-outline-success btn-sm" id="btnEdit${index+1}" href=${url_edit}><i class="fas fa-edit"></i></a>
                @endif
                @if(in_array('delete4',$permission) || Auth::user()->is_admin == 2)
                <a 
                  class="btn btn-outline-danger btn-sm" 
                  id="btnDelete1${index+1}" 
                  data-id="${commande.id}" 
                  data-route="${url_delete1}" 
                  href="javascript:deleteCommande(${index+1})">
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
          lignes += `
            <tr>
              <td>${commande.code}</td>
              <td>${commande.date}</td>
              <td>${commande.client.nom_client}</td>
              <td>${parseFloat(commande.total).toFixed(2)}</td>
              <td>${parseFloat(commande.avance).toFixed(2)}</td>
              <td>${parseFloat(commande.reste).toFixed(2)}</td>
              <td id="viewStatus${index}" style="display : none">${status}</td>
              <td id="viewFacture${index}" style="display : none">${facture}</td>
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
              <td colspan="7" class="border border-dark shadow p-3 mb-5 bg-light rounded">
                  ${actions}
                  <hr>
                  <div id="viewDetails${index}" style="display : none;">
                    <table class="table">
                      <thead class="thead-light">
                        <tr>
                          <th>Rèf</th>
                          <th style="display : none">id</th>
                          <th style="display : none">Commande_id</th>
                          <th style="display : none">Nom client</th>
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
    var client = null;
    var search = null;
    // -----------------------------------
    if($('#search').val() != ""){
      search = $('#search').val();
    }
    // -----------------------------------
    if($('#client').val()){
      client = $('#client').val(); 
      // clientHTML = $('#client').html(); 
      var create  = $('#create');
      var text = $("#client option:selected" ).text();
      var msg = `<i class="fa fa-plus"></i>&nbsp;Règlements [ ${text} ]` ;
      create.html(msg);
      var url = "{{route('reglement.create2',['client'=>'val'])}}";
      url = url.replace('val', client);
      create.attr('href',url);
    }
    else{
      var create  = $('#create');
      var msg = `<i class="fa fa-plus"></i>&nbsp;Règlements` ;
      create.html(msg);
      var url = "{{route('reglement.create2')}}";
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
    // searchSelect(client,facture,status);
    searchSelect(client,facture,status,search);
  }

  function actionButton(){
    table = $('#table');
    list = table.find('tbody').find('tr');
    for (let index = 0; index < list.length; index++) {
      // var item = list.eq(index).find('td');
      // var status = item.eq(6);
      // var facture = item.eq(7);
      var status = $('#viewStatus'+index);
      var facture = $('#viewFacture'+index);
      // var action = item.eq(8).find('button');
      // var btnFacture = action.eq(0);
      // var btnStatus = action.eq(1);
      var btnFacture = $('#btnFacture'+index);
      var btnStatus = $('#btnStatus'+index);
      var btnEdit = $('#btnEdit'+(index+1));
      var btnDelete1 = $('#btnDelete1'+(index+1));
      if(facture.html() == 'F'){
        btnFacture.attr('disabled',true);
        btnEdit.attr('style',"color : grey;cursor: default !important;pointer-events: none;");
        btnEdit.attr('onclick',"return false;");

        btnDelete1.attr('style',"color : grey;cursor: default !important;pointer-events: none;");
        btnDelete1.attr('onclick',"return false;");
      }
      else{
        btnFacture.attr('disabled',false);
        btnEdit.attr('style',"");
        btnEdit.attr('onclick',"");

        btnDelete1.attr('style',"");
        btnDelete1.attr('onclick',"");
      }
      (status.html() == 'R') ? btnStatus.attr('disabled',true): btnStatus.attr('disabled',false);
      // #################################################### //
      listAction = $('#viewActions'+index).find('table').find('tbody').find('tr');
      var existe = false;
      for (let indexAction = 0; indexAction < listAction.length; indexAction++) {
        var code = listAction.eq(indexAction).find('td').eq(0).html();
        if(code.substring(0, 3) === 'AVC'){
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
        if(code.substring(0, 3) !== 'AVC'){
          if(existe) {
            btnDelete2.attr('style',"color : grey;cursor: default !important;pointer-events: none;");
            btnDelete2.attr('onclick',"return false;");
          }  
          else{
            btnDelete2.attr('style',"");
            btnDelete2.attr('onclick',"javascript:deleteReglement("+(index+1)+(indexAction+1)+")");
          }
        }
      }
      // #################################################### //
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
        url:"{!!Route('reglement.avoir')!!}",
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
              window.location.assign("{{route('commande.index')}}")
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
  function deleteCommande(index){
    var btnDelete = $('#btnDelete1'+index);
    var action = btnDelete.data('route');
    Swal.fire({
      title: "Une commande est sur le point d'être détruite",
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
  function deleteReglement(index){
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