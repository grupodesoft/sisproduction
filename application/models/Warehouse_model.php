<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Warehouse_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function __destruct() {
        $this->db->close();
    }

    public function getDataPallets() {

        $query = $this->db->query("
            select r.idrevision, cl.nombre,ca.nombrecategoria, p.numeroparte,  CASE WHEN LENGTH(m.descripcion) > 12 
THEN CONCAT(SUBSTRING(m.descripcion, 1, 12), '...') 
ELSE m.descripcion END AS nombremodelo,
 r.descripcion as nombrerevision,
            (select COALESCE(sum(c2.cantidad),0) from parteposicionbodega ppb2, palletcajas pc2, tblcantidad c2 
            WHERE ppb2.idpalletcajas = pc2.idpalletcajas AND pc2.idcajas = c2.idcantidad  AND c2.idrevision = r.idrevision AND ppb2.ordensalida = 0 AND ppb2.salida = 0)  as total,
                       (SELECT 
            COALESCE(SUM(c2.cantidad), 0)
        FROM
            parteposicionbodega ppb2,
            palletcajas pc2,
            tblcantidad c2
        WHERE
            ppb2.idpalletcajas = pc2.idpalletcajas
                AND pc2.idcajas = c2.idcantidad
                AND c2.idrevision = r.idrevision ) AS totalentrada,
            (select 

            COALESCE(sum(
            CASE 
            WHEN os.tipo  = 1 THEN os.caja 
            ELSE 0
            END),0)
            from parteposicionbodega ppb2, palletcajas pc2, tblcantidad c2, ordensalida os
            WHERE ppb2.idpalletcajas = pc2.idpalletcajas AND pc2.idcajas = c2.idcantidad AND pc2.idpalletcajas = os.idpalletcajas  AND c2.idrevision = r.idrevision AND ppb2.ordensalida = 1)  as totalsalidaparciales,
            (select 

            COALESCE(sum(
            CASE 
            WHEN os.tipo  = 0 THEN c2.cantidad 
            ELSE 0
            END),0)
            from parteposicionbodega ppb2, palletcajas pc2, tblcantidad c2, ordensalida os
            WHERE ppb2.idpalletcajas = pc2.idpalletcajas AND pc2.idcajas = c2.idcantidad AND pc2.idpalletcajas = os.idpalletcajas  AND c2.idrevision = r.idrevision AND ppb2.ordensalida = 1)  as totalsalidapallet
            from palletcajas pc 
            inner join tblcantidad c on c.idcantidad = pc.idcajas
            inner join tblrevision r on r.idrevision =  c.idrevision
            inner join tblmodelo m  on m.idmodelo = r.idmodelo
            inner join parte p on p.idparte = m.idparte
            inner join tblcategoria ca on p.idcategoria = ca.idcategoria
            inner join cliente cl on cl.idcliente = p.idcliente
            inner join parteposicionbodega ppb on ppb.idpalletcajas = pc.idpalletcajas
            group by r.idrevision");

        return $query->result();
    }

    public function getDataPalletsPosicion() {

        $query = $this->db->query(" SELECT 
    ppb.idposicion,
    r.idrevision,
    cl.nombre,
    ca.nombrecategoria,
    p.numeroparte,
    m.descripcion AS nombremodelo,
    r.descripcion AS nombrerevision,
    pb.nombreposicion,
    (SELECT 
            COALESCE(SUM(c2.cantidad), 0)
        FROM
            parteposicionbodega ppb2,
            palletcajas pc2,
            tblcantidad c2
        WHERE
            ppb2.idpalletcajas = pc2.idpalletcajas
                AND pc2.idcajas = c2.idcantidad
                AND ppb2.idposicion = ppb.idposicion
                AND ppb2.ordensalida = 0
                AND ppb2.salida = 0
        GROUP BY ppb2.idposicion) AS total,
    (SELECT 
            COALESCE(SUM(c2.cantidad), 0)
        FROM
            parteposicionbodega ppb2,
            palletcajas pc2,
            tblcantidad c2
        WHERE
            ppb2.idpalletcajas = pc2.idpalletcajas
                AND pc2.idcajas = c2.idcantidad
                AND ppb2.idposicion = ppb.idposicion
        GROUP BY ppb2.idposicion) AS totalentrada,
    (SELECT 
            COALESCE(SUM(os.caja), 0)
        FROM
            parteposicionbodega ppb2,
            palletcajas pc2,
            tblcantidad c2,
            ordensalida os
        WHERE
            ppb2.idpalletcajas = pc2.idpalletcajas
                AND pc2.idcajas = c2.idcantidad
                AND ppb2.idposicion = ppb.idposicion
                AND pc2.idpalletcajas = os.idpalletcajas
                AND os.tipo = 1
                AND ppb2.ordensalida = 1
        GROUP BY ppb2.idposicion) AS totalsalidaparciales,
    (SELECT 
            COALESCE(SUM(c2.cantidad), 0)
        FROM
            parteposicionbodega ppb2,
            palletcajas pc2,
            tblcantidad c2,
            ordensalida os
        WHERE
            ppb2.idpalletcajas = pc2.idpalletcajas
                AND pc2.idcajas = c2.idcantidad
                AND ppb2.idposicion = ppb.idposicion
                AND pc2.idpalletcajas = os.idpalletcajas
                AND os.tipo = 0
                AND ppb2.ordensalida = 1
        GROUP BY ppb2.idposicion) AS totalsalidapallet
FROM
    palletcajas pc
        INNER JOIN
    tblcantidad c ON c.idcantidad = pc.idcajas
        INNER JOIN
    tblrevision r ON r.idrevision = c.idrevision
        INNER JOIN
    tblmodelo m ON m.idmodelo = r.idmodelo
        INNER JOIN
    parte p ON p.idparte = m.idparte
        INNER JOIN
    tblcategoria ca ON p.idcategoria = ca.idcategoria
        INNER JOIN
    cliente cl ON cl.idcliente = p.idcliente
        INNER JOIN
    parteposicionbodega ppb ON ppb.idpalletcajas = pc.idpalletcajas
        INNER JOIN
    posicionbodega pb ON ppb.idposicion = pb.idposicion
GROUP BY ppb.idposicion");

        return $query->result();
    }

    public function getDataEntry($first_date = '', $second_date = '', $categoria = '', $parte = '') {
        $this->db->select('pc.idpalletcajas,pc.idtransferancia,pc.pallet,DATE_FORMAT(ppb.fecharegistro,  "%d/%m/%Y") as fecha,ca.nombrecategoria, pc.idcajas,pc.idestatus, p.idparte,c.nombre,p.numeroparte,count(tc.idcantidad) as totalpallet,tc.cantidad as cantidadxpallet, sum(tc.cantidad) as cantidad, tr.descripcion, s.nombrestatus, pc.idestatus,pb.nombreposicion');
        $this->db->from('palletcajas pc');
        $this->db->join('tblcantidad  tc', 'tc.idcantidad = pc.idcajas');
        $this->db->join('tblrevision  tr', 'tr.idrevision = tc.idrevision');
        $this->db->join('tblmodelo  tm', 'tm.idmodelo = tr.idmodelo');
        $this->db->join('parte  p', 'tm.idparte = p.idparte');
        $this->db->join('tblcategoria  ca', 'ca.idcategoria = p.idcategoria');
        $this->db->join('cliente  c', 'c.idcliente = p.idcliente');
        $this->db->join('status  s', 's.idestatus = pc.idestatus');
        $this->db->join('parteposicionbodega  ppb', 'pc.idpalletcajas = ppb.idpalletcajas');
        $this->db->join('posicionbodega  pb', 'ppb.idposicion = pb.idposicion');
        $this->db->where('pc.idestatus', 8);

        if (!empty($first_date) && !empty($second_date)) {
            $this->db->where('date(ppb.fecharegistro) >=', $first_date);
            $this->db->where('date(ppb.fecharegistro) <=', $second_date);
        }
        if(!empty($categoria) && $categoria != 0){
            $this->db->where('p.idcategoria', $categoria);
        }
        if(!empty($parte)){
            $this->db->like('p.numeroparte', $parte);
        }
        //$this->db->where('pc.idestatus', 8);
        //$this->db->where('ppb.ordensalida', 0);
        $this->db->group_by('tc.idcantidad');
        $query = $this->db->get();

        return $query->num_rows() > 0 ? $query->result() : FALSE;
    }

    public function getDataExits($first_date = '', $second_date = '', $tipo, $categoria = '', $parte = '', $salida = '') {
        $this->db->select('pc.idpalletcajas,
       pc.idestatus,
       pc.idtransferancia,
       c.nombre,
       p.numeroparte,
       DATE_FORMAT(os.fecharegistro,  "%d/%m/%Y") as fecha,
       Sum(tc.cantidad)     AS cantidad,
       Count(
			   CASE
					WHEN os.tipo = 0  THEN 0
					WHEN  os.tipo = 1 THEN 1
					ELSE 0
				END
       ) AS totalpallet, 
       tc.cantidad  AS cantidadxpallet,
       tr.descripcion,
       s.nombrestatus,
       pb.nombreposicion,
       os.caja,
       os.idordensalida,
       sal.numerosalida,
       ca.nombrecategoria,
       sal.orden,
       sal.finalizado,
        os.tipo,
        sum(
        CASE
			WHEN os.tipo = 0 && os.caja = 0 THEN tc.cantidad 
                        WHEN os.tipo = 0 && os.caja > 0 THEN os.caja 
			ELSE 0
		END
        ) totalcajaspallet,
         sum(
        CASE
			WHEN os.tipo = 1 THEN os.caja
			ELSE 0
		END
        ) totalcajasparciales');
        $this->db->from('palletcajas pc');
        $this->db->join('tblcantidad tc', 'tc.idcantidad = pc.idcajas');
        $this->db->join('tblrevision tr ', 'tr.idrevision = tc.idrevision');
        $this->db->join('tblmodelo tm ', ' tm.idmodelo = tr.idmodelo');
        $this->db->join('parte p ', ' tm.idparte = p.idparte');
        $this->db->join('tblcategoria ca ', ' ca.idcategoria = p.idcategoria');
        $this->db->join('cliente c ', ' c.idcliente = p.idcliente');
        $this->db->join('status s ', ' s.idestatus = pc.idestatus');
        $this->db->join('parteposicionbodega ppb ', ' pc.idpalletcajas = ppb.idpalletcajas');
        $this->db->join('posicionbodega pb ', ' ppb.idposicion = pb.idposicion');
        $this->db->join('ordensalida os ', ' pc.idpalletcajas = os.idpalletcajas');
        $this->db->join('salida sal ', ' os.idsalida = sal.idsalida');

        if (!empty($first_date) && !empty($second_date)) {
            $this->db->where('date(os.fecharegistro) >=', $first_date);
            $this->db->where('date(os.fecharegistro) <=', $second_date);
            //$this->db->where('os.tipo', $tipo);
        } 
        if (isset($tipo)  && $tipo != 2) {
            //$this->db->where('date(os.fecharegistro) >=', $first_date);
            //$this->db->where('date(os.fecharegistro) <=', $second_date);
            $this->db->where('os.tipo', $tipo);
        } 
         
        if (!empty($categoria) && $categoria != 0) {
            $this->db->where('p.idcategoria', $categoria);
        }
        if (!empty($parte)) {
            $this->db->like('p.numeroparte', $parte);
        }
        if (!empty($salida)) {
            $this->db->like('sal.numerosalida', $salida);
        }
        $this->db->where('pc.idestatus', 8);
        $this->db->where('ppb.ordensalida', 1);
        $this->db->group_by('tc.idcantidad');
        $this->db->group_by('os.idsalida');
        $this->db->group_by('os.tipo');
        $query = $this->db->get();

        return $query->num_rows() > 0 ? $query->result() : FALSE;
    }

    public function getDataEntradas($id) {
        $query = $this->db->query("
            SELECT pc.idpalletcajas, pc.idtransferancia, pc.pallet, pc.idcajas, pc.idestatus,
            ppb.fecharegistro, p.idparte, c.nombre, p.numeroparte, tc.cantidad, tr.descripcion,
            s.nombrestatus, pc.idestatus, pb.nombreposicion, ppb.ordensalida, ppb.salida
            FROM palletcajas pc
            JOIN tblcantidad tc ON tc.idcantidad = pc.idcajas 
            JOIN tblrevision tr ON tr.idrevision = tc.idrevision
            JOIN tblmodelo tm ON tm.idmodelo = tr.idmodelo 
            JOIN parte p ON tm.idparte = p.idparte 
            JOIN cliente c ON c.idcliente = p.idcliente 
            JOIN status s ON s.idestatus = pc.idestatus 
            JOIN parteposicionbodega ppb ON pc.idpalletcajas = ppb.idpalletcajas 
            JOIN posicionbodega pb ON ppb.idposicion = pb.idposicion
            WHERE tr.idrevision = $id  
            ORDER BY pc.idpalletcajas ASC ");

        return $query->result();
    }

    public function getDataSalidaParcial($id) {
        $query = $this->db->query("
            SELECT pc.idpalletcajas, pc.idtransferancia, pc.pallet, pc.idcajas, pc.idestatus, 
            os.fecharegistro, p.idparte, c.nombre, p.numeroparte, tc.cantidad, tr.descripcion, 
            s.nombrestatus, pc.idestatus, pb.nombreposicion, ppb.ordensalida, ppb.salida,os.caja
            FROM palletcajas pc 
            JOIN tblcantidad tc ON tc.idcantidad = pc.idcajas 
            JOIN tblrevision tr ON tr.idrevision = tc.idrevision 
            JOIN tblmodelo tm ON tm.idmodelo = tr.idmodelo
            JOIN parte p ON tm.idparte = p.idparte
            JOIN cliente c ON c.idcliente = p.idcliente 
            JOIN STATUS s ON s.idestatus = pc.idestatus
            JOIN parteposicionbodega ppb ON pc.idpalletcajas = ppb.idpalletcajas
            JOIN posicionbodega pb ON ppb.idposicion = pb.idposicion 
            JOIN ordensalida os ON pc.idpalletcajas = os.idpalletcajas
            JOIN salida sal ON os.idsalida = sal.idsalida
            WHERE tr.idrevision = $id AND os.tipo = 1 AND ppb.ordensalida = 1
            ORDER BY pc.idpalletcajas ASC");

        return $query->result();
    }

    public function getDataSalidaPallet($id) {
        $query = $this->db->query("
            SELECT pc.idpalletcajas, pc.idtransferancia, pc.pallet, pc.idcajas, pc.idestatus, 
            pc.fecharegistro, p.idparte, c.nombre, p.numeroparte, tc.cantidad, tr.descripcion, 
            s.nombrestatus, pc.idestatus, pb.nombreposicion, ppb.ordensalida, ppb.salida,tc.cantidad
            FROM palletcajas pc 
            JOIN tblcantidad tc ON tc.idcantidad = pc.idcajas 
            JOIN tblrevision tr ON tr.idrevision = tc.idrevision 
            JOIN tblmodelo tm ON tm.idmodelo = tr.idmodelo
            JOIN parte p ON tm.idparte = p.idparte
            JOIN cliente c ON c.idcliente = p.idcliente 
            JOIN STATUS s ON s.idestatus = pc.idestatus
            JOIN parteposicionbodega ppb ON pc.idpalletcajas = ppb.idpalletcajas
            JOIN posicionbodega pb ON ppb.idposicion = pb.idposicion 
            JOIN ordensalida os ON pc.idpalletcajas = os.idpalletcajas
            JOIN salida sal ON os.idsalida = sal.idsalida
            WHERE tr.idrevision = $id AND os.tipo = 0
            ORDER BY pc.idpalletcajas ASC");

        return $query->result();
    }

    // Obtener datos posicion historial
    public function getDataEntradasPosicion($idposicion) {
        $query = $this->db->query("
            SELECT pc.idpalletcajas, pc.idtransferancia, pc.pallet, pc.idcajas, pc.idestatus,
            ppb.fecharegistro, p.idparte, c.nombre, p.numeroparte, tc.cantidad, tr.descripcion,
            s.nombrestatus, pc.idestatus, pb.nombreposicion, ppb.ordensalida, ppb.salida
            FROM palletcajas pc
            JOIN tblcantidad tc ON tc.idcantidad = pc.idcajas 
            JOIN tblrevision tr ON tr.idrevision = tc.idrevision
            JOIN tblmodelo tm ON tm.idmodelo = tr.idmodelo 
            JOIN parte p ON tm.idparte = p.idparte 
            JOIN cliente c ON c.idcliente = p.idcliente 
            JOIN status s ON s.idestatus = pc.idestatus 
            JOIN parteposicionbodega ppb ON pc.idpalletcajas = ppb.idpalletcajas 
            JOIN posicionbodega pb ON ppb.idposicion = pb.idposicion
            WHERE ppb.idposicion = $idposicion ");

        return $query->result();
    }

    public function getDataSalidaParcialPosicion($idposicion) {
        $query = $this->db->query("
            SELECT pc.idpalletcajas, pc.idtransferancia, pc.pallet, pc.idcajas, pc.idestatus, 
            os.fecharegistro, p.idparte, c.nombre, p.numeroparte, tc.cantidad, tr.descripcion, 
            s.nombrestatus, pc.idestatus, pb.nombreposicion, ppb.ordensalida, ppb.salida,os.caja
            FROM palletcajas pc 
            JOIN tblcantidad tc ON tc.idcantidad = pc.idcajas 
            JOIN tblrevision tr ON tr.idrevision = tc.idrevision 
            JOIN tblmodelo tm ON tm.idmodelo = tr.idmodelo
            JOIN parte p ON tm.idparte = p.idparte
            JOIN cliente c ON c.idcliente = p.idcliente 
            JOIN STATUS s ON s.idestatus = pc.idestatus
            JOIN parteposicionbodega ppb ON pc.idpalletcajas = ppb.idpalletcajas
            JOIN posicionbodega pb ON ppb.idposicion = pb.idposicion 
            JOIN ordensalida os ON pc.idpalletcajas = os.idpalletcajas
            JOIN salida sal ON os.idsalida = sal.idsalida
            WHERE ppb.idposicion = $idposicion AND os.tipo = 1 AND ppb.ordensalida = 1");

        return $query->result();
    }

    public function getDataSalidaPalletPosicion($idposicion) {
        $query = $this->db->query("
            SELECT pc.idpalletcajas, pc.idtransferancia, pc.pallet, pc.idcajas, pc.idestatus, 
            pc.fecharegistro, p.idparte, c.nombre, p.numeroparte, tc.cantidad, tr.descripcion, 
            s.nombrestatus, pc.idestatus, pb.nombreposicion, ppb.ordensalida, ppb.salida,tc.cantidad
            FROM palletcajas pc 
            JOIN tblcantidad tc ON tc.idcantidad = pc.idcajas 
            JOIN tblrevision tr ON tr.idrevision = tc.idrevision 
            JOIN tblmodelo tm ON tm.idmodelo = tr.idmodelo
            JOIN parte p ON tm.idparte = p.idparte
            JOIN cliente c ON c.idcliente = p.idcliente 
            JOIN STATUS s ON s.idestatus = pc.idestatus
            JOIN parteposicionbodega ppb ON pc.idpalletcajas = ppb.idpalletcajas
            JOIN posicionbodega pb ON ppb.idposicion = pb.idposicion 
            JOIN ordensalida os ON pc.idpalletcajas = os.idpalletcajas
            JOIN salida sal ON os.idsalida = sal.idsalida
            WHERE ppb.idposicion = $idposicion AND os.tipo = 0");

        return $query->result();
    }

}
