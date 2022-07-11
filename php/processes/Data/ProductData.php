<?php

    class ProductData{
        public function __construct($productID){
            global $connect;

            $query = $connect -> query(
                "SELECT * FROM `products` WHERE `id` = '$productID' ORDER BY `timestamp` DESC"
            );

            if($query){
                $row = $query -> fetch_assoc();
                // $row['images'] = read_folder('../../../products/images/' . $row['id']);
    
                $this -> response = json_encode(array(
                    'type' => 'success',
                    'data' => $row
                ));
            }
            else{
                $this -> response = json_encode(array(
                    'type' => 'error',
                    'data' => null
                ));
            }
        }
        public function response(){
            return $this -> response;
        }
    }

?>