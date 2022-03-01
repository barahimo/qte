$(document).ready(function(){
    searchSelect(null,'all','all');
    // -----------Change Select_Facturée--------------//
    $(document).on('change','#f',function(){
    search();
    });
    // -----------End Select_Facturée--------------//
    // -----------Change Select_NonFacturée--------------//
    $(document).on('change','#nf',function(){
    search();
    });
    // -----------End Select_NonFacturée--------------//
    // -----------Change Select_Reglée--------------//
    $(document).on('change','#r',function(){
    search();
    });
    // -----------End Select_Reglée--------------//
    // -----------Change Select_NonReglée--------------//
    $(document).on('change','#nr',function(){
    search();
    });
    // -----------End Select_NonReglée--------------//
    // -----------Change Select_Client--------------//
    $(document).on('change','#client',function(){
    // var client=$(this).val();
    // searchSelect(client,null);
    search();
    });
    // -----------End Select_Client--------------//
});
// -----------My function--------------//
function searchSelect(param1,param2,param3){
    var table=$('#table');
    $.ajax({
    type:'get',
    url:'{!!URL::to('getCommandes5')!!}',
    data:{'client':param1,'facture':param2,'status':param3},
    success:function(data){
        var lignes = '';
        // console.log(lignes);
        // ------------------ //
        table.find('tbody').html("");
        var details = ''; 
        // ------------------ //
        var commandes = data;
        commandes.forEach((commande,index) => {
        // ************************ //
        var url_edit = "{{route('commande.edit2',['id'=>":id"])}}".replace(':id', commande.id);
        var url_show = "{{route('commande.show2',['id'=>":id"])}}".replace(':id', commande.id);
        var url_reg = "{{route('reglement.create3',['commande'=>"commande_id"])}}".replace('commande_id', commande.id);
        var url_fac = "{{route('commande.facture2',['commande'=>"commande_id"])}}".replace('commande_id', commande.id);
        var facture = "NF";
        if(commande.facture == "f")
            facture = "F";
        var status = "R";
        if(commande.reste > 0)
            status = "NR";
        // ************************ //
        details = ''; 
            // ------ begin reglements ------
            commande.reglements.forEach(reglement => {
            var style = "";
            var btnAvoir = reglement.reglement;
            (reglement.reste > 0) ? style = "color : red" : style = "color : green";
            if(reglement.reglement == 'AV'){
                btnAvoir = `<button  
                    onclick="avoir({
                                    reg_id: ${reglement.id}, 
                                    reg_avance: ${reglement.avance}, 
                                    reg_reste: ${reglement.reste},
                                    cmd_id: ${ligne.id}, 
                                    cmd_avance: ${ligne.avance}, 
                                    cmd_reste: ${ligne.reste}
                                })"
                    class="btn btn-outline-success">Avoir</button>`;
            }
            var url_show = "{{route('reglement.show2',['id'=>":id"])}}".replace(':id', reglement.id);
            var btnPrint = `<a class="btn btn-outline-info" href=${url_show}>Print</a>`;
            if(reglement.avance != 0) 
                details+=`<tr  style="${style}">
                    <td>REG-${reglement.code}</td>
                    <td style="display : none">${reglement.id}</td>
                    <td style="display : none">${reglement.commande_id}</td>
                    <td style="display : none">${reglement.nom_client}</td>
                    <td>${reglement.mode_reglement}</td>
                    <td>${reglement.avance}</td>
                    <td>${reglement.reste}</td>
                    <td>${reglement.date}</td>
                    <td class="text-center">${btnAvoir}</td>
                    <td class="text-center">${btnPrint}</td>
                    </tr>`;
            }) ;
            // ------ end reglements ------
        // ************************ //
        lignes += `<tr>
            <td>BON-${commande.code}</td>
            <td>${commande.date}</td>
            <td>${commande.nom_client}</td>
            <td>${commande.totale}</td>
            <td>${commande.avance}</td>
            <td>${commande.reste}</td>
            <td style="display : none">${status}</td>
            <td style="display : none">${facture}</td>
            <td>
                <button class="btn btn-link" onclick="window.location.assign('${url_fac}')"><i class="fa fa-plus">&nbsp;<i class="fas fa-receipt"></i></i></button>
                <button class="btn btn-link" onclick="window.location.assign('${url_reg}')"><i class="fa fa-plus">&nbsp;<i class="fas fa-hand-holding-usd"></i></i></button>
                <a class="btn btn-link" href=${url_edit}><i class="fas fa-edit"></i></a>
                <a class="btn btn-link" href=${url_show}><i class="fas fa-eye"></i></a>
                <i class="fas fa-plus"
                id="btnDetails${index}"
                data-index="${index}" 
                data-status="false" 
                onclick="handleEvent(event)"
                ></i>
            </td>
            </tr>
            <tr id="viewDetails${index}" style="display: none;border:1px solid black">
            <td colspan="7" class="border border-dark shadow p-3 mb-5 bg-white rounded">
                <table class="table">
                <thead  class="thead-light">
                    <tr>
                    <th>#</th>
                    <th style="display : none">id</th>
                    <th style="display : none">Commande_id</th>
                    <th style="display : none">Nom_client</th>
                    <th>Mode_reglement</th>
                    <th>Montant payer</th>
                    <th>Reste</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                    </tr>
                </thead>
                <tbody>${details}</tbody>
                </table>
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
    // -----------------------------------
    if($('#client').val() != ""){
    client = $('#client').val(); 
    clientHTML = $('#client').html(); 
    var create  = $('#create');
    var text = $("#client option:selected" ).text();
    var msg = `<i class="fa fa-plus">&nbsp;Règlement</i> - ${text}`;
    create.html(msg);
    var url = "{{route('reglement.create2',['client'=>"val"])}}";
    url = url.replace('val', client);
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
    searchSelect(client,facture,status);
}
function actionButton(){
    table = $('#table');
    list = table.find('tbody').find('tr');
    for (let index = 0; index < list.length; index++) {
    var item = list.eq(index).find('td');
    var status = item.eq(6);
    var facture = item.eq(7);
    var action = item.eq(8).find('button');
    var btnFacture = action.eq(0);
    var btnStatus = action.eq(1);
    (facture.html() == 'F') ?btnFacture.attr('disabled',true) : btnFacture.attr('disabled',false);
    (status.html() == 'R') ? btnStatus.attr('disabled',true): btnStatus.attr('disabled',false);
    }
}
// ------------------------------------ //
function handleEvent(e){
    var item = e.target.getAttribute('id');
    var index = e.target.getAttribute('data-index');
    var status = e.target.getAttribute('data-status');
    if(status == 'true'){
    e.target.setAttribute('data-status','false');
    $('#'+item).parent().parent().parent().find('#viewDetails'+index).prop('style','display: none;');
    // $('#'+item).prop('class','fas fa-eye');
    $('#'+item).prop('class','fas fa-plus');
    }
    else{
    e.target.setAttribute('data-status','true');
    $('#'+item).parent().parent().parent().find('#viewDetails'+index).prop('style','display: contents;');
    // $('#'+item).prop('class','fas fa-eye-slash');
    $('#'+item).prop('class','fas fa-minus');
    }
}
function avoir(obj){
    $.ajax({
        type:'post',
        url:'{!!URL::to('avoir')!!}',
        data:{
        _token : $('input[name=_token]').val(),
        obj : obj,
        },
        success: function(data){
        Swal.fire(data.message);
        search();
        },
        error:function(err){
        (err.status === 500) ? Swal.fire(err.statusText):Swal.fire("Erreur !!!") ;
        },
    });
}