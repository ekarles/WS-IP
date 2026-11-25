<?php

namespace SEGURIDAD\SeguridadBundle\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use JOYAS\JoyasBundle\Services\SessionManager;
use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of Menú
  */
class Menu{
    
    private $Usuario  = [];
    private $Menu     = [];
    private $SubMenu  = [];
    private $Perfiles = [];
    private $Opciones = [];
    //
    private $submenu  = [];
    
    public function __construct() {
        
    }
    
    public function getOpcionesMenu(){
        /*
         PERFIL =>  0, PERMISO => 0 , ETIQUETA => "Consultas disponibles",
         PERFIL =>  3, PERMISO => 1 , ETIQUETA => "Personas"             ,
         PERFIL =>  4, PERMISO => 3 , ETIQUETA => "Documentos"           ,
         PERFIL => 62, PERMISO => 8 , ETIQUETA => "Vehículos"            ,
         PERFIL =>  1, PERMISO => 61, ETIQUETA => "Combinada"            ,
         PERFIL =>  1, PERMISO => 5 , ETIQUETA => "Consulta por lote"    ,
         PERFIL =>  0, PERMISO => 0 , ETIQUETA => "Administración"       ,
         PERFIL =>  1, PERMISO => 5 , ETIQUETA => "Lotes WISDM"          ,
         PERFIL =>  1, PERMISO => 5 , ETIQUETA => "Lotes WISDM Carga Masiva",
         PERFIL =>  1, PERMISO => 5 , ETIQUETA => "Lotes procesados"     ,
         PERFIL =>  0, PERMISO => 0 , ETIQUETA => "Opciones Personales"  ,
         PERFIL =>  0, PERMISO => 0 , ETIQUETA => "Cambiar contraseña"   ,
         */
        $OpcionesMenu = [];
        $Opcion       = [];

        foreach ( $this->Usuario as $perfil ){
            
            $permisos = $perfil->getPermisoid();
            array_push( $this->Perfiles,  ["perfilid"=>$perfil->getId(), "perfilnombre"=>$perfil->getDescripcion()]);
            foreach ( $permisos as $permiso ){
                
                $Opcion = [
                    "perfilid"      => $perfil->getId()  ,
                    "perfilnombre"  => $perfil->getDescripcion()    ,
                    "permisoid"     => $permiso->getId(),
                    "permisonombre" => $permiso->getPermiso()
                ];
                array_push( $OpcionesMenu, $Opcion );
            }
        } 
        foreach ( $OpcionesMenu as $om){
            $TieneTitulo      = false;
            $sm["opcionmenu"] = "";
            $sm["titulo"]     = "";
            $sm["accion"]     = "";
            $sm["orden"]      = 0;
            $sm["menu"]       = 0;
            $sm = [
                "orden"=>0, 
                "perfilid"=>$om["perfilid"], 
                "permisoid"=>$om["permisoid"], 
                "titulo"=>$om["permisonombre"], 
                "opcionmenu"=>"NO", 
                "accion"=>"",
                "menu" => "",
                "iconomenu" => ""
            ];
                
            if( $om["permisoid"] == 1){
                    $sm["opcionmenu"] = "SI";
                    $sm["accion"] = "persona";
                    $sm["orden"] = 1;
                    $sm["menu"] = 1;
                    $sm["iconomenu"] = "fas fa-address-book";
            }  
            
            if( $om["permisoid"] == 3 ){
                    $sm["opcionmenu"] = "SI";
                    $sm["accion"] = "documento";
                    $sm["orden"] = 2;
                    $sm["menu"] = 1;
                    $sm["iconomenu"] = "far fa-id-card";
            }  
            
            if( $om["permisoid"] == 8 ){
                $sm["opcionmenu"] = "SI";
                $sm["accion"] = "vehiculo";
                $sm["orden"] = 3;
                $sm["menu"] = 1;
                $sm["iconomenu"] = "fas fa-car-side";
            } 
            
            if( $om["permisoid"] == 61 ){
                $sm["opcionmenu"] = "SI";
                $sm["accion"] = "combinada";
                $sm["orden"] = 4;
                $sm["menu"] = 1;
                $sm["iconomenu"] = "fas fa-project-diagram";
            }  
          
            if( $om["permisoid"] == 6 ){
                $sm["opcionmenu"] = "SI";
                $sm["titulo"] = "Usuarios";
                $sm["accion"] = "usuario";
                $sm["permisoid"] = 601;
                $sm["orden"] = 1;
                $sm["menu"] = 3;
                $sm["iconomenu"] = "fa fa-users";
                array_push($this->submenu, $sm);
                
                $sm["opcionmenu"] = "SI";
                $sm["titulo"] = "Perfiles";
                $sm["accion"] = "admin_perfil";
                $sm["permisoid"] = 602;
                $sm["orden"] = 2;
                $sm["menu"] = 3;
                $sm["iconomenu"] = "fa fa-tasks";
                array_push($this->submenu, $sm);
                
                $sm["opcionmenu"] = "SI";
                $sm["titulo"] = "Dependencias";
                $sm["accion"] = "admin_dependencia";
                $sm["permisoid"] = 603;
                $sm["orden"] = 3;
                $sm["menu"] = 3;
                $sm["iconomenu"] = "fas fa-building";
                array_push($this->submenu, $sm);
                
                $sm["opcionmenu"] = "SI";
                $sm["titulo"] = "Instituciones";
                $sm["accion"] = "admin_institucion";
                $sm["permisoid"] = 604;
                $sm["orden"] = 4;
                $sm["menu"] = 3;
                $sm["iconomenu"] = "fas fa-university";
                array_push($this->submenu, $sm);
                
                $sm["opcionmenu"] = "SI";
                $sm["titulo"] = "Mapas";
                $sm["accion"] = "admin_mapa";
                $sm["permisoid"] = 605;
                $sm["orden"] = 5;
                $sm["menu"] = 3;
                $sm["iconomenu"] = "fas fa-map-marker-alt";
                array_push($this->submenu, $sm);
                
                $sm["opcionmenu"] = "SI";
                $sm["titulo"] = "Tipos de Delito";
                $sm["accion"] = "admin_Tipodelito";
                $sm["permisoid"] = 606;
                $sm["orden"] = 6;
                $sm["menu"] = 3;
                $sm["iconomenu"] = "fas fa-mask";
                array_push($this->submenu, $sm);
            }  
            
            if( $om["permisoid"] == 5 ){
                $sm["opcionmenu"] = "SI";
                $sm["accion"] = "lote";
                $sm["orden"] = 5;
                $sm["menu"] = 1;
                $sm["iconomenu"] = "fas fa-upload";
                array_push($this->submenu, $sm);
                
                if($om["perfilid"]== 1){ 
                
                	$sm["opcionmenu"] = "SI";
	                $sm["titulo"] = "Lotes WISDM";
	                $sm["accion"] = "lotedocumento_show";
	                $sm["permisoid"] = 500;
	                $sm["orden"] = 1;
	                $sm["menu"] = 2;
	                $sm["iconomenu"] = "fas fa-passport";
	                array_push($this->submenu, $sm);
	                
	                $sm["opcionmenu"] = "SI";
	                $sm["titulo"] = "Lotes WISDM Carga Masiva";
	                $sm["accion"] = "wisdm_showCargaMasiva";
	                $sm["permisoid"] = 5012;
	                $sm["orden"] = 3;
	                $sm["menu"] = 2;
	                $sm["iconomenu"] = "fas fa-stream";
	                array_push($this->submenu, $sm);
	                
	                $sm["opcionmenu"] = "SI";
	                $sm["titulo"] = "Auditor&iacute;a WISDM";
	                $sm["accion"] = "auditoriawisdm";
	                $sm["permisoid"] = 5013;
	                $sm["orden"] = 4;
	                $sm["menu"] = 2;
	                $sm["iconomenu"] = "fas fa-database";
	                array_push($this->submenu, $sm);
	                
	                $sm["opcionmenu"] = "SI";
	                $sm["titulo"] = "Administración CNRT";
	                $sm["accion"] = "admin_cnrtpersona";
	                $sm["permisoid"] = 5014;
	                $sm["orden"] = 4;
	                $sm["menu"] = 2;
	                $sm["iconomenu"] = "fas fa-bus";
	                array_push($this->submenu, $sm);
	                
                }
                
                $sm["opcionmenu"] = "SI";
                $sm["titulo"] = "Lotes procesados";
                $sm["accion"] = "lotesprocesados";
                $sm["permisoid"] = 501;
                $sm["orden"] = 2;
                $sm["menu"] = 2;
                $sm["iconomenu"] = "fa fa-list";
                
            } 
            
            
            if( $om["permisoid"] == 11){
            	$sm["opcionmenu"] = "SI";
            	$sm["titulo"] = "Panel de Control";
            	$sm["accion"] = "en_construccion";
            	$sm["orden"] = 1;
            	$sm["menu"] = 4;
            	$sm["iconomenu"] = "fas fa-gear";
            }  
            
            if( $om["permisoid"] == 12){
                $sm["opcionmenu"] = "SI";
                $sm["titulo"] = "Alarmas";
                $sm["accion"] = "alarmas";
                $sm["orden"] = 2;
                $sm["menu"] = 4;
                $sm["iconomenu"] = "fas fa-flag";
            }  
            
            if( $om["permisoid"] == 43){
                $sm["opcionmenu"] = "SI";
                $sm["titulo"] = "Estad&iacute;sticas";
                $sm["accion"] = "en_construccion";
                $sm["orden"] = 3;
                $sm["menu"] = 4;
                $sm["iconomenu"] = "fas fa-chart-bar";
            } 
            
            if( $om["permisoid"] == 41){
                $sm["opcionmenu"] = "SI";
                $sm["titulo"] = "Reprocesar Offline";
                $sm["accion"] = "en_construccion";
                $sm["orden"] = 4;
                $sm["menu"] = 4;
                $sm["iconomenu"] = "far fa-calendar-alt";
            }
            
            if( $om["permisoid"] == 44){
                $sm["opcionmenu"] = "SI";
                $sm["titulo"] = "Dependencias";
                $sm["accion"] = "en_construccion";
                $sm["orden"] = 5;
                $sm["menu"] = 4;
                $sm["iconomenu"] = "fas fa-building";
            }
            
            if( $om["permisoid"] == 81){
            	$sm["opcionmenu"] = "SI";
            	$sm["titulo"] = "Auditor&iacute;a";
            	$sm["accion"] = "en_construccion";
            	$sm["orden"] = 6;
            	$sm["menu"] = 4;
            	$sm["iconomenu"] = "fas fa-database";
            }
            
            if( $om["permisoid"] == 122){
                $sm["opcionmenu"] = "SI";
                $sm["titulo"] = "Avisos de Movimiento";
                $sm["accion"] = "gestion_personaobservada";
                $sm["orden"] = 7;
                $sm["menu"] = 4;
                $sm["iconomenu"] = "fas fa-user-times";
            }
            
            array_push($this->submenu, $sm);
        }  
        
        $this->Opciones = $this->submenu;
        $this->crearMenu();
        $this->crearSubMenu();
        return ;    
    }
    
