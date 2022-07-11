<?php

    include '../../init.php';

    class Register{
        public function __construct(){
            global $connect;

            if(isset($_POST) && !empty($_POST)){
                $f_name = filterInput($_POST['f_name']);
                $l_name = filterInput($_POST['l_name']);
                $email = filterInput($_POST['email']);
                $phone = filterInput($_POST['phone']);
                $password = filterInput($_POST['password']);
                $c_password = filterInput($_POST['c_password']);

                if(!empty($f_name) && !empty($l_name) && !empty($email) && !empty($phone) && !empty($password) && !empty($c_password)){
                    if(strlen($f_name) > 255){
                        echo json_encode(
                            array(
                                'type' => 'error',
                                'data' => 'First name too long, max. of 255 charatcers!'
                            )
                        );
                    }
                    elseif(strlen($l_name) > 255){
                        echo json_encode(
                            array(
                                'type' => 'error',
                                'data' => 'Last name too long, max. of 255 charatcers!'
                            )
                        );
                    }
                    elseif(strlen($email) > 255){
                        echo json_encode(
                            array(
                                'type' => 'error',
                                'data' => 'Email address too long, max. of 255 charatcers!'
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
                    elseif(is_email_exist($email)){
                        echo json_encode(
                            array(
                                'type' => 'error',
                                'data' => 'Email already in use, try another!'
                            )
                        );
                    }
                    elseif(strlen($phone) > 20){
                        echo json_encode(
                            array(
                                'type' => 'error',
                                'data' => 'Phone number too long, max. of 20 charatcers!'
                            )
                        );
                    }
                    elseif(strlen($password) < 8){
                        echo json_encode(
                            array(
                                'type' => 'error',
                                'data' => 'Password too short, max. of 8 charatcers!'
                            )
                        );
                    }
                    elseif($c_password !== $password){
                        echo json_encode(
                            array(
                                'type' => 'error',
                                'data' => 'Passwords do not match!'
                            )
                        );
                    }
                    else{
                        $id = generate_customer_id();
                        $password = md5($password);

                        $query = $connect -> query(
                            "INSERT INTO `customer` SET `id` = '$id', `f_name` = '$f_name', `l_name` = '$l_name', `email` = '$email', `phone` = '$phone', `password` = '$password'"
                        );

                        if($query){
                            echo json_encode(
                                array(
                                    'type' => 'success',
                                    'data' => 'Registration successful!',
                                    'user_data' => array(
                                        'id' => $id,
                                        'email' => $email,
                                        'f_name' => $f_name
                                    )
                                )
                            );
                        }
                        else{
                            echo json_encode(
                                array(
                                    'type' => 'error',
                                    'data' => 'An error occured, please try again!'
                                )
                            );
                        }
                    }
                }
                else{
                    echo json_encode(
                        array(
                            'type' => 'error',
                            'data' => 'One or more compulsory fields are empty!'
                        )
                    );
                }
            }
            else{
                echo json_encode(array(
                    'type' => 'error',
                    'data' => 'Incomplete request body.'
                ));
            }
        }
    }

    $Register = new Register();

?>