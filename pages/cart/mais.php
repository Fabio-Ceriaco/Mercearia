<?php
   
    include '../../includes/conexao.php';

    
    $message = null;  //mensagem para o cliente
    $post = filter_input_array(INPUT_POST, FILTER_DEFAULT); //filtrar inputs para evitar ataques de SQL Injection
    $postFilters = array_map('strip_tags', $post); //remover tags HTML do input

   
    foreach($postFilters as $key => $value){ //processar cada input
        
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

        //obter id do produto e quantidade
        $cproduto_id = $carrinho['produto_id'];
        $cproduto_quantidade = $carrinho['quantidade'];
        
        //verificar se produto tem stock
        $queryProduto = $conn->prepare('SELECT * FROM produtos WHERE id = :id');
        $queryProduto->bindParam(':id', $cproduto_id, PDO::PARAM_INT);
        $queryProduto->execute();
        $produto = $queryProduto->fetch(PDO::FETCH_ASSOC);

        //verificar se produto tem stock suficiente
        $produto_id = $produto['id'];
        $produto_quantidade = $produto['stock'];

        //se não tiver stock suficiente, mostrar mensagem e não remover
        if($produto_quantidade <= 0){
            $message = [
                'message'=> 'Produto esgotado',
                'status'=> 'error',
                'redirect'=> ''
            ];
            echo json_encode($message);
            return;
        }else{ //se tiver stock suficiente, adicionar produto do carrinho

            //incrementar quantidade do produto e atualizar preço no carrinho
            $plus = $cproduto_quantidade + 1;
            $plus_valor = $produto['preco'] * $plus;
            $menosStock = $produto_quantidade - 1;

            //adicionar quantidade ao produto do carrinho e atualizar stock no produto
            $queryPlus = $conn->prepare('UPDATE carrinho SET quantidade = :quantidade, preco = :preco WHERE id = :id');
            $queryPlus->bindParam(':preco', $plus_valor, PDO::PARAM_INT);
            $queryPlus->bindParam(':quantidade', $plus, PDO::PARAM_INT);
            $queryPlus->bindParam(':id', $value, PDO::PARAM_INT);
            $queryPlus->execute();

            //update do stock do produto
            $queryStock = $conn->prepare('UPDATE produtos SET stock = :stock WHERE id = :id');
            $queryStock->bindParam(':stock', $menosStock, PDO::PARAM_INT);
            $queryStock->bindParam(':id', $produto_id, PDO::PARAM_INT);
            $queryStock->execute();

            
            if($queryPlus && $queryStock){ //se as queries forem executadas com sucesso

                //obter novos dados do carrinho para mostrar na página
                $query = $conn->prepare('SELECT carrinho.id, carrinho.produto_id As idProduto, produtos.nome As nomeproduto, carrinho.quantidade, carrinho.preco,
                produtos.imagem As imagemproduto  FROM carrinho join produtos ON carrinho.produto_id = produtos.id Where carrinho.id = :id ');
                $query->bindParam(':id', $value, PDO::PARAM_INT);
                $query->execute();
                $cart_items = $query->fetchAll(PDO::FETCH_ASSOC);
                            
                //obter novo total do carrinho       
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
        
    