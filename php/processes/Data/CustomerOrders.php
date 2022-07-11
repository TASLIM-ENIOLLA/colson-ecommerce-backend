<?php

    include  '../../init.php';

    class GetCustomerOrders{
        public function __construct(){
            global $connect;
            
            if(isset($_GET)){
                $id = filterInput($_GET['id']);

                $query = $connect -> query(
                    "SELECT * FROM `orders` WHERE `customer_id` = '$id'"
                );

                if($query && $query -> num_rows > 0){
                    $arr = [];

                    while($row = $query -> fetch_assoc()){
                        $row['order_data'] = json_decode($row['order_data']);
                        $row['timestamp'] = date('D, d m Y - h:i a', strtotime($row['timestamp']));
                        array_push($arr, $row);
                    }

                    echo json_encode(
                        array(
                            'type' => 'success',
                            'data' => $arr
                        )
                    );
                }
                else{
                    echo json_encode(array(
                        'type' => 'error',
                        'data' => 'Something went wrong, please retry!'
                    ));
                }
            }
            else{
                echo json_encode(array(
                    'type' => 'error',
                    'data' => 'FORBIDDEN'
                ));
            }
        }
    }

    $GetCustomerOrders = new GetCustomerOrders();

?>