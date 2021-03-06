<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('America/Tijuana');

class Documentos extends CI_Controller {

  function __construct()
  {
    parent::__construct();

    if (!isset($_SESSION['user_id'])) {
      $this->session->set_flashdata('flash_data', 'You don\'t have access! ss');
      return redirect('Login');
    }

    $this->load->helper('url');
    $this->load->model('documentos_model', 'documentos');
    $this->load->library('permission');
    $this->load->library('session');
  }

  public function specs()
  {
      Permission::grant(uri_string());
    $data = array(
      'data'=>$this->documentos->getAllInfo(),
      'partes'=>$this->documentos->showAllParte()
    );

    $this->load->view('header');
    $this->load->view('documentos/index',$data);
    $this->load->view('footer');
  }
  public function procedimientos()
  {
      Permission::grant(uri_string());
     $data = array(
      'data'=>$this->documentos->getAllProcedimientos(),
      'partes'=>$this->documentos->showAllParte(),
      'tiposdocumento'=>$this->documentos->showAllTipoDocumento(),
      'areas'=>$this->documentos->showAllArea()
    );

    $this->load->view('header');
    $this->load->view('documentos/procedimientos',$data);
    $this->load->view('footer');
  }
public function allModelo()
{
  # code...
   $idparte = $this->input->post("idparte");

   $data = $this->documentos->showAllModelo($idparte);
    $select="";
    if($data != false){
   foreach ($data as  $value) {
     # code...
    $select .= '<option value="'.$value->idmodelo.'">'.$value->descripcion.'</option>';
   }
 }
   echo $select;
}
public function allRevision()
{
  # code...
   $idmodelo = $this->input->post("idmodelo");

   $data = $this->documentos->showAllRevision($idmodelo);
    $select="";
    if($data != false){
   foreach ($data as  $value) {
     # code...
    $select .= '<option value="'.$value->idrevision.'">'.$value->descripcion.'</option>';
   }
 }
   echo $select;
}


public function subir_procedimientos()
{
   if (empty($_FILES['archivo']) ) {
      echo(json_encode(array('status'=>'vacio')));
    } else {
      $content = base64_encode(file_get_contents($_FILES['archivo']['tmp_name']));
      $extension = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);

      $newName = str_replace(' ','_',pathinfo($_FILES['archivo']['name'], PATHINFO_FILENAME)."_".date("Ymdhis").".".$extension);

      $extensionesPermitidas = array("pdf","PDF");

      if(!in_array($extension, $extensionesPermitidas)){
        echo(json_encode(array('status'=>'incorrect')));
      }else{
        $config['upload_path'] = 'specs_procedimientos/';
        $config['allowed_types'] = '*';
        $config['max_size'] = 50000;
        $config['file_name'] = $newName;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('archivo')) {
          $error = array('error' => $this->upload->display_errors());
          echo json_encode($error);
        } else {

          $data = array(
            'idtipodocumento'=> $this->input->post('tipodocumento'),
            'idarea'=> $this->input->post('area'),
            'nombredocumento'=>$this->input->post('titulodocumento'),
            'codigo'=> $this->input->post('codigo'),
            'revision'=> $this->input->post('revision'),
            'nombre'=> $newName,
            'extension'=> '.'.$extension,
            'activo'=> 1,
            'idusuario'=> $this->session->user_id,
            'fecharegistro'=>date('Y-m-d H:i:s')
          );
          $result = $this->documentos->addSpecsProcedimiento($data);
          if ($result) {
            echo(json_encode(array('status'=>'true')));
          }
        }
      }
    }

}

  public function subir_documento()
  {
    if (empty($_FILES['archivo']) ) {
      echo(json_encode(array('status'=>'vacio')));
    } else {
      $content = base64_encode(file_get_contents($_FILES['archivo']['tmp_name']));
      $extension = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);

      $newName = str_replace(' ','_',pathinfo($_FILES['archivo']['name'], PATHINFO_FILENAME)."_".date("Ymdhis").".".$extension);

      $extensionesPermitidas = array("pdf","PDF");

      if(!in_array($extension, $extensionesPermitidas)){
        echo(json_encode(array('status'=>'incorrect')));
      }else{
        $config['upload_path'] = 'specs/';
        $config['allowed_types'] = '*';
        $config['max_size'] = 50000;
        $config['file_name'] = $newName;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('archivo')) {
          $error = array('error' => $this->upload->display_errors());
          echo json_encode($error);
        } else {

          $data = array(
            'idrevision'=> $this->input->post('revision'),
            'documento'=> '',
            'nombre'=> $newName,
            'extension'=> '.'.$extension,
            'activo'=> 1,
            'idusuario'=> $this->session->user_id,
            'fecharegistro'=>date('Y-m-d H:i:s')
          );
          $result = $this->documentos->saveDocument($data);
          if ($result) {
            echo(json_encode(array('status'=>'true')));
          }
        }
      }
    }

  }

  public function downloadDocument($iddoc)
  {

    $result = $this->documentos->getDataDocument($iddoc);
     $data = $result[0]->documento;
     $name = "ddd.pdf";


//$data = base64_decode($data);
header('Content-Type: application/pdf');
echo $data;




 }

 public function actualizar_documento()
 {
  $iddoc =  $this->input->post("iddoc");
  $numeroparte = $this->input->post("numeroparte");

  if (empty($_FILES['archivo'])) {
    echo(json_encode(array('status'=>'vacio')));
  } else {

    $content = base64_encode(file_get_contents($_FILES['archivo']['tmp_name']));
    $extension = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);

    $newName = $numeroparte."_".date("Ymdhis").".pdf";

    $extensionesPermitidas = array("pdf","PDF");

    if(!in_array($extension, $extensionesPermitidas)){
      echo(json_encode(array('status'=>'incorrect')));
    }else{
      $config['upload_path'] = 'specs/';
      $config['allowed_types'] = '*';
      $config['max_size'] = 50000;
      $config['file_name'] = $newName;

      $this->load->library('upload', $config);

      if (!$this->upload->do_upload('archivo')) {
        $error = array('error' => $this->upload->display_errors());
        echo json_encode($error);
      } else {

        $data = array(
          'documento'=> $content,
          'nombre'=> $newName,
          'extension'=> '.'.$extension,
          'activo'=> 1,
          'idusuario'=> $this->session->user_id,
          'fecharegistro'=>date('Y-m-d H:i:s')
        );

        $result = $this->documentos->updateDocument($iddoc,$data);
        if ($result) {
          echo(json_encode(array('status'=>'true')));
        }
      }
    }
  }

}


 public function eliminar_documento_procedimiento()
{
  $iddoc = $this->input->post("iddoc");

  $data = array(
    'activo'=> 0,
    'idusuario'=> $this->session->user_id,
    'fecharegistro'=>date('Y-m-d H:i:s')
  );

  $result = $this->documentos->updateProcedimiento($iddoc,$data);
  if ($result) {
    echo(json_encode(array('status'=>'true')));
  }else{
    echo(json_encode(array('status'=>'false')));
  }
}


public function eliminar_documento()
{
  $iddoc = $this->input->post("iddoc");

  $data = array(
    'activo'=> 0,
    'idusuario'=> $this->session->user_id,
    'fecharegistro'=>date('Y-m-d H:i:s')
  );

  $result = $this->documentos->deleteDocument($iddoc,$data);
  if ($result) {
    echo(json_encode(array('status'=>'true')));
  }else{
    echo(json_encode(array('status'=>'false')));
  }
}

}
?>
