<html>

	<head>
		<meta http-equiv="Page-Enter" content="blendtrans(duration=0.3)">
		<meta http-equiv="Cache-Control" content="no-cache, no-store">
		<meta http-equiv="Pragma" content="no-cache, no-store">
		<meta http-equiv="expires" content="Mon, 06 Jan 1990 00:00:01 GMT">
		<link HREF="includes/padrao.css" type="text/css" rel="stylesheet">		
		<link href="includes/bootstrap.min.css" type="text/css" rel="stylesheet"> 
		<link href="includes/bootstrap-grid.css" type="text/css" rel="stylesheet">
		<script src="includes/validacao.js" type="text/javascript"></script>
		<script src="includes/jquery.min.js" type="text/javascript"></script>
		<script language="javascript" type="text/javascript">
		function Foco()
		{
			document.getElementById("tela").focus();
		}

		function Filtrar()
		{
			if (document.getElementById("tela").value == "1")
			{
				document.getElementById("tela1").style.display			= "inline";
				document.getElementById("tela2").style.display			= "none";
				document.getElementById("tela3").style.display			= "none";
				document.getElementById("divLista").style.display		= "none";
				document.getElementById("aguardeLista").style.display	= "none";
			}
			else if (document.getElementById("tela").value == "2")
			{
				document.getElementById("tela1").style.display			= "none";
				document.getElementById("tela2").style.display			= "inline";
				document.getElementById("tela3").style.display			= "none";
				document.getElementById("divLista").style.display		= "none";
				document.getElementById("aguardeLista").style.display	= "none";
			}
			else if (document.getElementById("tela").value == "3")
			{
				document.getElementById("tela1").style.display			= "none";
				document.getElementById("tela2").style.display			= "none";
				document.getElementById("tela3").style.display			= "inline";
				document.getElementById("divLista").style.display		= "none";
				document.getElementById("aguardeLista").style.display	= "none";
			}
		}

		//*************************************************************

		function VerificaAlteracaoDeEstado2()
		{
			if (HttpRequest2.readyState != 4)
				return;

			document.getElementById(g_div2).style.display	= "inline";
			document.getElementById(g_div2).innerHTML		= ReplaceAll(HttpRequest2.responseText,"﻿","");
			
			try {
				document.getElementById(g_divAguarde2).style.display = "none";
			}
			catch(e){}
		}
		
		function AbrePagina2(url, div, divAguarde)
		{
			g_divAguarde2 = divAguarde;
			try {
				document.getElementById(g_divAguarde2).style.display = "inline";
			}
			catch(e){}

			g_div2 = div;

			HttpRequest2.open("GET",url,true);
			HttpRequest2.setRequestHeader("Content-Type","application/x-www-form-urlencoded;");
			HttpRequest2.setRequestHeader("Pragma", "no-cache");
			HttpRequest2.onreadystatechange	= VerificaAlteracaoDeEstado2;
			HttpRequest2.send(null);
		}
		
		var g_div2			= "";
		var HttpRequest2	= openAjax();

		function Pesquisar()
		{
			if (document.getElementById("tela").value == "2")
			{
				var data_inicial	= document.getElementById("data_inicial").value;
				var data_final		= document.getElementById("data_final").value;
				var sURL2			= "auxiliar.php?tela=2&data_inicial=" + data_inicial + "&data_final=" + data_final;
			}
			else if (document.getElementById("tela").value == "3")
			{
				var tipo			= document.getElementById("tipo").value;
				var data_inicial	= document.getElementById("data_inicial").value;
				var data_final		= document.getElementById("data_final").value;
				var sURL2			= "auxiliar.php?tela=3&tipo=" + tipo + "&data_inicial=" + data_inicial + "&data_final=" + data_final;
			}

			AbrePagina2(sURL2,"divLista","aguardeLista");
		}

		$(document).ready(function (e) {
			$("#form1").on('submit',(function(e) {
				e.preventDefault();
				$.ajax({
					url: "auxiliar.php?tela=1",
					type: "POST",
					data:  new FormData(this),
					contentType: false,
					cache: false,
					processData:false,
					beforeSend : function() {
						document.getElementById("divLista").style.display		= "none";
						document.getElementById("aguardeLista").style.display	= "inline";
					},
					success: function(data) {
						document.getElementById("divLista").innerHTML			= ReplaceAll(data,"﻿","");
						document.getElementById("divLista").style.display		= "inline";
						document.getElementById("aguardeLista").style.display	= "none";
					},
					error: function(e) {
						console.log(e);
					}          
				});
			}));
		});		
		</script> 
		<?php include "includes/calendario.php"; ?>
	</head>

	<body topmargin="0" leftmargin="0" bottommargin="0" rightmargin="0" onLoad="Foco()" oncontextMenu="return false;">
		<form method="post" id="form1" name="form1" enctype="multipart/form-data">
			<div id="container" class="container" style="background-color:#FFFFFF; padding:5px; margin-bottom:10px">
			<div class="col-md-12">
				<div class="col-md-12">
					<div class="row" style="padding:5px">
						<label>Opções</label><br>
						<select class="campo" id="tela" name="tela" style='width:100%;height:30px' onChange="Filtrar()">
							<option value=""></option>
							<option value="1">Importação de Vendas</option>
							<option value="2">Listagem de Vendas Realizadas</option>
							<option value="3">Indicadores de Vendas</option>
						</select>
					</div>
					<div class="row" id="tela1" style="display:none;">
						<div class="col-md-12 Filtro">
							<div class="row">
								<label>Arquivo</label>
								<input id="file" type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" name="file" style="width:100%;" />
							</div>
							<div class="row" style="padding:5px">
								<button type="submit" class="botao2" name="gerar" id="gerar">GERAR</button>
							</div>
						</div>	
					</div>
					<div class="row" id="tela2" style="display:none">	
						<div class="col-md-12 Filtro">
							<div class="row" style="padding:5px">
								<label>Data Inicial&nbsp;<a href="#" onClick="javascript:Calendario('data_inicial','flightSearch'); return false;"><img title="Calend&aacute;rio" src="images/calendario.gif" border="0" align="absmiddle" name="data_inicial_anchor" id="data_inicial_anchor"></a></label>
								<input class="campo" style="width:100%;" id="data_inicial" name="data_inicial" maxlength="10" value="" onKeyPress="return VerificaData(this, event);" onBlur="ValidaDataSaida(this)">
							</div>
							<div class="row" style="padding:5px">
								<label>Data Final&nbsp;<a href="#" onClick="javascript:Calendario('data_final','flightSearch'); return false;"><img title="Calend&aacute;rio" src="images/calendario.gif" border="0" align="absmiddle" name="data_final_anchor" id="data_final_anchor"></a></label>
								<input class="campo" style="width:100%;" id="data_final" name="data_final" maxlength="10" value="" onKeyPress="return VerificaData(this, event);" onBlur="ValidaDataSaida(this)">
							</div>
							<div class="row" style="padding:5px">
								<button type="button" class="botao2" name="pesquisar" id="pesquisar" onClick="Pesquisar()">CONSULTAR</button>
							</div>
						</div>	
					</div>
					<div class="row" id="tela3" style="display:none">
						<div class="col-md-12 Filtro">
							<div class="row" style="padding:5px">
								<label>Tipo</label><br>
								<select class="campo" id="tipo" name="tipo" style="width:100%;height:30px"> 
									<option value="1" selected>Vendas por Cliente</option> 
									<option value="2">Vendas por Serviço</option> 
									<option value="3">Vendas por M&ecirc;s</option> 
								</select> 
							</div>
							<div class="row" style="padding:5px">
								<label>Data Inicial&nbsp;<a href="#" onClick="javascript:Calendario('data_inicial','flightSearch'); return false;"><img title="Calend&aacute;rio" src="images/calendario.gif" border="0" align="absmiddle" name="data_inicial_anchor" id="data_inicial_anchor"></a></label>
								<input class="campo" style="width:100%;" id="data_inicial" name="data_inicial" maxlength="10" value="" onKeyPress="return VerificaData(this, event);" onBlur="ValidaDataSaida(this)">
							</div>
							<div class="row" style="padding:5px">
								<label>Data Final&nbsp;<a href="#" onClick="javascript:Calendario('data_final','flightSearch'); return false;"><img title="Calend&aacute;rio" src="images/calendario.gif" border="0" align="absmiddle" name="data_final_anchor" id="data_final_anchor"></a></label>
								<input class="campo" style="width:100%;" id="data_final" name="data_final" maxlength="10" value="" onKeyPress="return VerificaData(this, event);" onBlur="ValidaDataSaida(this)">
							</div>
							<div class="row" style="padding:5px">
								<button type="button" class="botao2" name="pesquisar" id="pesquisar" onClick="Pesquisar()">CONSULTAR</button>
							</div>
						</div>
					</div>
					<div id="divLista" style="display:none; padding:5px"></div>
					<div id="aguardeLista" style="display:none"><img src="images/load.gif" border="0" align="absmiddle"></div>
				</div>	
			</div>	
			<input type="hidden" name="DTMinimadata_inicial_data" id="DTMinimadata_inicial_data">
			<input type="hidden" name="DTMaximadata_inicial_data" id="DTMaximadata_inicial_data">
			<input type="hidden" name="Relacionadata_inicial_data" id="Relacionadata_inicial_data">
			<input type="hidden" name="DTMinimadata_final_data" id="DTMinimadata_final_data">
			<input type="hidden" name="DTMaximadata_final_data" id="DTMaximadata_final_data">
			<input type="hidden" name="Relacionadata_final_data" id="Relacionadata_final_data">
			<div id="DivCalendario" style="position:absolute;background-color:white;layer-background-color:white;visibility:hidden; z-index:70;">
				<iframe name="calendarioFrame" id="calendarioFrame" frameborder="0" width="250" height="200"></iframe>
			</div>
		</form>
	</body>

</html>