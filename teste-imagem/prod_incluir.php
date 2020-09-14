<?php
include("seguranca.php");
include("conexao.php");

if (isset($_POST['excluir']) && $_POST['excluir']==1){
	$_POST['id']= $mysqli->real_escape_string($_POST['id']);
	
	$result_foto = $mysqli->query("SELECT fot_id FROM tb_produto_foto WHERE fot_idproduto={$_POST['id']} ORDER BY fot_id");
	while($row_foto = $result_foto->fetch_object()){
		$foto = nome_foto('prod', $row_foto->fot_id, dir_img_hd, gr);
		excluir_arquivo($foto);

		$foto = nome_foto('prod', $row_foto->fot_id, dir_img_hd, pq);
		excluir_arquivo($foto);
	}
	
	$sql = "DELETE FROM tb_produto_foto WHERE fot_idproduto='{$_POST['id']}'";
	$mysqli->query($sql);
	
	$sql = "DELETE FROM tb_produto WHERE prod_id='{$_POST['id']}'";
	$mysqli->query($sql);
	
	if ($mysqli->affected_rows==1){
		salva_log('E', 'tb_produto', $_POST['id']);
		echo TRUE;
	}else{
		echo 'Erro ao tentar excluir o registro. Caso o problema persista entre em contato com o suporte técnico.';
	}
	
	exit;
}

if (isset($_POST['desativar']) && $_POST['desativar']==1){
	$_POST['id']= $mysqli->real_escape_string($_POST['id']);
	$sql = "UPDATE tb_produto SET prod_ativo=0 WHERE prod_id='{$_POST['id']}'";
	$mysqli->query($sql);
	
	if ($mysqli->affected_rows==1){
		salva_log('A', 'tb_produto', $_POST['id'], 'prod_ativo', 0, 1);
		echo TRUE;
	}else{
		echo 'Erro ao tentar desativar o registro. Caso o problema persista entre em contato com o suporte técnico.';
	}
	exit;
}

if (isset($_POST['reativar']) && $_POST['reativar']==1){
	$_POST['id']= $mysqli->real_escape_string($_POST['id']);
	$sql = "UPDATE tb_produto SET prod_ativo=1 WHERE prod_id='{$_POST['id']}'";
	$mysqli->query($sql);
	
	if ($mysqli->affected_rows==1){
		salva_log('A', 'tb_produto', $_POST['id'], 'prod_ativo', 1, 0);
		echo TRUE;
	}else{
		echo 'Erro ao tentar reativar o registro. Caso o problema persista entre em contato com o suporte técnico.';
	}
	exit;
}

if ($_SERVER["REQUEST_METHOD"]=='DELETE' && isset($_GET['imagem'])){
	$foto = nome_foto('prod', $_GET['imagem'], dir_img_hd, gr);
	if (is_file($foto)) {
		excluir_arquivo($foto);

		$foto = nome_foto('prod', $_GET['imagem'], dir_img_hd, pq);
		if (is_file($foto)) {
			excluir_arquivo($foto);
			$exclusao = true;
			$sql = "DELETE FROM tb_produto_foto WHERE fot_id='{$_GET['imagem']}'";
			$mysqli->query($sql);
			salva_log('E', 'tb_produto_foto', $_GET['imagem']);
		}
	}else{
		$exclusao = false;
	}

	$ret[$_GET['imagem']] = $exclusao;
	echo json_encode($ret);
	exit;
}else if ($_SERVER["REQUEST_METHOD"]=='GET' && isset($_GET['prod_id'])){
	$result_foto = $mysqli->query("SELECT fot_id FROM tb_produto_foto WHERE fot_idproduto={$_GET['prod_id']} ORDER BY fot_id");

	$i=0;
	while($row_foto = $result_foto->fetch_object()){
			
		$foto = nome_foto('prod', $row_foto->fot_id, dir_img_hd, gr);
		if (!is_file($foto)) continue;
		list(,,$tipo) = getimagesize($foto);
		$ret['files'][$i]['name']=nome_foto('prod', $row_foto->fot_id, '', gr);
		$ret['files'][$i]["size"]=filesize($foto);
		$ret['files'][$i]["type"]=$tipo;
		$ret['files'][$i]["url"] = nome_foto('prod', $row_foto->fot_id, dir_img_site, gr);
		$ret['files'][$i]["thumbnailUrl"] = nome_foto('prod', $row_foto->fot_id, dir_img_site, pq);;
		$ret['files'][$i]["deleteUrl"] = $_SERVER["PHP_SELF"] . "?imagem={$row_foto->fot_id}";
		$ret['files'][$i]["deleteType"]="DELETE";
		$i++;
	}
	
	echo json_encode($ret);
	exit;
}

