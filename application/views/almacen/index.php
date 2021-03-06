<div class="right_col" role="main">
  <div class="">
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h3>Módulo de Localización de Pallets</h3>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div id="app">
              <!-- Seccion buscador -->
              <div class="row">
                <div class="col-md-6 col-sm-12 col-xs-12">
                  <button id="reload" type="button" class="btn btn-info"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                </div>
                <div class="col-md-6 col-sm-12 col-xs-12">
                  <input placeholder="Buscar" type="search" :autofocus="'autofocus'" class="form-control btn-round" v-model="search.text" @keyup="searchDetalleStatus" name="search">
                </div>
              </div>
              <br>
              <!-- /. Seccion buscador-->
              <!-- Tabla de datos -->
              <table class="table table-striped responsive-utilities jambo_table bulk_action">
                <thead class="text-white bg-dark" >
                  <th class="text-white">Cliente </th>
                  <th class="text-white">Número de parte </th>
                  <th class="text-white">Total pallets </th>
                  <th class="text-white">Total cajas </th>
                  <th class="text-white">Opción </th>
                </thead>
                <tbody>
                  <tr v-for="row in detallestatus" class="table-default">
                    <td>{{row.nombrecliente}} </td>
                    <td>{{row.numeroparte}} </td>
                    <td>{{row.totalpallet}}</td>
                    <td>{{row.totalcajas}} </td>
                    <td>
                      <a href="javascript:void(0)" v-bind:href="'detallepallet/'+ row.idparte" class="btn btn-icons btn-rounded btn-success btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> Ver detalles</a>
                      <!--<a href="javascript:void(0)"  class="btn btn-icons btn-rounded btn-success btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> Ver detalles</a>-->
                    </td>
                  </tr>
                  <tr v-if="emptyResult">
                    <td colspan="9" rowspan="4" class="text-center h4">No encontrado</td>
                  </tr>
                </tbody>
                <!-- Paginacion -->
                <tfoot>
                  <tr>
                    <td colspan="8" align="center">
                      <pagination
                      :current_page="currentPage"
                      :row_count_page="rowCountPage"
                      @page-update="pageUpdate"
                      :total_users="totalDetalleStatus"
                      :page_range="pageRange">
                    </pagination>
                  </td>
                </tr>
              </tfoot>
              <!-- /. Paginacion -->
            </table>
            <!-- /. Tabla de datos -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
<script src="https://momentjs.com/downloads/moment-with-locales.min.js"></script>-->
<script src="<?php echo base_url();?>/assets/js/appvue/appalmacen.js"></script>
