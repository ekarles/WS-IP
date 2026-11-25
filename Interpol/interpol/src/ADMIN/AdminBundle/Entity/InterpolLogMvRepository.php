<?php
namespace ADMIN\AdminBundle\Entity;
use Doctrine\ORM\EntityRepository;
use ADMIN\AdminBundle\Entity\InterpolLogMv;
use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\ORM\Query\ResultSetMapping;

include_once("/apache/includes/ambiente.php");

/**
*  InterpolLogMvRepository
*/
class InterpolLogMvRepository extends EntityRepository{
    
    
    public function getByFilter($filter){
    	
        $sSql = "SELECT i FROM ADMINAdminBundle:InterpolLogMv i WHERE 1=1 ";
        
        if(!empty($filter['txtUsuario'])){
            if($filter['txtUsuario'] != ""){
                $sSql .= " and upper(i.usuario) like upper( '%".$filter['txtUsuario']   ."%' )";
            }
        }
        
        $sSql .= " ORDER BY i.iaId DESC";
       
        $query = $this->getEntityManager()->createQuery( $sSql  );
        
        return $query;
    }
    
    
    public function ultimasConsultasList($filter){
        
        $filtro = "";
        $currentDate = new \Datetime();
        $now = $currentDate->format('d/m/Y H:i:s');
        $lastDay = (AMBIENTE=="DESARROLLO")?"3600":"1/(24*60)";
        
        if(isset($filter['institucion']) && !empty($filter['institucion'])){
            $filtro = " INNER JOIN SEGURIDADSeguridadBundle:Dependencia d ON i.iaUsuarioDependencia = d.nombre AND d.institucionId = ". $filter['institucion'] ;
        }
        
        $qa = "SELECT COUNT(i) FROM ADMINAdminBundle:InterpolLogMv i  ". $filtro ." WHERE i.iaTimestamp > TO_DATE('$now','DD/MM/YYYY HH24:MI:SS') - $lastDay AND i.iaSistema ='INTERPOL'";
        
        $qb = "SELECT COUNT(i) FROM ADMINAdminBundle:InterpolLogMv i  ". $filtro ." WHERE i.iaTimestamp > TO_DATE('$now','DD/MM/YYYY HH24:MI:SS') - $lastDay AND i.iaResultCode NOT IN('NO_ANSWER','NO_ERROR') AND i.iaSistema ='INTERPOL'";
        
        $qc = "SELECT TO_CHAR(AVG(i.iaTimeResponse)/1000, 'FM999999999999999990.00') AS PROMEDIO, TO_CHAR(MAX(i.iaTimeResponse)/1000, 'FM999999999999999990.00') AS MAXIMO, TO_CHAR(MIN(i.iaTimeResponse)/1000, 'FM999999999999999990.00') AS MINIMO
		FROM ADMINAdminBundle:InterpolLogMv i  ". $filtro ." WHERE i.iaTimestamp > TO_DATE('$now','DD/MM/YYYY HH24:MI:SS') - $lastDay AND i.iaSistema ='INTERPOL' AND i.iaConsultaTipo <> 'NOMINALSIMAGE'";
        
        $resultA = $this->getEntityManager()->createQuery( $qa )->getSingleScalarResult();
        $resultB = $this->getEntityManager()->createQuery( $qb )->getSingleScalarResult();
        $resultC = $this->getEntityManager()->createQuery( $qc )->getResult();
        
        if(AMBIENTE=='DESARROLLO'){
            $resultA=rand(100,500);
            $resultB=rand(0,5); 
            $resultC[0]['MAXIMO']+=rand(-700,300);
            $resultC[0]['MINIMO']+=rand(0,300);
            $resultC[0]['PROMEDIO']=rand(100,250);
        }
        
        return ["consultas"=>$resultA,"errores"=>$resultB,"tiempos"=>$resultC[0]];
        
    }
    
    
    public function consultasDependencias($filter){
        
        $filtro = "";
        $currentDate = new \Datetime();
        $now = $currentDate->format('d/m/Y H:i:s');
        $lastDay = (AMBIENTE=="DESARROLLO")?"3600":"1/(24*60)";
        
        if(isset($filter['institucion']) && !empty($filter['institucion'])){
            $filtro = " INNER JOIN SEGURIDADSeguridadBundle:Dependencia d ON i.iaUsuarioDependencia = d.nombre AND d.institucionId = ". $filter['institucion'] ;
        }
        
        $qa = "SELECT i.iaUsuarioDependenciaId AS DEPENID, i.iaUsuarioDependencia AS DEPEN,
               (SELECT COUNT(d) FROM SEGURIDADSeguridadBundle:Dependencia d WHERE i.iaUsuarioDependencia = d.nombre) AS EXISTE,
               COUNT(i) CONSULTAS
               FROM ADMINAdminBundle:InterpolLogMv i  ". $filtro ."
               WHERE i.iaTimestamp > TO_DATE('$now','DD/MM/YYYY HH24:MI:SS') - $lastDay AND i.iaSistema ='INTERPOL'
               GROUP BY i.iaUsuarioDependenciaId, i.iaUsuarioDependencia
               ORDER BY EXISTE, CONSULTAS DESC";
        
        $resultA = $this->getEntityManager()->createQuery( $qa )->getResult();
        
        return $resultA;
    }
    
