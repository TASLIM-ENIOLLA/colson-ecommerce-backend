<?php

    include  '../../init.php';

    class PlaceOrder{
        public function __construct(){
            global $connect;
            
            if(isset($_POST)){
                $f_name = filterInput($_POST['f_name']);
                $l_name = filterInput($_POST['l_name']);
                $country = filterInput($_POST['country']);
                $address = filterInput($_POST['address']);
                $customer_id = filterInput($_POST['customer_id']);
                $town_or_city = filterInput($_POST['town_or_city']);
                $state_or_region = filterInput($_POST['state_or_region']);
                $postal_or_zipcode = filterInput($_POST['postal_or_zipcode']);
                $phone = filterInput($_POST['phone']);
                $email = filterInput($_POST['email']);
                $notes = filterInput($_POST['notes']);
                $order_list = $_POST['order_list'];

                if(!empty($f_name) && !empty($l_name) && !empty($email) && !empty($phone) && !empty($country) && !empty($address) && !empty($town_or_city) && !empty($state_or_region) && !empty($postal_or_zipcode)){
                    if(strlen($order_list) < 1){
                        echo json_encode(array(
                            'type' => 'error',
                            'data' => 'Order list can\'t be empty!'
                        ));
                    }
                    elseif(strlen($f_name) > 50){
                        echo json_encode(
                            array(
                                'type' => 'error',
                                'data' => 'First name too long, max. of 50 characters!'
                            )
                        );
                    }
                    elseif(!preg_match("/^[a-zA-Z ]*$/", $f_name)) {
                        echo json_encode(
                            array(
                                'type' => 'error',
                                'data' => 'First name contains unwanted characters, only letters and whitespace are allowed!'
                            )
                        );
                    }
                    elseif(strlen($l_name) > 50){
                        echo json_encode(
                            array(
                                'type' => 'error',
                                'data' => 'Last name too long, max. of 50 characters!'
                            )
                        );
                    }
                    elseif(!preg_match("/^[a-zA-Z ]*$/", $l_name)) {
                        echo json_encode(
                            array(
                                'type' => 'error',
                                'data' => 'Last name contains unwanted characters, only letters and whitespace are allowed!'
                            )
                        );
                    }
                    elseif(strlen($email) > 255){
                        echo json_encode(
                            array(
                                'type' => 'error',
                                'data' => 'Email address too long, max. of 255 characters!'
                            )
                        );
                    }
                    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                        echo json_encode(
                            array(
                                'type' => 'error',
                                'data' => 'Email format is invalid!'
                            )
                        );
                    }
                    elseif(!preg_match("/[0-9\-\+]/", $phone)){
                        echo json_encode(
                            array(
                                'type' => 'error',
                                'data' => 'Phone number contains unwanted characters!'
                            )
                        );
                    }
                    elseif(strlen($phone) > 20){
                        echo json_encode(
                            array(
                                'type' => 'error',
                                'data' => 'Phone number too long, max. of 20 characters!'
                            )
                        );
                    }
                    elseif(strlen($postal_or_zipcode) > 8){
                        echo json_encode(
                            array(
                                'type' => 'error',
                                'data' => 'Postal code too long, max. of 8 characters!'
                            )
                        );
                    }
                    else{
                        $id = generate_order_id();
                        
                        $query = $connect -> query(
                            "INSERT INTO 
                            `orders` 
                            SET 
                            `id` = '$id',
                            `customer_id` = '$customer_id',
                            `f_name` = '$f_name', 
                            `l_name` = '$l_name',
                            `country` = '$country',
                            `address` = '$address',
                            `town_or_city` = '$town_or_city',
                            `state_or_region` = '$state_or_region',
                            `postal_or_zipcode` = '$postal_or_zipcode',
                            `phone` = '$phone',
                            `email` = '$email',
                            `notes` = '$notes',
                            `order_data` = '$order_list'"
                        );
    
                        if($query){
                            $to = "somebody@example.com";
                            
                            $headers = "From: " . $email . "\r\n" .
                            "CC: " . $email;

                            @mail($to,$subject,$message,$headers);

                            echo json_encode(
                                array(
                                    'type' => 'success',
                                    'data' => 'Order placed successfully. Your product delivery is being processed!'
                                )
                            );
                        }
                        else{
                            echo json_encode(array(
                                'type' => 'error',
                                'data' => 'Something went wrong, please retry!',
                            ));
                        }
                    }
                }
                else{
                    echo json_encode(array(
                        'type' => 'error',
                        'data' => 'One or more fields are empty!'
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

    $PlaceOrder = new PlaceOrder();

?>