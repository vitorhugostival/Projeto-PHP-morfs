<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesquisar Produtos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="estilo.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 15px; /* Aumenta o espaço interno das células */
            text-align: left; /* Alinha o texto à esquerda */
        }
        th {
            background-color: #007bff; /* Cor de fundo para o cabeçalho da tabela */
            color: white; /* Cor do texto no cabeçalho */
        }
        tr:nth-child(even) {
            background-color: #f2f2f2; /* Cor de fundo para linhas pares */
        }
        tr:hover {
            background-color: #d1e7fd; /* Cor ao passar o mouse */
        }
        body {
            max-width: 1200px; /* Define uma largura máxima para o corpo */
            margin: auto; /* Centraliza o corpo na tela */
        }
    </style>
</head>
<body>

    <h1 class="mt-5">Pesquisar Produtos Cadastrados</h1>
    <form action="pesquisar.php" method="post">
        <label for="pesquisar">Pesquisar os produtos na lista de Compras</label>
        <input type="text" class="form-control" name="pesquisar" id="pesquisar" required>
        <input type="submit" class="btn btn-primary mt-2" value="Pesquisar">
    </form>

    <?php 
    include("conexao.php");

    // Recebe o termo de pesquisa enviado pelo formulário
    $pesquisar = $_POST['pesquisar'] ?? ''; // Corrigido para o nome correto

    // Atualizando a consulta SQL para buscar apenas os produtos que contenham o termo pesquisado
    $sql = "SELECT id_saida, imagem, id_usuario, nome_usuario, cod_produto, nome_produto, id_local, preco_custo, nome_local, id_estoque, qtd_saida, valor_total, observacao, data_saida 
            FROM saida 
            WHERE nome_produto LIKE ?"; // Adicionando condição de pesquisa

    $stmt = $conexao->prepare($sql);
    $pesquisar = '%' . $pesquisar . '%'; // Adiciona os caracteres coringa para a pesquisa
    $stmt->bind_param('s', $pesquisar);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        echo "<table class='table'>
                <thead>
                    <tr>
                        <th>ID Saída</th>
                        <th>Imagem</th>
                        <th>ID Usuário</th>
                        <th>Nome do Usuário</th>
                        <th>Código do Produto</th>
                        <th>Nome do Produto</th>
                        <th>ID Local</th>
                        <th>Preço de Custo</th>
                        <th>Nome do Local</th>
                        <th>ID Estoque</th>
                        <th>Quantidade de Saída</th>
                        <th>Valor Total</th>
                        <th>Observação</th>
                        <th>Data de Saída</th>
                    </tr>
                </thead>
                <tbody>";
        
        while ($row = mysqli_fetch_assoc($resultado)) {
            // Verifica se a imagem existe e gera a tag img
            $imgTag = '';
            if (!empty($row['imagem'])) {
                $imgTag = "<img src='" . $row['imagem'] . "' width='100' height='100' alt='Imagem do Produto'/>";
            } else {
                $imgTag = "<span>Sem imagem</span>"; // Caso não tenha imagem
            }

            echo "<tr>
                    <td>" . htmlspecialchars($row['id_saida']) . "</td>
                    <td>" . $imgTag . "</td>
                    <td>" . htmlspecialchars($row['id_usuario']) . "</td>
                    <td>" . htmlspecialchars($row['nome_usuario']) . "</td>
                    <td>" . htmlspecialchars($row['cod_produto']) . "</td>
                    <td>" . htmlspecialchars($row['nome_produto']) . "</td>
                    <td>" . htmlspecialchars($row['id_local']) . "</td>
                    <td>" . htmlspecialchars($row['preco_custo']) . "</td>
                    <td>" . htmlspecialchars($row['nome_local']) . "</td>
                    <td>" . htmlspecialchars($row['id_estoque']) . "</td>
                    <td>" . htmlspecialchars($row['qtd_saida']) . "</td>
                    <td>" . htmlspecialchars($row['valor_total']) . "</td>
                    <td>" . htmlspecialchars($row['observacao']) . "</td>
                    <td>" . htmlspecialchars($row['data_saida']) . "</td>
                  </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "Zero Resultados";
    }

    $stmt->close();
    mysqli_close($conexao);
    ?>

</body>
</html>
