$(document).ready(function(){

    $(".addUser").click(function(){
       
        let params = {
        'empresaSelect' : $('#empresaSelect').val()               
        };
        console.log(params);
        
        var url = "{{route('processing.empresas')}}";
        
        axios.get(url)
        .then(function () {
            // handle success
           
        })
         .catch(function (error) {
         // handle error
          console.log(error);
         })
         .then(function () {
         // always executed
         $('#ModalCenter').modal(true); 
         $('#usuarios-table').DataTable().ajax.reload()
           });

           

           

    });

});