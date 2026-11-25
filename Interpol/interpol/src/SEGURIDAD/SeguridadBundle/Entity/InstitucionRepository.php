<?php
namespace SEGURIDAD\SeguridadBundle\Entity;
use Doctrine\ORM\EntityRepository;

/**
*  InstitucionRepository
*/
class InstitucionRepository extends EntityRepository{
             
    
    public function getByFilter($filter){
    	
        $sSql = "SELECT i FROM SEGURIDADSeguridadBundle:Institucion i WHERE 1=1 ";
        
        if(!empty($filter['txtNombre'])){
            if($filter['txtNombre'] != ""){
                $sSql .= " and upper(i.nombre) like upper( '%".$filter['txtNombre']   ."%' )";
            }
        }
        $sSql .= " ORDER BY i.id DESC";
        
        $query = $this->getEntityManager()->createQuery( $sSql  );
        
        return $query;
        
    }
    
    public function getAll(){
        
        $sSql = "SELECT i FROM SEGURIDADSeguridadBundle:Institucion i ORDER BY i.nombre";
        
        $query = $this->getEntityManager()->createQuery( $sSql );
        
        return $query->getResult();
        
    }
    
}
