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
        //echo $sSql;
        return $query;
    }
    
    public function getByAdministradores($filter){
        
        $resultado = ["ADMIN"=>"","ADMIN_EXT"=>""];
        $cant = 0;
        $cant = count($filter);
        $in = "";
        
        for($k=0; $k<$cant; $k++ ){
            $in .= "" . $filter[$k]. ",";  
        }
        
        $in = substr( $in, 0, strlen( $in)-1);
        
        $sSql = "SELECT i FROM SEGURIDADSeguridadBundle:Perfil i WHERE 1 = 1 and i.id in (". $in .") ";
        $sSql .= " ORDER BY i.id DESC";
        
        $query = $this->getEntityManager()->createQuery( $sSql  );
        $oRes = $query->getResult();
        for($k=0; $k<$cant; $k++ ){
            if($oRes[$k]->getAdmin()==1){
                $resultado["ADMIN"]=1;
            }
            if($oRes[$k]->getAdminext()==1){
                $resultado["ADMIN_EXT"]=1;
            }
        }
        
        return $resultado;
    }
    
    
}
