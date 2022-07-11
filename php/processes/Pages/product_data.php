<?php

    include '../../init.php';

    class ProductDataRouteHandler{
        public function __construct(){
            if(isset($_GET)){
                global $connect;

                $productID = $_GET['productID'];

                $query = $connect -> query(
                    "SELECT * FROM `products` WHERE `id` = '$productID' ORDER BY `timestamp` DESC"
                );

                if($query && $query -> num_rows > 0){
                    $row = $query -> fetch_assoc();
                    $row['images'] = read_folder('../../../products/images/' . $row['id']);
        
                    echo json_encode(array(
                        'type' => 'success',
                        'data' => $row
                    ));
                }
                else{
                    echo json_encode(array(
                        'type' => 'error',
                        'data' => null
                    ));
                }
            }
            else{
                echo json_encode(array(
                    'type' => 'error',
                    'data' => 'Unacceptable request!'
                ));   
            }
        }
    }

    $ProductDataRouteHandler = new ProductDataRouteHandler();

?>