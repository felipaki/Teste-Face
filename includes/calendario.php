<script language="javascript">
<!--
function VerificaCalendario(campo)
{
	if (document.getElementById(campo).value == "")
	{
		setTimeout("Calendario('"+campo+"','flightSearch');",500);
		return;
	}	
}

function MontaCalendario(nome, NoBase)
{
	/*
	 * var MESnome	= new Array("Janeiro","Fevereiro","Mar�o","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro");
	 * var DIAnome	= new Array("Domingo","Segunda","Ter�a","Quarta","Quinta","Sexta","S�bado");
	 * var MESabrev	= new Array("Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez");
	 * var DIAabrev	= new Array("D","S","T","Q","Q","S","S");
	*/

	if(typeof(NoBase) == "undefined")
	{
		NoBase = ""
	}

	var MESnome			= new Array("Janeiro","Fevereiro","Mar&ccedil;o","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro");
	var DIAnome			= new Array("domingo","segunda-feira","ter&ccedil;a-feira","quarta-feira","quinta-feira","sexta-feira","s&aacute;bado");
	var MESabrev		= new Array("jan","fev","mar","abr","mai","jun","jul","ago","set","out","nov","dez");
	var DIAabrev		= new Array("D","S","T","Q","Q","S","S");
	var currentDate	= null;

	//Estilo que devera ser escrito dentro do iframe do calendario	
	var styleCalendario = "";

	styleCalendario += "<style type='text/css'>";
	styleCalendario += "<!--";
	styleCalendario += ".TDDiaMes{font-family: arial;	font-size:12pt;	font-weight: normal; text-align: right;	}";
	styleCalendario += ".TDDiaMes a{text-decoration: none; color: #000000;}";
	styleCalendario += ".TDDiaMes a:hover{border-color: #000000; background-color: #C0C0C0;}";
	styleCalendario += ".TDDiaOutroMes{font-family:arial; font-size:12pt; font-weight: normal;	text-align: right;}";
	styleCalendario += ".TDDiaOutroMes	a{text-decoration:none; color: #CCCCCC;}";
	styleCalendario += ".TDDiaOutroMes	a:hover{border-color: #000000; background-color: #C0C0C0;}";
	styleCalendario += ".TDHoje	{color:#000000; background-color: #C0C0C0; border-width:1; border:solid thin #000000;}";
	styleCalendario += ".TDHoje	a{color:#000000; text-decoration:none;}";
	styleCalendario += ".CalendarioTB{border-width:1; border:solid thin #000000;	background-color: White;}";
	styleCalendario += ".CalendarioNavigator{font-family:arial;	font-size:12pt;	font-weight:bold; background-color:#C0C0C0; text-align:center; 	text-decoration:none; height: 10px;}";
	styleCalendario += ".CalendarioNavigator a{text-decoration:none; color: #000000;}";
	styleCalendario += ".CalendarioDiasSemana{font-family:arial; color: #000000; font-weight: bold; font-size:12pt;text-align:right; border:solid thin #000000;border-width:0 0 1 0;}";
	styleCalendario += ".CalendarioPeriodo{font-family:arial; color: #000000; font-weight: bold; font-size:12pt;}";	
	styleCalendario += "</style>";

	//Monta o cabecalho do Iframe
	var cabecalho = "";

	cabecalho += "<HTML><HEAD>";
	cabecalho += "<TITLE> Calendario</TITLE>";
	cabecalho += styleCalendario;
	cabecalho += "</HEAD>";
	cabecalho += "<BODY bgcolor='#ffffff' leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>";
	
	//Monta o rodap� do Iframe
	var abre	= "<";
	var fecha	= ">;";

	var rodape = "";
	rodape += "</BODY></HTML>";
	
	if (getData(nome) != null){
		currentDate = getData(nome);
	}	

	var now = new Date();
	
	if (currentDate == null){
		currentDate = now;
	}
	
	if (arguments.length > 2){
		var Mes = arguments[2];
	}else{
		var Mes = currentDate.getMonth()+1;
	}

	if (arguments.length > 3 && arguments[3]>0 && arguments[3]-0==arguments[3]){
		var Ano = arguments[3];
	}else{
		var Ano = currentDate.getFullYear();
	}
	
	var qtdDiasMes= new Array(0,31,28,31,30,31,30,31,31,30,31,30,31);
	
	if (((Ano%4 == 0)&&(Ano%100 != 0)) || (Ano%400 == 0)) {
		qtdDiasMes[2] = 29;
	}
	
	var mes_atual = new Date(Ano,Mes-1,1);
	var AnoExibir = Ano;
	var MesExibir = Mes;
	var DataExibir= 1;
	var DiaSemana= mes_atual.getDay();
	var DiaSemanaInicial = 0;
	var offset = 0;
	var	offset = (DiaSemana >= DiaSemanaInicial) ? DiaSemana-DiaSemanaInicial : 7-DiaSemanaInicial+DiaSemana ;
	
	if (offset > 0){
		MesExibir--;
		if (MesExibir < 1){
			MesExibir = 12; AnoExibir--;
		}
		DataExibir = qtdDiasMes[MesExibir]-offset+1;
	}
	
	var ProxMes = Mes+1;
	var ProxMesAno = Ano;
	
	if (ProxMes > 12){
		ProxMes=1; ProxMesAno++;
	}
	
	var UltMes = Mes-1;
	var UltMesAno = Ano;
	
	if (UltMes < 1){
		UltMes=12; UltMesAno--;
	}
	
	var date_class;		
	var calendario = "";
	calendario += "<TABLE id='tbCalendario' CLASS=CalendarioTB WIDTH=250 HEIGHT=200 BORDER=0 BORDERWIDTH=1 CELLSPACING=0 CELLPADDING=2><TR><TD ALIGN=CENTER><CENTER>";
	calendario += "<TABLE CLASS=CalendarioNavigator><TR><TD WIDTH='20' HEIGHT='10'><A HREF='javascript:parent.Calendario(\""+nome+"\",\""+NoBase+"\","+UltMes+","+UltMesAno+")'>&lt;&lt;</A></TD><TD WIDTH='100'><SPAN CLASS='CalendarioPeriodo'>"+ MESnome[(Mes-1)] +"</SPAN></TD><TD WIDTH='20'><A HREF='javascript:parent.Calendario(\""+nome+"\",\""+NoBase+"\","+ProxMes+","+ProxMesAno+")'>&gt;&gt;</A></TD><TD WIDTH='10'>&nbsp;</TD><TD WIDTH='20'><A HREF='javascript:parent.Calendario(\""+nome+"\",\""+NoBase+"\","+Mes+","+(Ano-1)+")'>&lt;&lt;</A></TD><TD WIDTH='60'><SPAN CLASS='CalendarioPeriodo'>"+ Ano +"</SPAN></TD><TD WIDTH='20'><A HREF='javascript:parent.Calendario(\""+nome+"\",\""+NoBase+"\","+Mes+","+(Ano+1)+")'>&gt;&gt;</A></TD></TR></TABLE>";
	calendario += "<TABLE WIDTH=120 CLASS=CalendarioDiasSemana BORDER=0 CELLSPACING=0 CELLPADDING=1 ALIGN=CENTER  HEIGHT=140>";
	calendario += "<TR>";	

	for (var iDia=0; iDia<7; iDia++){
		calendario += "<TD WIDTH='14%' CLASS=CalendarioDiasSemana>"+ DIAabrev[(DiaSemanaInicial+iDia)] +"</TD>\n";
	}	
	
	calendario += "</TR>\n";	

	for (var row=1; row<=6; row++)
	{
		calendario += "<TR>\n";
		for (var col=1; col<=7; col++)
		{
			var disabled=false;	var css = "";
			if ((MesExibir == currentDate.getMonth()+1) && (DataExibir==currentDate.getDate()) && (AnoExibir==currentDate.getFullYear())){
				css = "TDHoje";
			}
			else if (MesExibir == Mes) {
				css = "TDDiaMes";
			}
			else{
				css = "TDDiaOutroMes";
			}
			
			var DataSelec = DataExibir;
			var MesSelec = MesExibir;
			var AnoSelec = AnoExibir;
			var d = new Date(AnoSelec,MesSelec-1,DataSelec,0,0,0,0);
			
			d.setDate(d.getDate() + (7-col));
	
			if (AnoSelec < 1000){
				AnoSelec += 1900;
			}
			
			if (MesSelec < 10){
				MesSelec = 0+MesSelec.toString();
			}
			
			if (DataSelec < 10){
				DataSelec = 0+DataSelec.toString();
			}

			calendario += "<TD WIDTH='14%' class='"+css+"'><a href=\"javascript:parent.setData('"+DataSelec+"/"+MesSelec+"/"+AnoSelec+"','"+nome+"');parent.hideCalendario();\">";
							
			if(DataExibir<10){
				calendario +="&nbsp;&nbsp;";
			}
			calendario += MarcaDataAtual(DataExibir, MesExibir, AnoExibir) + "</a></TD>\n";
			DataExibir++;
			
			if (DataExibir > qtdDiasMes[MesExibir]){
				DataExibir=1;
				MesExibir++;
			}
			if (MesExibir > 12){
				MesExibir=1;
				AnoExibir++;
			}								
		}
		calendario += "</TR>";		
	}		

	var DiaSemanaAtual = now.getDay() - DiaSemanaInicial;
	if (DiaSemanaAtual < 0) {
		DiaSemanaAtual += 7;
	}		

	calendario += "<TR>";
	calendario += "<TD CLASS=CalendarioNavigator COLSPAN=7 ALIGN=CENTER ><A HREF=\"javascript:parent.setData('<?php print(date('d/m/Y')); ?>','"+nome+"');parent.hideCalendario();\">selecionar hoje</A><BR></TD>";
	calendario += "</TR>";
	calendario += "</TABLE></TD></TR></TABLE>";

	var frmCalendario = frames['calendarioFrame'];

	frmCalendario.document.open();
	frmCalendario.document.write(cabecalho + calendario + rodape);
	frmCalendario.document.close();
}

