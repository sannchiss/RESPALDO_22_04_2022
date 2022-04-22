$(document).ready(function(){


    /**AGREGAR CUENTA(S) A USUARIOS CREADOS */
$(document).on('click','.eliminarEjecutivo',function(event){
    event.preventDefault();
    
    let params = {
        'Item_id' : $(this).data('id')                
      };
      console.log(params)
            let url = "processing/eliminarEjecutivo?"+ $.param(params);;
            axios.delete(url)
            .then((response)=>{
                console.log(response)
                $('#ejecutivos-table').DataTable().ajax.reload();
                                                
            })


});

$('#button-aleatorio').click(function(){
    var aleatorio = Math.round(Math.random()*20000);
 
    $('#password123').val('Fedex.'+ aleatorio);

});



});