
    function calculTotal(){

      // console.log(quantite);

      var prix = document.getElementById("prod_price").value;
      var quantite = document.getElementById("quantite").value;
      var TVA = document.getElementById("prod_TVA").value;

      var amount = ((prix * quantite ) + (prix * quantite * TVA));
      document.getElementById("amount").value = amount;
      
      // var reste = document.getElementById("rst").value;
      var avance = document.getElementById("avc").value;
      var prixtotale = document.getElementById("prix-totale").value;
      var r="réglé";
      var n="non réglé";
      

      var resultat = (prixtotale - avance);
      document.getElementById("rst").value = resultat;
      
       if(resultat == 0)
       { console.log(r);
        document.getElementById("reglement").value = r;
       
       }
       else{
        console.log(n);
        document.getElementById("reglement").value= n;
       
       }
       

    // document.getElementById("ttc").value = amount;
    // document.getElementById("tset").value = amount;
    // document.getElementById("yarbi").value = amount;
    
    // if (amount>1)
    // {
    //   alert( 'ok')
    // }

  }

