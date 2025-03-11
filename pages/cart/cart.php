<?php 

ini_set('display_errors', 1);
error_reporting(E_ALL);
    include '../../includes/conexao.php';
    if(!isset($_SESSION)){
        session_start();
    }

    $message = null; //mensagem de retorno
    $post = filter_input_array(INPUT_POST, FILTER_DEFAULT); ///filtrar inputs para evitar ataques de SQL Injection
    $postFilters = array_map('strip_tags', $post);//remover tags HTML do input
    if($_SESSION &&!empty($_SESSION['id'])){
        $user_id = $_SESSION['id'];
        
    }
   
    if(!empty($user_id)){

        foreach($postFilters as $key => $value){ //aplicar filtro para nome do produto
            $product = str_replace('-','', mb_strtolower($key)); 
    
    
            $products = $conn->prepare('SELECT * FROM produtos WHERE id = :id');
            $products->bindParam(':id', $value, PDO::PARAM_INT);
            $products->execute();
            
            $stmt = $conn->prepare('SELECT count(*) AS nLinas FROM carrinho');
            $stmt->execute();
            $count = $stmt->fetch(PDO::FETCH_ASSOC)['nLinas'];
    
            
            
            foreach($products as $product){
    
                // verificar se existe stock do produto
                $product_id = strip_tags($product['id']);
                $product_name = strip_tags($product['nome_produto']);  
                $product_preco = strip_tags($product['preco']);
                $product_stock = strip_tags($product['stock']);
                $product_descricao = strip_tags($product['descricao']);
                $produto_imagem = strip_tags($product['imagem']);
              
                if($product_stock <= 0){
    
                    $message = [
                        'message' => 'Produto Esgotado',
                        'status' => 'error',
                        'redirect' => '' 
                    ];
                    echo json_encode($message);
                    return;
                    
                }
    
                //verificar se o produto já existe no carrinho            
                    $carts = $conn->prepare('SELECT * FROM carrinho WHERE produto_id = :produto_id');
                    $carts->bindParam(':produto_id', $product_id, PDO::PARAM_INT);
                    $carts ->execute();
                    
                    
                  
                        
                       if($carts->rowCount() == 0){
                            
                           //produto não existe no carrinho, adicionar
                            $nova_quantidade = 1;
                            $novo_valor = $product_preco * $nova_quantidade;
                            $create = $conn->prepare('INSERT INTO carrinho (user_id, produto_id, quantidade, preco) VALUES (:user_id, :produto_id, :quantidade, :preco)');
                            $create->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                            $create->bindParam(':produto_id', $product_id, PDO::PARAM_INT);
                            $create->bindParam(':quantidade', $nova_quantidade, PDO::PARAM_INT);
                            $create->bindParam(':preco',$novo_valor , PDO::PARAM_STR);
                            $create->execute();
                            
                            //update do stock do produto
                            $newStock = $product_stock - 1;
                            $stock = $conn->prepare('UPDATE produtos SET stock = :stock WHERE id = :id');
                            $stock->bindParam(':stock', $newStock, PDO::PARAM_INT);
                            $stock->bindParam(':id', $product_id, PDO::PARAM_INT);
                            $stock->execute();
                            
                            if($create){ //se adicionado com sucesso
                                $query = $conn->prepare('SELECT carrinho.id, carrinho.user_id, carrinho.produto_id As idProduto, produtos.nome_produto As nomeproduto, quantidade, carrinho.preco,
                                 produtos.imagem As imagemproduto  FROM carrinho join produtos ON carrinho.produto_id = produtos.id Where carrinho.produto_id = :product_id ');
                                $query->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                                $query->execute();
                                $cart_items = $query->fetchAll(PDO::FETCH_ASSOC);
                               
                                $query = $conn->prepare('SELECT sum(preco) AS total FROM carrinho');
                                $query->execute();
                                $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
                                $message = [
                                    'message' => "$product_name adicionado ao carrinho",
                                    'status' => 'success',
                                    'redirect' => '',
                                    'cart_items' => $cart_items,
                                    'count' => $count + 1,
                                    'total' => $total
                                ];
                            
                            }else{
                                $message = [
                                    'message' => "Erro ao adicionar $product_name ao carrinho",
                                    'status' => 'error',
                                    'redirect' => '' 
                                ];
                                
                            }
                           
                        }else{
                            //produto existe no carrinho, incrementar a quantidade
                            $cart = $carts->fetch(PDO::FETCH_ASSOC);
                            $cart_quantidade = strip_tags($cart['quantidade'] + 1);
                            $value = $product_preco * $cart_quantidade;
                            $stockToUp = $product_stock - 1;
                            
                            //atualizar a quantidade e preço do item no carrinho
                            $updateStock = $conn->prepare('UPDATE carrinho SET quantidade = :quantidade, preco = :value WHERE id = :id AND produto_id = :produto_id');
                            $updateStock->bindParam(':quantidade', $cart_quantidade, PDO::PARAM_INT);
                            $updateStock->bindParam(':value', $value, PDO::PARAM_STR);
                            $updateStock->bindParam(':id', $cart['id'], PDO::PARAM_INT);
                            $updateStock->bindParam(':produto_id', $product_id,PDO::PARAM_INT);
                            $updateStock->execute();
            
                            //update do stock do produto
                        
                            $stockNew = $conn->prepare('UPDATE produtos SET stock = :stock WHERE id = :id');
                            $stockNew->bindParam(':stock', $stockToUp, PDO::PARAM_INT);
                            $stockNew->bindParam(':id', $product_id, PDO::PARAM_INT);
                            $stockNew->execute();
    
    
                            
                            if($stockNew){ //se stockNew foram actualizados com sucesso
                                //obter itens do carrinho para mostrar na página
                                $query = $conn->prepare('SELECT carrinho.id, carrinho.produto_id As idProduto, produtos.nome_produto As nomeproduto, carrinho.quantidade, carrinho.preco,
                                 produtos.imagem As imagemproduto  FROM carrinho join produtos ON carrinho.produto_id = produtos.id Where carrinho.produto_id = :product_id ');
                                $query->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                                $query->execute();
                                $cart_items = $query->fetchAll(PDO::FETCH_ASSOC);
                                
                                //obter o total do carrinho
                                $query = $conn->prepare('SELECT sum(preco) AS total FROM carrinho');
                                $query->execute();
                                $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
                                $message = [
                                    'message' => "$product_name adicionado ao carrinho",
                                    'status' => 'success',
                                    'redirect' => '',
                                    'cart_items' => $cart_items,
                                    'count' => $count,
                                    'total' => $total,
                                    
                                ];
                                
                                
                            }else{
                                $message = [
                                    'message' => "Erro ao adicionar $product_name ao carrinho",
                                    'status' => 'error',
                                    'redirect' => '' 
                                ];
                                
                            }
                        }      
                echo json_encode($message);
            }

    }
    
        
}else{


    foreach($postFilters as $key => $value){ //aplicar filtro para nome do produto
        $product = str_replace('-','', mb_strtolower($key)); 


        $products = $conn->prepare('SELECT * FROM produtos WHERE id = :id');
        $products->bindParam(':id', $value, PDO::PARAM_INT);
        $products->execute();
        
        $stmt = $conn->prepare('SELECT count(*) AS nLinas FROM carrinho');
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['nLinas'];

        
        
        foreach($products as $product){

            // verificar se existe stock do produto
            $product_id = strip_tags($product['id']);
            $product_name = strip_tags($product['nome_produto']);  
            $product_preco = strip_tags($product['preco']);
            $product_stock = strip_tags($product['stock']);
            $product_descricao = strip_tags($product['descricao']);
            $produto_imagem = strip_tags($product['imagem']);
          
            if($product_stock <= 0){

                $message = [
                    'message' => 'Produto Esgotado',
                    'status' => 'error',
                    'redirect' => '' 
                ];
                echo json_encode($message);
                return;
                
            }

            //verificar se o produto já existe no carrinho            
                $carts = $conn->prepare('SELECT * FROM carrinho WHERE produto_id = :produto_id');
                $carts->bindParam(':produto_id', $product_id, PDO::PARAM_INT);
                $carts ->execute();
                
                
              
                    
                   if($carts->rowCount() == 0){
                        
                       //produto não existe no carrinho, adicionar
                        $nova_quantidade = 1;
                        $novo_valor = $product_preco * $nova_quantidade;
                        $create = $conn->prepare('INSERT INTO carrinho (produto_id, quantidade, preco) VALUES (:produto_id, :quantidade, :preco)');
                        $create->bindParam(':produto_id', $product_id, PDO::PARAM_INT);
                        $create->bindParam(':quantidade', $nova_quantidade, PDO::PARAM_INT);
                        $create->bindParam(':preco',$novo_valor , PDO::PARAM_STR);
                        $create->execute();
                        
                        //update do stock do produto
                        $newStock = $product_stock - 1;
                        $stock = $conn->prepare('UPDATE produtos SET stock = :stock WHERE id = :id');
                        $stock->bindParam(':stock', $newStock, PDO::PARAM_INT);
                        $stock->bindParam(':id', $product_id, PDO::PARAM_INT);
                        $stock->execute();
                        
                        if($create){ //se adicionado com sucesso
                            $query = $conn->prepare('SELECT carrinho.id, carrinho.produto_id As idProduto, produtos.nome_produto As nomeproduto, quantidade, carrinho.preco,
                             produtos.imagem As imagemproduto  FROM carrinho join produtos ON carrinho.produto_id = produtos.id Where carrinho.produto_id = :product_id ');
                            $query->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                            $query->execute();
                            $cart_items = $query->fetchAll(PDO::FETCH_ASSOC);
                           
                            $query = $conn->prepare('SELECT sum(preco) AS total FROM carrinho');
                            $query->execute();
                            $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
                            $message = [
                                'message' => "$product_name adicionado ao carrinho",
                                'status' => 'success',
                                'redirect' => '',
                                'cart_items' => $cart_items,
                                'count' => $count + 1,
                                'total' => $total
                            ];
                        
                        }else{
                            $message = [
                                'message' => "Erro ao adicionar $product_name ao carrinho",
                                'status' => 'error',
                                'redirect' => '' 
                            ];
                            
                        }
                       
                    }else{
                        //produto existe no carrinho, incrementar a quantidade
                        $cart = $carts->fetch(PDO::FETCH_ASSOC);
                        $cart_quantidade = strip_tags($cart['quantidade'] + 1);
                        $value = $product_preco * $cart_quantidade;
                        $stockToUp = $product_stock - 1;
                        
                        //atualizar a quantidade e preço do item no carrinho
                        $updateStock = $conn->prepare('UPDATE carrinho SET quantidade = :quantidade, preco = :value WHERE id = :id AND produto_id = :produto_id');
                        $updateStock->bindParam(':quantidade', $cart_quantidade, PDO::PARAM_INT);
                        $updateStock->bindParam(':value', $value, PDO::PARAM_STR);
                        $updateStock->bindParam(':id', $cart['id'], PDO::PARAM_INT);
                        $updateStock->bindParam(':produto_id', $product_id,PDO::PARAM_INT);
                        $updateStock->execute();
        
                        //update do stock do produto
                    
                        $stockNew = $conn->prepare('UPDATE produtos SET stock = :stock WHERE id = :id');
                        $stockNew->bindParam(':stock', $stockToUp, PDO::PARAM_INT);
                        $stockNew->bindParam(':id', $product_id, PDO::PARAM_INT);
                        $stockNew->execute();


                        
                        if($stockNew){ //se stockNew foram actualizados com sucesso
                            //obter itens do carrinho para mostrar na página
                            $query = $conn->prepare('SELECT carrinho.id, carrinho.produto_id As idProduto, produtos.nome_produto As nomeproduto, carrinho.quantidade, carrinho.preco,
                             produtos.imagem As imagemproduto  FROM carrinho join produtos ON carrinho.produto_id = produtos.id Where carrinho.produto_id = :product_id ');
                            $query->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                            $query->execute();
                            $cart_items = $query->fetchAll(PDO::FETCH_ASSOC);
                            
                            //obter o total do carrinho
                            $query = $conn->prepare('SELECT sum(preco) AS total FROM carrinho');
                            $query->execute();
                            $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
                            $message = [
                                'message' => "$product_name adicionado ao carrinho",
                                'status' => 'success',
                                'redirect' => '',
                                'cart_items' => $cart_items,
                                'count' => $count,
                                'total' => $total,
                                
                            ];
                            
                            
                        }else{
                            $message = [
                                'message' => "Erro ao adicionar $product_name ao carrinho",
                                'status' => 'error',
                                'redirect' => '' 
                            ];
                            
                        }
                    }      
            echo json_encode($message);
        }

}

}
        
        
    
?>