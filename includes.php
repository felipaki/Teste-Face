<?php
function DBOpen()
{
	$server		= "localhost";
	$user		= "root";
	$password	= "netunos@2022";
	$database	= "face";
	
	$connection	= new mysqli($server, $user, $password, $database);

	if (!$connection)
        die("Não conectou"); 
	else
		return $connection;
}

function DBClose($connection)
{
	mysqli_close($connection);
}

function FormataDataBanco($Data)
{
	if (trim($Data) != "")
	{
		list($Dia,$Mes,$Ano)	= explode("/",$Data);
		$FormataData			= "'" . $Ano . "-" . $Mes . "-" . $Dia . "'";
	}	
	else
		$FormataData			= "NULL";

	return $FormataData;
}

function FormataDataTela($Data)
{
	if ($Data != "")
	{
		$Dia				= substr($Data,8,2);
		$Mes				= substr($Data,5,2);
		$Ano				= substr($Data,0,4);
		$FormataData  		= $Dia . "/" . $Mes . "/" . $Ano;
		
		if ($FormataData == "01/01/1900")
			$FormataData	= "";
	}
	else	
		$FormataData		= "";

	return $FormataData;
}

function DescricaoMes($mes_num)
{
	switch ($mes_num)
	{ 
		case "01": 
			$mes_port = "Janeiro"; 
			break; 
		case "02": 
			$mes_port = "Fevereiro"; 
			break; 
		case "03": 
			$mes_port = "Março"; 
			break; 
		case "04": 
			$mes_port = "Abril"; 
			break; 
		case "05": 
			$mes_port = "Maio"; 
			break; 
		case "06": 
			$mes_port = "Junho"; 
			break; 
		case "07": 
			$mes_port = "Julho"; 
			break; 
		case "08": 
			$mes_port = "Agosto"; 
			break; 
		case "09": 
			$mes_port = "Setembtro"; 
			break; 
		case "10":     
			$mes_port = "Outubro"; 
			break; 
		case "11": 
			$mes_port = "Novembro"; 
			break; 
		case "12": 
			$mes_port = "Dezembro"; 
			break; 
	} 

	return $mes_port; 
}	

function FormataNumeroBanco($Numero)
{
	if (trim($Numero) != "")
	{
		$FormataNumeroBanco	= str_replace(",","",$Numero);
		return $FormataNumeroBanco;
	}	
	else
		return "NULL";
}

function FormataNumeroTela($AuxNumero)
{
	if (trim($AuxNumero) != "")
	{
		$FormataNumeroTela	=	number_format($AuxNumero,2,",",".");
		return $FormataNumeroTela;
	}	
}

function CodificaString($AuxString)
{
	return nl2br(htmlentities($AuxString, ENT_QUOTES, "ISO-8859-1")); 
}

function RetirarAcentos($string)
{
	$string = preg_replace("[^a-zA-Z0-9_.]", "", strtr($string, "�������������������������� ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
	return $string;
}
?>