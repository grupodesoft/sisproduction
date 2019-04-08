<!-- page content -->
<div class="right_col" role="main">

    <div class="">
        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h3>Módulo de Ordenes</h3>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">


                        <div class="container">
                            
                            
                            <div class="row">
                                <div class="col-md-4 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <h4>Número de Transferencia: <strong><?php echo $detallesalida->idsalida; ?></strong></h4>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <h4>Número de Control: <strong><?php echo $detallesalida->numerosalida; ?></strong></h4>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12 col-xs-12 text-right">
                                <div class="form-group">
                                    <h4>Cliente: <strong><?php echo $detallesalida->nombre; ?></strong></h4>
                                </div>
                            </div>
                        </div> 
                            
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                     <div class="form-group">
                                        <label>Escaneo Codigo de Barra:</label>
                                        <input type="text" class="form-control" placeholder="Escaneo Codigo de Barra" id="item" autofocus="">
                                        <input type="hidden" name="idsalida" id="txtidsalida" value="<?php echo $detallesalida->idsalida; ?>" />
                                      </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label style="color: red" id="msgerror"></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <table class="table table-striped responsive-utilities jambo_table bulk_action" >
                                        <thead>
                                            <tr>
                                                <th><strong>Número de parte</strong></th>
                                                <th><strong>C. Pallet</strong></th>
                                                <th><strong>C. Caja por Pallet</strong></th>
                                                <th><strong>Modelo</strong></th>
                                                <th><strong>Revisión</strong></th>
                                                <th><strong>Ubicación</strong></th>
                                            </tr>
                                        </thead>
                                        <?php
                                        if(isset($detalleordenparciales) && !empty($detalleordenparciales)){
                                                   foreach ($detalleordenparciales as $value) {
                                              

                                                // code...
                                                echo "<tr>";
                                                echo "<td>"; 
                                                
                                        
                                                    if($value->salida == 0){
                                        ?>
                                                    
                                                    <a href="<?php echo site_url('orden/marcar/').$value->idpalletcajas."/".$idsalida ?>"><i class="fa fa-check-square" aria-hidden="true"></i>
</a>
                                                    
                                                <?php
                                                    }else{
                                                        echo'<i style="color:green;" class="fa fa-check-square" aria-hidden="true"></i> ';
                                                        
                                                    }
                                                    
                                                echo $value->numeroparte; 
                                                echo "</td>";
                                                echo "<td><i class='fa fa-check'  style='color:#1abd53;' aria-hidden='true'></i><strong> 1 </strong></td>";
                                                echo "<td>" .$value->caja. "</td>";
                                                echo "<td>" . $value->modelo . "</td>";
                                                echo "<td>" . $value->revision . "</td>"; 
                                                echo "<td>" . $value->nombreposicion . "</td>"; 
                                                echo "</tr>";
                                            }
                                        }
                                        if (isset($detalleorden) && !empty($detalleorden)) {
                                            $totalpallet = 0;
                                            $totalcajas = 0;
                                            foreach ($detalleorden as $value) {
                                                $totalpallet += $value->pallet;
                                                if ($value->tipo == 0) {
                                                    $totalcajas += $value->cajaspallet;
                                                } else {
                                                    $totalcajas += $value->caja;
                                                }


                                                // code...
                                                echo "<tr>";
                                                echo "<td>"; 
                                                if($value->tipo == 0 ){
                                                if($value->salida == 1){
                                                    echo '<i class="fa fa-thumbs-up" style="color:green;" aria-hidden="true"></i> ';

                                                }else{
                                                   echo '<i class="fa fa-thumbs-down" style="color:red;" aria-hidden="true"></i> ';

                                                }
                                                }else{ 
                                        
                                                    if($value->salida == 0){
                                        ?>
                                                    
                                                    <a href="<?php echo site_url('orden/marcar/').$value->idpalletcajas."/".$idsalida ?>"><i class="fa fa-check-square" aria-hidden="true"></i>
</a>
                                                    
                                                <?php
                                                    }else{
                                                        echo'<i style="color:green;" class="fa fa-check-square" aria-hidden="true"></i> ';
                                                        
                                                    }
                                                    }
                                                echo $value->numeroparte; 
                                                echo "</td>";
                                                echo "<td><i class='fa fa-check'  style='color:#8938f5;' aria-hidden='true'></i> <strong>" . $value->pallet . "</strong></td>";
                                                ?>
                                                <td>
                                                    <?php
                                                    if ($value->tipo == 0) {
                                                        echo $value->cajaspallet;
                                                    } else {
                                                        echo $value->caja;
                                                    }
                                                    ?>
                                                </td>
                                                <?php
                                                echo "<td>" . $value->modelo . "</td>";
                                                echo "<td>" . $value->revision . "</td>"; 
                                                echo "<td>" . $value->nombreposicion . "</td>"; 
                                                echo "</tr>";
                                            }
                                          
                                        }
                                        ?>
                                    </table>
                                </div>
                            </div>
                            <?php
                            if (isset($detallepallet) && !empty($detallepallet)) {
                            ?>
                            <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12 ">

                                <div class="panel-group">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" style=" background-color: #d8d8d8">
                                            <h4 class="panel-title" >
                                                <a data-toggle="collapse" href="#collapse1"><i class="fa fa-bars" aria-hidden="true"></i> Click para ver detalles de la Orden.</a>
                                            </h4>
                                        </div>
                                        <div id="collapse1" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Número de parte</th>
                                                            <th>Modelo</th>
                                                            <th>Pallet</th>
                                                            <th>Cajas</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                          $totalpallet = 0;
                                                        $totalcajas = 0;
                                                        if (isset($detallepallet) && !empty($detallepallet)) {
                                                            foreach ($detallepallet as $value) {
                                                                $totalpallet += $value->totalpallet;
                                                                $totalcajas += $value->sumacajas;
                                                                echo "<tr>";
                                                                echo "<td><i class='fa fa-check'  style='color:#8938f5;' aria-hidden='true'></i> $value->numeroparte </td>";
                                                                echo "<td>$value->modelo</td>";
                                                                echo "<td>$value->totalpallet</td>";
                                                                echo "<td>$value->sumacajas</td>";
                                                                echo "</tr>";
                                                            }
                                                        }
                                                        if (isset($detalleparciales) && !empty($detalleparciales)) {
                                                            foreach ($detalleparciales as $value) {
                                                                $totalpallet += 1;
                                                                $totalcajas += $value->sumacajas;
                                                                echo "<tr>";
                                                                echo "<td><i class='fa fa-check'  style='color:#1abd53;' aria-hidden='true'></i> $value->numeroparte </td>";
                                                                echo "<td>$value->modelo</td>";
                                                                echo "<td>1</td>";
                                                                echo "<td>$value->sumacajas</td>";
                                                                echo "</tr>";
                                                            }
                                                        }

                                                        echo "<tr>"; 
                                                        echo "<td></td>";
                                                        echo "<td></td>";
                                                        echo "<td><strong>" . number_format($totalpallet) . "</strong></td>";
                                                        echo "<td><strong>" . number_format($totalcajas) . "</strong></td>";
                                                        echo "</tr>";
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
<script>
  $.fn.delayPasteKeyUp = function(fn, ms) {
              var timer = 0;
              $(this).on("propertychange input", function() {
                  clearTimeout(timer);
                  timer = setTimeout(fn, ms);
              });
          };
           $(document).ready(function() {
              $("#item").delayPasteKeyUp(function() {
          
                  item = $("#item").val();
                  idsalida = $("#txtidsalida").val();
                  
                  $.ajax({
                      type: "POST",
                      url: "<?= base_url('orden/validar') ?>",
                      data: "item=" + item  + "&idsalida=" + idsalida,
                      dataType: "html",
                      beforeSend: function() {
                          //imagen de carga
                          //$("#resultado").html("<p align='center'><img src='ajax-loader.gif' /></p>");
                      },
                      error: function() {
                          alert("error petición ajax");
                      },
                      success: function(data) {

                        console.log(data);
                      if(data == 0){
                            $("#msgerror").text("El formato del codigo escaneado esta incorrecto.");
                            $('#item').empty();
                            $("#item").focus(); 
                        }else if(data == 1){
                            $("#msgerror").text("La orden ya esta llena.");
                            $('#item').empty();
                            $("#item").focus(); 
                        }else if(data == 2){
                           location.reload(true);
                        }else{
                             location.reload(true);
                        }
          
                      }
                  }); 

              }, 200);
          });
</script>