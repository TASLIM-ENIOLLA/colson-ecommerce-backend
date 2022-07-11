<?php

    include '../../init.php';

    class SignIn{
        public function __construct(){
            global $connect;

            if(isset($_POST) && !empty($_POST)){
                $email = filterInput($_POST['email']);
                $password = filterInput($_POST['password']);

                if(!empty($email) && !empty($password)){
                    if(strlen($email) > 255){
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
                    elseif(!is_email_exist($email)){
                        echo json_encode(
                            array(
                                'type' => 'error',
                                'data' => 'Email is unfamliar!'
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
                    }else{
                        $password = md5($password);

                        $query = $connect -> query(
                            "SELECT `id`, `f_name` FROM `customer` WHERE `email` = '$email' AND `password` = '$password'"
                        );

                        if($query && $query -> num_rows > 0){
                            $row = $query -> fetch_assoc();

                            echo json_encode(
                                array(
                                    'type' => 'success',
                                    'data' => 'Sign in successful!',
                                    'user_data' => array(
                                        'id' => $row['id'],
                                        'email' => $email,
                                        'f_name' => $row['f_name']
                                    )
                                )
                            );
                        }
                        else{
                            echo json_encode(
                                array(
                                    'type' => 'error',
                                    'data' => 'An error occured, password may be incorrect!'
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

    $SignIn = new SignIn();

?>