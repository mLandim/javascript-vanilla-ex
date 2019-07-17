
// Marcelo Landim - c078868
// 17/07/2019
// Vanilla Javascript + jQuery para Requisições Ajax
// Module Pattern - isolando escopo local e organizando código
let app = (function(){

    // Limitando visibilidade dos dados
    let $private = {

        data:{
            sistema:{
                usuario:{
                    matricula: null,
                    nome: null,
                    cicoc: null
                }
            },
            centralizadoraSel:null,
            database:[],
            databaseOriginal:[],
            srs:['2640','2641','2642','2650','2651','2655','2692'],
            srSel:'TODAS',
            baseMes:['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
            mes:null,
            ano:null
        }

    }
    let $public = {}



    // Métodos Publicos

    $public.iniciar = function(){

        console.log('App iniciado')

        $private.setDataInicial()
        $private.montaSrs()

        // Consulta Empregado retorna uma promisse
        $private.consultaEmpregado().then(resposta => {

            console.log('Resposta')
            console.log(resposta)

            // Depois da resposta da consulta empregado - consulta a base de dados e atualiza propriedade $private.data.database
            $private.consultaBaseDados().then(resposta2 => {
                
                $private.data.databaseOriginal = resposta2
                $private.filtraBaseDados()
                                              

            }).catch(error2 => {
                console.error(`Erro: ${error2}`)
            })


        }).catch(error => {
            console.error(`Erro: ${error}`)
        })
        

    }

    $public.mudaMes = function(tipo){

        if(tipo===1){
            if($private.data.mes == 11){
                
                $private.data.mes = 0
                $private.data.ano++
           
            }else{
                $private.data.mes++
            }
        }else{
            if($private.data.mes == 0){
                
                $private.data.mes = 11
                $private.data.ano--
           
            }else{
                $private.data.mes--
            }

        }


        $private.liberaCalendario($private.data.mes, $private.data.ano)
        
    }

    $public.setSr = function(sr){

        $private.data.srSel = sr
        $private.filtraBaseDados()
        

    }


    // Métodos privados

    $private.montaSrs = function() {

        let srsDom = document.querySelector('.srs-label')
        let listaSrs = '<span>Selecione a Sr: </span><div class="sr-comando sr-sel">TODAS</div>'

        for (var index = 0; index < this.data.srs.length; index++) {
            var element = this.data.srs[index];

            listaSrs += '<div class="sr-comando">'+element+'</div>'

            
        }

        srsDom.innerHTML = listaSrs

    }

    $private.setDataInicial = function(){

        console.log('setDataInicial')
        $private.data.ano = new Date().getFullYear()
        console.log('>> '+$private.data.ano)
        $private.data.mes = new Date().getMonth()
        console.log('>> '+$private.data.mes)

    }

    $private.consultaEmpregado = async function(){
        
        let self = this // Neste caso o this = $private
        $.ajaxSetup({ cache: false}) // Impedindo o cache na leitura dos arquivos json
        // Aguarda finalização da requisição para continuar
        await $.getJSON('./model/info.Empregado.php' , function(data){
            
            self.data.sistema.usuario.matricula = data.MATRICULA
            self.data.sistema.usuario.nome = data.NOME.trim()
            self.data.sistema.usuario.cicoc = data.CICOC
            self.data.centralizadoraSel = data.CICOC
           
        })
        return self.data

    }

    $private.consultaBaseDados = async function(){
        let resposta
        let self = this // Neste caso o this = $private
        $.ajaxSetup({ cache: false}) // Impedindo o cache na leitura dos arquivos json
        await $.getJSON('./model/info.Lista.php' , function(data){
            
            resposta = data.LISTA
           
        })
        return resposta

    }

    $private.filtraBaseDados = function(){
        console.log('filtraBaseDados')
        
        let sr = this.data.srSel
        //console.log('sr >> '+sr)
        $private.data.database = []
        if(sr==='TODAS'){
            $private.data.database = this.data.databaseOriginal
        }else{
            for (var index2 = 0; index2 < this.data.databaseOriginal.length; index2++) {
                var element2 = this.data.databaseOriginal[index2];
                //console.log(element2.SR+' x '+sr)
                if(element2.SR==sr){
                    //console.log(element2.SR+' x '+sr)
                    $private.data.database.push(element2)
                }
            }
        }
        
        //console.log($private.data.database)
        
        $private.liberaCalendario($private.data.mes, $private.data.ano)
        
    }

    $private.liberaCalendario = function(mes, ano){

        console.log('Liberando calendário')

        let esteMes = new Date(ano, mes, 1)
        //console.log(esteMes.getDay()+' >> '+esteMes.getMonth()+' >> '+esteMes.getFullYear())
        let mesLabelText = `${this.data.baseMes[esteMes.getMonth()]} de ${ano}`
        let mesLabelDom = document.querySelector('.mes-label').textContent = mesLabelText

        let idPrimeiroDia = esteMes.getDay()
        let idUltimoDia = new Date(ano, parseInt(mes+1), 0).getDate()

        let calendarioDom = document.querySelector('.calendario-datas')
        calendarioDom.innerHTML = ''
        //console.log(calendarioDom)
        let dia = 1
        for (var index = 0; index < 36; index++) {

            let element = document.createElement('div')
            let attr = document.createAttribute('class')
            let attr2 = document.createAttribute('id')
            let text

            if(index < idPrimeiroDia){
                
                //text = document.createTextNode('-')
                attr.value = 'item-no-data'
                attr2.value = 'item-no-'+index
                
            }else{

                if(dia > idUltimoDia){
                    //text= document.createTextNode('-')
                    attr.value = 'item-no-data'
                    attr2.value = 'item-no-'+index
                }else{

                    //text = document.createTextNode(dia)
                    let hojeDia = new Date().getDate()
                    let hojeMes = new Date().getMonth()
                    let hojeAno = new Date().getFullYear()
                    if(dia == hojeDia && mes === hojeMes && ano === hojeAno){
                        attr.value = 'item-data-hoje'
                    }else{
                        attr.value = 'item-data' 
                    }
                    //attr.value = 'item-data'
                    attr2.value = 'item-'+dia

                    let divData = document.createElement('div')
                    let divDataAttr = document.createAttribute('class')
                    divDataAttr.value='item-data-data'
                    divDataText = document.createTextNode(dia)
                    divData.setAttributeNode(divDataAttr)
                    divData.appendChild(divDataText)
                    element.appendChild(divData)

                    let divUnidades = document.createElement('div')
                    let divUnidadesAttr = document.createAttribute('class')
                    divUnidadesAttr.value='item-data-unidades'
                    divUnidades.setAttributeNode(divUnidadesAttr)
                    


                    for (var index2 = 0; index2 < this.data.database.length; index2++) {
                        var element2 = this.data.database[index2];
                        if( parseInt(element2.ANO)==ano && parseInt(element2.MES)==(mes+1) && parseInt(element2.DT.split('/')[0]) === dia){
                            
                            let div2 = document.createElement('div')
                            let div2Attr = document.createAttribute('class')
                            if(element2.TIPO==='MUN'){
                                div2Attr.value='feriado-municipal'
                            }else{
                                div2Attr.value='feriado-estadual'
                            }
                            let div2Text = document.createTextNode(element2.UNIDADE)
                            div2.setAttributeNode(div2Attr)
                            div2.appendChild(div2Text)
                            divUnidades.appendChild(div2)

                            
                        }else{

                        }

                    }

                    element.appendChild(divUnidades)


                }

               
                dia++
               
            }
            
            element.setAttributeNode(attr)
            element.setAttributeNode(attr2)
            //element.appendChild(text)
            calendarioDom.appendChild(element)
            
        }


    }



    // Retornando apenas dados e métodos públicos
    return $public

})()


// Iniciando aplicação
app.iniciar()

// Listeners vanilla
document.querySelector('i[class*="volta-mes"]').addEventListener('click', function(){
    console.log('volta mes')
    app.mudaMes(-1)
})
document.querySelector('i[class*="avanca-mes"]').addEventListener('click', function(){
    console.log('avanca mes')
    app.mudaMes(1)
})

// Forçando o NodeList (retornado pelo querySelectorAll) a ter o mesmo comportamento de um array em relação ao método forEach
NodeList.prototype.forEach = Array.prototype.forEach
document.querySelectorAll('div[class*="sr-comando"]').forEach(function(el){

    el.addEventListener('click', function () {

        console.log(this.textContent)
        let sr = this.textContent
        let srAnterior = document.querySelector('div[class*="sr-sel"]')
        srAnterior.setAttribute('class', 'sr-comando')
        this.setAttribute('class','sr-comando sr-sel')
        app.setSr(sr)
    
    })

})


// Listeners jQuery
/*
$(document).on('click','i[class*="volta-mes"]', function(){
    console.log('volta mes')
    app.mudaMes(-1)
})
$(document).on('click','i[class*="avanca-mes"]', function(){
    console.log('avanca mes')
    app.mudaMes(1)
})
$(document).on('click','div[class*="sr-comando"]', function(){
    console.log(this.textContent)
    let sr = this.textContent
    let srAnterior = document.querySelector('div[class*="sr-sel"]')
    srAnterior.setAttribute('class', 'sr-comando')
    this.setAttribute('class','sr-comando sr-sel')
    app.setSr(sr)
})
*/