function complementaValor(valor)
{
	var _valor = new String();
	_valor = valor.toString();
	if (_valor.length < 2){
		return '0' + _valor
	}
	else
		return _valor
}

function getCharNumer(texto, caracter)
{
	var cont = 0;
	var posi = 0;
	
	if (texto.indexOf(caracter, posi) >= 0)
	{
		while (texto.indexOf(caracter, posi) != -1)
		{
			posi = texto.indexOf(caracter, posi) + 1;
			cont++;
		}
	}
	return cont;
}

function MarcaDataAtual(dia, mes, ano)
{
	var datadeHoje = '<?php print(date('d/m/Y')); ?>';
	var data = '' + ano ;

	data += complementaValor(mes);
	data += complementaValor(dia);		

	if (datadeHoje == data)
		return '<b>' + complementaValor(dia) + '</b>';
	else
		return complementaValor(dia);
}

function getPosicao(campo, NoBase)
{
	campo = campo + "_anchor"
	var posicao = new Object();
	var obj;
	var continuar;
	continuar = true;
	
	if (typeof(NoBase) == "undefined")
	{
		NoBase = ""
	}
	
	if (document.getElementById && document.all)
	{
		if (isNaN(window.screenX))
		{
			obj = document.getElementById(campo);
			posicao.x = obj.offsetLeft;
			while ((obj=obj.offsetParent) != null)
			{
				if(obj.id==NoBase && NoBase!="")
				{
					continuar = false;
				}
				if(continuar)
				{
					posicao.x += obj.offsetLeft;
				}
			}
			if(continuar)
			{
				posicao.x = posicao.x - document.body.scrollLeft;
			}
			continuar = true;
			obj = document.getElementById(campo)
			posicao.y = obj.offsetTop + document.body.scrollTop;
			while((obj=obj.offsetParent) != null)
			{
				if(obj.id==NoBase && NoBase!="")
				{
					continuar = false;
				}
				if(continuar)
				{
					posicao.y += obj.offsetTop;
				}
			}
			if(continuar)
			{
				posicao.y = posicao.y - document.body.scrollTop;
			}
		}
	}
	else 
	{
		posicao.x=document.images[campo].x;
		posicao.y=document.images[campo].y;
	}

	document.onmouseup = hideCalendario;

	return posicao;
}

