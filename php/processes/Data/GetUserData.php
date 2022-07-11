<?php

    include  '../../init.php';

    class GetUserData{
        public function __construct(){
            global $connect;
            
            if(isset($_GET)){
                $id = filterInput($_GET['id']);

                $query = $connect -> query(
                    "SELECT * FROM `customer` WHERE `id` = '$id'"
                );

                if($query && $query -> num_rows > 0){
                    $row = $query -> fetch_assoc();

                    if($row['profile_img'] === null || !file_exists('../../../customers/images/' . $row['id'] . '/' . $row['profile_img'])){
                        $row['profile_img'] = '../default.png';
                    }

                    echo json_encode(
                        array(
                            'type' => 'success',
                            'data' => $row
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

    $GetUserData = new GetUserData();

?>