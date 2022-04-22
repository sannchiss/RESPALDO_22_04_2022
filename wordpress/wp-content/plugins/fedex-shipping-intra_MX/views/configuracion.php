<?php if (current_user_can('manage_options')) : #Condicional Alternativa 

?>

<div class="accordion" id="accordionExample">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                aria-expanded="true" aria-controls="collapseOne">
                Configuración de Cuenta
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
            data-bs-parent="#accordionExample">
            <div class="accordion-body">

                <div class="container">

                    <form id="configuration">

                        <div class="row">

                            <div class="col-md-12">

                                <div class="shadow p-3 mb-5 bg-white rounded">

                                    <div class="container-fluid" style="z-index:1">

                                        <div class="panel-heading">

                                            <h3 class="panel-title mb-3">Credenciales</h3>

                                        </div>

                                        <div class="row">

                                            <div class="col-md-5">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" placeholder=""
                                                        id="accountNumber" name="accountNumber" minlength="9"
                                                        maxlength="9" value="" required>
                                                    <label for="accountNumber">Account Number</label>
                                                </div>

                                            </div>

                                        </div>

                                        <!--Meter Number--->

                                        <div class="row">

                                            <div class="col-md-5">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" placeholder=""
                                                        id="meterNumber" name="meterNumber" minlength="9" maxlength="9"
                                                        required>
                                                    <label for="meterNumber">Meter Number</label>
                                                </div>


                                            </div>

                                        </div>

                                        <!--wskey User Credential--->
                                        <div class="row">

                                            <div class="col-md-5">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" placeholder=""
                                                        id="wskeyUserCredential" name="wskeyUserCredential"
                                                        minlength="9" maxlength="9" required>
                                                    <label for="wskeyUserCredential">Wskey User Credential</label>
                                                </div>


                                            </div>

                                        </div>

                                        <!--wspassword User Credential--->
                                        <div class="row">

                                            <div class="col-md-5">

                                                <div class="form-floating mb-3">
                                                    <input type="password" class="form-control" placeholder=""
                                                        id="wskeyPasswordCredential" name="wskeyPasswordCredential"
                                                        minlength="9" maxlength="9" required>
                                                    <label for="wskeyPasswordCredential">Wspassword User
                                                        Credential</label>
                                                </div>

                                            </div>

                                        </div>

                                        <hr>

                                        <!--service Type--->
                                        <div class="row">

                                            <div class="panel-heading">

                                                <h3 class="panel-title mb-3">Servicios/Tipos de emblaje/Tipo
                                                    pagador/Impresión/Unidad de
                                                    medida</h3>

                                            </div>

                                            <div class="col-md-3">

                                                <div class="form-floating mb-3">
                                                    <select class="form-select" id="serviceType" name="serviceType"
                                                        required aria-label="Floating label select example">
                                                        <option selected disabled value="">Search...</option>
                                                        <option value="ECONOMY" selected>ECONOMY</option>
                                                        <option value="STANDARD_OVERNIGHT">STANDARD_OVERNIGHT</option>
                                                        <option value="PRIORITY_OVERNIGHT">PRIORITY_OVERNIGHT</option>
                                                        <option value="FIRST_OVERNIGHT">FIRST_OVERNIGHT</option>
                                                        <option value="FREIGHT_1D">FREIGHT_1D</option>
                                                        <option value="FREIGHT_2D">FREIGHT_2D</option>

                                                    </select>
                                                    <label for="serviceType">Service Type</label>
                                                </div>
                                            </div>

                                            <!--Packaging Type--->
                                            <div class="col-md-3">

                                                <div class="form-floating mb-3">
                                                    <select class="form-select" id="packagingType" name="packagingType"
                                                        required aria-label="Floating label select example">
                                                        <option selected disabled value="">Search...</option>
                                                        <option value="YOUR_PACKAGING" selected>YOUR_PACKAGING</option>
                                                        <option value="FEDEX_ENVELOPE">FEDEX_ENVELOPE</option>
                                                        <option value="FEDEX_PAK">FEDEX_PAK</option>
                                                        <option value="FEDEX_BOX">FEDEX_BOX</option>
                                                        <option value="FEDEX_TUBE">FEDEX_TUBE</option>
                                                        <option value="FEDEX_BOX_10">FEDEX_BOX_10</option>
                                                        <option value="FEDEX_BOX_25">FEDEX_BOX_25</option>
                                                    </select>
                                                    <label for="packagingType">Packaging Type</label>
                                                </div>
                                            </div>

                                        </div>

                                        <!--label Type--->
                                        <div class="row">

                                            <div class="col-md-2">

                                                <div class="form-floating mb-3">
                                                    <select class="form-select" id="paymentType" name="paymentType"
                                                        required aria-label="Floating label select example">
                                                        <option selected disabled value="">Search...</option>
                                                        <option value="SENDER" selected>SENDER</option>
                                                        <option value="RECIPIENT">RECIPIENT</option>
                                                        <option value="THIRD_PARTY">THIRD_PARTY</option>
                                                    </select>
                                                    <label for="paymentType">Payment Type</label>
                                                </div>
                                            </div>

                                            <div class="col-md-2">

                                                <div class="form-floating mb-3">
                                                    <select class="form-select" id="labelType" name="labelType" required
                                                        aria-label="Floating label select example">
                                                        <option selected disabled value="">Search...</option>
                                                        <option value="PNG" selected>PNG</option>
                                                        <option value="ZPL">ZPL</option>
                                                    </select>
                                                    <label for="labelType">label Type</label>
                                                </div>
                                            </div>

                                            <div class="col-md-2">

                                                <div class="form-floating mb-3">
                                                    <select class="form-select" id="measurementUnits"
                                                        name="measurementUnits" required
                                                        aria-label="Floating label select example">
                                                        <option selected disabled value="">Search...</option>
                                                        <option value="KG/CM" selected>KG/CM</option>
                                                        <option value="LBS/IN">LBS/IN</option>
                                                    </select>
                                                    <label for="measurementUnits">Measurement Units</label>
                                                </div>

                                            </div>



                                        </div>

                                        <hr>
                                        <!--Environment--->
                                        <div class="row">

                                            <div class="panel-heading">

                                                <h3 class="panel-title mb-3">Ambiente</h3>

                                            </div>

                                            <div class="col-md-2">

                                                <div class="form-floating mb-3">
                                                    <select class="form-select" id="environment" name="environment"
                                                        required aria-label="Floating label select example">
                                                        <option selected disabled value="">Search...</option>
                                                        <option value="QA" selected>QA</option>
                                                        <option value="PRODUCTION">PRODUCTION</option>
                                                    </select>
                                                    <label for="environment">Environment</label>
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <hr>


                                    <div class="footer">

                                        <div class="container">

                                            <div class="row">

                                                <div class="col-md-12">

                                                    <div class="footer">
                                                        <button type="submit"
                                                            class="send_config btn btn-primary">Guardar</button>
                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>


                    </form>

                </div>

            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                Datos de Origen
            </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
            data-bs-parent="#accordionExample">
            <div class="accordion-body">


                <div class="container-fluid">


                    <div class="row">

                        <div class="col-md-12">

                            <div class="shadow p-3 mb-5 bg-white rounded">


                                <div class="card-body">

                                    <form id="originShipper">


                                        <div class="row">

                                            <div class="col-md-6">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="personNameShipper"
                                                        name="personNameShipper"
                                                        aria-label="Floating label example input">
                                                    <label for="personNameShipper">Person Name </label>
                                                </div>


                                            </div>

                                            <div class="col-md-4">

                                                <div class="form-floating mb-3">
                                                    <input type="tel" class="form-control" id="phoneShipper"
                                                        name="phoneShipper" aria-label="Floating label example input">
                                                    <label for="phoneShipper">Phone </label>
                                                </div>


                                            </div>


                                        </div>

                                        <div class="row">

                                            <div class="col-md-6">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="companyNameShipper"
                                                        name="companyNameShipper"
                                                        aria-label="Floating label example input">
                                                    <label for="companyNameShipper">Company Name</label>
                                                </div>

                                            </div>

                                            <div class="col-md-4">

                                                <div class="form-floating mb-3">
                                                    <input type="email" class="form-control" id="emailShipper"
                                                        name="emailShipper" aria-label="Floating label example input">
                                                    <label for="emailShipper">Email</label>
                                                </div>
                                            </div>


                                        </div>

                                        <div class="row">

                                            <div class="col-md-2">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="vatNumberShipper"
                                                        name="vatNumberShipper"
                                                        aria-label="Floating label example input">
                                                    <label for="vatNumberShipper">Nif</label>
                                                </div>

                                            </div>

                                            <div class="col-md-3">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="cityShipper"
                                                        name="cityShipper" aria-label="Floating label example input">
                                                    <label for="cityShipper">City</label>
                                                </div>

                                            </div>

                                            <div class="col-md-1">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control"
                                                        id="stateOrProvinceCodeShipper"
                                                        name="stateOrProvinceCodeShipper" value="MX" readonly
                                                        aria-label="Floating label example input">
                                                    <label for="stateOrProvinceCodeShipper">State</label>
                                                </div>

                                            </div>


                                            <div class="col-md-2">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="postalCodeShipper"
                                                        name="postalCodeShipper"
                                                        aria-label="Floating label example input">
                                                    <label for="postalCodeShipper">Postal Code</label>
                                                </div>

                                            </div>

                                            <div class="col-md-1">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="countryCodeShipper"
                                                        name="countryCodeShipper" value="MX" readonly
                                                        aria-label="Floating label example input">
                                                    <label for="countryCodeShipper">Country</label>
                                                </div>

                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-md-6">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="addressLine1Shipper"
                                                        name="addressLine1Shipper"
                                                        aria-label="Floating label example input">
                                                    <label for="addressLine1Shipper">Address Line 1</label>
                                                </div>

                                            </div>

                                            <div class="col-md-6">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="addressLine2Shipper"
                                                        name="addressLine2Shipper"
                                                        aria-label="Floating label example input">
                                                    <label for="addressLine2Shipper">Address Line 2</label>
                                                </div>

                                            </div>

                                        </div>

                                        <div class="row">


                                            <div class="col-md-1">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="taxIdShipper"
                                                        name="taxIdShipper" aria-label="Floating label example input">
                                                    <label for="taxIdShipper">Tax</label>
                                                </div>

                                            </div>

                                            <div class="col-md-1">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="ieShipper"
                                                        name="ieShipper" aria-label="Floating label example input">
                                                    <label for="ieShipper">Ie</label>
                                                </div>

                                            </div>


                                        </div>
                                        <hr>

                                        <div class="row">

                                            <div class="col-md-12">

                                                <div class="footer">
                                                    <button type="submit"
                                                        class="send_config btn btn-primary">Guardar</button>
                                                </div>

                                            </div>


                                        </div>

                                    </form>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>


</div>










<!-- Separador -->







<?php else : ?>
<p>
    No tienes acceso a esta sección
</p>
<?php endif; ?>