function Calendario(nome, NoBase)
{
	if (typeof(NoBase) == "undefined")
	{
		NoBase = ""
	}

	var posicao = getPosicao(nome, NoBase);
	var TamanhoDefinoIframe = 190;
	var posLeft = posicao.x - 75;	
	var posTop = posicao.y + 20;
	var objdata = validaData2(document.getElementById(nome), document.getElementById(nome).value);

	if (objdata == null)
	{
		document.getElementById(nome).value = "<?php print(date('d/m/Y')); ?>";
	}

	if (arguments.length > 3) {
		var _Calendario = MontaCalendario(nome, NoBase, arguments[2],arguments[3]);
	}else{
		var _Calendario = MontaCalendario(nome, NoBase);
	}

	document.getElementById("DivCalendario").style.left					= posLeft;
	document.getElementById("DivCalendario").style.top					= posTop;
	document.getElementById("DivCalendario").style.visibility		= "visible";
}

function resizeFrame(tamanhoX,tamanhoY)
{
	document.getElementById("calendarioFrame").style.height = tamanhoX;
	document.getElementById("calendarioFrame").style.width = tamanhoY;
}

function hideCalendario()
{
	document.getElementById("DivCalendario").style.visibility = "hidden";
	document.getElementById("DivCalendario").style.left = 0;
	document.getElementById("DivCalendario").style.top = 0;
}

