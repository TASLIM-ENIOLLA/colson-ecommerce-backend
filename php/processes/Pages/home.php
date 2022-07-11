<?php

    include '../../init.php';

    include '../data/BestSellers.php';
    include '../data/NewArrivals.php';

    class Home{
        public function __construct(){
            if(isset($_GET)){
                $bestSellers = new BestSellers();
                $newArrivals = new NewArrivals();

                echo json_encode(array(
                    'type' => 'success',
                    'data' => array(
                        'bestSellers' => $bestSellers -> getBestSellers(),
                        'newArrivals' => $newArrivals -> getNewArrivals()
                    )
                ));
            }
            else{
                echo json_encode(array(
                    'type' => 'error',
                    'data' => 'Unacceptable request!'
                ));   
            }
        }
    }

    $Home = new Home();

?>