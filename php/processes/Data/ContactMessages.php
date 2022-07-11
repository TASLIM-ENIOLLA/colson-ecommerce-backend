<?php

    include  '../../init.php';

    class ContactMessages{
        public function __construct(){
            global $connect;
            
            if(isset($_POST)){
                $name = filterInput($_POST['name']);
                $email = filterInput($_POST['email']);
                $phone = filterInput($_POST['phone']);
                $subject = filterInput($_POST['subject']);
                $message = filterInput($_POST['message']);

                if(!empty($name) && !empty($email) && !empty($phone) && !empty($subject) && !empty($message)){
                    if(strlen($name) > 50){
                        echo json_encode(
                            array(
                                'type' => 'error',
                                'data' => 'Name too long, max. of 50 charatcers!'
                            )
                        );
                    }
                    elseif(!preg_match("/^[a-zA-Z ]*$/", $name)) {
                        echo json_encode(
                            array(
                                'type' => 'error',
                                'data' => 'Name contains unwanted characters, only letters and whitespace are allowed!'
                            )
                        );
                    }
                    elseif(empty($email)){
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
                                'data' => 'Phone number too long, max. of 20 charatcers!'
                            )
                        );
                    }
                    elseif(strlen($subject) > 50){
                        echo json_encode(
                            array(
                                'type' => 'error',
                                'data' => 'Subject too long, max. of 50 charatcers!'
                            )
                        );
                    }
                    else{
                        $query = $connect -> query(
                            "INSERT INTO `contact_messages` SET `name` = '$name', `phone` = '$phone', `email` = '$email', `subject` = '$subject', `message` = '$'"
                        );
    
                        if($query){
                            $to = "somebody@example.com";
                            
                            $headers = "From: " . $email . "\r\n" .
                            "CC: " . $email;

                            @mail($to,$subject,$message,$headers);

                            echo json_encode(
                                array(
                                    'type' => 'success',
                                    'data' => 'Message sent successfully. Expect a reply shortly!'
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

    $ContactMessages = new ContactMessages();

?>