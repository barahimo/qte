@extends('layout.dashboard')
@section('contenu')
<table class="table table-striped table-bordered" id="table" >
    <thead class="bg-primary text-white">
      <tr class="text-center">
          <!-- <th><a onclick="isSort('date','asc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-asc"></i></a>&nbsp;&nbsp;Date&nbsp;&nbsp;<a onclick="isSort('date','desc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-desc"></i></a></th>
          <th><a onclick="isSort('nom','asc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-asc"></i></a>&nbsp;&nbsp;Fournisseur | Client&nbsp;&nbsp;<a onclick="isSort('nom','desc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-desc"></i></a></th>
          <th><a onclick="isSort('nature','asc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-asc"></i></a>&nbsp;&nbsp;Nature&nbsp;&nbsp;<a onclick="isSort('nature','desc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-desc"></i></a></th>
          <th><a onclick="isSort('code','asc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-asc"></i></a>&nbsp;&nbsp;N° de pièce&nbsp;&nbsp;<a onclick="isSort('code','desc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-desc"></i></a></th>
          <th><a onclick="isSort('debit','asc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-asc"></i></a>&nbsp;&nbsp;Débit (DH)&nbsp;&nbsp;<a onclick="isSort('debit','desc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-desc"></i></a></th>
          <th><a onclick="isSort('credit','asc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-asc"></i></a>&nbsp;&nbsp;Crédit (DH)&nbsp;&nbsp;<a onclick="isSort('credit','desc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-desc"></i></a></th> -->
          <th><a onclick="sorting('date','date','asc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-asc"></i></a>&nbsp;&nbsp;Date&nbsp;&nbsp;<a onclick="sorting('date','date','desc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-desc"></i></a></th>
          <th><a onclick="sorting('string','nom','asc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-asc"></i></a>&nbsp;&nbsp;Fournisseur | Client&nbsp;&nbsp;<a onclick="sorting('string','nom','desc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-desc"></i></a></th>
          <th><a onclick="sorting('string','nature','asc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-asc"></i></a>&nbsp;&nbsp;Nature&nbsp;&nbsp;<a onclick="sorting('string','nature','desc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-desc"></i></a></th>
          <th><a onclick="sorting('string','code','asc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-asc"></i></a>&nbsp;&nbsp;N° de pièce&nbsp;&nbsp;<a onclick="sorting('string','code','desc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-desc"></i></a></th>
          <th><a onclick="sorting('number','debit','asc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-asc"></i></a>&nbsp;&nbsp;Débit (DH)&nbsp;&nbsp;<a onclick="sorting('number','debit','desc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-desc"></i></a></th>
          <th><a onclick="sorting('number','credit','asc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-asc"></i></a>&nbsp;&nbsp;Crédit (DH)&nbsp;&nbsp;<a onclick="sorting('number','credit','desc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-desc"></i></a></th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
<script>
  $(document).ready(()=>{
    sorting('date','date','asc');
    redirectLinks();
  });
  redirectLinks = () =>{
    route = "{{route('balance')}}";
    pagination = $('.pagination a');
    for (let i = 0; i < pagination.length; i++) {
      pagination.eq(i).attr('href',route+'?'+pagination.eq(i).attr('href').substr(2,));
    }
  }
  function format_date(date){
    today = new Date(date);
    var dd = today.getDate();
    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd;
    }
    if (mm < 10) {
        mm = '0' + mm;
    }
    var today = dd + '/' + mm + '/' + yyyy;
    return today;
  }
  function sorting(type,link,order){
  // function sorting(){
    // console.log(type,link,order);
    // var link =$('#type').val(param1);
    // var link =$('#link').val(param2);
    // var order =$('#order').val(param3);
    // var date1 = $('#date1').val();
    // var date2 = $('#date2').val();

    var date1 = "2021-01-01";
    var date2 = "2022-01-01";
    
    $.ajax({
        type:'get',
        url:"{{Route('balance.getMouvement')}}",
        data:{
          'from' : date1,
          'to' : date2,
          'type' : type,
          'link' : link,
          'order' : order,
        },
        success: function(data){
          var table = $('#table');
          table.find('tbody').html("");
          var lignes = '';
          data.forEach((ligne,i) => {
              debit = '-';
              if(ligne.debit) debit = ligne.debit;
              credit = '-';
              if(ligne.credit) credit = ligne.credit;
              (credit == '-') ? style = "color : red" : style = "color : green";
              lignes+=`<tr class="text-center" style="${style}">
                  <td>${ligne.date}</td>
                  <td class="text-left">${ligne.nom}</td>
                  <td>${ligne.nature}</td>
                  <td>${ligne.code}</td>
                  <td class="text-center">${debit}</td>
                  <td class="text-center">${credit}</td>
                </tr>`;
          });
          table.find('tbody').append(lignes);
        },
        error:function(err){
            // Swal.fire("Erreur !");
            message('error','',"Erreur !");
        },
    });
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
@endsection