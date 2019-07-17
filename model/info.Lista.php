<?php
########################################################################
#INCLUDES IMPORTANTES
header("Content-Type: text/html; charset=UTF-8",true);
$pag = $_SERVER['SCRIPT_FILENAME'];
$x1 = explode("\\", $pag);
$raiz = $x1[0]."\\".$x1[1]."\\".$x1[2]."\\infofixa\\infofixa.php";
$_classes = $x1[0]."\\".$x1[1]."\\".$x1[2]."\\infofixa\\Classes.class.php";
include ($raiz);
include ($_classes);

	$cicoc = $_REQUEST['cicoc'];

    $arrayLinhasFinal = array();
	$arrayLinhas = array();

	$sqlDemandas = "SELECT  *, CONVERT(CHAR(10), DATA, 103) AS DT FROM APP_VIEW_CICOC_FERIADOS order by ANO, MES, DATA, UNIDADE";
	$sqlDemandasC = mssql_query($sqlDemandas, $conexao);
	while($sqlDemandasR = mssql_fetch_array($sqlDemandasC)){

			$arrInterno["DT"] = $sqlDemandasR["DT"];
			$arrInterno["ANO"] =  $sqlDemandasR["ANO"];
			$arrInterno["MES"] = $sqlDemandasR["MES"];
			$arrInterno["UNIDADE"] =  $sqlDemandasR["UNIDADE"];
			$arrInterno["TIPO"] = $sqlDemandasR["TIPO"];
			$arrInterno["SR"] = $sqlDemandasR["SR"];
			
	
			$arrayLinhas[] = $arrInterno;
		
    }
    
    $arrayLinhasFinal["LISTA"] = $arrayLinhas;


	#RESUMO
	/*
    $arrayLinhas2 = array();

    $sqlResumo = "SELECT     CONVERT(CHAR(10), DATAMOV, 103) AS DT, UNID, NOME_DA_UNIDADE, SR, CICOC, EMAIL_INTERNO, SUM(CASE WHEN LEFT(CONTA, 1) = '3' THEN SALDO ELSE 0 END) AS ATIVO, SUM(CASE WHEN LEFT(CONTA, 1) = '9' THEN SALDO ELSE 0 END) 
                    AS PASSIVO, SUM(SALDO) AS SALDO_FINAL
                    FROM         APP_VIEW_BATIMENTO_COMPENSADO WHERE CICOC='$cicoc'
                    GROUP BY DATAMOV, UNID, NOME_DA_UNIDADE, SR, CICOC, EMAIL_INTERNO
                    ORDER BY SR, UNID";
	$sqlResumoC = mssql_query($sqlResumo, $conexao);
	while($sqlResumoR = mssql_fetch_array($sqlResumoC)){

			$arrInterno2["DT"] = $sqlResumoR["DT"];
			$arrInterno2["UNID"] = $sqlResumoR["UNID"];
			$arrInterno2["NUNIDADE"] = $sqlResumoR["NOME_DA_UNIDADE"];
			$arrInterno2["SR"] = $sqlResumoR["SR"];
            $arrInterno2["CICOC"] = $sqlResumoR["CICOC"];
            $arrInterno2["EMAIL_INTERNO"] = $sqlResumoR["EMAIL_INTERNO"];
            $arrInterno2["ATIVO"] = number_format($sqlResumoR["ATIVO"],2,',','.');
            $arrInterno2["PASSIVO"] = number_format($sqlResumoR["PASSIVO"],2,',','.');
			$arrInterno2["SALDO"] = number_format($sqlResumoR["SALDO_FINAL"],2,',','.');
			

			$arrayLinhas2[] = $arrInterno2;
		
    }
    
    $arrayLinhasFinal["RESUMO"] = $arrayLinhas2;
	*/
	
	echo json_encode($arrayLinhasFinal);