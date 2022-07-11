<?php

    include  '../../init.php';

    class NewsLetter{
        public function __construct(){
            global $connect;
            
            if(isset($_POST)){
                $email = filterInput($_POST['email']);

                if(empty($email)){
                    echo json_encode(array(
                        'type' => 'error',
                        'data' => 'Email cannot be empty!'
                    ));
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
                elseif(is_email_exist($email, 'newsletter')){
                    echo json_encode(
                        array(
                            'type' => 'error',
                            'data' => 'Email has previously subscribed to newsletter!'
                        )
                    );
                }
                else{
                    $query = $connect -> query(
                        "INSERT INTO `newsletter` SET `email` = '$email'"
                    );

                    if($query){
                        echo json_encode(
                            array(
                                'type' => 'success',
                                'data' => 'You\'ve successfully subscribed to our newsletter!'
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
            }
            else{
                echo json_encode(array(
                    'type' => 'error',
                    'data' => 'FORBIDDEN'
                ));
            }
        }
    }

    $NewsLetter = new NewsLetter();

?>