@extends('layouts.app')
@section('title', 'Tercera edad rural')
@push('styles')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="col-md-12">
                    <h2 class="text-center">Tercera edad rural años anteriores </h2>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col">
                    <div style="display:none" id="aletMensajes" class="alert alert-info">

                    </div>
            </div>
        </div>
        <form id="formConsulta" action="{{route('consulta.rural.exoneracion')}}" method="post">
            <div class="row justify-content-md-center">
                <div class="col-3">
                    <div class="mb-3">
                        <label for="inputMatricula">* codigo catastral: </label>
                        <input type="number" class="form-control {{$errors->has('inputMatricula') ? 'is-invalid' : ''}}" id="inputMatricula" name="inputMatricula" value="" autofocus required>
                        <div class="invalid-feedback">
                            @if($errors->has('inputMatricula'))
                                {{$errors->first('inputMatricula')}}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <br>
                        <button id="btnConsulta" class="btn btn-primary" type="submit">
                            <span id="spanConsulta" class="bi bi-search" role="status" aria-hidden="true"></span>
                            Consultar
                        </button>
                    </div>
                </div>

            </div>

        </form>
        <div class="row">
            <div class="col-3">
                <div class="mb-3">
                    <br>
                    <button id="buttonExoneracion" class="btn btn-primary"><i class="bi bi-fullscreen"></i> Generar exoneración</button>
                </div>
            </div>
        </div>
        <form class="" action="{{route('store.tesoreria')}}" id="formExonerar" name="formExonerar" method="post" enctype="multipart/form-data">
        <div class="row mt-3">

            <div class="col-12">
                <h3>Lista de liquidaciones</h3>
            </div>
            <div class="col-md-12">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tableCita" style="width: 100%">
                        <thead>
                            <tr>
                            <th scope="col">*</th>
                            <th scope="col">Seleccionar</th>
                            <th scope="col">Año</th>
                            <th>Liquidación</th>
                            <th scope="col">Cod. Catastral</th>
                            <th scope="col">Propietario</th>
                            <th scope="col">Total de pago</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyurban">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Exoneracion -->
    <div class="modal fade" id="modalExoneracion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Exoneración de la tercera edad</h5>

            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @csrf
                <div class="row">
                    <h6 id="exampleModalLabel">Exoneración de la tercera edad años anteriores</h6>

                </div>
                <div class="row">
                    <div class="col">
                            <div style="display:none" id="alerMensajesExoneracion" class="alert alert-warning">
                                Llene todos los campos obligatorios
                            </div>
                    </div>
                </div>
                <div class="row mt-3">
                   <div class="col-6">
                        <div class="mb-3">
                            <label for="num_resolucion">* Codigo de resolución : </label>
                            <input class="form-control" id="num_resolucion" name="num_resolucion" disabled/>
                            <div class="invalid-feedback">

                            </div>
                        </div><div class="mb-3">
                            <label for="ruta_resolucion" class="form-label">Cargar Resolución</label>
                            <input class="form-control" type="file" id="ruta_resolucion" name="ruta_resolucion" disabled>
                            <div class="invalid-feedback">

                            </div>
                        </div>


                   </div>
                   <div class="col-6">
                        <div class="mb-3">
                            <label for="observacion">* Observacion : </label>
                            <textarea class="form-control" id="observacion" name="observacion" rows="5" disabled></textarea>
                        </div>
                   </div>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="btnAplicar">
                    <span id="spanConsultaExo" class="bi bi-save" role="status" aria-hidden="true"></span>
                    Aplicar
                </button>
            </div>
        </div>
        </div>
    </div>
    </form>
