<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reporte_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function __destruct()
    {
        $this->db->close();
    }
    public function allTransferenciaPacking($fechainicio,$fechafin)
    {
        $query =$this->db->query('SELECT * FROM viewprocesopallet
            WHERE (fecha BETWEEN "'.$fechainicio.'" AND "'.$fechafin.'")
            AND idestatus IN (1,3,8)');
        return $query->result();
    }
    public function allTransferenciaCalidad($fechainicio,$fechafin)
    {
        $query =$this->db->query('SELECT * FROM viewprocesopallet
            WHERE (fecha BETWEEN "'.$fechainicio.'" AND "'.$fechafin.'")
            AND idestatus IN (4,5,6,8)');
        return $query->result();
    }
    public function allTransferenciaBodega($fechainicio,$fechafin)
    {
        $query =$this->db->query('SELECT * FROM viewprocesopallet
            WHERE (fecha BETWEEN "'.$fechainicio.'" AND "'.$fechafin.'")
            AND idestatus IN (4,8)');
        return $query->result();
    }


    public function allProcesos() {
        $query = $this->db->query("SELECT  p.idproceso, p.nombreproceso,
            (SELECT
            GROUP_CONCAT(CONCAT_WS('.- ', dp.numero, m.nombremaquina) ORDER BY dp.numero ASC SEPARATOR ', ')
            FROM tbldetalle_proceso dp
            INNER JOIN tblmaquina m ON dp.idmaquina = m.idmaquina
            WHERE dp.idproceso = p.idproceso  AND dp.activo = 1  group by dp.idproceso ORDER BY dp.numero ASC) as pasos
            FROM tblproceso p");
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }

    }


    public function allMaquinas() {
        $this->db->select('m.idmaquina, m.nombremaquina');
        $this->db->from('tblmaquina m');
        //$this->db->where('p.nombreproceso', $nombreproceso);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }


    public function allNumeroPartes(){
       $this->db->select('p.idparte, c.idcliente,p.idcategoria, ca.nombrecategoria, p.numeroparte,c.nombre,u.name,  p.activo, m.descripcion as modelo, r.descripcion as revision');
       $this->db->from('parte p');
       $this->db->join('cliente c', 'p.idcliente=c.idcliente');
       $this->db->join('tblcategoria ca', 'p.idcategoria=ca.idcategoria');
       $this->db->join('users u', 'p.idusuario=u.id');
       $this->db->join('tblmodelo m', 'm.idparte=p.idparte');
       $this->db->join('tblrevision r', 'm.idmodelo=r.idmodelo');
       $query = $this->db->get();
       if ($query->num_rows() > 0) {
           return $query->result();
       } else {
        return false;
    }
}


public function busqueda_proceso_final($finicio = '',$ffin = '',$proceso = '')
{
        # code...
  $this->db->select("ep.identradaproceso,
    ep.cantidad AS cantidadinicial,
    SUM(d.cantidaderronea) AS totalerronea,
    p.idproceso,
    ep.metaproduccion,
    p.nombreproceso,
    d.finalizado,
    d.idmaquina,
    ep.finalizado AS finalizadoproceso,
    (
    SELECT
    pa.numeroparte
    FROM
    parte pa
    WHERE
    pa.idparte = ep.idparte
    ) AS numeroparte,
    (
    SELECT
    pa2.numeroparte
    FROM
    parte pa2
    WHERE
    pa2.idparte = ep.idlamina
    ) AS lamina,
    (
    SELECT
    GROUP_CONCAT(
    CONCAT_WS(
    '.- ',
    dp.numero,
    m.nombremaquina
    )
    ORDER BY
    dp.numero ASC SEPARATOR ', '
    )
    FROM
    tbldetalle_proceso dp
    INNER JOIN tblmaquina m ON
    dp.idmaquina = m.idmaquina
    WHERE
    dp.idproceso = p.idproceso AND dp.activo = 1
    GROUP BY
    dp.idproceso
    ORDER BY
    dp.numero ASC
    ) AS pasos,
    (
    SELECT
    CONCAT_WS(
    '.- ',
    edp.numerodetalleproceso,
    ma.nombremaquina
    )
    FROM
    tblentradadetalleproceso edp,
    tblmaquina ma
    WHERE
    edp.identradaproceso = ep.identradaproceso AND ma.idmaquina = edp.idmaquina
    ORDER BY
    edp.identradadetalleproceso
    DESC
    LIMIT 1
    ) AS procesoactual, d.cantidadentrada as testca,
    sum(d.cantidadentrada) as cantidadentrada, SUM(d.cantidadsalida) AS cantidadsalida, sum(d.cantidaderronea) as cantidaderronea ,
    (select sum(edp4.cantidaderronea) from tblentradadetalleproceso as  edp4 WHERE edp4.identradaproceso = d.identradaproceso  AND edp4.idmaquina = 3) as totalerroneascrap,
    (select sum(edp6.cantidadentrada) from tblentradadetalleproceso as  edp6 WHERE edp6.identradaproceso = d.identradaproceso  AND edp6.idmaquina = 3 AND edp6.finalizado = 0) as totalenespera,
    d.fecharegistro, d.fechaliberado,(
    SELECT
    maq.nombremaquina
    FROM
    tblmaquina maq,
    tbldetalle_proceso dpr
    WHERE
    dpr.idmaquina = maq.idmaquina AND dpr.iddetalle = d.iddetalleproceso
    ) AS maquinaactual,
    d.numerodetalleproceso AS numerodelproceso");
  $this->db->from('tblproceso p');
  $this->db->join('tblentrada_proceso ep', 'p.idproceso = ep.idproceso');
  $this->db->join('tblentradadetalleproceso d', 'd.identradaproceso = ep.identradaproceso');
        //$this->db->where('p.nombreproceso', $nombreproceso);

  if (!empty($finicio) && !empty($ffin)) {
           // $this->db->where('(ep.fecharegistro >='.$finicio.' AND ep.fecharegistro <= '.$ffin.')');
    $this->db->where('ep.fecharegistro BETWEEN "'. $finicio. '" and "'. $ffin.'"');
            //$this->db->where('', $ffin);
}
if (!empty($lamina)) {
    $this->db->where('(ep.idparte = '.$lamina.' or ep.idlamina = '.$lamina.')');
}
if (!empty($proceso)) {
 $this->db->where('d.idmaquina', $proceso);
}
$this->db->group_by("ep.identradaproceso");
$query = $this->db->get();
if ($query->num_rows() > 0) {
    return $query->result();
} else {
    return false;
}
}
public function busqueda_almacen_entrada_lamina($finicio = '',$ffin = '',$idparte)
{
    $this->db->select("p.numeroparte, m.descripcion as modelo, r.descripcion as revision, l.*");
   $this->db->from('tbllamina l');
   $this->db->join('parte p', 'p.idparte = l.idparte');
    $this->db->join('tblmodelo m', 'p.idparte = m.idparte');
  $this->db->join('tblrevision r', 'm.idmodelo = r.idmodelo');
   if (!empty($finicio) && !empty($ffin)) {
     $this->db->where('l.fecharegistro BETWEEN "'. $finicio. '" and "'. $ffin.'"');
   }
    if (!empty($idparte)) {
        $this->db->where('p.idparte',$idparte);
    }
  $this->db->where('l.activo',1);
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
        return $query->result();
    } else {
        return false;
    }
}
public function busqueda_almacen_salida_lamina($finicio = '',$ffin = '',$idparte)
{
   $this->db->select("p.numeroparte, m.descripcion as modelo, r.descripcion as revision, l.*");
   $this->db->from('tbllaminasalida l');
   $this->db->join('parte p', 'p.idparte = l.idparte');
    $this->db->join('tblmodelo m', 'p.idparte = m.idparte');
  $this->db->join('tblrevision r', 'm.idmodelo = r.idmodelo');
   if (!empty($finicio) && !empty($ffin)) {
     $this->db->where('l.fecharegistro BETWEEN "'. $finicio. '" and "'. $ffin.'"');
   }
    if (!empty($idparte)) {
        $this->db->where('p.idparte',$idparte);
    }
      $this->db->where('l.activo',1);
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
        return $query->result();
    } else {
        return false;
    }
}
public function busqueda_almacen_devolucion_lamina($finicio = '',$ffin = '',$idparte)
{
   $this->db->select("p.numeroparte, m.descripcion as modelo, r.descripcion as revision, l.*");
   $this->db->from('tbllaminadevolucion l');
    $this->db->join('parte p', 'p.idparte = l.idparte');
     $this->db->join('tblmodelo m', 'p.idparte = m.idparte');
   $this->db->join('tblrevision r', 'm.idmodelo = r.idmodelo');


   if (!empty($finicio) && !empty($ffin)) {
     $this->db->where('l.fecharegistro BETWEEN "'. $finicio. '" and "'. $ffin.'"');
   }
    if (!empty($idparte)) {
        $this->db->where('p.idparte',$idparte);
    }
      $this->db->where('l.activo',1);
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
        return $query->result();
    } else {
        return false;
    }
}
public function busqueda_almacen_entrada_litho($finicio = '',$ffin = '',$idparte, $tipo = '')
{
   $this->db->select("p.numeroparte, m.descripcion as modelo, r.descripcion as revision, l.*");
   $this->db->from('tbllitho l');
   $this->db->join('tblrevision r', 'l.idrevision = r.idrevision');
   $this->db->join('tblmodelo m', 'r.idmodelo = m.idmodelo');
   $this->db->join('parte p', 'p.idparte = m.idparte');


   if (!empty($finicio) && !empty($ffin)) {
     $this->db->where('l.fecharegistro BETWEEN "'. $finicio. '" and "'. $ffin.'"');
   }
    if (!empty($idparte)) {
        $this->db->where('p.idparte',$idparte);
    }
    if (!empty($tipo)) {
        $this->db->where('l.idcategoria',$tipo);
    }
  $this->db->where('l.activo',1);
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
        return $query->result();
    } else {
        return false;
    }
}
public function busqueda_almacen_salida_litho($finicio = '',$ffin = '',$idparte, $tipo = '')
{
   $this->db->select("p.numeroparte, m.descripcion as modelo, r.descripcion as revision, l.*");
   $this->db->from('tbllithosalida l');
   $this->db->join('tblrevision r', 'l.idrevision = r.idrevision');
   $this->db->join('tblmodelo m', 'r.idmodelo = m.idmodelo');
   $this->db->join('parte p', 'p.idparte = m.idparte');
   if (!empty($finicio) && !empty($ffin)) {
     $this->db->where('l.fecharegistro BETWEEN "'. $finicio. '" and "'. $ffin.'"');
   }
    if (!empty($idparte)) {
        $this->db->where('p.idparte',$idparte);
    }
    if (!empty($tipo)) {
        $this->db->where('l.idcategoria',$tipo);
    }
      $this->db->where('l.activo',1);
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
        return $query->result();
    } else {
        return false;
    }
}
public function busqueda_almacen_devolucion_litho($finicio = '',$ffin = '',$idparte,$tipo = '')
{
   $this->db->select("p.numeroparte, m.descripcion as modelo, r.descripcion as revision, l.*");
   $this->db->from('tbllithodevolucion l');
   $this->db->join('tblrevision r', 'l.idrevision = r.idrevision');
   $this->db->join('tblmodelo m', 'r.idmodelo = m.idmodelo');
   $this->db->join('parte p', 'p.idparte = m.idparte');
   if (!empty($finicio) && !empty($ffin)) {
     $this->db->where('l.fecharegistro BETWEEN "'. $finicio. '" and "'. $ffin.'"');
   }
    if (!empty($idparte)) {
        $this->db->where('p.idparte',$idparte);
    }
    if (!empty($tipo)) {
        $this->db->where('l.idcategoria',$tipo);
    }
      $this->db->where('l.activo',1);
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
        return $query->result();
    } else {
        return false;
    }
}

