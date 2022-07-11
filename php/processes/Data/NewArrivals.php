<?php

    class NewArrivals{
        public function __construct(){
            global $connect;

            $query = $connect -> query(
                "SELECT * FROM `products` ORDER BY `timestamp` DESC"
            );

            $this -> query = $query;
        }
        public function getNewArrivals(){
            $data = [];

            while($row = $this -> query -> fetch_assoc()){
                $row['images'] = read_folder('../../../products/images/' . $row['id']);
                array_push($data, $row);
            }

            return $data;
        }
    }

?>