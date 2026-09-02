<?php

namespace GESTION\GestionBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ConsultaLoteRepository
 *
 */
class ConsultaLoteRepository extends EntityRepository
{
    
    public function getByFilterNew($filter){
       
        $query = $this->createQueryBuilder('cl')->select('cl.id','cl.fecAlta', 'cl.tipoLote', 'cl.archivoNombre', 'cl.estado', 'cl.error');
        $query->join("cl.usuario","u");
        $query->addSelect('u.usuario');
        
        $query->join("cl.consultaLoteDetalle","ld");
        $query->addSelect(",(".$cantdet_query->getDQL().") as status");
        
        
        if($filter["fdesde"]!=''){
            $query->andWhere("cl.fecAlta >= to_date(:fdesde,'DD/MM/YYYY HH24:MI:SS')");
            $query->setParameter(":fdesde", $filter["fdesde"]);
        }
        
        if($filter["fhasta"]!=''){
            $query->andWhere("cl.fecAlta <= to_date(:fhasta,'DD/MM/YYYY HH24:MI:SS')");
            $query->setParameter(":fhasta", $filter["fhasta"]);
        }
        
        if($filter['usuario'] != ""){
            $query->andWhere("upper(u.usuario) = upper(:usuario)");
            $query->setParameter(":usuario", $filter["usuario"]);
        }
        
        if($filter['tipolote'] != ""){
            $query->andWhere("cl.tipoLote = :tipoLote");
            $query->setParameter(":tipoLote", $filter["tipoLote"]);
        }
        
        if($filter['resultado'] != ""){
            switch($filter['resultado']){
                case "F":
                    $query->andWhere("cl.estado = 'F'");
                    break;
                case "I":
                    $query->andWhere("cl.estado = 'I'");
                    break;
                case "E":
                    $query->andWhere("cl.error > 0");
                    break;
            }
            
        }
        
        $query->orderBy('cl.fecAlta', 'DESC');
        
        return $query->getQuery();
                
    }
    
    
    public function getByFilter($filter){
                
        $sSql = "SELECT cl as consultaLote, (SELECT COUNT(ld.id) FROM GESTIONGestionBundle:ConsultaLoteDetalle ld WHERE cl.id = ld.consultaLoteId) as cantDetalle 
                FROM GESTIONGestionBundle:ConsultaLote cl LEFT JOIN cl.usuario u WHERE 1=1 ";
        
        if($filter["fdesde"]!=''){
            $sSql .= " and cl.fecAlta >= to_date( '".$filter["fdesde"]."', 'DD/MM/YYYY HH24:MI:SS' )";
        }
        
        if($filter["fhasta"]!=''){
            $sSql .= " and cl.fecAlta <= to_date( '".$filter["fhasta"]."', 'DD/MM/YYYY HH24:MI:SS' )";
        }
        
        if($filter['usuario'] != ""){      
            $sSql .= " and upper(u.usuario) = upper('".$filter['usuario']."')";    
        }

        if($filter['tipolote'] != ""){
            $sSql .= " and cl.tipoLote = '".$filter['tipolote']."'";
        }
        
        if($filter['resultado'] != ""){
            switch($filter['resultado']){
                case "F":
                    $sSql .= " and cl.estado = 'F'";
                    break;
                case "I":
                    $sSql .= " and cl.estado = 'I'";
                    break;
                case "E":
                    $sSql .= " and cl.error > 0";
                    break;
            }
            
        }
        
        $sSql .= " ORDER BY cl.fecAlta DESC";

        
        $query = $this->getEntityManager()->createQuery( $sSql  );

        return $query;

    }
}