    private function crearMenu(){
        $perfil_id = "";
        $menu_opc = [];
        
        $existeMenu = array_search( 1, array_column( $this->submenu, 'menu' ) );
        if(is_numeric( $existeMenu ) ){
            array_push( $menu_opc, ["ORDEN"=>1, "PERFILID" => 1 ,"TITULOMENU"=>"Consultas disponibles", "ICONOMENU"=>"fa fa-search" ] );
        }
        
        $existeMenu = array_search( 2, array_column( $this->submenu, 'menu' ) );
        if(is_numeric( $existeMenu ) ){
            array_push( $menu_opc, ["ORDEN"=>2, "PERFILID" => 2 ,"TITULOMENU"=>"Administraci&oacute;n de lotes" , "ICONOMENU"=>"fa fa-list"] );
        }
        
        $existeMenu = array_search( 3, array_column( $this->submenu, 'menu' ) );
        if(is_numeric( $existeMenu ) ){
            array_push( $menu_opc, ["ORDEN"=>3, "PERFILID" => 3 ,"TITULOMENU"=>"Administraci&oacute;n", "ICONOMENU"=>"fas fa-cogs"] );
        }
        
        $existeMenu = array_search( 4, array_column( $this->submenu, 'menu' ) );
        if(is_numeric( $existeMenu ) ){
            array_push( $menu_opc, ["ORDEN"=>4, "PERFILID" => 4 ,"TITULOMENU"=>"Gesti&oacute;n", "ICONOMENU"=>"fas fa-marker"] );
        }
        
        array_push( $menu_opc, ["ORDEN"=>5, "PERFILID" => 5 ,"TITULOMENU"=>"Opciones personales", "ICONOMENU"=>"fa fa-user"] );
        $this->Menu = $menu_opc;
    }
    