@endsection
@push('scripts')
<script>
    var buttonExoneracion = document.getElementById('buttonExoneracion');
    var buttonBuscar = document.getElementById('buttonBuscar');
    let token = "{{csrf_token()}}";
    buttonExoneracion.addEventListener('click', function() {
        var modalExoneracion = new bootstrap.Modal(document.getElementById('modalExoneracion'), {
        keyboard: false
        })
        modalExoneracion.show();
    });
    var formConsulta = document.getElementById('formConsulta');
    formConsulta.addEventListener('submit', function(e) {
        e.preventDefault()
        var btnConsulta = document.getElementById('btnConsulta');
        btnConsulta.setAttribute("disabled", "disabled");
        btnConsulta.innerHTML = '<span id="spanConsulta" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Consultando...';
        inputMatricula = document.getElementById('inputMatricula').value;
        let formData = new FormData(this);
        formData.append('_token',token);
        formData.append('num_predio',inputMatricula);
        var aletMensajes = document.getElementById('aletMensajes');
        axios.post(this.getAttribute('action'),formData).then(function(res) {

            aletMensajes.setAttribute("style","display: none");
            if(res.status==200) {
                if(res.data.estado == 'ok'){
                    var array_rural = res.data.liquidacionRural;

                    var countUrban = Object.keys(array_rural).length;

                    var tbodyurban = document.getElementById('tbodyurban');
                    var tablahtmlUrbano = '';

                    if(countUrban > 0){
                        for (let clave2 in array_rural){
                            var contadorUrbano = 0;
                            for (let clave3 of array_rural[clave2]) {

                                tablahtmlUrbano += '<tr>';

                                if(array_rural[clave2][contadorUrbano]['estado_liquidacion'] == 1)
                                {
                                    tablahtmlUrbano += '<td>';
                                    tablahtmlUrbano += '<i class="bi bi-circle-fill" style="color:green;"></i>';
                                    tablahtmlUrbano += '</td>';
                                    tablahtmlUrbano += '<td>';
                                    tablahtmlUrbano += '</td>';
                                }else
                                {
                                    tablahtmlUrbano += '<td>';
                                    tablahtmlUrbano += '<i class="bi bi-circle-fill" style="color:red;"></i>';
                                    tablahtmlUrbano += '</td>';
                                    tablahtmlUrbano += '<td>';
                                    tablahtmlUrbano += '<div class="form-check">';
                                    tablahtmlUrbano += '<input class="form-check-input" type="checkbox" value="'+array_rural[clave2][contadorUrbano]['id']+'" name="checkLiquidacion[]">';
                                    tablahtmlUrbano += '</div>';
                                    tablahtmlUrbano += '</td>';
                                }
                                tablahtmlUrbano += '<td>';
                                tablahtmlUrbano += array_rural[clave2][contadorUrbano]['anio'];
                                tablahtmlUrbano += '</td>';
                                tablahtmlUrbano += '<td>';
                                tablahtmlUrbano += array_rural[clave2][contadorUrbano]['id_liquidacion'];
                                tablahtmlUrbano += '</td>';
                                tablahtmlUrbano += '<td>';
                                tablahtmlUrbano += array_rural[clave2][contadorUrbano]['clave_cat'];
                                tablahtmlUrbano += '</td>';
                                tablahtmlUrbano += '<td>';
                                    if(array_rural[clave2][contadorUrbano]['nombres'] != null){
                                        tablahtmlUrbano += array_rural[clave2][contadorUrbano]['nombres']+' '+array_rural[clave2][contadorUrbano]['apellidos']
                                    }else{
                                        tablahtmlUrbano += array_rural[clave2][contadorUrbano]['nombre_comprador'];
                                    }
                                tablahtmlUrbano += '</td>';
                                tablahtmlUrbano += '<td>';
                                tablahtmlUrbano += array_rural[clave2][contadorUrbano]['total_pago'];
                                tablahtmlUrbano += '</td>';
                                tablahtmlUrbano += '</tr>';
                                contadorUrbano = contadorUrbano + 1;
                            }

                        }
                    }
                    //rural
                    var tablahtml = '';
                    tbodyurban.innerHTML = tablahtmlUrbano;
                    cambiarAtributoButton();

                }else{

                    aletMensajes.removeAttribute('style');
                    aletMensajes.innerHTML = res.data.mensaje;
                    cambiarAtributoButton();
                    console.log('error al consultar al servidor');
                }

            }
        }).catch(function(err) {
            console.log(err);
            if(err.response.status == 500){
                aletMensajes.removeAttribute('style');
                aletMensajes.setAttribute('class','alert alert-danger');
                aletMensajes.innerHTML = "¡ATENCIÓN!. Error de comunicacion, intenta mas tarde . Contacta al administrador de sistemas";
                cambiarAtributoButton();
                console.log('error al consultar al servidor');
            }

            if(err.response.status == 419){
                //toastr.error('Es posible que tu session haya caducado, vuelve a iniciar sesion');
                console.log('Es posible que tu session haya caducado, vuelve a iniciar sesion');
            }
            if(err.response.status == 422){
                //toastr.error('Revise la validacion del archivo');

            }
        }).then(function() {
                //loading.style.display = 'none';
        });
    });
    var formExonerar = document.getElementById('formExonerar');
    formExonerar.addEventListener('submit', function(e) {
        e.preventDefault();
        var btnAplicar = document.getElementById('btnAplicar');
        var alerMensajesExoneracion = document.getElementById('alerMensajesExoneracion');
        btnAplicar.setAttribute("disabled", "disabled");
        btnAplicar.innerHTML = '<span id="spanConsultaExo" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Aplicando...';
        let verificar = verificarSeleccionCasillas();
        if(verificar == false){
            alerMensajesExoneracion.setAttribute('style','');
            alerMensajesExoneracion.setAttribute('class','alert alert-danger');
            alerMensajesExoneracion.innerHTML = '¡Informacion!. Seleccione al menos una liquidacion';
            cambiarAtributoButtonAplicar();
            return false;
        }
        inputMatricula = document.getElementById('inputMatricula').value;
        let formData = new FormData(this);
        formData.append('num_predio',inputMatricula);
        axios.post('/tesoreria/exonerar',formData).then(function(res) {
            console.log(res);
            if(res.status==200) {
                if(res.data.estado == 'ok'){
                    alerMensajesExoneracion.setAttribute('style','');
                    alerMensajesExoneracion.setAttribute('class','alert alert-success');
                    alerMensajesExoneracion.innerHTML = res.data.success;
                    document.getElementById('formExonerar').reset();
                    cambiarAtributoButtonAplicar();
                    deshabilitarFormularioExoneracion();
                }else{
                    alerMensajesExoneracion.setAttribute('style','');
                    alerMensajesExoneracion.setAttribute('class','alert alert-warning');
                    alerMensajesExoneracion.innerHTML = res.data.success;;
                    cambiarAtributoButtonAplicar();
                }

            }
        }).catch(function(err) {
            console.log(err);
            if(err.response.status == 500){
                alerMensajesExoneracion.setAttribute('style','');
                alerMensajesExoneracion.setAttribute('class','alert alert-warning');
                alerMensajesExoneracion.innerHTML = '¡Error! '+err.message+' .Contacta al administrador de sistemas';
                cambiarAtributoButtonAplicar();
                console.log('error al consultar al servidor');
            }

            if(err.response.status == 419){
                //toastr.error('Es posible que tu session haya caducado, vuelve a iniciar sesion');
                console.log('Es posible que tu session haya caducado, vuelve a iniciar sesion');
            }
            if(err.response.status == 422){
                toastr.error('Revise la validacion del archivo');

            }
        }).then(function() {
                //loading.style.display = 'none';
        });
    });
    function cambiarAtributoButton(){
        btnConsulta.removeAttribute('disabled')
        btnConsulta.innerHTML = '<span id="spanConsulta" class="bi bi-search" role="status" aria-hidden="true"></span> Consultar';
    }

    function cambiarAtributoButtonAplicar(){
        btnAplicar.removeAttribute("disabled");
        btnAplicar.innerHTML = '<span id="spanConsultaExo" class="bi bi-save" role="status" aria-hidden="true"></span> Aplicar';
    }

    function verificarSeleccionCasillas(){
        let formData2 = new FormData(formExonerar);
        if(formData2.getAll("checkLiquidacion[]").length > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function MostrarCamposErrores(errores){
        var num_resolucion = document.getElementById('num_resolucion');
        var ruta_resolucion = document.getElementById('ruta_resolucion');
        if(errores.num_resolucion != null){
            num_resolucion.setAttribute('class','form-control is-invalid');
            var elementosiguiente = num_resolucion.nextElementSibling;
            elementosiguiente.innerHTML = errores.num_resolucion;
        }else{
            num_resolucion.setAttribute('class','form-control');
        }
        if(errores.ruta_resolucion != null){
            ruta_resolucion.setAttribute('class','form-control is-invalid');
            var elementosiguiente2 = ruta_resolucion.nextElementSibling;
            elementosiguiente2.innerHTML = errores.ruta_resolucion;
        }else{
            ruta_resolucion.setAttribute('class','form-control');
        }
    }
    var modalExoneracion = document.getElementById('modalExoneracion');
    modalExoneracion.addEventListener('show.bs.modal', function () {
        var num_resolucion = document.getElementById('num_resolucion');
        var btnAplicar = document.getElementById('btnAplicar');
        num_resolucion.focus();
        var verificar = verificarSeleccionCasillas();
        if(verificar === true){
            habilitarFormularioExoneracion();
            alerMensajesExoneracion.setAttribute('style','display:none;');
        }else{
            deshabilitarFormularioExoneracion();
            alerMensajesExoneracion.setAttribute('style','');
            alerMensajesExoneracion.setAttribute('class','alert alert-danger');
            alerMensajesExoneracion.innerHTML = '¡Advertencia!. Debe seleccionar al menos una liquidacion';
        }
    })

    function deshabilitarFormularioExoneracion(){
        document.getElementById('num_resolucion').setAttribute('disabled','disabled');
        document.getElementById('ruta_resolucion').setAttribute('disabled','disabled');
        document.getElementById('observacion').setAttribute('disabled','disabled');
        btnAplicar.setAttribute('disabled','disabled');
    }

    function habilitarFormularioExoneracion(){
        document.getElementById('num_resolucion').removeAttribute("disabled")
        document.getElementById('ruta_resolucion').removeAttribute("disabled")
        document.getElementById('observacion').removeAttribute("disabled")
        btnAplicar.removeAttribute("disabled");
    }
</script>
@endpush