    public function consultas24CP(){
        
        $filtro = "";
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('FECHA', 'fecha');
        $rsm->addScalarResult('CONSULTAS', 'consultas');

        $currentDate = new \Datetime();
        
        if(AMBIENTE=="DESARROLLO"){
            $now = "21/07/2018 10:00:00";
        }else{
            $now = $currentDate->format('d/m/Y H:i:s');
        }
        
        $lastDay = "1/(24*60)";
        $lastWeek = "7";
        

        if(isset($filter['institucion']) && !empty($filter['institucion'])){
            $filtro = " INNER JOIN DEPENDENCIA d ON i.IA_USUARIO_DEPENDENCIA = d.NOMBRE AND d.INSTITUCION_ID = ". $filter['institucion'] ;
        }
        
        $qa = "
            SELECT TO_CHAR(TRUNC(i.IA_TIMESTAMP), 'mm-dd') AS FECHA,
                COUNT(*) AS CONSULTAS
            FROM INTERPOL_LOG_MV i
            $filtro
            WHERE i.IA_TIMESTAMP >= TRUNC(TO_DATE('$now', 'DD/MM/YYYY HH24:MI:SS') - $lastWeek)
            AND i.IA_SISTEMA = 'INTERPOL'
            GROUP BY TRUNC(i.IA_TIMESTAMP)
            ORDER BY TRUNC(i.IA_TIMESTAMP)
        ";


        $institucion = null;
        
        if(isset($filter['institucion']) && !empty($filter['institucion'])){
            $institucion = $filter['institucion'];
        }
        
        $dias = 5000;
        $error = "";

        $qb = "SELECT COUNT(i) FROM ADMINAdminBundle:InterpolLogMv i  ". $filtro ." WHERE i.iaTimestamp > TO_DATE('$now','DD/MM/YYYY HH24:MI:SS') - $lastDay AND i.iaSistema ='INTERPOL'";

        $resultA = $this->getEntityManager()->createNativeQuery( $qa, $rsm )->getResult();
        
        $resultB = $this->getEntityManager()->createQuery( $qb )->getResult();
        

        if(AMBIENTE=="DESARROLLO"){
            $resultB = '169';
        }
        
        $result = array("cp"=>$resultA, "consultas24"=>$resultB);
        $json = json_encode($result);
     
        return $json;
        
    }


    public function consultasHoy($filter=[]){
        $filtro = "";
        $currentDate = new \Datetime();
        $now = $currentDate->format('d/m/Y').' 00:00:00';
        
        if(isset($filter['institucion']) && !empty($filter['institucion'])){
            $filtro = " INNER JOIN SEGURIDADSeguridadBundle:Dependencia d ON i.iaUsuarioDependencia = d.nombre AND d.institucionId = ". $filter['institucion'] ;
        }
        
        $qa = "SELECT COUNT(i) FROM ADMINAdminBundle:InterpolLogMv i  ". $filtro ." WHERE i.iaTimestamp >= TO_DATE('$now','DD/MM/YYYY HH24:MI:SS') AND i.iaSistema ='INTERPOL'";
        
        $resultA = $this->getEntityManager()->createQuery( $qa )->getSingleScalarResult();
        
        if(AMBIENTE=="DESARROLLO"){
            $resultA = '179243';
        }

        return $resultA;
    }    
    
 }
 
?>