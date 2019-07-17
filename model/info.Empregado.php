<?php 
########################################################################
#INCLUDES IMPORTANTES
$pag = $_SERVER[SCRIPT_FILENAME];
$x1 = explode("\\", $pag);
$raiz = $x1[0]."\\".$x1[1]."\\".$x1[2]."\\infofixa\\infofixa.php";
$_classes = $x1[0]."\\".$x1[1]."\\".$x1[2]."\\infofixa\\Classes.class.php";
require_once ($raiz);
require_once ($_classes);

#######################################################################
#Informaï¿½ï¿½es do Empregado


$empregadoObj = new Empregado; # INSTANCIANDO NOVO OBJETO COM BASE NA CLASSE Empregado
//$empregadoObj->informaVisitantes($conexPdo);  # REGISTRANDO ACESSO
$apresantacaoArr = array();
$apresentacaoStr = $empregadoObj->getEmpregadoInfo($conexPdo);
$apresantacaoArr = $empregadoObj->getEmpregadoArr();
$matricula = $empregadoObj->matricula;
$_NOME = $apresantacaoArr[1];
$_LOT= $apresantacaoArr[2]; // LOT
$_LOFI = $apresantacaoArr[3]; // LOFI
$_LOFI_NOME = $apresantacaoArr[4]; //NOME DA LOFI
$_FC = $apresantacaoArr[5]; //FUNï¿½ï¿½O
$acessoEspecial = $empregadoObj->getAcessoEspecial($matricula); # CONFERINDO ACESSO ESPECIAL

#######################################################################
#Array com matriculas do agentes de RH autorizados (para qualquer setor)
$faseObj = new PensandoJuntosFazendoDiferente;
$desenvolvedor = $faseObj->confereDesenvolvedor($matricula);
$gestor = $faseObj->confereGestor($matricula);



$arrInterno["MATRICULA"] = strtoupper($matricula);
$arrInterno["NOME"] = strtoupper($_NOME);
$arrInterno["USUARIO_LOGADO"] = array(strtoupper($matricula), $_NOME, $_FC );
$arrInterno["ACESSO_ESPECIAL"] = $acessoEspecial;
$arrInterno["APRESENTACAO"] = $empregadoObj->getApresentacao();
$arrInterno["UNIDADE_LOFI"] = $_LOFI;




# PERFIL DE ACESSO - CICOC DO EMPREGADO
#######################################################################

$sqlSr0 = "SELECT NOME_DA_UNIDADE, TIPO_DE_UNIDADE FROM UNIDADES_CAIXA WHERE CGC = '$_LOT' ";
$sqlSrC0 = mssql_query($sqlSr0,$conexao);
$sqlSrR0 = mssql_fetch_array($sqlSrC0);
$_TIPO_UNIDADE = $sqlSrR0['TIPO_DE_UNIDADE'];
$_NOME_UNIDADE = $sqlSrR0['NOME_DA_UNIDADE'];

$arrInterno['TIPO_DE_UNIDADE'] = $_TIPO_UNIDADE;

$_cicoc = '';

//Verifica o Tipo da Unidade
switch ($_TIPO_UNIDADE) {

    case 'CR':

        // Verifica se é unidade de conciliação
        if(strpos($_NOME_UNIDADE, 'CONCILIACAO') !== false){
            $_cicoc = $_LOT;
        }else{
            $_cicoc = '5401';
        }
        
       
        break;

    case 'SR':

        $sqlSr = "SELECT CICOC FROM UNIDADES_CICOC_CAIXA WHERE SR = '$_LOT' ";
        $sqlSrC = mssql_query($sqlSr,$conexao);
        $sqlSrR = mssql_fetch_array($sqlSrC);
        
        $_cicoc = $sqlSrR['CICOC'];

        break;

    case 'AGE':

        $sqlSr = "SELECT CICOC FROM UNIDADES_CICOC_CAIXA WHERE CGC = '$_LOT' ";
        $sqlSrC = mssql_query($sqlSr,$conexao);
        $sqlSrR = mssql_fetch_array($sqlSrC);
        
        $_cicoc = $sqlSrR['CICOC'];

        break;    

    case 'PA':

        $sqlSr = "SELECT CICOC FROM UNIDADES_CICOC_CAIXA WHERE CGC = '$_LOT' ";
        $sqlSrC = mssql_query($sqlSr,$conexao);
        $sqlSrR = mssql_fetch_array($sqlSrC);
        
        $_cicoc = $sqlSrR['CICOC'];

        break;

    case 'GI':

        $sqlSr = "SELECT TOP 1 CICOC FROM UNIDADES_CICOC_CAIXA WHERE GIRET = '$_LOT' ";
        $sqlSrC = mssql_query($sqlSr,$conexao);
        $sqlSrR = mssql_fetch_array($sqlSrC);
        
        $_cicoc = $sqlSrR['CICOC'];

        break;  

    case 'GN':     

        $_cicoc = '5401';
    
    default:

        $_cicoc = '5401';
        break;
}

$arrInterno['CICOC'] = $_cicoc;



$arrayLinhas[] = $arrInterno;

echo json_encode($arrInterno);
