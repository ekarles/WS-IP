<?php
namespace SEGURIDAD\SeguridadBundle\Entity;
use Doctrine\ORM\EntityRepository;

/**
*  InstitucionRepository
*/
class PerfilRepository extends EntityRepository{
             
    
    public function getByFilter($filter){
    	
        $sSql = "SELECT i FROM SEGURIDADSeguridadBundle:Perfil i WHERE 1 = 1 ";
        
        if(!empty($filter['txtNombre'])){
            if($filter['txtNombre'] != ""){
                $sSql .= " and upper(i.nombre) like upper( '%".$filter['txtNombre']   ."%' )";
            }
        }
        
        if(!empty($filter['txtDescripcion'])){
            if($filter['txtDescripcion'] != ""){
                $sSql .= " and upper(i.descripcion) like upper( '%".$filter['txtDescripcion']   ."%' )";
            }
        }
        
        $sSql .= " ORDER BY i.id DESC";
        
        $query = $this->getEntityManager()->createQuery( $sSql  );
        
        return $query;
        
    }
    
}
