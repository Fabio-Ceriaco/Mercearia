<?php 
    session_start();
    include '../../includes/conexao.php';

    $message = null;
    $post = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $postFilters = array_map('strip_tags', $post);


    foreach($postFilters as $key => $value){
        $product = str_replace('-','', mb_strtolower($key)); 

        
        
        if(!isset($_SESSION) || empty($_SESSION)){   //sessão do utilizador
            $_SESSION = rand(1, 1000);
        }

        $products = $conn->prepare('SELECT * FROM produtos WHERE id = :id');
        $products->bindParam(':id', $value, PDO::PARAM_INT);
        $products->execute();
        
        
        foreach($products as $product){

            // verificar se existe stock do produto
            $product_id = strip_tags($product['id']);
            $product_name = strip_tags($product['nome']);  
            $product_preco = strip_tags($product['preco']);
            $product_stock = strip_tags($product['stock']);
            $product_descricao = strip_tags($product['descricao']);
          
            if($product_stock <= 0){

                $message = [
                    'message' => 'Produto Esgotado',
                    'status' => 'warning',
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
                        $create = $conn->prepare('INSERT INTO carrinho ( produto_id, quantidade, preco) VALUES ( :produto_id, :quantidade, :preco)');
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
                        
                        if($create){
                            $message = [
                                'message' => "$product_name adicionado ao carrinho",
                                'status' => 'success',
                                'redirect' => ''
                                
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
        
                        if($stockNew){
                            $message = [
                                'message' => "$product_name adicionado ao carrinho",
                                'status' => 'success',
                                'redirect' => ''
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
        
        
    
?>