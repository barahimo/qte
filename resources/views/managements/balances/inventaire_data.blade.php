<?php
    use function App\Providers\hasPermssion;
?>
<div class="table-responsive">
  <input type="hidden" id="link" value="date"/>
  <input type="hidden" id="order" value="asc"/>
  <table class="table table-striped table-bordered" id="table" >
    <thead class="bg-primary text-white">
      <tr class="text-center">
          <!-- <th><a onclick="isSort('date','asc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-asc"></i></a>&nbsp;&nbsp;Date&nbsp;&nbsp;<a onclick="isSort('date','desc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-desc"></i></a></th>
          <th><a onclick="isSort('produit','asc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-asc"></i></a>&nbsp;&nbsp;Produit&nbsp;&nbsp;<a onclick="isSort('produit','desc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-desc"></i></a></th>
          <th><a onclick="isSort('type','asc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-asc"></i></a>&nbsp;&nbsp;Type&nbsp;&nbsp;<a onclick="isSort('type','desc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-desc"></i></a></th>
          <th><a onclick="isSort('nom','asc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-asc"></i></a>&nbsp;&nbsp;Fournisseur | Client&nbsp;&nbsp;<a onclick="isSort('nom','desc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-desc"></i></a></th>
          <th><a onclick="isSort('prix','asc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-asc"></i></a>&nbsp;&nbsp;PU&nbsp;&nbsp;<a onclick="isSort('prix','desc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-desc"></i></a></th>
          <th><a onclick="isSort('quantite','asc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-asc"></i></a>&nbsp;&nbsp;Qté&nbsp;&nbsp;<a onclick="isSort('quantite','desc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-desc"></i></a></th>
          <th><a onclick="isSort('total','asc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-asc"></i></a>&nbsp;&nbsp;Prix Total&nbsp;&nbsp;<a onclick="isSort('total','desc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-desc"></i></a></th> -->
          <th><a onclick="sorting('date','date','asc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-asc"></i></a>&nbsp;&nbsp;Date&nbsp;&nbsp;<a onclick="sorting('date','date','desc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-desc"></i></a></th>
          <th><a onclick="sorting('string','nom_produit','asc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-asc"></i></a>&nbsp;&nbsp;Produit&nbsp;&nbsp;<a onclick="sorting('string','nom_produit','desc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-desc"></i></a></th>
          <th><a onclick="sorting('string','type','asc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-asc"></i></a>&nbsp;&nbsp;Type&nbsp;&nbsp;<a onclick="sorting('string','type','desc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-desc"></i></a></th>
          <th><a onclick="sorting('string','nom','asc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-asc"></i></a>&nbsp;&nbsp;Fournisseur | Client&nbsp;&nbsp;<a onclick="sorting('string','nom','desc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-desc"></i></a></th>
          <th><a onclick="sorting('number','prix','asc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-asc"></i></a>&nbsp;&nbsp;PU&nbsp;&nbsp;<a onclick="sorting('number','prix','desc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-desc"></i></a></th>
          <th><a onclick="sorting('number','quantite','asc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-asc"></i></a>&nbsp;&nbsp;Qté&nbsp;&nbsp;<a onclick="sorting('number','quantite','desc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-desc"></i></a></th>
          <th><a onclick="sorting('number','total','asc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-asc"></i></a>&nbsp;&nbsp;Prix Total&nbsp;&nbsp;<a onclick="sorting('number','total','desc')" class="text-white" style="cursor: pointer;"><i class="fa fa-sort-desc"></i></a></th>
      </tr>
    </thead>
    <tbody>
      @foreach($data as $ligne)
      <?php
        ($ligne->type == 'Sortie' ) ? $style = "color : red" : $style = "color : green";
      ?>
        <tr class="text-center" style="<?php echo $style;?>">
          <td>{{$ligne->date}}</td>
          <td class="text-left">{{$ligne->ref_produit}} | {{substr($ligne->nom_produit,0,15)}}...</td>
          <td>{{$ligne->type}}</td>
          <td class="text-left">{{$ligne->nom}}</td>
          <td class="text-left">{{number_format($ligne->prix,2, '.', '')}} DH</td>
          <td class="text-left">{{number_format($ligne->quantite,2, '.', '')}}</td>
          <td class="text-left">{{number_format($ligne->total,2, '.', '')}} DH</td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <th class="text-right" colspan="4"></th>
        <th class="text-right" colspan="2">Quantité entrée :</th>
        <th class="text-left">{{number_format($quantite_entree,2, '.', '')}}</th>
      </tr>
      <tr>
        <th class="text-right" colspan="4"></th>
        <th class="text-right" colspan="2">Quantité sortie :</th>
        <th class="text-left">{{number_format($quantite_sortie,2, '.', '')}}</th>
      </tr>
      <tr>
        <th class="text-right" colspan="4"></th>
        <th class="text-right" colspan="2">Dépenses :</th>
        <th class="text-left">{{number_format($total_entree,2, '.', '')}} DH</th>
      </tr>
      <tr>
        <th class="text-right" colspan="4"></th>
        <th class="text-right" colspan="2">Recettes :</th>
        <th class="text-left">{{number_format($total_sortie,2, '.', '')}} DH</th>
      </tr>
    </tfoot>
  </table>
  {{$data->links()}}
</div>