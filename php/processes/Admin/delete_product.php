<?php

    include '../../init.php';

    class DeleteProduct{
        function __construct(){
            global $connect;

            if(isset($_GET)){
                $productID = filterInput($_GET['productID']);

                $query = $connect -> query(
                    "DELETE FROM `products` WHERE `id` = '$productID'"
                );

                if($query){
                    clear_folder('../../../products/images/' . $productID . '/');
                    rmdir('../../../products/images/' . $productID . '/');

                    echo json_encode(array(
                        'type' => 'success',
                        'data' => 'Product removed successfully'
                    ));
                }
            }
            else{
                echo json_encode(
                    array(
                        'type' => 'error',
                        'data' => 'FORBIDDEN'
                    )
                );
            }
        }
    }

    $DeleteProduct = new DeleteProduct();

?>