<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Parte extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (!isset($_SESSION['user_id'])) {
            $this->session->set_flashdata('flash_data', 'You don\'t have access! ss');
            return redirect('Login');
        }

        $this->load->helper('url');
        $this->load->model('data_model');
        $this->load->model('parte_model', 'parte');
        $this->load->model('user_model', 'usuario');
        $this->load->model('linea_model', 'linea');
        $this->load->model('palletcajas_model', 'palletcajas');
        $this->load->model('palletcajasproceso_model', 'palletcajasproceso');
          $this->load->model('modelo_model', 'modelo');
           $this->load->model('revision_model', 'revision');
           $this->load->model('cantidad_model', 'cantidad');
        $this->load->library('permission');
        $this->load->library('session');
    }

    public function set_barcode($code) {
        Permission::grant(uri_string());
        //load library
        $this->load->library('zend');
        //load in folder Zend
        $this->zend->load('Zend/Barcode');
        //generate barcode
        $file = Zend_Barcode::draw('code128', 'image', array('text' => $code,  'factor'=>1.5,'stretchText'=>true), array());
        $code = time();
        $barcodeRealPath = $_SERVER['DOCUMENT_ROOT'] . '/sisproduction/assets/cache/' . $code . '.png';

        // header('Content-Type: image/png');
        $store_image = imagepng($file, $barcodeRealPath);
        return base_url() . 'assets/cache/' . $code . '.png';
    }

    public function etiquetaCalidad($id, $idpalletcajas, $cajas) {
        Permission::grant(uri_string());
        $detalle = $this->parte->detalleDelDetallaParte($id);
        $lista = $this->parte->cantidadesPartes($id);
        $datausuario = $this->usuario->detalleUsuario($this->session->user_id);

        $hora = date("h:i:s a");
        $fecha = date("d-M-Y");
        $semana = date("W");
        $mes = date("F");
        $this->load->library('html2pdf');
        ob_start();
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);

        $mipdf = new HTML2PDF('L', 'Letter', 'es', 'true', 'UTF-8');
        $mipdf->pdf->SetDisplayMode('fullpage');
        $mipdf->writeHTML('<page  format="130x182" >
    <style type="text/css">
			table {border-collapse:collapse}
			td
				{
					border:0px solid black
				}
	</style>
	<br>
    <table border="1" align="center">
		<tr>
			<td   width="320" height="75" style="font-size:40px; font-family:arial; font-weight:bold; background: #; color:#fff; " rowspan="2" >OQC Passed</td>
			<td  width="315" align="center" style="font-size:20px; font-family:arial; font-weight:bold; background: ; color:#fff;  " >CUSTOMERS</td>
		</tr>

		<tr>

			<td  align="center"    style="font-size:50px; font-family:arial; font-weight:bold; background: #; " >' . $detalle->nombre . '</td>
		</tr>
		<tr>
			<td  align="" height="60px" style="font-size:60px; font-family:arial; font-weight:bold; background: #; color:#fff;" >PART NO</td>
			<td  align="" style="font-size:20px; font-family:arial; font-weight:bold; background: #; color:#fff;" >QUANTITY</td>
		</tr>
		<tr>
			<td  align="center" height="50px" style="font-size:30px; font-family:arial; font-weight:bold; background: #; " >' . $detalle->numeroparte . '</td>
			<td  align="center" style="font-size:50px; font-family:arial; font-weight:bold; background: #; " >' . $cajas . '</td>
		</tr>
		<tr>
			<td  align="" height="50" style="font-size:20px; font-family:arial; font-weight:bold; background: #;color:#fff; " >MODEL</td>
			<td  align="" style="font-size:20px; font-family:arial; font-weight:bold; background: #;color:#fff; " >DATE</td>
		</tr>
		<tr>
			<td  align="center" height="40" style="font-size:40px; font-family:arial; font-weight:bold; background: #; " >' . $detalle->modelo . '</td>
			<td  align="center" style="font-size:35px; font-family:arial; font-weight:bold; background: #; " >' . $fecha . '</td>
		</tr>
		<tr>
			<td  align="" height="50" style="font-size:20px; font-family:arial; font-weight:bold; background: #; color:#fff; " >OQC INSPECTOR</td>
			<td  align="center" style="font-size:25px; font-family:arial; font-weight:bold; background: #; " ></td>
		</tr>
		<tr>
			<td  align="center" height="60" style="font-size:50px; font-family:arial; font-weight:bold; background: #;vertical-align:bottom; " >' . $datausuario->usuario . '</td>
			<td  align="center" style="font-size:30px; font-family:arial; font-weight:bold; background: #; " ></td>
		</tr>



	</table>

</page>');

        //$mipdf->pdf->IncludeJS('print(TRUE)');
        $mipdf->Output(APPPATH . 'pdfs\\' . 'Calidad' . date('Ymdgisv') . '.pdf', 'F');
        $mipdf->Output('Etiqueta_Calidad.pdf');
    }

    public function imprimirEtiquetaCalidad($id, $idpalletcajas, $cajas) {
        Permission::grant(uri_string());
        $detalle = $this->parte->detalleDelDetallaParte($id);
        $lista = $this->parte->cantidadesPartes($id);
        $datausuario = $this->usuario->detalleUsuario($this->session->user_id);

        $hora = date("h:i:s a");
        $fecha = date("d-M-Y");
        $semana = date("W");
        $mes = date("F");
        $this->load->library('html2pdf');
        ob_start();
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);

        $mipdf = new HTML2PDF('L', 'Letter', 'es', 'true', 'UTF-8');
        $mipdf->pdf->SetDisplayMode('fullpage');
        $mipdf->writeHTML('<page  format="130x182" >
    <style type="text/css">
			table {border-collapse:collapse}
			td
				{
					border:0px solid black
				}
	</style>
	<br>
    <table border="1" align="center">
		<tr>
			<td   width="320" height="75" style="font-size:40px; font-family:arial; font-weight:bold; background: #; color:#fff; " rowspan="2" >OQC Passed</td>
			<td  width="315" align="center" style="font-size:20px; font-family:arial; font-weight:bold; background: ; color:#fff;  " >CUSTOMERS</td>
		</tr>

		<tr>

			<td  align="center"    style="font-size:50px; font-family:arial; font-weight:bold; background: #; " >' . $detalle->nombre . '</td>
		</tr>
		<tr>
			<td  align="" height="60px" style="font-size:60px; font-family:arial; font-weight:bold; background: #; color:#fff;" >PART NO</td>
			<td  align="" style="font-size:20px; font-family:arial; font-weight:bold; background: #; color:#fff;" >QUANTITY</td>
		</tr>
		<tr>
			<td  align="center" height="50px" style="font-size:30px; font-family:arial; font-weight:bold; background: #; " >' . $detalle->numeroparte . '</td>
			<td  align="center" style="font-size:50px; font-family:arial; font-weight:bold; background: #; " >' . $cajas . '</td>
		</tr>
		<tr>
			<td  align="" height="50" style="font-size:20px; font-family:arial; font-weight:bold; background: #;color:#fff; " >MODEL</td>
			<td  align="" style="font-size:20px; font-family:arial; font-weight:bold; background: #;color:#fff; " >DATE</td>
		</tr>
		<tr>
			<td  align="center" height="40" style="font-size:40px; font-family:arial; font-weight:bold; background: #; " >' . $detalle->modelo . '</td>
			<td  align="center" style="font-size:35px; font-family:arial; font-weight:bold; background: #; " >' . $fecha . '</td>
		</tr>
		<tr>
			<td  align="" height="50" style="font-size:20px; font-family:arial; font-weight:bold; background: #; color:#fff; " >OQC INSPECTOR</td>
			<td  align="center" style="font-size:25px; font-family:arial; font-weight:bold; background: #; " ></td>
		</tr>
		<tr>
			<td  align="center" height="60" style="font-size:50px; font-family:arial; font-weight:bold; background: #;vertical-align:bottom; " >' . $datausuario->usuario . '</td>
			<td  align="center" style="font-size:30px; font-family:arial; font-weight:bold; background: #; " ></td>
		</tr>



	</table>

</page>');

        //$mipdf->pdf->IncludeJS('print(TRUE)');
        $nombrepdf = APPPATH . 'pdfs\\' . 'Calidad' . date('Ymdgisv') . '.pdf';
        $mipdf->Output($nombrepdf, 'F');
        $cmd = "C:\\Program Files (x86)\\Adobe\\Acrobat Reader DC\\Reader\\AcroRd32.exe /t \"$nombrepdf\" \"HP Officejet Pro 8600 (Red)\"";
        echo $cmd;
    }
    public function index() {
         Permission::grant(uri_string());
           $this->load->view('header');
        $this->load->view('parte/index');
        $this->load->view('footer');
    }
    public function modelo($idparte) {
        Permission::grant(uri_string());
         $datamodelo = $this->modelo->showAll($idparte);
        $data=array(
            'datamodelo'=>$datamodelo,
            'idparte'=>$idparte
        );
        $this->load->view('header');
        $this->load->view('modelo/index',$data);
        $this->load->view('footer');
    }
    public function revision($idmodelo) {
        Permission::grant(uri_string());
          $datarevision = $this->revision->showAll($idmodelo);

        $data=array(
            'datarevision'=>$datarevision,
            'idmodelo'=>$idmodelo
        );
        $this->load->view('header');
        $this->load->view('revision/index',$data);
        $this->load->view('footer');
    }
    public function cantidad($idrevision) {
        Permission::grant(uri_string());
         $datacantidad = $this->cantidad->showAll($idrevision);

        $data=array(
            'datacantidad'=>$datacantidad,
            'idrevision'=>$idrevision
        );
        $this->load->view('header');
        $this->load->view('cantidad/index',$data);
        $this->load->view('footer');
    }
    public function parteadmin() {
        Permission::grant(uri_string());
        $this->load->view('header');
        $this->load->view('catSistema/parte/index');
        $this->load->view('footer');
    }



    public function etiquetaPacking($id, $idpalletcajas) {
         Permission::grant(uri_string());
        date_default_timezone_set("America/Tijuana");
        $detalle = $this->parte->detalleDelDetallaParte($id);
        //var_dump($detalle);
        $detallepallet = $this->palletcajas->detallePalletCajas($idpalletcajas);
        $lista = $this->parte->cantidadesPartes($id);
        $totalcajas = $detallepallet->cajas;
        $totalpallet=0;
        foreach ($lista as $value) {
            $totalpallet++;
        }

        //$codigo = $detalle->codigo;
        $barcode = $this->set_barcode($detalle->codigo."_".$detalle->folio."_".$totalcajas);
        $hora = date("h:i a");
        $fecha = date("j/n/Y");
        $dia = date("j");
        $semana = date("W");
        $mes = date("F");
        $this->load->library('html2pdf');
        ob_start();


        $mipdf = new HTML2PDF('L', 'Letter', 'es', 'true', 'UTF-8');
        $mipdf->pdf->SetDisplayMode('fullpage');
        $mipdf->writeHTML('<page  format="400x165"  >
 <style type="text/css">
            table {border-collapse:collapse}
            td
                {
                    border:0px  solid black;
                }
    </style>

    <br>
    <table border="1" align="center">
        <tr>
            <td  align="center" height="45" width="200"  style="font-size:35px; font-family:arial; font-weight:bold; background: #fff; color:#fff; " colspan="" >Customer</td>
            <td  align="center" width="220"  style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#fff; " colspan="" ></td>
            <td  align="center" width="220"  style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#fff;" colspan="">Pallet Quatity</td>
            <td  align="center" width="220"  style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#fff;" colspan=""></td>
            <td  align="center" width="220"  style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#fff;" colspan="">Month</td>
            <td  align="center" width="220"  style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#fff;" colspan="">Week</td>

        </tr>

        <tr>
            <td align="center"  height="90"   valign="bottom" style="font-size:50px; font-family:arial; font-weight:bold;  " colspan="2"><b>' . $detalle->nombre . '</b></td>


            <td align="center" width="250"  style="font-size:80px; font-family:arial; font-weight:bold;  " colspan=""><b>' . $totalcajas . '</b></td>

            <td align="center" style="font-size:60px; font-family:arial; vertical-align: top;  font-weight:bold;  " colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;' . $mes . '&nbsp;' . $dia . '</td>

            <td align="center" style="font-size:90px; font-family:arial; font-weight:bold;  " colspan="" valign="bottom" >' . $semana . '</td>

        </tr>

        <tr>
            <td  align="center" width=""  height=""  style="font-size:30px; font-family:arial; font-weight:bold; background: #; color:#fff; "  rowspan="" ></td>
            <td  align="center" width=""style="font-size:30px; font-family:arial; font-weight:bold; background: #; color:#fff;" colspan=""></td>
            <td  align="center" width=""  style="font-size:30px; font-family:arial; font-weight:bold; background: #; color:#fff; "  rowspan="" ></td>
            <td  align="center" width=""style="font-size:30px; font-family:arial; font-weight:bold; background: #; color:#fff;" colspan=""></td>
            <td  align="left" valign="top" style="font-size:35px; font-family:arial; font-weight:bold; background: #fff; color:#000;" colspan="2"> &nbsp; ' . $hora . ' </td>

        </tr>

        <tr>
            <td  align="center" width="" height="50"  style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#fff; "  colspan="3" >Part Number</td>
            <td  align="center" width=""style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#fff;" colspan="3">Model Name</td>
        </tr>

        <tr>
        <td colspan="3" rowspan="2" align="center"  style="font-size:25px;  font-family:arial; font-weight:bold; overflow:auto; height:120px; "  >' . $detalle->numeroparte . ' <br><img src="' . $barcode . '" /> </td>
        <td height="60" colspan="3" align="center"  style="font-size:60px; font-family:arial; vertical-align: top;  font-weight:bold; overflow:auto;" > &nbsp; &nbsp; &nbsp;' . $detalle->modelo . '</td>

        </tr>

        <tr>
        <td align="" height="" style="font-size:25px; font-family:arial; font-weight:bold;  " >&nbsp;</td>
        <td align="center"  style="font-size:30px; font-family:arial; font-weight:bold; overflow:auto; background: #fff; color:#fff; " >Rev No.</td>
        <td align="center"  style="font-size:30px; font-family:arial; font-weight:bold; overflow:auto;background: #fff; color:#fff; "  >Pallet No.</td>
        </tr>

        <tr>
            <td  align="center" width=""  style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#fff; " colspan="" rowspan="2">ROHS</td>
            <td  align="center" height="70"width=""style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#fff;"    colspan="">Line No</td>
            <td  align="center" width=""style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#fff;"    colspan="">Prod.</td>
            <td  align="center" width=""style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#fff;"    colspan="">W/H</td>
            <td align="center" valign="bottom" style="font-size:50px; font-family:arial; vertical-align: ;font-weight:bold; padding-top:15px; " colspan="">' . $detalle->revision . '</td>
            <td align="center" valign="bottom" style="font-size:50px; font-family:arial; font-weight:bold; padding-top:15px; " colspan="">1</td>
        </tr>
        <tr>
            <td  align="center" height="60" width=""style="font-size:50px; font-family:arial; font-weight:bold; background: #fff; color:#000;padding-top:15px;"    colspan="">' . $detalle->nombrelinea . '</td>
            <td  align="center" width=""style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#000;"    colspan=""></td>
            <td  align="center" width=""style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#000;"    colspan=""></td>
            <td align="center" style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#fff;"  colspan=""></td>
            <td align="center" style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#fff;"  colspan="">WOORI USA</td>
        </tr>

    </table>
</page>
');

        //$mipdf->pdf->IncludeJS('print(TRUE)');
          $mipdf->Output(APPPATH . 'pdfs\\' . 'Packing' . date('Ymdgisv') . '.pdf', 'F');
          $mipdf->Output('Etiqueta_Packing.pdf');
    }

    public function imprimirEtiquetaPacking() {
        Permission::grant(uri_string());
        $id = $this->input->post('iddetalleparte');
        $idpalletcajas = $this->input->post('idpalletcajas');
        date_default_timezone_set("America/Tijuana");
        $detalle = $this->parte->detalleDelDetallaParte($id);

        $detallepallet = $this->palletcajas->detallePalletCajas($idpalletcajas);

        $lista = $this->parte->cantidadesPartes($id);
        $totalpallet=0;
        foreach ($lista as $value) {
            $totalpallet++;
        }
        $totalcajas = $detallepallet->cajas;
        $codigo = $detalle->codigo;
        $barcode = $this->set_barcode($codigo . "_" . $totalcajas);
        $hora = date("h:i a");
        $fecha = date("j/n/Y");
        $dia = date("j");
        $semana = date("W");
        $mes = date("F");
        $this->load->library('html2pdf');
        ob_start();
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);

        $mipdf = new HTML2PDF('L', 'Letter', 'es', 'true', 'UTF-8');
        $mipdf->pdf->SetDisplayMode('fullpage');
        $mipdf->writeHTML('<page  format="400x165"  >
 <style type="text/css">
            table {border-collapse:collapse}
            td
                {
                    border:0px  solid black;
                }
    </style>

    <br>
    <table border="0" align="center">
        <tr>
            <td  align="center" height="45" width="200"  style="font-size:35px; font-family:arial; font-weight:bold; background: #fff; color:#fff; " colspan="" >Customer</td>
            <td  align="center" width="220"  style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#fff; " colspan="" ></td>
            <td  align="center" width="220"  style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#fff;" colspan="">Pallet Quatity</td>
            <td  align="center" width="220"  style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#fff;" colspan=""></td>
            <td  align="center" width="220"  style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#fff;" colspan="">Month</td>
            <td  align="center" width="220"  style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#fff;" colspan="">Week</td>

        </tr>

        <tr>
            <td align="center"  height="90"   valign="bottom" style="font-size:85px; font-family:arial; font-weight:bold;  " colspan="2"><b>' . $detalle->nombre . '</b></td>


            <td align="center" width="250"  style="font-size:80px; font-family:arial; font-weight:bold;  " colspan=""><b>' . $totalcajas . '</b></td>

            <td align="center" style="font-size:60px; font-family:arial; vertical-align: top;  font-weight:bold;  " colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;' . $mes . '&nbsp;' . $dia . '</td>

            <td align="center" style="font-size:90px; font-family:arial; font-weight:bold;  " colspan="" valign="bottom" >' . $semana . '</td>

        </tr>

        <tr>
            <td  align="center" width=""  height=""  style="font-size:30px; font-family:arial; font-weight:bold; background: #; color:#fff; "  rowspan="" ></td>
            <td  align="center" width=""style="font-size:30px; font-family:arial; font-weight:bold; background: #; color:#fff;" colspan=""></td>
            <td  align="center" width=""  style="font-size:30px; font-family:arial; font-weight:bold; background: #; color:#fff; "  rowspan="" ></td>
            <td  align="center" width=""style="font-size:30px; font-family:arial; font-weight:bold; background: #; color:#fff;" colspan=""></td>
            <td  align="left" valign="top" style="font-size:35px; font-family:arial; font-weight:bold; background: #fff; color:#000;" colspan="2"> &nbsp; ' . $hora . ' </td>

        </tr>

        <tr>
            <td  align="center" width="" height="50"  style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#fff; "  colspan="3" >Part Number</td>
            <td  align="center" width=""style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#fff;" colspan="3">Model Name</td>
        </tr>

        <tr>
        <td colspan="3" rowspan="2" align="center"  style="font-size:30px;  font-family:arial; font-weight:bold; overflow:auto; height:120px; "  >' . $detalle->numeroparte . ' <img style="width:400px;" src="' . $barcode . '"/> </td>
        <td height="60" colspan="3" align="center"  style="font-size:60px; font-family:arial; vertical-align: top;  font-weight:bold; overflow:auto;" > &nbsp; &nbsp; &nbsp;' . $detalle->modelo . '</td>

        </tr>

        <tr>
        <td align="" height="" style="font-size:25px; font-family:arial; font-weight:bold;  " >&nbsp;</td>
        <td align="center"  style="font-size:30px; font-family:arial; font-weight:bold; overflow:auto; background: #fff; color:#fff; " >Rev No.</td>
        <td align="center"  style="font-size:30px; font-family:arial; font-weight:bold; overflow:auto;background: #fff; color:#fff; "  >Pallet No.</td>
        </tr>

        <tr>
            <td  align="center" width=""  style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#fff; " colspan="" rowspan="2">ROHS</td>
            <td  align="center" height="70"width=""style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#fff;"    colspan="">Line No</td>
            <td  align="center" width=""style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#fff;"    colspan="">Prod.</td>
            <td  align="center" width=""style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#fff;"    colspan="">W/H</td>
            <td align="center" valign="bottom" style="font-size:50px; font-family:arial; vertical-align: ;font-weight:bold; padding-top:15px; " colspan="">' . $detalle->revision . '</td>
            <td align="center" valign="bottom" style="font-size:50px; font-family:arial; font-weight:bold; padding-top:15px; " colspan="">' . $totalpallet . '</td>
        </tr>
        <tr>
            <td  align="center" height="60" width=""style="font-size:50px; font-family:arial; font-weight:bold; background: #fff; color:#000;padding-top:15px;"    colspan="">' . $detalle->nombrelinea . '</td>
            <td  align="center" width=""style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#000;"    colspan=""></td>
            <td  align="center" width=""style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#000;"    colspan=""></td>
            <td align="center" style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#fff;"  colspan=""></td>
            <td align="center" style="font-size:30px; font-family:arial; font-weight:bold; background: #fff; color:#fff;"  colspan="">WOORI USA</td>
        </tr>

    </table>
</page>
');

        //$mipdf->pdf->IncludeJS('print(TRUE)');
        $nombrepdf = APPPATH . 'pdfs\\' . 'Packing' . date('Ymdgisv') . '.pdf';
        $mipdf->Output($nombrepdf, 'F');
        $cmd = "C:\\Program Files (x86)\\Adobe\\Acrobat Reader DC\\Reader\\AcroRd32.exe /t \"$nombrepdf\" \"HP Officejet Pro 8600 (Red)\"";
        echo $cmd;
        //$mipdf->Output('Etiqueta_Packing.pdf');
    }

    public function generarPDFEnvio($id) {
        Permission::grant(uri_string());
        $this->load->library('tcpdf');
        $listapartes = $this->parte->palletReporte($id);
        $lista = $this->parte->cantidadesPartes($id);
        $totalpallet = 0;
        $totalcajas = 0;
        if ($lista != false) {

            foreach ($lista as $value) {
                $totalpallet++;
                $totalcajas = $totalcajas + $value->cajas;
            }
        }


        $detalle = $this->parte->detalleDelDetallaParte($id);
        $operador = $detalle->nombreoperador;
        $horario = $detalle->horainicial . " - " . $detalle->horafinal;
        $linkimge = base_url() . '/assets/images/woorilogo.png';
        $fechaactual = date('d/m/Y');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Generar documento de envio.');
        $pdf->SetHeaderMargin(30);
        $pdf->SetTopMargin(20);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(true);
        $pdf->SetAuthor('Author');
        $pdf->SetDisplayMode('real', 'default');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->AddPage();

        $tbl = '
<style type="text/css">
.textgeneral{
	font-size:8px;
	color:#000;
	font-weight:bold;
	font-family:Verdana, Geneva, sans-serif
	}
	.textfooter{
	font-size:8px;
	color:#000;
	font-weight:bold;
	font-family:Verdana, Geneva, sans-serif
	}

.lineabajo{
	border-bottom:solid 1px #000000;
	}
.imgtitle{ width:120px;}

</style>

<table width="536"   cellpadding="1" cellspacing="1" >
  <tr>
    <td align="center" class="textgeneral"><center><img  align="center" class="imgtitle" src="' . $linkimge . '"; /></center></td>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td width="234" align="center" class="textgeneral"><strong>Transferencia de producto terminado</strong></td>
    <td width="22">&nbsp;</td>
    <td width="96">&nbsp;</td>
    <td width="100" align="center" style="border-left:solid 1px #000000; border-right:solid 1px #000000; border-top:solid 1px #000"><p class="textgeneral">TRANSFERENCIA NÚMERO</p></td>
    <td width="82" align="center" style="border-top:solid 1px #000000; border-right:solid #000 1px">' . $detalle->folio . '</td>
  </tr>
  <tr>
    <td class="textgeneral lineabajo">FECHA: ' . $fechaactual . '</td>
    <td>&nbsp;</td>
      <td colspan="3" align="center" class="textgeneral" style="border-top:solid 1px #000; border-right:solid 1px #000; border-left:solid 1px #000; border-bottom:solid 1px #000;">PRODUCCIÓN</td>
  </tr>
  <tr>
    <td class="textgeneral lineabajo">HORA: ' . $horario . '</td>
    <td>&nbsp;</td>
    <td colspan="3" class="textgeneral lineabajo">HECHA POR: ' . $detalle->name . '</td>
  </tr>
  <tr>
    <td class="textgeneral lineabajo">TURNO: ' . $detalle->nombreturno . '</td>
    <td>&nbsp;</td>
    <td colspan="3" class="textgeneral lineabajo">RECIBIDA POR: ' . $operador . '</td>
  </tr>
</table>
<br><br>
<table width="536"  style="margin-top:10px" cellpadding="1" cellspacing="1">
  <tr class="textgeneral">
    <td width="58" align="center" valign="middle" style="border:solid 1px #000000">CLIENTE</td>
    <td width="125" align="center" valign="middle"  style="border-top:solid 1px #000000; border-bottom:solid 1px #000000; border-right:solid 1px #000000;">NUM. PARTE</td>
    <td width="52" align="center" valign="middle" style="border-top:solid 1px #000000; border-bottom:solid 1px #000000; border-right:solid 1px #000000;">MODELO</td>
    <td width="66" align="center" valign="middle" style="border-top:solid 1px #000000; border-bottom:solid 1px #000000; border-right:solid 1px #000000;">CANTIDAD POR PALLET</td>
    <td width="67" align="center" valign="middle" style="border-top:solid 1px #000000; border-bottom:solid 1px #000000; border-right:solid 1px #000000;">TOTAL DE PALLET</td>
    <td width="66" align="center" valign="middle" style="border-top:solid 1px #000000; border-bottom:solid 1px #000000; border-right:solid 1px #000000;">CANTIDAD TOTAL</td>
    <td width="100" align="center" valign="middle" style="border-top:solid 1px #000000; border-bottom:solid 1px #000000; border-right:solid 1px #000000;">ALMACEN VERIFICACIÓN</td>
  </tr>
  ';
        foreach ($listapartes as $value) {
            $tbl .= '<tr>
    <td style="border-left:solid 1px
    #000000; border-bottom:solid 1px #000; font-size:8px; border-right:solid 1px #000;">&nbsp;' . $value->nombre . '</td>
    <td style="border-bottom:solid 1px #000; font-size:8px;  border-right:solid 1px #000;">&nbsp;' . $value->numeroparte . '</td>
    <td style="border-bottom:solid 1px #000; font-size:8px;  border-right:solid 1px #000;">&nbsp;' . $value->modelo . '</td>
    <td style="border-bottom:solid 1px #000; font-size:8px; border-right:solid 1px #000;">&nbsp;' . number_format($value->cajasporpallet) . '</td>
    <td style="border-bottom:solid 1px #000; font-size:8px; border-right:solid 1px #000;">&nbsp;' . number_format($value->totalpallet) . '</td>
    <td style="border-bottom:solid 1px #000; font-size:8px; border-right:solid 1px #000;">&nbsp;' . number_format($value->totalcajas) . '</td>
    <td style="border-bottom:solid 1px #000; font-size:8px; border-right:solid 1px #000;">&nbsp;</td>
  </tr>';
        }
        $tbl .= '<tr>
    <td style="border-left:solid 1px
    #000000; border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
  </tr>
    <tr>
    <td style="border-left:solid 1px
    #000000; border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
  </tr>
    <tr>
    <td style="border-left:solid 1px
    #000000; border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
  </tr>
    <tr>
    <td style="border-left:solid 1px
    #000000; border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
  </tr>
    <tr>
    <td style="border-left:solid 1px
    #000000; border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
  </tr>
  <tr>
    <td style="border-left:solid 1px
    #000000; border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
  </tr>
    <tr>
    <td style="border-left:solid 1px
    #000000; border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
  </tr>
    <tr>
    <td style="border-left:solid 1px
    #000000; border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
  </tr>
    <tr>
    <td style="border-left:solid 1px
    #000000; border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
  </tr>
    <tr>
    <td style="border-left:solid 1px
    #000000; border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
  </tr>
    <tr>
    <td style="border-left:solid 1px
    #000000; border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
  </tr>
    <tr>
    <td style="border-left:solid 1px
    #000000; border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
  </tr>
    <tr>
    <td style="border-left:solid 1px
    #000000; border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
  </tr>
    <tr>
    <td style="border-left:solid 1px
    #000000; border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
  </tr>
    <tr>
    <td style="border-left:solid 1px
    #000000; border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
  </tr>
    <tr>
    <td style="border-left:solid 1px
    #000000; border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
  </tr>
      <tr>
    <td style="border-left:solid 1px
    #000000; border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
  </tr>
    <tr>
    <td style="border-left:solid 1px
    #000000; border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
  </tr>
    <tr>
    <td style="border-left:solid 1px
    #000000; border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
  </tr>

    <tr>
    <td style="border-left:solid 1px
    #000000; border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
  </tr>
    <tr>
    <td style="border-left:solid 1px
    #000000; border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
  </tr>
    <tr>
    <td style="border-left:solid 1px
    #000000; border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
  </tr>
    <tr style=" background-color:#EAEAEA">
    <td style=" border-left:solid 1px
    #000000; border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
    <td class="textfooter" style="border-bottom:solid 1px #000; border-right:solid 1px #000;">TOTAL:</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000; font-size:9px; margin-top:20px;">&nbsp;' . $totalpallet . ' </td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000; font-size:9px;">&nbsp;' . number_format(($totalcajas / $totalpallet) * ($totalpallet)) . '</td>
    <td style="border-bottom:solid 1px #000; border-right:solid 1px #000;">&nbsp;</td>
  </tr>
 <tr>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td>&nbsp; </td>
    <td >&nbsp;</td>
    <td colspan="2" align="right" class="textfooter" >WBKP-PR-FO-007</td>
  </tr>
 <tr>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td>&nbsp; </td>
    <td >&nbsp;</td>
    <td colspan="2" align="right" class="textfooter" >Rev. 01</td>
  </tr>
    <tr>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td>&nbsp; </td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="textfooter" >Inspección 100% por:</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="textfooter"style="border-right:solid 1px #000; border-left:solid 1px #000; border-top:solid 1px #000; padding-left:5px;"  >&nbsp;&nbsp;RESPONSABLE DE PACKING</td>
    <td colspan="4" class="lineabajo" >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="textfooter" style="border-right:solid 1px #000; border-left:solid 1px #000; border-top:solid 1px #000; padding-left:5px;" >&nbsp;&nbsp;INSPECTOR OQC</td>
    <td colspan="4" class="lineabajo" >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
    <tr>
    <td colspan="2" class="textfooter" style="border-right:solid 1px #000; border-left:solid 1px #000; border-top:solid 1px #000; padding-left:5px;" >&nbsp;&nbsp;RESPONSABLE DE ALMACEN</td>
    <td colspan="4" class="lineabajo" >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
    <tr>
    <td colspan="2" class="textfooter" style="border-bottom:solid 1px #000; border-top:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000; padding-left:5px;" >&nbsp;&nbsp;2DA INSTAPECCION EXTERNA</td>
    <td colspan="4" class="lineabajo" >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
    <tr>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td colspan="4" align="center" class="textfooter" >NOMBRE/FIRMA</td>
    <td >&nbsp;</td>
  </tr>

  </table>
 ';

        $pdf->writeHTML($tbl, true, false, false, false, '');

        ob_end_clean();


        $pdf->Output('My-File-Name.pdf', 'I');
    }

    public function packing($id) {
        Permission::grant(uri_string());
        $usuarioscalidad = $this->usuario->showAllCalidad();
        $detalleparte = $this->parte->detalleParteId($id);
        $detallelinea = $this->linea->showAllLinea();

        $data = array(
            'usuarioscalidad' => $usuarioscalidad,
            'detalleparte' => $detalleparte,
            'lineas' => $detallelinea,
            'idparte' => $id
        );

        $this->load->view('header');
        $this->load->view('parte/packing', $data);
        $this->load->view('footer');
    }

    public function detalleenvio($iddetalle) {
        Permission::grant(uri_string());
        $usuarioscalidad = $this->usuario->showAllCalidad();
        $listalinea = $this->linea->showAllLinea();
        $detalledeldetalleparte = $this->parte->detalleDelDetallaParte($iddetalle);
        $palletcajas = $this->palletcajas->showAllId($iddetalle);
        $dataerror = $this->parte->motivosCancelacionCalidad($iddetalle);
        //var_dump($palletcajas);
        $data = array(
            'iddetalle' => $iddetalle,
            'detalle' => $detalledeldetalleparte,
            'usuarioscalidad' => $usuarioscalidad,
            'palletcajas' => $palletcajas,
            'lineas' => $listalinea,
            'dataerrores' => $dataerror
        );

        //var_dump($detalledeldetalleparte);
        $this->load->view('header');
        $this->load->view('parte/detalleenviado', $data);
        $this->load->view('footer');
    }

    public function quitarPalletCajas($idpalletcaja, $iddetalleparte) {
        Permission::grant(uri_string());
        $this->palletcajas->eliminarPalletCajas($idpalletcaja);
        redirect('Parte/detalleenvio/' . $iddetalleparte);
    }

    public function agregarPalletCajas() {
        Permission::grant(uri_string());
        $data = array(
            'iddetalleparte' => $this->input->post('iddetalleparte'),
            'pallet' => 1,
            'cajas' => $this->input->post('numerocajas'),
            'idestatus' => 1,
            'idusuario' => $this->session->user_id,
            'fecharegistro' => date('Y-m-d H:i:s')
        );
        $idpalletcajas = $this->palletcajas->addPalletCajas($data);

        $dataproceso = array(
            'idpalletcajas' => $idpalletcajas,
            'idestatus' => 1,
            'idusuario' => $this->session->user_id,
            'fecharegistro' => date('Y-m-d H:i:s')
        );
        $this->palletcajasproceso->addPalletCajasProceso($dataproceso);
        redirect('Parte/detalleenvio/' . $this->input->post('iddetalleparte'));
    }

    public function modificarTransferencia() {
        Permission::grant(uri_string());
        $iddetalleparte = $this->input->post('iddetalleparte');
        $data = array(
            'modelo' => $this->input->post('modelo'),
            'revision' => $this->input->post('revision'),
            'idlinea' => $this->input->post('linea'),
            'idusuario' => $this->session->user_id,
            'fecharegistro' => date('Y-m-d H:i:s')
        );

        $this->parte->updateDetalleParte($iddetalleparte, $data);
        redirect('Parte/detalleenvio/' . $iddetalleparte);
    }

    public function reenviarCalidad() {
        Permission::grant(uri_string());
        $iddetalleparte = $this->input->post('iddetalleparte');
        $data = array(
            'modelo' => $this->input->post('modelo'),
            'revision' => $this->input->post('revision'),
            'idlinea' => $this->input->post('linea'),
            'idusuario' => $this->session->user_id,
            'fecharegistro' => date('Y-m-d H:i:s')
        );

        $this->parte->updateDetalleParte($iddetalleparte, $data);
        $ids = $this->input->post('id');
        foreach ($ids as $value) {
            $dataupdate = array(
                'idestatus' => 1,
                'idusuario' => $this->session->user_id,
                'fecharegistro' => date('Y-m-d H:i:s')
            );
            $this->parte->updatePalletCajas($value, $dataupdate);
        }

        foreach ($ids as $value) {
            $dataestatus = array(
                'idpalletcajas' => $value,
                'idestatus' => 1,
                'idusuario' => $this->session->user_id,
                'fecharegistro' => date('Y-m-d H:i:s')
            );
            $this->palletcajasproceso->addPalletCajasProceso($dataestatus);
        }
    }

    public function enviarCalidadNew() {
        Permission::grant(uri_string());
        $id = $this->input->post('idparte');
        if ($this->input->post('cajas') != FALSE) {
            $data = array(
                'folio' => 0,
                'idparte' => $this->input->post('idparte'),
                'modelo' => $this->input->post('modelo'),
                'revision' => $this->input->post('revision'),
                'pallet' => 0,
                'cantidad' => 0,
                'idlinea' => $this->input->post('linea'),
                'idestatus' => 1,
                'idusuario' => $this->session->user_id,
                'fecharegistro' => date('Y-m-d H:i:s')
            );

            $iddetalleparte = $this->parte->addDetalleParte($data);
            $dataupdatefolio = array(
                'folio' => $iddetalleparte
            );
            $this->parte->updateDetalleParte($iddetalleparte, $dataupdatefolio);

            $cajas = $this->input->post("cajas");
            foreach ($this->input->post("pallet") as $index => $code) {
                $datapalletcaja = array(
                    'iddetalleparte' => $iddetalleparte,
                    'pallet' => $code,
                    'cajas' => $cajas[$index],
                    'idestatus' => 1,
                    'idusuario' => $this->session->user_id,
                    'fecharegistro' => date('Y-m-d H:i:s')
                );
                $idpalletcajas = $this->palletcajas->addPalletCajas($datapalletcaja);

                $dataproceso = array(
                    'idpalletcajas' => $idpalletcajas,
                    'idestatus' => 1,
                    'idusuario' => $this->session->user_id,
                    'fecharegistro' => date('Y-m-d H:i:s')
                );
                $this->palletcajasproceso->addPalletCajasProceso($dataproceso);
            }

            redirect('Parte/detalleenvio/' . $iddetalleparte);
        } else {
            $usuarioscalidad = $this->usuario->showAllCalidad();
            $detalleparte = $this->parte->detalleParteId($id);
            $detallelinea = $this->linea->showAllLinea();

            $data = array(
                'usuarioscalidad' => $usuarioscalidad,
                'detalleparte' => $detalleparte,
                'lineas' => $detallelinea,
                'idparte' => $id,
                'error' => 1
            );

            $this->load->view('header');
            $this->load->view('parte/packing', $data);
            $this->load->view('footer');
        }
    }

    public function verEnviados() {
        Permission::grant(uri_string());
        $this->load->view('header');
        $this->load->view('parte/enviados');
        $this->load->view('footer');
    }

    public function showAll() {
        Permission::grant(uri_string());
        $query = $this->parte->showAll();
        if ($query) {
            $result['partes'] = $this->parte->showAll();
        }
        echo json_encode($result);
    }

    public function showAllEnviados() {
        Permission::grant(uri_string());
        $query = $this->parte->showAllEnviados($this->session->user_id);
        if ($query) {
            $result['detallestatus'] = $this->parte->showAllEnviados($this->session->user_id);
        }
        echo json_encode($result);
    }

    public function addPart() {
        Permission::grant(uri_string());
        $config = array(
            array(
                'field' => 'numeroparte',
                'label' => 'Número de parte',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Campo obligatorio.'
                )
            ),
             array(
                'field' => 'idcategoria',
                'label' => 'Categoria',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Campo obligatorio.'
                )
            ),
            array(
                'field' => 'idcliente',
                'label' => 'Cliente',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Campo obligatorio.'
                )
            )
        );

        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() == FALSE) {
            $result['error'] = true;
            $result['msg'] = array(
                'numeroparte' => form_error('numeroparte'),
                'idcategoria' => form_error('idcategoria'),
                'idcliente' => form_error('idcliente')
            );
        } else {
            $idcliente = $this->input->post('idcliente');
            $idcategoria = $this->input->post('idcategoria');
            $numeroparte = trim($this->input->post('numeroparte'));
            $resuldovalidacion = $this->parte->validarClienteParte($idcliente, $numeroparte,$idcategoria);
            if ($resuldovalidacion == FALSE) {
                $data = array(
                    'numeroparte' => strtoupper($this->input->post('numeroparte')),
                    'idcliente' => $this->input->post('idcliente'),
                    'idcategoria' => $this->input->post('idcategoria'),
                    'idusuario' => $this->session->user_id,
                    'activo' => 1,
                    'fecharegistro' => date('Y-m-d H:i:s')
                );
                $this->parte->addParte($data);
            } else {
                $result['error'] = true;
                $result['msg'] = array(
                    'smserror' => "El número parte ya se encuentra registrado."
                );
            }
        }
        echo json_encode($result);
    }

    public function updateParte() {
        Permission::grant(uri_string());
        $config = array(
            array(
                'field' => 'numeroparte',
                'label' => 'Número de parte',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Campo obligatorio.'
                )
            ),
             array(
                'field' => 'idcategoria',
                'label' => 'Categoria',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Campo obligatorio.'
                )
            ),
            array(
                'field' => 'idcliente',
                'label' => 'Cliente',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Campo obligatorio.'
                )
            )
        );

        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() == FALSE) {
            $result['error'] = true;
            $result['msg'] = array(
                'numeroparte' => form_error('numeroparte'),
                'idcategoria' => form_error('idcategoria'),
                'idcliente' => form_error('idcliente')
            );
        } else {
            $idcliente = $this->input->post('idcliente');
            $idcategoria = $this->input->post('idcategoria');
            $numeroparte = trim(strtoupper($this->input->post('numeroparte')));
            $idparte = $this->input->post('idparte');
            $resuldovalidacion = $this->parte->validarClientePartePorIdParte($idparte, $idcliente, $numeroparte,$idcategoria);
            if ($resuldovalidacion == FALSE) {
                $data = array(
                    'numeroparte' => strtoupper($this->input->post('numeroparte')),
                    'idcliente' => $this->input->post('idcliente'),
                    'idcategoria' => $this->input->post('idcategoria'),
                    'idusuario' => $this->session->user_id,
                    'activo' => $this->input->post('activo'),
                    'fecharegistro' => date('Y-m-d H:i:s')
                );
                $this->parte->updateParte($idparte, $data);
            } else {
                $result['error'] = true;
                $result['msg'] = array(
                    'smserror' => "El número de parte ya se encuentra registrado."
                );
            }
        }
        echo json_encode($result);
    }

    public function searchEnviados() {
        Permission::grant(uri_string());
        $value = $this->input->post('text');
        $query = $this->parte->buscarEnviados($value);
        if ($query) {
            $result['detallestatus'] = $query;
        }
        echo json_encode($result);
    }

    public function searchParte() {
        Permission::grant(uri_string());
        $value = $this->input->post('text');
        $query = $this->parte->searchPartes($value);
        if ($query) {
            $result['partes'] = $query;
        }
        echo json_encode($result);
    }

       public function deleteParte()
    {
        # code...
        $idparte = $this->input->get('idparte');
        $query = $this->parte->deleteParte($idparte);
        if ($query) {
            $result['partes'] = true;
        }
        echo json_encode($result);
    }

}

?>
