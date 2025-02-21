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
        
        //apanhar row do carrinho a incrementar quantidade
        $queryCarrinho = $conn->prepare('SELECT * FROM carrinho WHERE id = :id');
        $queryCarrinho->bindParam(':id', $value, PDO::PARAM_INT);
        $queryCarrinho->execute();
        $carrinho = $queryCarrinho->fetch(PDO::FETCH_ASSOC);

        $cproduto_id = $carrinho['produto_id'];
        $cproduto_quantidade = $carrinho['quantidade'];
        var_dump($cproduto_quantidade);
        //verificar se produto tem stock
        $queryProduto = $conn->prepare('SELECT * FROM produtos WHERE id = :id');
        $queryProduto->bindParam(':id', $cproduto_id, PDO::PARAM_INT);
        $queryProduto->execute();
        $produto = $queryProduto->fetch(PDO::FETCH_ASSOC);


        $produto_id = $produto['id'];
        $produto_quantidade = $produto['stock'];

        if($produto_quantidade <= 0){
            $message = [
                'message'=> 'Produto esgotado',
                'status'=> 'error',
                'redirect'=> ''
            ];
            echo json_encode($message);
            return;
        }else{
            $plus = $cproduto_quantidade + 1;
            $plus_valor = $produto['preco'] * $plus;
            $menosStock = $produto_quantidade - 1;

            var_dump($plus);
            $queryPlus = $conn->prepare('UPDATE carrinho SET quantidade = :quantidade, preco = :preco WHERE id = :id');
            $queryPlus->bindParam(':preco', $plus_valor, PDO::PARAM_INT);
            $queryPlus->bindParam(':quantidade', $plus, PDO::PARAM_INT);
            $queryPlus->bindParam(':id', $value, PDO::PARAM_INT);
            $queryPlus->execute();

            $queryStock = $conn->prepare('UPDATE produtos SET stock = :stock WHERE id = :id');
            $queryStock->bindParam(':stock', $menosStock, PDO::PARAM_INT);
            $queryStock->bindParam(':id', $produto_id, PDO::PARAM_INT);
            $queryStock->execute();

            if($queryPlus && $queryStock){
                $query = $conn->prepare('SELECT carrinho.id, carrinho.produto_id As idProduto, produtos.nome As nomeproduto, carrinho.quantidade, carrinho.preco,
                produtos.imagem As imagemproduto  FROM carrinho join produtos ON carrinho.produto_id = produtos.id Where carrinho.id = :id ');
                $query->bindParam(':id', $value, PDO::PARAM_INT);
                $query->execute();
                $cart_items = $query->fetchAll(PDO::FETCH_ASSOC);
                            
                            
                $query = $conn->prepare('SELECT sum(preco) AS total FROM carrinho');
                $query->execute();
                $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
                $message = [
                        'message' => "",
                        'status' => 'success',
                        'redirect' => '',
                        'cart_items' => $cart_items,
                        'total' => $total,
                                
                        ];
            }else{
                $message = [
                    'message' => "Erro ao adicionar $product_name ao carrinho",
                    'status' => 'error',
                    'redirect' => '' 
                ];
            }
            echo json_encode($message);
        }  
    }
        
    