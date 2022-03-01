function calculReste(){
 var reste = document.getElementById("rst").value ;
//  console.log(reste);
 var avance =  document.getElementById("avc").value ;
//  console.log(avance);
 var Navance =  document.getElementById("avance").value ;
 var r="regle";
 var n="non réglé";

 reste = parseFloat(reste);
 avance = parseFloat(avance);

 var total = (reste + avance );
 console.log(total);
 var resu = (total - Navance);
 console.log(resu);
 document.getElementById("reste").value = resu ;

 if(resu == 0)
 { console.log(r);
  document.getElementById("reglement").value = r;
 
 }
 else{
  console.log(n);
  document.getElementById("reglement").value= n;
 
 }
 

}