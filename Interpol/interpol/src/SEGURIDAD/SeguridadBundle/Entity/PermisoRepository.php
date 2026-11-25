<?php
namespace SEGURIDAD\SeguridadBundle\Entity;
use Doctrine\ORM\EntityRepository;

/**
*  PermisoRepository
*/
class PermisoRepository extends EntityRepository{
             
    
    public function getByFilter($filter){
    	
        $sSql = "SELECT i FROM SEGURIDADSeguridadBundle:Permiso i WHERE 1 = 1 ";
        
        //$sSql .= " ORDER BY i.id DESC";
        
        $query = $this->getEntityManager()->createQuery( $sSql  );
        
        return $query;
        
    }
    
}
