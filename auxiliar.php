<?php include "includes.php"; ?>
<?php
header('Content-type: text/html; charset=UTF-8'); 

require_once 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

$conn			= DBOpen();	
$tela			= $_GET['tela'];
$tipo			= $_GET['tipo'];
$data_inicial	= $_GET['data_inicial'];
$data_final 	= $_GET['data_final'];

if ($tela == "1")
{
	$file_temp		= $_FILES['file']['tmp_name'];
	$file_name		= $_FILES['file']['name'];
	$file_size		= $_FILES['file']['size'];
	$file_type		= $_FILES['file']['type'];
	$diretorio		= "files/";

	print("<div class='col-12 Filtro'>" . chr(13));
	print("<div class='row' style='padding:5px'>" . chr(13));
	print("<table border='0' cellpadding='2' cellspacing='0' width='100%'>" . chr(13));
	
	if (trim($file_temp) != "")
	{
		$nome		= strtolower(RetirarAcentos($file_name));
		$arquivo	= time() . "_" . $nome;
		$filed		= $diretorio . $arquivo;

		if (move_uploaded_file($file_temp, $filed))
		{	
			$reader			= new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			$spreadsheet	= $reader->load($filed);
			$sheetData		= $spreadsheet->getActiveSheet()->toArray();
			
			if (!empty($sheetData)) {
				for ($i=1; $i<count($sheetData); $i++) {
					$cnpj			= $sheetData[$i][0];
					$razao			= $sheetData[$i][1];
					$endereco		= $sheetData[$i][2];
					$cidade			= $sheetData[$i][3];
					$uf				= $sheetData[$i][4];
					$cep			= $sheetData[$i][5];
					$data			= FormataDataBanco($sheetData[$i][6]);
					$servico_cod	= $sheetData[$i][7];
					$servico_dsc	= $sheetData[$i][8];
					$horas			= FormataNumeroBanco($sheetData[$i][9]);
					$faturado		= FormataNumeroBanco($sheetData[$i][10]);
					$custo			= FormataNumeroBanco($sheetData[$i][11]);
					$resultado		= FormataNumeroBanco($sheetData[$i][12]);
					
					//*** Serviço
					$sql	= "SELECT A001_codigo_servico codigo FROM T001_SERVICO WHERE A001_descricao_servico = '" . $servico_dsc . "' ";
					$rs		= mysqli_query($conn, $sql);
					
					if (mysqli_affected_rows($conn) > 0) {
						while ($row = mysqli_fetch_row($rs)) {		
							$codigo_servico	= $row[0];
						}
						mysqli_free_result($rs);
					}
					else {
						$sql	= "INSERT INTO T001_SERVICO (A001_codigo_servico, A001_descricao_servico, A001_valor_hora_servico) VALUES (" . $servico_cod . ",'" . $servico_dsc . "',0)";
						mysqli_query($conn, $sql);

						$sql	= "SELECT A001_codigo_servico codigo FROM T001_SERVICO WHERE A001_descricao_servico = '" . $servico_dsc . "' ";
						$rs		= mysqli_query($conn, $sql);
						
						if (mysqli_affected_rows($conn) > 0) {
							while ($row = mysqli_fetch_row($rs)) {		
								$codigo_servico	= $row[0];
							}
							mysqli_free_result($rs);
						}
					}
					
					//*** Cliente
					$sql	= "SELECT A002_codigo_cliente codigo FROM T002_CLIENTE WHERE A002_cnpj_cliente = '" . $cnpj . "' ";
					$rs		= mysqli_query($conn, $sql);
					
					if (mysqli_affected_rows($conn) > 0) {
						while ($row = mysqli_fetch_row($rs)) {		
							$codigo_cliente	= $row[0];
						}
						mysqli_free_result($rs);
					}
					else {
						$sql	= "INSERT INTO T002_CLIENTE (A002_cnpj_cliente, A002_razao_social_cliente, A002_endereco_cliente, A002_cidade_cliente, A002_uf_cliente, A002_cep_cliente) VALUES ('" . $cnpj . "','" . $razao . "','" . $endereco . "','" . $cidade . "','" . $uf . "','" . $cep . "')";
						mysqli_query($conn, $sql);

						$sql	= "SELECT A002_codigo_cliente codigo FROM T002_CLIENTE WHERE A002_cnpj_cliente = '" . $cnpj . "' ";
						$rs		= mysqli_query($conn, $sql);

						if (mysqli_affected_rows($conn) > 0) {
								while ($row = mysqli_fetch_row($rs)) {		
								$codigo_cliente	= $row[0];
							}
							mysqli_free_result($rs);
						}
					}

					//*** Venda
					$sql	= "SELECT A003_codigo_venda codigo FROM T003_VENDA WHERE A001_codigo_servico = " . $codigo_cliente . " AND A002_codigo_cliente = " . $codigo_cliente . " AND A003_data_venda = " . $data . " ";
					$rs		= mysqli_query($conn, $sql);

					if (mysqli_affected_rows($conn) > 0) {
						while ($row = mysqli_fetch_row($rs)) {		
							$codigo_venda	= $row[0];
						}
						mysqli_free_result($rs);
					}
					else {
						$sql	= "INSERT INTO T003_VENDA (A001_codigo_servico, A002_codigo_cliente, A003_data_venda, A003_faturado_venda, A003_custo_venda) VALUES (" . $codigo_servico . "," . $codigo_cliente . "," . $data . "," . $faturado . "," . $custo . ")";
						mysqli_query($conn, $sql);
					}
					
					print("<tr height='25'>" . chr(13));
					print("<td class='registro' align='center'>Linha " . $i . " Carregada</td>" . chr(13));
					print("</tr>" . chr(13));
				}
			}
			else
			{
				print("<tr height='25'>" . chr(13));
				print("<td class='registro' align='center'>Erro no carregamento do Arquivo</td>" . chr(13));
				print("</tr>" . chr(13));
			}
		}
		else
		{
			print("<tr height='25'>" . chr(13));
			print("<td class='registro' align='center'>Erro no carregamento do Arquivo</td>" . chr(13));
			print("</tr>" . chr(13));
		}
	}
	else
	{
		print("<tr height='25'>" . chr(13));
		print("<td class='registro' align='center'>Arquivo não localizado</td>" . chr(13));
		print("</tr>" . chr(13));
	}

	print("</table>" . chr(13));
	print("</div>" . chr(13));
	print("</div>" . chr(13));
}
else if ($tela == "2")
{
	print("<div class='col-12 Filtro'>" . chr(13));
	print("<div class='row' style='padding:5px'>" . chr(13));
	print("<table border='0' cellspacing='0' cellpadding='0' width='100%'>" . chr(13));

	print("<tr>" . chr(13));
	print("<td class='Cabecalho' width='012%' align='center'>Data</td>" . chr(13));
	print("<td class='Cabecalho' width='020%' align='left'>Cliente</td>" . chr(13));
	print("<td class='Cabecalho' width='020%' align='center'>Serviço Vendido</td>" . chr(13));
	print("<td class='Cabecalho' width='012%' align='right'>Horas</td>" . chr(13));
	print("<td class='Cabecalho' width='012%' align='right'>Valor Venda</td>" . chr(13));
	print("<td class='Cabecalho' width='012%' align='right'>Custo Venda</td>" . chr(13));
	print("<td class='Cabecalho' width='012%' align='right'>Resultado Venda</td>" . chr(13));
	print("</tr>" . chr(13));

	$sql		= "";
	$sql		= $sql . "SELECT T003.A003_data_venda data, T002.A002_razao_social_cliente cliente, T001.A001_descricao_servico servico, T003.A003_faturado_venda faturado, T003.A003_custo_venda custo, (T003.A003_faturado_venda / T001.A001_valor_hora_servico) horas, (T003.A003_faturado_venda - T003.A003_custo_venda) resultado, T003.A003_codigo_venda codigo ";
	$sql		= $sql . "FROM T003_VENDA T003 ";
	$sql		= $sql . "INNER JOIN T001_SERVICO T001 ON T003.A001_codigo_servico = T001.A001_codigo_servico ";
	$sql		= $sql . "INNER JOIN T002_CLIENTE T002 ON T003.A002_codigo_cliente = T002.A002_codigo_cliente ";
	$sql		= $sql . "WHERE T003.A003_codigo_venda IS NOT NULL ";

	if ($data_inicial != "")
		$sql	= $sql . "AND T003.A003_data_venda >= " . FormataDataHoraBanco($data_inicial . " 00:00") . " ";
	if ($data_final != "")
		$sql	= $sql . "AND T003.A003_data_venda <= " . FormataDataHoraBanco($data_final . " 23:59") . " ";

	$sql		= $sql . "ORDER BY T003.A003_data_venda ";
	
	$rs			= mysqli_query($conn, $sql);

	if (mysqli_affected_rows($conn) > 0) {
		while ($row = mysqli_fetch_row($rs)) {		
			print("<tr height='020'>" . chr(13));
			print("<td class='Branco' width='012%' align='center'>" . FormataDataTela($row[0]) . "</td>" . chr(13));
			print("<td class='Branco' width='020%' align='left'>" . CodificaString($row[1]) . "</td>" . chr(13));
			print("<td class='Branco' width='020%' align='center'>" . CodificaString($row[2]) . "</td>" . chr(13));
			print("<td class='Branco' width='012%' align='right'>" . FormataNumeroTela($row[5]) . "</td>" . chr(13));
			print("<td class='Branco' width='012%' align='right'>" . FormataNumeroTela($row[3]) . "</td>" . chr(13));
			print("<td class='Branco' width='012%' align='right'>" . FormataNumeroTela($row[4]) . "</td>" . chr(13));
			print("<td class='Branco' width='012%' align='right'>" . FormataNumeroTela($row[6]) . "</td>" . chr(13));
			print("</tr>" . chr(13));
		}
		mysqli_free_result($rs);
	}
	else {
		print("<tr height='20'>" . chr(13));
		print("<td class='Branco' align='center' colspan='7'>Nenhum registro foi encontrado.</td>" . chr(13));
		print("</tr>" . chr(13));
	}

	print("</table>" . chr(13));
	print("</div>" . chr(13));
	print("</div>" . chr(13));
}
elseif ($tela == "3")
{
	print("<div class='col-12 Filtro'>" . chr(13));
	print("<div class='row' style='padding:5px'>" . chr(13));
	print("<table border='0' cellspacing='0' cellpadding='0' width='100%'>" . chr(13));

	if ($tipo == "1")
	{
		print("<tr>" . chr(13));
		print("<td class='Cabecalho' width='040%' align='left'>Cliente</td>" . chr(13));
		print("<td class='Cabecalho' width='015%' align='right'>Total Horas</td>" . chr(13));
		print("<td class='Cabecalho' width='015%' align='right'>Total Vendas</td>" . chr(13));
		print("<td class='Cabecalho' width='015%' align='right'>Custo Total</td>" . chr(13));
		print("<td class='Cabecalho' width='015%' align='right'>Resultado</td>" . chr(13));
		print("</tr>" . chr(13));

		$sql		= "";
		$sql		= $sql . "SELECT T002.A002_razao_social_cliente cliente, SUM(T003.A003_faturado_venda) faturado, SUM(T003.A003_custo_venda) custo, SUM(T003.A003_faturado_venda / T001.A001_valor_hora_servico) horas, SUM(T003.A003_faturado_venda - T003.A003_custo_venda) resultado ";
		$sql		= $sql . "FROM T003_VENDA T003 ";
		$sql		= $sql . "INNER JOIN T001_SERVICO T001 ON T003.A001_codigo_servico = T001.A001_codigo_servico ";
		$sql		= $sql . "INNER JOIN T002_CLIENTE T002 ON T003.A002_codigo_cliente = T002.A002_codigo_cliente ";
		$sql		= $sql . "WHERE T003.A003_codigo_venda IS NOT NULL ";

		if ($data_inicial != "")
			$sql	= $sql . "AND T003.A003_data_venda >= " . FormataDataHoraBanco($data_inicial . " 00:00") . " ";
		if ($data_final != "")
			$sql	= $sql . "AND T003.A003_data_venda <= " . FormataDataHoraBanco($data_final . " 23:59") . " ";

		$sql		= $sql . "GROUP BY T002.A002_razao_social_cliente ";
		$sql		= $sql . "ORDER BY T002.A002_razao_social_cliente ";

		$rs			= mysqli_query($conn, $sql);
		
		if (mysqli_affected_rows($conn) > 0) {
			while ($row = mysqli_fetch_row($rs)) {		
				print("<tr height='020'>" . chr(13));
				print("<td class='Branco' width='040%' align='left'>" . CodificaString($row[0]) . "</td>" . chr(13));
				print("<td class='Branco' width='015%' align='right'>" . FormataNumeroTela($row[3]) . "</td>" . chr(13));
				print("<td class='Branco' width='015%' align='right'>" . FormataNumeroTela($row[1]) . "</td>" . chr(13));
				print("<td class='Branco' width='015%' align='right'>" . FormataNumeroTela($row[2]) . "</td>" . chr(13));
				print("<td class='Branco' width='015%' align='right'>" . FormataNumeroTela($row[4]) . "</td>" . chr(13));
				print("</tr>" . chr(13));
			}
			mysqli_free_result($rs);
		}
		else {
			print("<tr height='20'>" . chr(13));
			print("<td class='Branco' align='center' colspan='5'>Nenhum registro foi encontrado.</td>" . chr(13));
			print("</tr>" . chr(13));
		}
	}
	elseif ($tipo == "2")
	{
		print("<tr>" . chr(13));
		print("<td class='Cabecalho' width='040%' align='left'>Servi&ccedil;o</td>" . chr(13));
		print("<td class='Cabecalho' width='015%' align='right'>Total Horas</td>" . chr(13));
		print("<td class='Cabecalho' width='015%' align='right'>Total Vendas</td>" . chr(13));
		print("<td class='Cabecalho' width='015%' align='right'>Custo Total</td>" . chr(13));
		print("<td class='Cabecalho' width='015%' align='right'>Resultado</td>" . chr(13));
		print("</tr>" . chr(13));

		$sql		= "";
		$sql		= $sql . "SELECT T001.A001_descricao_servico servico, SUM(T003.A003_faturado_venda) faturado, SUM(T003.A003_custo_venda) custo, SUM(T003.A003_faturado_venda / T001.A001_valor_hora_servico) horas, SUM(T003.A003_faturado_venda - T003.A003_custo_venda) resultado ";
		$sql		= $sql . "FROM T003_VENDA T003 ";
		$sql		= $sql . "INNER JOIN T001_SERVICO T001 ON T003.A001_codigo_servico = T001.A001_codigo_servico ";
		$sql		= $sql . "INNER JOIN T002_CLIENTE T002 ON T003.A002_codigo_cliente = T002.A002_codigo_cliente ";
		$sql		= $sql . "WHERE T003.A003_codigo_venda IS NOT NULL ";

		if ($data_inicial != "")
			$sql	= $sql . "AND T003.A003_data_venda >= " . FormataDataHoraBanco($data_inicial . " 00:00") . " ";
		if ($data_final != "")
			$sql	= $sql . "AND T003.A003_data_venda <= " . FormataDataHoraBanco($data_final . " 23:59") . " ";

		$sql		= $sql . "GROUP BY T001.A001_descricao_servico ";
		$sql		= $sql . "ORDER BY T001.A001_descricao_servico ";

		$rs			= mysqli_query($conn, $sql);
	
		if (mysqli_affected_rows($conn) > 0) {
			while ($row = mysqli_fetch_row($rs)) {		
				print("<tr height='020'>" . chr(13));
				print("<td class='Branco' width='040%' align='left'>" . CodificaString($row[0]) . "</td>" . chr(13));
				print("<td class='Branco' width='015%' align='right'>" . FormataNumeroTela($row[3]) . "</td>" . chr(13));
				print("<td class='Branco' width='015%' align='right'>" . FormataNumeroTela($row[1]) . "</td>" . chr(13));
				print("<td class='Branco' width='015%' align='right'>" . FormataNumeroTela($row[2]) . "</td>" . chr(13));
				print("<td class='Branco' width='015%' align='right'>" . FormataNumeroTela($row[4]) . "</td>" . chr(13));
				print("</tr>" . chr(13));
			}
			mysqli_free_result($rs);
		}
		else {
			print("<tr height='20'>" . chr(13));
			print("<td class='Branco' align='center' colspan='5'>Nenhum registro foi encontrado.</td>" . chr(13));
			print("</tr>" . chr(13));
		}
	}
	elseif ($tipo == "3")
	{
		print("<tr>" . chr(13));
		print("<td class='Cabecalho' width='040%' align='left'>M&ecirc;s</td>" . chr(13));
		print("<td class='Cabecalho' width='015%' align='right'>Total Horas</td>" . chr(13));
		print("<td class='Cabecalho' width='015%' align='right'>Total Vendas</td>" . chr(13));
		print("<td class='Cabecalho' width='015%' align='right'>Custo Total</td>" . chr(13));
		print("<td class='Cabecalho' width='015%' align='right'>Resultado</td>" . chr(13));
		print("</tr>" . chr(13));

		$sql		= "";
		$sql		= $sql . "SELECT YEAR(T003.A003_data_venda) ano, MONTH(T003.A003_data_venda) mes, SUM(T003.A003_faturado_venda) faturado, SUM(T003.A003_custo_venda) custo, SUM(T003.A003_faturado_venda / T001.A001_valor_hora_servico) horas, SUM(T003.A003_faturado_venda - T003.A003_custo_venda) resultado ";
		$sql		= $sql . "FROM T003_VENDA T003 ";
		$sql		= $sql . "INNER JOIN T001_SERVICO T001 ON T003.A001_codigo_servico = T001.A001_codigo_servico ";
		$sql		= $sql . "INNER JOIN T002_CLIENTE T002 ON T003.A002_codigo_cliente = T002.A002_codigo_cliente ";
		$sql		= $sql . "WHERE T003.A003_codigo_venda IS NOT NULL ";

		if ($data_inicial != "")
			$sql	= $sql . "AND T003.A003_data_venda >= " . FormataDataHoraBanco($data_inicial . " 00:00") . " ";
		if ($data_final != "")
			$sql	= $sql . "AND T003.A003_data_venda <= " . FormataDataHoraBanco($data_final . " 23:59") . " ";

		$sql		= $sql . "GROUP BY YEAR(T003.A003_data_venda), MONTH(T003.A003_data_venda) ";
		$sql		= $sql . "ORDER BY YEAR(T003.A003_data_venda), MONTH(T003.A003_data_venda) ";

		$rs			= mysqli_query($conn, $sql);
	
		if (mysqli_affected_rows($conn) > 0) {
			while ($row = mysqli_fetch_row($rs)) {		
				print("<tr height='020'>" . chr(13));
				print("<td class='Branco' width='040%' align='left'>" . DescricaoMes($row[1]) . "/" . $row[0] . "</td>" . chr(13));
				print("<td class='Branco' width='015%' align='right'>" . FormataNumeroTela($row[4]) . "</td>" . chr(13));
				print("<td class='Branco' width='015%' align='right'>" . FormataNumeroTela($row[2]) . "</td>" . chr(13));
				print("<td class='Branco' width='015%' align='right'>" . FormataNumeroTela($row[3]) . "</td>" . chr(13));
				print("<td class='Branco' width='015%' align='right'>" . FormataNumeroTela($row[5]) . "</td>" . chr(13));
				print("</tr>" . chr(13));
			}
			mysqli_free_result($rs);
		}
		else {
			print("<tr height='20'>" . chr(13));
			print("<td class='Branco' align='center' colspan='6'>Nenhum registro foi encontrado.</td>" . chr(13));
			print("</tr>" . chr(13));
		}
	}					

	print("</table>" . chr(13));
	print("</div>" . chr(13));
	print("</div>" . chr(13));
}

DBClose($conn);
?>