if (isset($_GET['imagem']) && $_GET['imagem']==1){
	include('canvas.php');
	
	//usleep(rand(0, 20)*rand(10000, 1000000));

	$i=0;
		
	if ($_FILES['prod_imagem']['error'][$i]==0){
		$tamanho = getimagesize($_FILES['prod_imagem']['tmp_name'][$i]);
		if ($tamanho[2]>4 || $tamanho[2]==0){
			continue;
		}
		
		$sql = "INSERT INTO tb_produto_foto SET fot_idproduto={$_GET['prod_id']}, fot_data=NOW()";
		$mysqli->query($sql);
		$result_foto = $mysqli->query("SELECT MAX(fot_id) AS fot_id FROM tb_produto_foto");
		$row_foto = $result_foto->fetch_object();
		salva_log('I', 'tb_produto_foto', $row_foto->fot_id);
		$nome_arq_orig = "../tmp/{$_FILES['prod_imagem']['name'][$i]}";
		$nome_arq = nome_foto('prod', $row_foto->fot_id, dir_img_hd,gr);
		$nome_arq_pq = nome_foto('prod', $row_foto->fot_id, dir_img_hd,pq);
		
		
		if (!move_uploaded_file($_FILES['prod_imagem']['tmp_name'][$i], $nome_arq_orig)) continue;

		$img = new canvas($nome_arq_orig);

		if ($tamanho[0]>$tamanho[1]){
			if ($tamanho[0]>prod_max_width) $img->resize(prod_max_width);
		}else{
			if ($tamanho[1]>prod_max_height) $img->resize('', prod_max_height);
		}

		$img->save($nome_arq);
		
		$img->resize(prod_list_width, prod_list_height);
		$img->save($nome_arq_pq);
		
		excluir_arquivo($nome_arq_orig);
		
		
	}

	$ret['files'][0]['name']=nome_foto('prod', $row_foto->fot_id, '', gr);
	$ret['files'][0]["size"]=filesize($nome_arq);
	$ret['files'][0]["type"]=$_FILES['prod_imagem']['type'][$i];
	$ret['files'][0]["url"] = nome_foto('prod', $row_foto->fot_id, dir_img_site, gr);
	$ret['files'][0]["thumbnailUrl"] = nome_foto('prod', $row_foto->fot_id, dir_img_site, pq);;
	$ret['files'][0]["deleteUrl"] = $_SERVER["PHP_SELF"] . "?imagem={$row_foto->fot_id}";
	$ret['files'][0]["deleteType"]="DELETE";
	
	echo json_encode($ret);
	exit;
	
}

$erro = '';

//Limpar os espaços vazios das variáveis
foreach ($_POST as $key => $value) {
	if ($key=='prod_imagem') continue;
	$_POST[$key] = $mysqli->real_escape_string(trim($value));
}

	
if($_POST['prod_nome']==''){
	$erro .= 'Nome do produto em branco<br>';	
}else{
	$sql = "SELECT prod_id FROM tb_produto WHERE prod_nome LIKE '{$_POST['prod_nome']}'" . ($_POST['prod_id']!=''?" AND prod_id<>'{$_POST['prod_id']}'":'');

	$result = $mysqli->query($sql);
	
	if ($result->num_rows<>0){
		$erro .= 'Produto já existente<br>'; 
	}
}

if($_POST['prod_idcategoria']==''){
	$erro .= 'Categoria não selecionada<br>';	
}

