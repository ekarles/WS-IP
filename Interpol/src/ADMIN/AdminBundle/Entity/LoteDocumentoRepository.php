<?php
namespace ADMIN\AdminBundle\Entity;
use Doctrine\ORM\EntityRepository;

class LoteDocumentoRepository extends EntityRepository{
    
    public function updateLoteDocumentoById($parameters){
        $sSql = "";
        $sMensaje = "";
        $date = new \DateTime();
        
        if( empty( $parameters['ID'] ) ){
            $parameters['ID'] = 0;
        }
        //  REEMPLAZO LOS CARACTERES COMILLA SIMPLE Y DOBLE, PARA EVITAR QUE ROMPAN EL STRING SQL
        $sMensaje = $parameters["mensajeRemoto"];
        $sMensaje = str_replace("'", "", $sMensaje);
        $sMensaje = str_replace("\"", "", $sMensaje);
        
        $sSql .= "UPDATE ADMINAdminBundle:LoteDocumento ld SET ld.mensajeRemoto = '".$sMensaje."', ";
        $sSql .= "ld.descargado = 'S', ";
        $sSql .= "ld.itemid = '".$parameters["itemid"]."', ";
        $sSql .= "ld.fechaHoraDesc = TO_DATE('".$date->format('d-m-Y H:i:s')."','DD-MM-YYYY HH24:MI:SS'), ";
        $sSql .= "ld.usuDesc = ".$parameters["UserId"]." ";
        $sSql .= "WHERE ld.id = '" . $parameters["ID"]."' ";
        $sSql .= "and ld.numeroDoc = '".$parameters["NroDoc"]."'";
        $query = $this->getEntityManager()->createQuery( $sSql  );
        $oRes = $query->getResult();
        return $oRes;
    }
    
    public function getById( $parameters ) {
        $sSql = "";
        
        if( empty( $parameters['ID'] ) ){
            $parameters['ID'] = 0;
        }
        $sSql = "SELECT ld FROM ADMINAdminBundle:LoteDocumento ld WHERE 1 = 1 ";
        
        if(!empty($parameters["ID"])){
            $sSql .= " and ld.id = " . $parameters["ID"];
        }
        
        $sSql .= " ORDER BY ld.id ASC";
        
        $query = $this->getEntityManager()->createQuery( $sSql  );
        $oRes = $query->getResult();
        return $oRes;
    }         
    
    public function getByFilter($filter){
        $sSql = "";

        if( empty( $filter['Apellido'] ) ){
            $filter['Apellido'] = "";
        }
        
        if(empty($filter['Nombre'])){
            $filter['Nombre'] = "";
        }
        
        
        if(!empty($filter["count"]) && $filter["count"]){
        	$sSql = "SELECT COUNT(ld) FROM ADMINAdminBundle:LoteDocumento ld WHERE 1 = 1 ";
        }else{
        	$sSql = "SELECT ld FROM ADMINAdminBundle:LoteDocumento ld WHERE 1 = 1 ";
        }
        
        if(!empty($filter["fDesde"])){
            $sSql .= " and ld.fecha > to_date( '".$filter["fDesde"]."', 'DD/MM/YYYY HH24:MI:SS' )";
        }
        
        if(!empty($filter["fHasta"])){
            $sSql .= " and ld.fecha <= to_date( '".$filter["fHasta"]."', 'DD/MM/YYYY HH24:MI:SS' )";
        }
        
        if(!empty($filter['Nombre'])){
            if($filter['Nombre'] != ""){
                $sSql .= " and ( upper( ld.nombre    ) like upper( '%".$filter['Nombre']   ."%' ) or upper( ld.otrosNombres   ) like upper( '%".$filter['Nombre']   ."%' ) ) ";
            }
        }
        
        if(!empty($filter['Apellido'])){
            if($filter['Apellido'] != ""){
                $sSql .= " and ( upper( ld.apellido  ) like upper( '%".$filter['Apellido'] ."%' ) or upper( ld.otrosApellidos ) like upper( '%".$filter['Apellido'] ."%' ) ) ";
            }
        }
        
        if(!empty($filter['txtTipDoc'])){
            if($filter['txtTipDoc'] != ""){
                $sSql .= " and upper( ld.tipoDoc ) like upper( '%".$filter['txtTipDoc'] ."%') ";
            }
        }
        
        if(!empty($filter['txtNroDoc'])){
            if($filter['txtNroDoc'] != ""){
                $sSql .= " and upper( ld.numeroDoc ) like upper( '%".$filter['txtNroDoc'] ."%') ";
            }
        }

        if(!empty($filter['chkError'])){
        	if($filter['chkError'] == "on"){
        		$sSql .= " and ld.mensajeRemoto is not null ";
                $sSql .= " and ld.mensajeRemoto <> 'REGISTRO ENVIADO CORRECTAMENTE' ";
                $sSql .= " and ld.mensajeRemoto <> 'NO_ERROR (0)' ";
            }
        }
        
        if(!empty($filter["count"]) && $filter["count"]){
        	$sSql .= " and (ld.descargado is null or ld.descargado <> 'S')";
        }else{
        	$sSql .= " ORDER BY ld.fecha DESC";
        }

        $query = $this->getEntityManager()->createQuery( $sSql  );
        return $query;
        
    }
    
    public function obtenerErrores24(){
        $date = new \DateTime();
        $date->modify("-1 day");
        
        $sSql = "SELECT ld FROM ADMINAdminBundle:LoteDocumento ld";
        $sSql .= " WHERE ld.fecha > to_date( '".$date->format("d/m/Y H:i:s")."', 'DD/MM/YYYY HH24:MI:SS' ) ";
        $sSql .= " AND ld.mensajeRemoto <> 'REGISTRO ENVIADO CORRECTAMENTE' ";
        $sSql .= " AND ld.mensajeRemoto <> 'NO_ERROR (0)' ";
        
        return  $query = $this->getEntityManager()->createQuery( $sSql )->getResult();
    }
    
}
