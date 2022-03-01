$(document).ready(function(){
    $(document).on('click','#test1',function(){
        console.log('This is test !!');
    });
});
// -----------My function--------------//
function test2(){
    console.log('This is test2 !!');
    console.log(URL::to('getCommandes5'));

    $.ajax({
    type:'get',
    // url:'{!!URL::to('getCommandes5')!!}',
    // data:{'client':param1,'facture':param2,'status':param3},
    success:function(data){
    },
    error:function(){
    }
    });
}