function setData(data,campo)
{
	var objdata = validaData2(document.getElementById(campo), data);
		
	if (objdata != null)
	{
		document.getElementById(campo).value = objdata.toString;
		if (VerificaDTMaxMix(data, campo) == null)
			return;
				
		if (document.getElementById("Relaciona"+campo+"_data").value.toLowerCase() == 'true')
		{
			if (RelacionaDatas() == null)
				return;
		}
	}
}

function getData(campo)
{
	var objdata = validaData2(document.getElementById(campo), document.getElementById(campo).value);
	if (objdata != null) {return objdata.data;}
}

function SplitDate(data)
{
	var v_Data = new Array(3);
	var posicao = 0;
	var posicaoAnterior;

	posicaoAnterior = posicao; posicao = data.indexOf("/",posicao);
	v_Data[0] = data.substring(posicaoAnterior,posicao);	
	posicaoAnterior = posicao + 1; posicao = data.indexOf("/",posicao + 1);
	v_Data[1] = data.substring(posicaoAnterior, posicao);
	posicaoAnterior = posicao + 1;

	v_Data[2] = data.substring(posicaoAnterior,data.length);
	
	return v_Data;
}

function VerificaDTMaxMix(data, nomeCampo)
{
	var datas = new Array(3); 
	var campoOrigem = document.getElementById(nomeCampo);
	datas[0] = data;
	datas[1] = document.getElementById('DTMinima' + nomeCampo + '_data').value;
	datas[2] = document.getElementById('DTMaxima' + nomeCampo + '_data').value;

	var dia = new Array(3); 
	var mes = new Array(3); 
	var ano = new Array(3); 

	if(datas[1] == '' && datas[2] == '')
		return true
		
	for(var i=0;i<datas.length;i++){
		if(datas[i] != ''){
			var auxData = SplitDate(datas[i].toString());
			dia[i] = auxData[0]
			mes[i] = auxData[1]
			ano[i] = auxData[2]
		}
	}
	
	var dataEscolhida = new Date();
	if (datas[1] != '') {var dataMinimo    = new Date();}
	if (datas[2] != '') {var dataMaximo	   = new Date();}

	dataEscolhida.setFullYear(parseInt(ano[0],10),(parseInt(mes[0],10) - 1), parseInt(dia[0],10));
	if (datas[1] != '') {dataMinimo.setFullYear(parseInt(ano[1],10),(parseInt(mes[1],10) - 1), parseInt(dia[1],10));}
	if (datas[2] != '') {dataMaximo.setFullYear(parseInt(ano[2],10),(parseInt(mes[2],10) - 1), parseInt(dia[2],10));}
	
	var dataBase  = dataEscolhida.getFullYear() + '' + complementaValor(dataEscolhida.getMonth()) + '' + complementaValor(dataEscolhida.getDate());
	if (datas[1] != '') {var dataMenor = dataMinimo.getFullYear() + '' + complementaValor(dataMinimo.getMonth()) + '' + complementaValor(dataMinimo.getDate());}
	if (datas[2] != '') {var dataMaior = dataMaximo.getFullYear() + '' + complementaValor(dataMaximo.getMonth()) + '' + complementaValor(dataMaximo.getDate());}

	if(datas[1] != ''){
		if(parseFloat(dataBase,10) < parseFloat(dataMenor,10)){
			SetValorCampo(campoOrigem, complementaValor(dataMinimo.getDate()), complementaValor((dataMinimo.getMonth() + 1)), dataMinimo.getFullYear());
			return null;
		}
	}	

	if(datas[2] != ''){
		if(parseFloat(dataBase,10) > parseFloat(dataMaior,10)){
			SetValorCampo(campoOrigem, complementaValor(dataMaximo.getDate()), complementaValor((dataMaximo.getMonth() + 1)), dataMaximo.getFullYear());
			return null;
		}
	}
	
	return true;
}

