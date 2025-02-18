<?php 
    session_start();
    include '../../includes/conexao.php';

    $message = null;
    $post = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $postFilters = array_map('strip_tags', $post);

    foreach($postFilters as $key => $value){

        /*if(!isset($_SESSION) || empty($_SESSION)){   //sessão do utilizador
            $message = [
                'message' => 'Não foi possível remover o produto',
                'status' => 'error',
                'redirect' => ''
            ];
            echo json_encode($message);
            return;
        }*/

        //selecta a tabela carrinho para obter o produto a remover
        $produtoCarrinho = $conn->prepare("SELECT * FROM carrinho WHERE id = :id");
        $produtoCarrinho->bindParam(":id", $value, PDO::PARAM_INT);
        $produtoCarrinho->execute();
        $produtoCarrinho = $produtoCarrinho->fetch(PDO::FETCH_ASSOC);

        //obter id do produto e quantidade
        $produto_id = $produtoCarrinho['produto_id'];
        $produto_quantidade = $produtoCarrinho['quantidade'];


        //fazer select a tabela produto para obter stock atual do produto
        $produtos = $conn->prepare("SELECT * FROM produtos WHERE id = :id");
        $produtos->bindParam(":id", $produto_id, PDO::PARAM_INT);
        $produtos->execute();
        $produto = $produtos->fetch(PDO::FETCH_ASSOC);


        //adicionar quantidade em carrinho a quantidaeade do produto no stock
        $newStock = $produto['stock'] + $produto_quantidade;
        
       
        //eleminar produto do carrinho
        $delete = $conn->prepare('DELETE FROM carrinho WHERE id = :id');
        $delete->bindParam(':id', $value, PDO::PARAM_INT);
        $delete->execute();
        
       
        if($delete->rowCount() > 0){

            //adicinar produto remivido ao stock
            $updateStock = $conn ->prepare('UPDATE produtos SET stock = :stock WHERE id = :id');
            $updateStock->bindParam(':id', $produto_id, PDO::PARAM_INT);
            $updateStock->bindParam(':stock', $newStock, PDO::PARAM_STR);
            $updateStock->execute();


            $message = [
                'message' => 'Produto removido com sucesso',
                'status' =>'success',
                'redirect' => ''
            ];
        }else{
            $message = [
                'message' => 'Não foi possível remover o produto',
                'status' => 'error',
                'redirect' => ''
            ];
        }
        echo json_encode($message);

        return;
    };