    private function crearSubMenu(){
        $perfil_id = "";
        $menu_opc = [];
        foreach ( $this->submenu as $menu ){
            if( $menu["opcionmenu"] == "SI" ){
                
                $ix = -1;
                $ix = array_search( $menu["permisoid"], array_column( $this->SubMenu, 'PERMISOID' ) );
                if( !is_numeric($ix) ){
                    $menu_opc = [
                        "ORDEN"=>$menu["orden"],
                        "MENU"=>$menu["menu"],
                        "PERFILID"=>$menu["perfilid"],
                        "PERFILNOMBRE"=>$this->Perfiles[array_search($menu["perfilid"], array_column($this->Perfiles, "perfilid"))]["perfilnombre"],
                        "PERMISOID"=>$menu["permisoid"],
                        "TITULOMENU"=>$menu["titulo"],
                        "ACCION"=>$menu["accion"],
                        "ICONOMENU"=>$menu["iconomenu"]
                    ];
                    array_push($this->SubMenu, $menu_opc);
                }  
            }   
        }  
        $menu_opc = [
            "ORDEN"=>9,
            "MENU"=>5,
            "PERFILID"=>0,
            "PERFILNOMBRE"=>"Cambiar contrase&ntilde;a",
            "PERMISOID"=>0,
            "TITULOMENU"=>"Cambiar contrase&ntilde;a",
            "ACCION"=>"fos_user_change_password",
            "ICONOMENU"=>"fa fa-lock"
        ];
        array_push( $this->SubMenu, $menu_opc );
        asort($this->SubMenu);
    }
    
    public function getMenu()
    {
        return $this->Menu;
    }
    
    public function getSuMenu()
    {
        return $this->SubMenu;
    }
    
    public function getPerfiles()
    {
        return $this->Perfiles;
    }
    
    public function getOpciones()
    {
        return $this->Opciones;
    }
        
    public function setUsuario($usuario)
    {
        $this->Usuario = $usuario->getPerfilid();
        return $this;
    }
    
    public function existe($permisoId){
        $bRetorno = 0;
        
        foreach( $this->Opciones as $fila){
            if( $fila["permisoid"]== $permisoId){
                $bRetorno = 1;
                break;
            }   
        }   
        
        return $bRetorno;
    }  
}
?>