function GetDataAtual(variavelData, dia, mes, ano)
{
	variavelData = complementaValor(dia) + '/' + complementaValor(mes) + '/' + ano;
}

function SetValorCampo(campoData, dia, mes, ano)
{
	campoData.value = complementaValor(dia) + '/' + complementaValor(mes) + '/' + ano;
}

function validaData2(campoData, data)
{
	var objdata = new Object();
	objdata.dia = 0;objdata.mes = 0;objdata.ano = 0;
	objdata.data = new Date();

	if(data == "")
	{
		SetValorCampo(campoData, <?php print(date('d')); ?>, <?php print(date('m')); ?>, <?php print(date('Y')); ?>);
	}
	else
	{
		var _subs = new String();
		_subs = data;

		if (getCharNumer(_subs, "/") != 2)
		{
			alert("Formato da data � inv�lido.");
			SetValorCampo(campoData, <?php print(date('d')); ?>, <?php print(date('m')); ?>, <?php print(date('Y')); ?>);
			return null;
		}

		if (VerificaDTMaxMix(data, campoData.name) == null)
			return;

		var posicao = 0;
		var posicaoAnterior;
		
		var auxData = SplitDate(_subs);
		var a = auxData[2]		
		var d = auxData[0]
		var m = auxData[1]

		if (campoData.value.length != 10)
		{
			SetValorCampo(campoData, complementaValor(d), complementaValor(m), a)
		}		

		if ((d == '' || parseInt(d, 10) == 0 || d.length > 2) || (m == '' || parseInt(m, 10) == 0 || m.length > 2) || (a == '' || parseInt(a, 10) == 0) || a.length > 4)
		{
			alert("Formato da data � inv�lido.");
			SetValorCampo(campoData, <?php print(date('d')); ?>, <?php print(date('m')); ?>, <?php print(date('Y')); ?>);
			return null;
		}
		var qtdDiasMes= new Array(0,31,28,31,30,31,30,31,31,30,31,30,31);
		if (!isNaN(a) && a.length == 4)
		{
			if ( ( (a%4 == 0)&&(a%100 != 0) ) || (a%400 == 0) ) 
			{
				qtdDiasMes[2] = 29;
			}	

			if (!isNaN(m))
			{
				if (m<0 || m>12)
				{
					alert("S� existem 12 meses");
					if (m<0)
						m = 1;
					else
						m = 12;
					SetValorCampo(campoData, d, m, a);
					return null;
				}
				else
				{
					if (!isNaN(d))
					{			
						if (d == 0 || d > qtdDiasMes[parseInt(m,10)])
						{
							alert('O m�s ' +  m.toString() + ' tem ' + qtdDiasMes[parseInt(m,10)].toString() + ' dias');
							if (d==0)
								d = 1;
							else
								d = qtdDiasMes[parseInt(m,10)];
							
							SetValorCampo(campoData, d, m, a);
							return null;
						}
						else
						{
							objdata.dia = d;
							objdata.mes = m;
							objdata.ano = a;

							objdata.toString = new String(d.toString()+"/"+m.toString()+"/"+a.toString());
  					
							objdata.data = new Date(a,m-1,d);
							return objdata;
						}
					}
					else
					{
						alert("Dia inv�lido");	
						SetValorCampo(campoData, <?php print(date('d')); ?>, m, a);
						return null;
					}
				}
			}
			else
			{
				alert("M�s inv�lido");
				SetValorCampo(campoData, d, <?php print(date('m')); ?>, a);
				return null;
			}
		}
		else
		{
			if (a.length != 4)
				alert("Por favor, utilize o ano com 4 digitos.");
			else
				alert("Ano inv�lido");
			SetValorCampo(campoData, d, m, <?php print(date('Y')); ?>);
			return null;
		}
	}
}

function formataData(campo, valor_campo, elemet)
{    
  var tam_campo = parseInt(valor_campo.length)
  switch(tam_campo)
  {
    case 2 :
      campo.value = valor_campo + "/";      
      break;
    case 5 :
      campo.value = valor_campo + "/";      
      break;
  }
}

function ChecaKeyPress()
{
	if (typeof(ExecutarCalendario) != "undefined")
	{
		ExecutarCalendario();
	}
	else
	{
		return false;
	}
}

function ValidaDataSaida(campo)
{
	if (campo.value != "")
	{
		if (campo.value.length != 10)
		{
			campo.value = "";
			campo.focus();			
		}
	}
}
</script>