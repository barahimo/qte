
    function calcul(){

        console.log(quantite);
  
        var prix = document.getElementById("prod_price").value;
        var quantite = document.getElementById("quantite").value;
        var TVA = document.getElementById("prod_TVA").value;
  
        // var reste = document.getElementById("rst").value;
       
        // var prixtotale = document.getElementById("prix-totale").value;
  
       
  
      
      var amount = ((prix * quantite ) + (prix * quantite * TVA));
      document.getElementById("amount").value = amount;
      // document.getElementById("ttc").value = amount;
      // document.getElementById("tset").value = amount;
      // document.getElementById("yarbi").value = amount;
      
      // if (amount>1)
      // {
      //   alert( 'ok')
      // }
  
    }
  
  