<?php
    use function App\Providers\hasPermssion;
?>
<div class="table-responsive">
  <!-- <button onclick="sorting('number','credit','asc')">TRI</button> -->
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
          <!-- <th>Date</th>
          <th>Fournisseur | Client</th>
          <th>Nature</th>
          <th>N° de pièce</th>
          <th>Débit (DH)</th>
          <th>Crédit (DH)</th> -->
      </tr>
    </thead>
    <tbody>
      @foreach($data as $ligne)
      <?php
        $debit = '-';
        if($ligne->debit) $debit = $ligne->debit;
        $credit = '-';
        if($ligne->credit) $credit = $ligne->credit;
        ($credit == '-') ? $style = "color : red" : $style = "color : green";
      ?>
      <tr class="text-center" style="<?php echo $style;?>">
        <td>{{$ligne->date}}</td>
        <td class="text-left">{{$ligne->nom}}</td>
        <td>{{$ligne->nature}}</td>
        <td>{{$ligne->code}}</td>
        <td class="text-center">{{$debit}}</td>
        <td class="text-center">{{$credit}}</td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr class="text-right">
        <th colspan="4">Totaux :</th>
        <th>{{number_format($total_debit,2, '.', '')}} DH</th>
        <th>{{number_format($total_credit,2, '.', '')}} DH</th>
      </tr>
    </tfoot>
  </table>
  {{$data->links()}}
</div>