<!-- page content -->
<div class="right_col" role="main">

    <div class="">

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h3>Detalles del envio</h3>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        
                        <form method="POST"  action="<?= base_url('parte/reenviarCalidad') ?>">

                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <div class="form-group">
                                        <h4>Número de parte: <strong><?php echo $detalle->numeroparte; ?></strong></h4>
                                        <h4><small>Número de transferencia:<small></small><strong><?php echo $detalle->folio; ?></strong></h4>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6" align="center">
                                    <div class="form-group">
                                        <h4>Cliente: <strong><?php echo $detalle->nombre; ?></strong></h4>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-6" align="right" >
                                    <div class="form-group">
                                        <p><h3 <?php
                                        if ($detalle->idestatus == 1) {
                                            echo 'style="color:green;"';
                                        } elseif ($detalle->idestatus == 3) {
                                            echo 'style="color:red;"';
                                        } elseif ($detalle->idestatus == 2) {
                                            echo 'style="color:green;"';
                                        } else {
                                            // code...
                                        }
                                        ?> >
                                                <?php
                                                if ($detalle->idestatus == 1) {
                                                    echo '<i class="fa fa-paper-plane" aria-hidden="true"></i>';
                                                } elseif ($detalle->idestatus == 3) {
                                                    echo '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>';
                                                } elseif ($detalle->idestatus == 2) {
                                                    echo '<i class="fa fa-thumbs-up" aria-hidden="true"></i>';
                                                } else {
                                                    // code...
                                                }
                                                ?>


                                            <?php echo $detalle->nombrestatus; ?></h3></p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label><font color="red">*</font> Modelo</label>
                                        <input type="text" class="form-control" name="modelo" id="modelo" autcomplete="off" placeholder="Modelo" value="<?php echo $detalle->modelo ?>">
                                        <label style="color:red;"><?php echo form_error('modelo'); ?></label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label><font color="red">*</font> Revision</label>
                                        <input type="text" class="form-control" id="revision" name="revision" autcomplete="off" placeholder="Revision" value="<?php echo $detalle->revision ?>">
                                        <label style="color:red;"><?php echo form_error('revision'); ?></label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label><font color="red">*</font> Linea</label>
                                        <input type="text" class="form-control" name="linea" id="linea" autcomplete="off" placeholder="Linea" value="<?php echo $detalle->linea ?>">
                                        <label style="color:red;"><?php echo form_error('linea'); ?></label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label><font color="red">*</font> Enviarlo a calidad</label>
                                        <select class="form-control" id="usuariocalidad" name="usuariocalidad">
                                            <option value="">Seleccionar</option>
                                            <?php foreach ($usuarioscalidad as $value) { ?>
                                                <option <?php
                                                if ($value->idusuario == $detalle->idoperador) {
                                                    echo "selected";
                                                }
                                                ?> value=" <?php echo $value->idusuario ?>"><?php echo $value->name ?></option>
                                                <?php }
                                                ?>
                                        </select>
                                        <label style="color:red;"><?php echo form_error('usuariocalidad'); ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <button type="button" class="btn btn-info btn-sm" id="btnagregarpallet" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus-circle" aria-hidden="true"></i>
 Agregar</button>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Pallet</th>
                                                <th>Cajas</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1;
                                            foreach ($palletcajas as $value) { ?>
                                                <tr>
                                                    <td><strong><?php echo $i++; ?></strong></td>
                                                    <td><?php echo $value->pallet ?></td>
                                                    <td><?php echo $value->cajas ?></td>
                                                    <td> <a style="color:red;" class="btnquitar" href="<?php echo site_url('parte/quitarPalletCajas/' . $value->idpalletcajas . '/' . $detalle->iddetalleparte) ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a> </td>
                                                </tr>
<?php } ?> 
                                        </tbody>
                                    </table>
                                    
                                    
                             
                            
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <p class="text-center text-gray" style="font-size: 14px; font-weight: bold;">Anotaciones de Calidad</p>
                                    <?php if ($detalle->idestatus == 3) { ?>
                                        <?php
                                        if (isset($dataerrores) && !empty($dataerrores)) {
                                            // code...
                                            foreach ($dataerrores as $value) {

                                                echo "<label style='color:red;'>";
                                                echo "* " . $value->comentariosrechazo . " - " . $value->fecharegistro;
                                                echo "</label>";
                                                echo "<br>"; 
                                            }
                                        } else {
                                        echo '<p class="text-center">Sin anotaciones</p>';
                                    }
                                        ?>
                                    <?php
                                    } else {
                                        echo '<p class="text-center">Sin anotaciones</p>';
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12" align="right">
                                    <input type="hidden" name="iddetalleparte" value="<?php echo $detalle->iddetalleparte ?>">
                               
                                    <button type="submit" id="btnmodificar" name="reenviar" class="btn btn-success  btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i> Reenviar</button>
                                    <a  class="btn btn-default  btn-sm" href="<?php echo site_url('parte/'); ?>"><i class="fa fa-print" aria-hidden="true"></i>
                                        Imprimir etiqueta</a>
                                    <a  class="btn btn-default  btn-sm" target="_blank" href="<?php echo site_url('parte/etiquetaPacking/' . $detalle->iddetalleparte) ?>"><i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                        Generar etiqueta</a>
                                    <a  class="btn btn-default  btn-sm" href="<?php echo site_url('parte/'); ?>"><i class="fa fa-print" aria-hidden="true"></i>
                                        Imprimir envio</a>

                                    <a  class="btn btn-default  btn-sm" target="_blank" href="<?php echo site_url('parte/generarPDFEnvio/' . $detalle->iddetalleparte) ?>"><i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                        Generar envio</a>

                                </div>
                            </div>

                        </form>
                        
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="exampleModalLabel"><strong>Agregar Pallet</strong></h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                     <form method="POST" action="<?= base_url('parte/agregarPalletCajas') ?>">
                                    <div class="modal-body">
                                       
                                            <div class="row">
                                                 <div class="col-md-6 col-sm-12 col-xs-12">
                                                      <label><font color="red">*</font> Número de pallet</label>
                                                        <input type="text" class="form-control" name="numeropallet"  autcomplete="off" value="1" required>
                                                 </div>
                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                      <label><font color="red">*</font> Número de cajas</label>
                                                        <input type="text" class="form-control" name="numerocajas"  autcomplete="off" required>
                                                </div>
                                            </div>
                                        
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        <input type="hidden" name="iddetalleparte" value="<?php echo $detalle->iddetalleparte; ?>"/>
                                         <input type="hidden" name="idoperador" value="<?php echo $detalle->idoperador; ?>"/>
                                        <button type="submit" class="btn btn-primary">Agregar</button>
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

<script type="text/javascript">
    $(document).ready(function () {
        var estatus = '<?php echo($detalle->idestatus); ?>';
        //  alert(estatus);
        if (estatus == '1') { 
            $("#usuariocalidad").attr("disabled", true);
            $("#modelo").attr("disabled", true);
            $("#revision").attr("disabled", true);
            $("#linea").attr("disabled", true);
            $('#btnmodificar').css("visibility", "hidden");
            $('.btnquitar').css("visibility", "hidden");
            $('#btnagregarpallet').css("visibility", "hidden");
            //$(this).css("visibility", "visible");
        } else if (estatus == '3') {
            $("#usuariocalidad").attr("disabled", false);
            $("#modelo").attr("disabled", false);
            $("#revision").attr("disabled", false);
            $("#linea").attr("disabled", false);
            $('#btnmodificar').css("visibility", "visible");
            $('.btnquitar').css("visibility", "visible");
            $('#btnagregarpallet').css("visibility", "visible");
        } else if (estatus == '2') {
           
        } else {

        }
    });
</script>
