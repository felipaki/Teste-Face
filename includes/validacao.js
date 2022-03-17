function VerificaData(campo, e)
{
	var strCheck 		= '0123456789';
	var whichCode 	= (window.Event) ? e.which : e.keyCode;

	if ((whichCode == 13) || (whichCode == 8) || (whichCode == 0)) return true;
	key = String.fromCharCode(whichCode);  // Valor para o c�digo da Chave
	if (strCheck.indexOf(key) == -1) 
		return false;  // Chave inv�lida
	else
	{
		Formatar(campo, '##/##/####');
		
		var data	= (campo.value + key);
		if (data.length >= 10)
		{
			if (!validadata(data))
			{
				campo.value = "";
				return false;
			}
			else
			{
				campo.value = data;
				autoTab(campo, 10, e);
				return false;
			}
		}
	}
}

function Formatar(src, mask) 
{
	var i 		= src.value.length;
	var saida = mask.substring(0,1);
	var texto = mask.substring(i);

	if (texto.substring(0,1) != saida)
		src.value += texto.substring(0,1);
}

function validadata(Data)
{
 var err    = 0;
 var string = Data;
 var valid  = "0123456789/";
 var ok     = "yes";

  for (var i=0; i<string.length; i++)
  {
    var temp = "" + string.substring(i, i+1);

    if (valid.indexOf(temp) == "-1")
      err = 1;
  }
	
	if (string.length != 10)
    err = 1;

  dia    = string.substring(0, 2);
  barra1 = string.substring(2, 3);
  mes    = string.substring(3, 5);
  barra2 = string.substring(5, 6);
  ano    = string.substring(6, 10);

  if ((dia < 1) || (dia > 31))
    err = 1;

  if (barra1 != '/')
    err = 1;

  if ((mes < 1) || (mes > 12))
    err = 1;

  if (barra2 != '/')
    err = 1;

  if (ano < 0)
    err = 1;

  if (mes == 4 || mes == 6 || mes == 9 || mes == 11)
  {
    if (dia == 31)
      err = 1;
  }

  if (mes == 2)
  {
    var g = parseInt(ano/4);

    if (isNaN(g))
      err = 1;

    if (dia > 29)
      err = 1;

    if ((dia == 29) && (((ano/4) != parseInt(ano/4))))
      err = 1;
  }

  if (err == 1)
    return(false);
  else
    return(true);
}

function VerificaData(campo, e)
{
	var strCheck 		= '0123456789';
	var whichCode 	= (window.Event) ? e.which : e.keyCode;

	if ((whichCode == 13) || (whichCode == 8) || (whichCode == 0)) return true;
	key = String.fromCharCode(whichCode);  // Valor para o c�digo da Chave
	if (strCheck.indexOf(key) == -1) 
		return false;  // Chave inv�lida
	else
	{
		Formatar(campo, '##/##/####');
		
		var data	= (campo.value + key);
		if (data.length >= 10)
		{
			if (!validadata(data))
			{
				campo.value = "";
				return false;
			}
			else
			{
				campo.value = data;
				autoTab(campo, 10, e);
				return false;
			}
		}
	}
}

function autoTab(input, len, e)
{
	var isNN = (navigator.appName.indexOf("Netscape") != -1); 
	var keyCode = (isNN) ? e.which : e.keyCode; 
	var filter = (isNN) ? [0,8,9] : [0,8,9,16,17,18,37,38,39,40,46]; 

	if (input.value.length >= len && !containsElement(filter, keyCode))
	{
		input.value = input.value.slice(0, len); 
		var i=1;
		while ((input.form[(getIndex(input)+i) % input.form.length].disabled) || (input.form[(getIndex(input)+i) % input.form.length].type == 'hidden'))
		{
			i++;
		}

		input.form[(getIndex(input)+i) % input.form.length].focus(); 
	} 
	
	// Fun��o auxiliar do autoTab Para contar os elementos da tela
	function containsElement(arr, ele) 
	{ 
		var found = false, index = 0; 
		while (!found && index < arr.length)
		{
			if (arr[index] == ele) 
				found = true; 
			else 
				index++; 
		}		
		return found; 
	} 

	// Fun��o auxiliar do autoTab Para pular de campo
	function getIndex(input) 
	{ 
		var index = -1, i = 0, found = false; 
		while (i < input.form.length && index == -1) 
			if (input.form[i] == input)
				index = i; 
			else 
				i++; 
			return index; 
	} 

	return true; 

} 

function ReplaceAll(str, de, para)
{
    var pos = str.indexOf(de);
    while (pos > -1){
		str = str.replace(de, para);
		pos = str.indexOf(de);
	}
    return (str);
}

function openAjax() 
{
	var ajax;

	try {
		ajax = new XMLHttpRequest(); // XMLHttpRequest para browsers decentes, como: Firefox, Safari, dentre outros.
	}
	catch(ee) {
		try {
			ajax = new ActiveXObject("Msxml2.XMLHTTP"); // Para o IE da MS
		}
		catch(e) {
			try {
				ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Para o IE da MS
			}
			catch(E) {
				ajax = false;
			}
		}
	}

	return ajax;

}