<div class="right_col" role="main">
  <?php
  $sumparcial = 0;
  $sumpallet = 0;
  $total =0;
  ?>
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <div class="row">
            <div class="col-md-11 col-sm-12 col-xs-12">
              <h3>Historial de Movimientos</h3>
            </div>
            <div class="col-md-1 col-sm-12 col-xs-12">
              <a href="<?php echo base_url('Warehouse/wharehouse')?>" class="btn btn-success"><i class="fa fa-home" aria-hidden="true"></i></a>
            </div>
          </div>

          <div class="clearfix"></div>
        </div>

        <div class="x_content">
         <div class="container">
          <div class="row">
            <div class="col-md-12">
              <table id="datatablerecordposicion" class="table">
                <thead>
                  <tr>
                    <th scope="col">Cliente</th>
                    <th scope="col">No. Parte</th>
                    <th scope="col">Revisión</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Ubicación</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Hora</th>
                    <th scope="col">Estatus</th>
                    <th scope="col">Tipo</th>
                  </tr>
                </thead>
                <tbody>
                  <!--Entradas-->
                  <?php if (isset($entradas) && !empty($entradas)):?>
                  <?php foreach ($entradas as $value):?>
                    <tr>
                      <td><?php echo $value->nombre; ?></td>
                      <td><?php echo $value->numeroparte; ?></td>
                      <td><?php echo $value->descripcion; ?></td>
                      <td><strong><?php echo number_format($value->cantidad); ?></strong></td>
                      <td><?php echo $value->nombreposicion; ?></td>
                      <td><?php echo date("d/m/Y", strtotime($value->fecharegistro));?></td>
                      <td><?php echo date("h:i:s a", strtotime($value->fecharegistro));?>
                      </td>
                      <td> <label style="color:green;">ENTRADA</label> </td>
                      <td><label style="color:green;">ENTRADA</label></td>
                    </tr>
                  <?php endforeach;?>
                <?php endif;?>

                <!--Salidas parciales-->
                <?php if (isset($salidasparciales) && !empty($salidasparciales)):?>
                <?php foreach ($salidasparciales as $value):?>
                  <?php $sumparcial = $sumparcial + $value->caja; ?>
                  <tr>
                    <td><?php echo $value->nombre; ?></td>
                    <td><?php echo $value->numeroparte; ?></td>
                    <td><?php echo $value->descripcion; ?></td>
                    <td><strong><?php echo number_format($value->caja); ?></strong></td>
                    <td><?php echo $value->nombreposicion; ?></td>
                    <td><?php echo date("d/m/Y", strtotime($value->fecharegistro));?></td>
                    <td><?php echo date("h:i:s a", strtotime($value->fecharegistro));?>
                    </td>
                    <td> <label style="color:red;">SALIDA</label> </td>
                    <td><label style="color:orange;">PARCIAL</label></td>
                  </tr>
                <?php endforeach;?>
              <?php endif;?>
              <!--Salidas Pallet-->
              <?php if (isset($salidaspallet) && !empty($salidaspallet)):?>
              <?php foreach ($salidaspallet as $value):?>
                <?php $sumpallet = $sumpallet + $value->cantidad; ?>
                <tr>
                  <td><?php echo $value->nombre; ?></td>
                  <td><?php echo $value->numeroparte; ?></td>
                  <td><?php echo $value->descripcion; ?></td>
                  <td></strong><?php echo number_format($value->cantidad); ?></strong></td>
                  <td><?php echo $value->nombreposicion; ?></td>
                  <td><?php echo date("d/m/Y", strtotime($value->fecharegistro));?></td>
                  <td><?php echo date("h:i:s a", strtotime($value->fecharegistro));?></td>
                  <td> <label style="color:red;">SALIDA</label> </td>
                  <td><label style="color: #3633ff ;">PALLET</label></td>
                </tr>
              <?php endforeach;?>
            <?php endif;?>

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</div>
</div>
</div>
<script type="text/javascript">
  $( document ).ready(function() {
    $('#datatablerecordposicion').DataTable( {
      dom: 'Bfrtip',
      buttons: [
      'excelHtml5',
      'pdfHtml5'
      ],
      "order": [[0, "desc"]],
                    "language": {
                        "sProcessing": "Procesando...",
                        "sLengthMenu": "Mostrar _MENU_ registros",
                        "sZeroRecords": "No se encontraron resultados",
                        "sEmptyTable": "Ningún dato disponible en esta tabla",
                        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sSearch": "Buscar:",
                        "sUrl": "",
                        "sInfoThousands": ",",
                        "sLoadingRecords": "Cargando...",
                        "oPaginate": {
                            "sFirst": "Primero",
                            "sLast": "Último",
                            "sNext": "Siguiente",
                            "sPrevious": "Anterior"
                        },
                        "oAria": {
                            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        }
                    }
    } );
  });
</script>
