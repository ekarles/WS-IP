<?php
namespace ADMIN\AdminBundle\Entity;
use Doctrine\ORM\EntityRepository;

/**
*  MapaRepository
*/
class MapaRepository extends EntityRepository{
                 
    public function getByFilter($filter){
    	
        $sSql = "SELECT i FROM ADMINAdminBundle:Mapa i WHERE 1=1 ";
        
        if(!empty($filter['txtDescripcion'])){
            if($filter['txtDescripcion'] != ""){
                $sSql .= " and upper(i.descripcion) like upper( '%".$filter['txtDescripcion']   ."%' )";
            }
        }
        
        if(!empty($filter['txtNombre'])){
            if($filter['txtNombre'] != ""){
                $sSql .= " and upper(i.nombre) like upper( '%".$filter['txtNombre']   ."%' )";
            }
        }
        
        $sSql .= " ORDER BY i.id DESC";
        
        $query = $this->getEntityManager()->createQuery( $sSql  );
        
        return $query;
    }
    
    
 }
?>