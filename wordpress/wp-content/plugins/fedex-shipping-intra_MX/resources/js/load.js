(function ($) {

  $(document).ready(function () {

    var table = $(".shippingList").DataTable({

      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
      },
      "order": [
        [0, "desc"]
      ],
      "columnDefs": [{
        "targets": [0],
        "orderable": false
      }],
      "lengthMenu": [
        [10, 25, 50, -1],
        [10, 25, 50, "Todos"],
      ],
      "pageLength": 10,
        

    });

 

    $("a.toggle-vis").on("click", function (e) {
      e.preventDefault();

      // Get the column API object
      var column = table.column($(this).attr("data-column"));

      // Toggle the visibility
      column.visible(!column.visible());
    });

    // Load configuration
    $.ajax({
      async: false,
      url: "admin-ajax.php",
      type: "POST",
      data: {
        action: "load_configuration",
        nonce: " load_configuration",
      },
      success: function (response) {
        var parse = JSON.parse(response);


        if (parse != null) {

        /**Input Data Account */
        if(parse["configuration"]["accountNumber"] != null){
        $("#accountNumber").val(parse["configuration"]["accountNumber"]);
        }
        $("#meterNumber").val(parse["configuration"]["meterNumber"]);
        $("#wskeyUserCredential").val(
          parse["configuration"]["wskeyUserCredential"]
        );
        if(parse["configuration"]["wskeyPasswordCredential"] != ""){
        $("#wskeyPasswordCredential").val(
          parse["configuration"]["wskeyPasswordCredential"]
        );
        }
        $("#serviceType").val(parse["configuration"]["serviceType"]);
        $("#packagingType").val(parse["configuration"]["packagingType"]);
        $("#paymentType").val(parse["configuration"]["paymentType"]);
        $("#labelType").val(parse["configuration"]["labelType"]);
        $("#measurementUnits").val(parse["configuration"]["measurementUnits"]);
        $("#environment").val(parse["configuration"]["environment"]);

        /**Input Data Origin */
        $("#personNameShipper").val(parse["shipper"]["personNameShipper"]);
        $("#phoneShipper").val(parse["shipper"]["phoneShipper"]);
        $("#companyNameShipper").val(parse["shipper"]["companyNameShipper"]);
        $("#emailShipper").val(parse["shipper"]["emailShipper"]);
        $("#vatNumberShipper").val(parse["shipper"]["vatNumberShipper"]);
        $("#cityShipper").val(parse["shipper"]["cityShipper"]);

        parse["shipper"]["stateOrProvinceCodeShipper"] == ""
          ? $("#stateOrProvinceCodeShipper").val("MX")
          : $("#stateOrProvinceCodeShipper").val(
              parse["shipper"]["stateOrProvinceCodeShipper"]
            );
        // $('#stateOrProvinceCodeShipper').val(parse['shipper']['stateOrProvinceCodeShipper);
        $("#postalCodeShipper").val(parse["shipper"]["postalCodeShipper"]);

        parse["shipper"]["countryCodeShipper"] == ""
          ? $("#countryCodeShipper").val("MX")
          : $("#countryCodeShipper").val(
              parse["shipper"]["countryCodeShipper"]
            );

        //$('#countryCodeShipper').val(parse.countryCodeShipper);
        $("#addressLine1Shipper").val(parse["shipper"]["addressLine1Shipper"]);
        $("#addressLine2Shipper").val(parse["shipper"]["addressLine2Shipper"]);
        $("#taxIdShipper").val(parse["shipper"]["taxIdShipper"]);
        $("#ieShipper").val(parse["shipper"]["ieShipper"]);

          }
      },

      error: function (error) {
        console.log(error);
      },
    });

    //Envio de formulario de configuración datos cliente
    jQuery("#configuration").on("submit", function (e) {
      e.preventDefault();

      let inputs = $("#configuration").serializeArray();

      $.ajax({
        url: "admin-ajax.php", // Url to which the request is send
        type: "POST",
        data: {
          inputs: inputs,
          action: "save_configuration",
        },
        success: function (data) {
          let timerInterval;
          Swal.fire({
            title: "Success",
            icon: "success",
            html: data,
            timer: 1500,
            timerProgressBar: true,
            didOpen: () => {
              Swal.showLoading();
              const b = Swal.getHtmlContainer().querySelector("b");
              timerInterval = setInterval(() => {
                b.textContent = Swal.getTimerLeft();
              }, 100);
            },
            willClose: () => {
              clearInterval(timerInterval);
            },
          }).then((result) => {
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
              location.reload();
            }
          });
        },
        error: function (data) {
          console.log(data);
        },
      });
    });

    /**Envio de Formulario OriginShipper */
    jQuery("#originShipper").on("submit", function (e) {
      e.preventDefault();

      let inputs = $("#originShipper").serializeArray();

      $.ajax({
        url: "admin-ajax.php", // Url to which the request is send
        type: "POST",
        data: {
          inputs: inputs,
          action: "save_originShipper",
        },
        success: function (data) {
          console.log(data);

          let timerInterval;
          Swal.fire({
            title: "Success",
            icon: "success",
            html: data,
            timer: 1500,
            timerProgressBar: true,
            didOpen: () => {
              Swal.showLoading();
              const b = Swal.getHtmlContainer().querySelector("b");
              timerInterval = setInterval(() => {
                b.textContent = Swal.getTimerLeft();
              }, 100);
            },
            willClose: () => {
              clearInterval(timerInterval);
            },
          }).then((result) => {
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
              location.reload();
            }
          });
        },
        completed: function () {},
        error: function (data) {
          console.log(data);
          Swal.fire({
            title: "Error",
            text: "Error al guardar los datos de origen",
            icon: "error",
            confirmButtonText: "Cerrar",
          });
        },
      });
    });

    /**Modal Orden Items */

    $(".itemsOrder").on("click", function (e) {
      e.preventDefault();

      // $(".modal-body").empty();

      $("#exampleModal").modal("show");

      $("modal-title").text("Items");

      let id = $(this).attr("data-id");
      $.ajax({
        url: "admin-ajax.php", // Url to which the request is send
        type: "POST",
        data: {
          orderId: $(this).data("order"),
          action: "get_itemsOrder",
        },
        success: function (data) {
          let parse = JSON.parse(data);

          console.log(parse);

          let html = "";

          let i = 1;
          $.each(parse, function (index, value) {
            html += `
            <tr>
            <td>${i}</td>
            <td>${value.name}</td>
            <td>${value.quantity}</td>
            <td>${value.total}</td>
            </tr>
            `;

            i++;
          });

          $("#itemsOrder").html(html); //Agrega el html al modal

          $("#itemsOrder").html(parse["itemsOrder"]);
          //  $("#modalItemsOrder").modal("show");
        },
        error: function (data) {
          console.log(data);
        },
      });
    });

    //Apertura de formulario de envio con datos cliente
    $(".order").click(function () {
      
      $.ajax({
        url: "admin-ajax.php", // Url to which the request is send
        type: "POST",
        data: {
          orderId: $(this).data("order"),
          action: "get_order",
        },
        success: function (data) {
          let parse = JSON.parse(data);

          console.log(parse);

          var today = new Date();
          var dd = String(today.getDate()).padStart(2, "0");
          var mm = String(today.getMonth() + 1).padStart(2, "0"); //January is 0!
          var yyyy = today.getFullYear();
          today = dd + "-" + mm + "-" + yyyy;

          $("#orderNumber").val(parse.id);
          $("#orderNumber").attr("readonly", true);
          $("#orderDate").val(today);
          $("#personNameRecipient").val(
            parse["billing"]["first_name"] + " " + parse["billing"]["last_name"]
          );
          $("#phoneNumberRecipient").val(parse["billing"]["phone"]);
          $("#companyNameRecipient").val(parse["billing"]["company"]);
          $("#emailRecipient").val(parse["billing"]["email"]);

          if(parse["customer_note"] != ""){
          $("#notesRecipient").val(parse["customer_note"]);
          }else{
            $("#notesRecipient").val("Ver nota fiscal");
          }

          $("#vatNumberRecipient").val(parse["billing"]["vat_number"]);
          $("#cityRecipient").val(parse["billing"]["city"]);
          $("#stateOrProvinceCodeRecipient").val(parse["billing"]["state"]);
          $("#postalCodeRecipient").val(parse["billing"]["postcode"]);
          $("#countryCodeRecipient").val(parse["billing"]["country"]);
          $("#streetLine1Recipient").val(parse["billing"]["address_1"]);
          $("#streetLine2Recipient").val(parse["billing"]["address_2"]);

          if(parse["orderDetails"] != null ){

          $("#numberOfPieces").val(parse["orderDetails"]["quantity"]);
          $("#weight").val(parse["orderDetails"]["weight"]);
          $("#weightUnits").val(parse["orderDetails"]["weightUnits"]);
          $("#length").val(parse["orderDetails"]["length"]);
          $("#width").val(parse["orderDetails"]["width"]);
          $("#height").val(parse["orderDetails"]["height"]);

          }

          console.log(parse);

          $("#orders").html(data);
        },
        error: function (data) {
          console.log(data);
        },
      });
    });

    //Cancelar envio Fedex
    $(".deleteOrder").click(function () {
      Swal.fire({
        title: "¿Estas seguro?",
        text: "No podras revertir esta acción",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.value) {
          $.ajax({
            url: "admin-ajax.php", // Url to which the request is send
            type: "POST",
            data: {
              orderId: $("#orderNumber").val(),
              action: "delete_order",
            },
            success: function (data) {
              console.log(data);

              let timerInterval;
              Swal.fire({
                title: "Success",
                icon: "success",
                html: data,
                timer: 1500,
                timerProgressBar: true,
                didOpen: () => {
                  Swal.showLoading();
                  const b = Swal.getHtmlContainer().querySelector("b");
                  timerInterval = setInterval(() => {
                    b.textContent = Swal.getTimerLeft();
                  }, 100);
                },
                willClose: () => {
                  clearInterval(timerInterval);
                },
              }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                  location.reload();
                }
              });
            },
            completed: function () {},
            error: function (data) {
              console.log(data);
              Swal.fire({
                title: "Error",
                text: "Error al guardar los datos de origen",
                icon: "error",
                confirmButtonText: "Cerrar",
              });
            },
          });
        }
      });
    });

    /******************************************************* */

    $(document).on("click", ".click", function () {
      let inputs = $("#orderSend").serializeArray();

      console.log(inputs);

      $.ajax({
        url: "admin-ajax.php", // Url to which the request is send
        type: "POST",
        data: {
          inputs: inputs,
          action: "create_OrderShipper",
        },
        success: function (data) {
          console.log(data);

          /*  let timerInterval
                Swal.fire({
                    title: 'Success',
                    icon: 'success',
                    html: data,
                    timer: 1500,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                        const b = Swal.getHtmlContainer().querySelector('b')
                        timerInterval = setInterval(() => {
                            
                            b.textContent = Swal.getTimerLeft()
                        }, 100)
                    },
                    willClose: () => {
                        clearInterval(timerInterval)
                    }
                }).then((result) => {
                    // Read more about handling dismissals below 
                    if (result.dismiss === Swal.DismissReason.timer) {
    
                        location.reload();
                    }
                }) */
        },
        completed: function () {},
        error: function (data) {
          console.log(data);
          Swal.fire({
            title: "Error",
            text: "Error al guardar los datos de destino",
            icon: "error",
            confirmButtonText: "Cerrar",
          });
        },
      });
    });
  });
})(jQuery);