public function busqueda_proceso($finicio = '',$ffin = '',$lamina = '',$proceso = '',$maquina = '')
{
        # code...
    $this->db->select("ep.identradaproceso,
        ep.cantidad AS cantidadinicial,
        SUM(d.cantidaderronea) AS totalerronea,
        p.idproceso,
        p.nombreproceso,
        d.finalizado,
        ep.finalizado AS finalizadoproceso,
        (
        SELECT
        pa.numeroparte
        FROM
        parte pa
        WHERE
        pa.idparte = ep.idparte
        ) AS numeroparte,
        (
        SELECT
        pa2.numeroparte
        FROM
        parte pa2
        WHERE
        pa2.idparte = ep.idlamina
        ) AS lamina,
        (
        SELECT
        GROUP_CONCAT(
        CONCAT_WS(
        '.- ',
        dp.numero,
        m.nombremaquina
        )
        ORDER BY
        dp.numero ASC SEPARATOR ', '
        )
        FROM
        tbldetalle_proceso dp
        INNER JOIN tblmaquina m ON
        dp.idmaquina = m.idmaquina
        WHERE
        dp.idproceso = p.idproceso AND dp.activo = 1
        GROUP BY
        dp.idproceso
        ORDER BY
        dp.numero ASC
        ) AS pasos,
        (
        SELECT
        CONCAT_WS(
        '.- ',
        edp.numerodetalleproceso,
        ma.nombremaquina
        )
        FROM
        tblentradadetalleproceso edp,
        tblmaquina ma
        WHERE
        edp.identradaproceso = ep.identradaproceso AND ma.idmaquina = edp.idmaquina
        ORDER BY
        edp.identradadetalleproceso
        DESC
        LIMIT 1
        ) AS procesoactual, d.cantidadentrada as testca,
        sum(d.cantidadentrada) as cantidadentrada, SUM(d.cantidadsalida) AS cantidadsalida, sum(d.cantidaderronea) as cantidaderronea ,
        (select sum(edp4.cantidaderronea) from tblentradadetalleproceso as  edp4 WHERE edp4.identradaproceso = d.identradaproceso  AND edp4.idmaquina = 3) as totalerroneascrap,
        d.fecharegistro, d.fechaliberado,(
        SELECT
        maq.nombremaquina
        FROM
        tblmaquina maq,
        tbldetalle_proceso dpr
        WHERE
        dpr.idmaquina = maq.idmaquina AND dpr.iddetalle = d.iddetalleproceso
        ) AS maquinaactual,
        d.numerodetalleproceso AS numerodelproceso");
    $this->db->from('tblproceso p');
    $this->db->join('tblentrada_proceso ep', 'p.idproceso = ep.idproceso');
    $this->db->join('tblentradadetalleproceso d', 'd.identradaproceso = ep.identradaproceso');
        //$this->db->where('p.nombreproceso', $nombreproceso);

    if (!empty($finicio) && !empty($ffin)) {
        $this->db->where('date(ep.fecharegistro) >=', $finicio);
        $this->db->where('date(ep.fecharegistro) <=', $ffin);
    }
    if (!empty($lamina)) {
        $this->db->where('(ep.idparte = '.$lamina.' or ep.idlamina = '.$lamina.')');
    }
    if (!empty($proceso)) {
     $this->db->where('ep.idproceso', $proceso);
 }
 if (!empty($maquina)) {
     $this->db->where('d.idmaquina', $maquina);
 }
 $query = $this->db->get();
 if ($query->num_rows() > 0) {
    return $query->result();
} else {
    return false;
}

}

public function maquinas_activas()
{
  $this->db->select('m.*');
  $this->db->from('tblmaquina m');
  $this->db->where('m.activo',1);
  $query = $this->db->get();
  if ($query->num_rows() > 0) {
      return $query->result();
  } else {
      return false;
  }
}

// Seleccionar Usuarios
public function getAllUsers(){
    $query = $this->db->get('users');
    if ($query->num_rows() > 0) {
        return $query->result();
    } else {
        return false;
    }
}

// Reporte PACKING
public function getAllInfoReporte($idparte='',$fechainicio='',$fechafin='',$tipo='',$tiporeporte = '',$idturno = '',$tinicio = '', $tfinal = '',$idusuario = '')
{
    $status = array(1,4,8);

    $this->db->select("
    pc.idpalletcajas, COUNT(pc.pallet) as totalpallet,p.numeroparte, SUM(c.cantidad) as totalcajas,c.cantidad as cantidadcajaspallet,m.descripcion as modelo, r.descripcion as revision, l.nombrelinea as tiempo, es.nombrestatus,
    (SELECT u.name FROM users u, palletcajasproceso pcp where pcp.idusuario =u.id and pcp.idestatus = pc.idestatus and pcp.idpalletcajas=pc.idpalletcajas order by pcp.idpalletcajasproceso asc limit 1) as nombreusuario");
    $this->db->from('parte p');
    $this->db->join('tblmodelo m', 'p.idparte = m.idparte');
    $this->db->join('tblrevision r', 'm.idmodelo = r.idmodelo');
    $this->db->join('tblcantidad c', 'c.idrevision = r.idrevision');
    $this->db->join('palletcajas pc', 'pc.idcajas=c.idcantidad');
    $this->db->join('tbltransferencia t', 't.idtransferancia=pc.idtransferancia');
    $this->db->join('users u', 'u.id=pc.idusuario');
    $this->db->join('status es', 'es.idestatus=pc.idestatus');
    $this->db->join('linea l', 'pc.idlinea = l.idlinea');

    // Condicionales
    if (!empty($fechainicio) && !empty($fechafin)) {
        $this->db->where('pc.fecharegistro >=', $fechainicio);
        $this->db->where('pc.fecharegistro <=', $fechafin);
    }
    if(!empty($idusuario)){
    //$this->db->where('pcp.idusuario',$idusuario);
    }

    if (!empty($idparte)) {
        $this->db->where('p.idparte',$idparte);
    }
    //$this->db->where('pcp.idestatus',1);
    $this->db->where_in('pc.idestatus', $status);


    if(!empty($tipo) && $tipo == 1){
     $this->db->where('pc.idtransferancia NOT IN (SELECT d.idtransferencia FROM tbldevolucion d)');
    }
    if(!empty($tipo) && $tipo == 0){
     $this->db->where('pc.idtransferancia IN (SELECT d.idtransferencia FROM tbldevolucion d)');
    }
    if(isset($tiporeporte) && !empty($tiporeporte)){
    $this->db->group_by('r.idrevision');
    if(!empty($idturno)){
    $this->db->where("pc.idpalletcajas IN (SELECT  DISTINCT pcp.idpalletcajas FROM palletcajasproceso pcp INNER JOIN users usu ON usu.id = pcp.idusuario WHERE pcp.idestatus = 1 AND usu.idturno = $idturno)");
        }
    }else{
    $this->db->group_by('c.idcantidad');
   $this->db->where("pc.idpalletcajas IN (SELECT  DISTINCT pcp.idpalletcajas FROM palletcajasproceso pcp INNER JOIN users usu ON usu.id = pcp.idusuario WHERE pcp.idestatus = 1 AND usu.idturno = $idturno)");

    }

    if((isset($tinicio) && !empty($tinicio)) && (isset($tfinal) && !empty($tfinal))){
    $this->db->where('t.folio >=', $tinicio);
    $this->db->where('t.folio <=', $tfinal);
    }
    $this->db->order_by('p.numeroparte ASC');
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
        return $query->result();
    } else {
        return false;
    }
}


// Reporte CALIDAD

public function getAllInfoReporteCalidad($idparte='',$fechainicio='',$fechafin='',$tipo='')
{
    $status = array(1,4,8);

    $this->db->select("
    pc.idpalletcajas, COUNT(pc.pallet) as totalpallet,p.numeroparte, SUM(c.cantidad) as totalcajas,c.cantidad as cantidadcajaspallet,m.descripcion as modelo, r.descripcion as revision, l.nombrelinea as tiempo, es.nombrestatus,
    (SELECT u.name FROM users u, palletcajasproceso pcp where pcp.idusuario =u.id and pcp.idestatus = pc.idestatus and pcp.idpalletcajas=pc.idpalletcajas order by pcp.idpalletcajasproceso asc limit 1) as nombreusuario");
    $this->db->from('parte p');
    $this->db->join('tblmodelo m', 'p.idparte = m.idparte');
    $this->db->join('tblrevision r', 'm.idmodelo = r.idmodelo');
    $this->db->join('tblcantidad c', 'c.idrevision = r.idrevision');
    $this->db->join('palletcajas pc', 'pc.idcajas=c.idcantidad');
    $this->db->join('status es', 'es.idestatus=pc.idestatus');
    $this->db->join('linea l', 'pc.idlinea = l.idlinea');

    // Condicionales
    if (!empty($fechainicio) && !empty($fechafin)) {
        $this->db->where('pc.fecharegistro >=', $fechainicio);
        $this->db->where('pc.fecharegistro <=', $fechafin);
    }

    if (!empty($idparte)) {
        $this->db->where('p.idparte',$idparte);
    }

    $this->db->where_in('pc.idestatus', $status);
     if(!empty($tipo) && $tipo == 1){
     $this->db->where('pc.idtransferancia NOT IN (SELECT d.idtransferencia FROM tbldevolucion d)');
    }
    if(!empty($tipo) && $tipo == 0){
     $this->db->where('pc.idtransferancia IN (SELECT d.idtransferencia FROM tbldevolucion d)');
    }

      $this->db->group_by('c.idcantidad');

    $query = $this->db->get();
    if ($query->num_rows() > 0) {
        return $query->result();
    } else {
        return false;
    }
}
// Reporte ALMACEN

public function getAllInfoReporteAlmacen($idparte='',$fechainicio='',$fechafin='',$tipo='')
{
    $status = array(4,8);

    $this->db->select("
    pc.idpalletcajas, COUNT(pc.pallet) as totalpallet,p.numeroparte, SUM(c.cantidad) as totalcajas,c.cantidad as cantidadcajaspallet,m.descripcion as modelo, r.descripcion as revision, l.nombrelinea as tiempo, es.nombrestatus,
    (SELECT u.name FROM users u, palletcajasproceso pcp where pcp.idusuario =u.id and pcp.idestatus = pc.idestatus and pcp.idpalletcajas=pc.idpalletcajas order by pcp.idpalletcajasproceso asc limit 1) as nombreusuario");
    $this->db->from('parte p');
    $this->db->join('tblmodelo m', 'p.idparte = m.idparte');
    $this->db->join('tblrevision r', 'm.idmodelo = r.idmodelo');
    $this->db->join('tblcantidad c', 'c.idrevision = r.idrevision');
    $this->db->join('palletcajas pc', 'pc.idcajas=c.idcantidad');
    $this->db->join('status es', 'es.idestatus=pc.idestatus');
    $this->db->join('linea l', 'pc.idlinea = l.idlinea');

    // Condicionales
    if (!empty($fechainicio) && !empty($fechafin)) {
        $this->db->where('pc.fecharegistro >=', $fechainicio);
        $this->db->where('pc.fecharegistro <=', $fechafin);
    }

    if (!empty($idparte)) {
        $this->db->where('p.idparte',$idparte);
    }

    $this->db->where_in('pc.idestatus', $status);
     if(!empty($tipo) && $tipo == 1){
     $this->db->where('pc.idtransferancia NOT IN (SELECT d.idtransferencia FROM tbldevolucion d)');
    }
    if(!empty($tipo) && $tipo == 0){
     $this->db->where('pc.idtransferancia IN (SELECT d.idtransferencia FROM tbldevolucion d)');
    }

   $this->db->group_by('c.idcantidad');

    $query = $this->db->get();
    if ($query->num_rows() > 0) {
        return $query->result();
    } else {
        return false;
    }
}

/*public function reportePoProceso($idproceso='', $fechainicio = '', $fechafin = '')
{
  $query = $this->db->query("SELECT ep.identradaproceso,ep.metaproduccion, ep.fecharegistro, COALESCE(ep.cantidad,0) totalentrada, pa.numeroparte, la.numeroparte as lamina,
  (SELECT COALESCE(SUM(edp2.cantidaderronea ),0) FROM tblentradadetalleproceso edp2 WHERE edp2.identradaproceso = ep.identradaproceso AND edp2.finalizadotodo = 1 AND edp2.idmaquina = 3) as totalerrorconscrap,
  (SELECT COALESCE(SUM(edp2.cantidaderronea ),0) FROM tblentradadetalleproceso edp2 WHERE edp2.identradaproceso = ep.identradaproceso AND edp2.finalizadotodo = 1 AND edp2.idmaquina != 3) as totalerrorsinscrap,
  (SELECT COALESCE(SUM(edp2.cantidadsalida ),0) FROM tblentradadetalleproceso edp2 WHERE edp2.identradaproceso = ep.identradaproceso AND edp2.finalizadotodo = 1 AND edp2.idmaquina != 3) as totalsalidasinscrap,
  (SELECT edp2.idmaquina FROM tblentradadetalleproceso edp2 WHERE edp2.identradaproceso = ep.identradaproceso AND edp2.finalizadotodo = 1 Limit 1) as idmaquinafina,
(SELECT COALESCE(SUM(edp2.finalizadotodo ),0) FROM tblentradadetalleproceso edp2 WHERE edp2.identradaproceso = ep.identradaproceso AND edp2.finalizadotodo = 1 ) as finalizado,
(SELECT
           GROUP_CONCAT(CONCAT_WS('.- ', dp.numero, m.nombremaquina) ORDER BY dp.numero ASC SEPARATOR ', ')
           FROM tbldetalle_proceso dp
           INNER JOIN tblmaquina m ON dp.idmaquina = m.idmaquina
           WHERE dp.idproceso = p.idproceso  AND dp.activo = 1  group by dp.idproceso ORDER BY dp.numero ASC) as pasos
 FROM tblproceso p
INNER JOIN tblentrada_proceso ep ON p.idproceso = ep.idproceso
INNER JOIN  tblentradadetalleproceso edp ON ep.identradaproceso = edp.identradaproceso
INNER JOIN parte pa ON pa.idparte = ep.idparte
INNER JOIN parte la ON la.idparte = ep.idlamina
WHERE ep.idproceso = $idproceso
AND ep.fecharegistro BETWEEN '$fechainicio' AND '$fechafin'
GROUP BY ep.identradaproceso");
  if ($query->num_rows() > 0) {
      return $query->result();
  } else {
      return false;
  }
}*/

public function reportePoProceso($proceso = '',$finicio = '',$ffin = '',$lamina = '')
{
        # code...
  $this->db->select("ep.identradaproceso,ep.metaproduccion, ep.fecharegistro, COALESCE(ep.cantidad,0) totalentrada, pa.numeroparte, la.numeroparte as lamina,
  (SELECT COALESCE(SUM(edp2.cantidaderronea ),0) FROM tblentradadetalleproceso edp2 WHERE edp2.identradaproceso = ep.identradaproceso AND edp2.finalizadotodo = 1 AND edp2.idmaquina = 3) as totalerrorconscrap,
  (SELECT COALESCE(SUM(edp2.cantidaderronea ),0) FROM tblentradadetalleproceso edp2 WHERE edp2.identradaproceso = ep.identradaproceso AND edp2.finalizadotodo = 1 AND edp2.idmaquina != 3) as totalerrorsinscrap,
  (SELECT COALESCE(SUM(edp2.cantidaderronea ),0) FROM tblentradadetalleproceso edp2 WHERE edp2.identradaproceso = ep.identradaproceso AND edp2.finalizado = 1 AND edp2.idmaquina NOT IN  (3,7)) as totalerrorgeneral,
  (SELECT COALESCE(SUM(edp2.cantidadsalida ),0) FROM tblentradadetalleproceso edp2 WHERE edp2.identradaproceso = ep.identradaproceso AND edp2.finalizadotodo = 1 AND edp2.idmaquina != 3) as totalsalidasinscrap,
  (SELECT edp2.idmaquina FROM tblentradadetalleproceso edp2 WHERE edp2.identradaproceso = ep.identradaproceso AND edp2.finalizadotodo = 1 Limit 1) as idmaquinafina,
(SELECT COALESCE(SUM(edp2.finalizadotodo ),0) FROM tblentradadetalleproceso edp2 WHERE edp2.identradaproceso = ep.identradaproceso AND edp2.finalizadotodo = 1 ) as finalizado,
(SELECT
           GROUP_CONCAT(CONCAT_WS('.- ', dp.numero, m.nombremaquina) ORDER BY dp.numero ASC SEPARATOR ', ')
           FROM tbldetalle_proceso dp
           INNER JOIN tblmaquina m ON dp.idmaquina = m.idmaquina
           WHERE dp.idproceso = p.idproceso  AND dp.activo = 1  group by dp.idproceso ORDER BY dp.numero ASC) as pasos");
  $this->db->from('tblproceso p');
  $this->db->join('tblentrada_proceso ep', 'p.idproceso = ep.idproceso');
  $this->db->join('tblentradadetalleproceso edp', 'edp.identradaproceso = ep.identradaproceso');
  $this->db->join('parte pa','pa.idparte = ep.idparte');
  $this->db->join('parte la','la.idparte = ep.idlamina');

  if (!empty($finicio) && !empty($ffin)) {
      $this->db->where('ep.fecharegistro BETWEEN "'. $finicio. '" and "'. $ffin.'"');
   }
  if (!empty($lamina)) {
        $this->db->where('(ep.idparte = '.$lamina.' or ep.idlamina = '.$lamina.')');
   }
  if (!empty($proceso) && $proceso != 2804) {
     $this->db->where('ep.idproceso', $proceso);
  }
    $this->db->group_by("ep.identradaproceso");
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
        return $query->result();
    } else {
        return false;
    }
    }

}