if($_POST['prod_preco']=='' || $_POST['prod_preco']=='0' || $_POST['prod_preco']=='R$ 0,00' || $_POST['prod_preco']=='0,00'){
	$erro .= 'Valor do produto não digitado<br>';	
}else{
	$_POST['prod_preco'] = str_replace(',', '.', $_POST['prod_preco']);
}

if ($erro){
	$ret['erro']=1;
	$ret['mensagem']=$erro;
}else{
	if($_POST['prod_id']==''){
		$sql = "INSERT INTO tb_produto SET prod_nome='{$_POST['prod_nome']}', prod_descricao='{$_POST['prod_descricao']}', 
				prod_preco='{$_POST['prod_preco']}', prod_idcategoria='{$_POST['prod_idcategoria']}', 
				prod_idsubcategoria=" . campo_nulo($_POST['prod_idsubcategoria']) . ", prod_destaque='" . (isset($_POST['prod_destaque']) && $_POST['prod_destaque']==1?1:0) . "', 
				prod_datacad=NOW(), prod_dataalt=NOW()";
	}else{
		if (!isset($_POST['prod_destaque'])) $_POST['prod_destaque']=0;
		
		$sql = "UPDATE tb_produto SET prod_nome='{$_POST['prod_nome']}', prod_descricao='{$_POST['prod_descricao']}', 
				prod_preco='{$_POST['prod_preco']}', prod_idcategoria='{$_POST['prod_idcategoria']}', 
				prod_idsubcategoria=" . campo_nulo($_POST['prod_idsubcategoria']) . ", prod_destaque='" . (isset($_POST['prod_destaque']) && $_POST['prod_destaque']==1?1:0) . "', 
				prod_dataalt=NOW() WHERE prod_id='{$_POST['prod_id']}'";
		salva_log('A', 'tb_produto', $_POST['prod_id'], 'prod_id');
	}
	$mysqli->query($sql) or die($sql);

	if ($mysqli->affected_rows==0 && $_POST['prod_id']){
		$ret['erro']=0;
		$ret['mensagem']='Nenhuma alteração efetuada';
	}else if ($mysqli->affected_rows==1){
		if ($_POST['prod_id']==''){
			$result = $mysqli->query("SELECT MAX(prod_id) AS prod_id FROM tb_produto");
			$row = $result->fetch_object();
			salva_log('I', 'tb_produto', $row->prod_id);
			
			
			if (isset($_FILES['prod_imagem'])){
				include('canvas.php');
				for ($i=0;$i<count($_FILES['prod_imagem']['name']); $i++){
					
					if ($_FILES['prod_imagem']['error'][$i]==0){
						$tamanho = getimagesize($_FILES['prod_imagem']['tmp_name'][$i]);
						if ($tamanho[2]>4 || $tamanho[2]==0){
							continue;
						}
						
						$sql = "INSERT INTO tb_produto_foto SET fot_idproduto={$row->prod_id}, fot_data=NOW()";
						$mysqli->query($sql);
						$result_foto = $mysqli->query("SELECT MAX(fot_id) AS fot_id FROM tb_produto_foto");
						$row_foto = $result_foto->fetch_object();
						salva_log('I', 'tb_produto_foto', $row_foto->fot_id);
						$nome_arq = nome_foto('prod', $row_foto->fot_id, dir_img_hd,gr);
						$nome_arq_pq = nome_foto('prod', $row_foto->fot_id, dir_img_hd,pq);
						
						
						if (!move_uploaded_file($_FILES['prod_imagem']['tmp_name'][$i], $nome_arq)) continue;

						$img = new canvas($nome_arq);

						if ($tamanho[0]>$tamanho[1]){
							$img->resize(prod_max_width);
						}else{
							$img->resize('', prod_max_height);
						}
	
						$img->save($nome_arq);
						
						$img->resize(prod_list_width, prod_list_height);
						$img->save($nome_arq_pq);
						
						
					}
				}
			}
		}
		
		$ret['erro']=0;
		$ret['mensagem']=($_POST['prod_id']?'Alterado':'Inserido') . ' com sucesso';
	}else{
		
	}
}
echo json_encode($ret);
?>