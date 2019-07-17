<?php

########################################################################
#INCLUDES IMPORTANTES
$pag = $_SERVER[SCRIPT_FILENAME];
$x1 = explode("\\", $pag);
$raiz = $x1[0]."\\".$x1[1]."\\".$x1[2]."\\infofixa\\infofixa.php";
$_classes = $x1[0]."\\".$x1[1]."\\".$x1[2]."\\infofixa\\Classes.class.php";
require_once ($raiz);
require_once ($_classes);

########################################################################
#Informações do Empregado
$empregadoObj = new Empregado;
$empregadoObj->informaVisitantes($conexPdo);  # REGISTRANDO ACESSO
$_MATRICULA = $empregadoObj->getMatricula();
$apresentacaoStr = $empregadoObj->getEmpregadoInfo($conexPdo);
$apresantacaoArr = array();
$apresantacaoArr = $empregadoObj->getEmpregadoArr();
$_NOME = $apresantacaoArr[1]; //NOME EMPREGADO
$_LTADM = $apresantacaoArr[2]; // LOT
$_LOFI = $apresantacaoArr[3]; // LOFI
$_LOFINOME = $apresantacaoArr[4]; //NOME DA LOFI
$_FC = $apresantacaoArr[5]; //FUNÇÃO


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Feriados</title>

    <!-- css -->
    <link rel="stylesheet" href="<?php echo $caminhoCssFontAwesome;?>" TYPE="text/css" /> <!-- FontAwesome (TERCEIROS) -->
    <link rel="stylesheet" href="./css/css.css" TYPE="text/css" /> <!-- Formatação específica da página -->
</head>
<body>
    
    <!-- conteúdo da página {app}-->
    <div id="app" class="f-conteudo" >

        <div class="calendario">
            <div class="calendario-comandos">
                
                <div class="mes-comandos">
                    <i class="fa fa-chevron-left fa-fw volta-mes comando"></i>
                    <i class="fa fa-chevron-right fa-fw avanca-mes comando"></i>
                </div>
                <div class="mes-label"></div>
                <div class="srs-label">
                
                </div>
            </div>
            <div class="calendario-titulo">
                <span>domingo</span>
                <span>segunda</span>
                <span>terça</span>
                <span>quarta</span>
                <span>quinta</span>
                <span>sexta</span>
                <span>sábado</span>
            </div>
            <div class="calendario-datas"></div>

        </div>

    </div>
    <div class="footer">
        Legenda: 
        <div class="feriado-municipal">Feriado Municipal</div>
        <div class="feriado-estadual">Feriado Estadual</div>
        <div class="legenda-hoje">Hoje</div>

    </div>




    <!-- Blocos com posicionamento Fixo ou Absoluto -->
	<div id="top">
        <span class="topTitle">
            <span class="iCaixa"></span>
            <span class="linha1">Feriados</span>
        </span>
        <span class="topInfo">
            <span class="userInfo"></span>
        </span> 
    </div>	
    
    <div id="barraMenu">
        <!--<i class="fa fa-bars fa-fw menuIco"></i>-->
        <!--<span class="barInfoLeft">CICOC <span id="cicoc"><i class="fa fa-refresh fa-spin fa-fw"></i></span> -  Movimento de <span id="dataMov"><i class="fa fa-refresh fa-spin fa-fw"></i></span></span>-->
        <span class="barInfo">Recomendamos o uso do navegador Mozilla Firefox <i class="fa fa-firefox fa-fw"></i></span>
    </div>


    <!-- js terceiros -->
    <script  type="text/javascript" src="<?php echo $jquery;?>"></script>
    <!-- js próprio - controlador da interface -->
    <script src="./js/app.js"></script>
    
</body>
</html>