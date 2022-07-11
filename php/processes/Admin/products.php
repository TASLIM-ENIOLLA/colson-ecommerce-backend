<?php

    include '../../init.php';

    class Products{
        function __construct(){
            global $connect;

            $query = $connect -> query(
                "SELECT * FROM `products` ORDER BY `timestamp` DESC"
            );

            if($query && $query -> num_rows > 0){
                $data = [];

                while($row = $query -> fetch_assoc()){
                    $row['timestamp'] = format_timestamp_for_messages($row['timestamp']);
                    array_push($data, $row);
                }

                echo json_encode(
                    array(
                        'type' => 'success',
                        'data' => $data
                    )
                );
            }
            else{
                echo json_encode(
                    array(
                        'type' => 'error',
                        'data' => []
                    )
                );
            }
        }
    }

    $Products = new Products();

?>