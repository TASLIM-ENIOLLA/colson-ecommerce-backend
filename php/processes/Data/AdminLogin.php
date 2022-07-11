<?php

    include '../../init.php';

    class AdminLogin{
        public function __construct(){
            global $connect;

            if(isset($_POST)){
                $email = filterInput($_POST['email']);
                $password = filterInput($_POST['password']);

                if(!empty($email) && !empty($password)){
                    if(strlen($email) > 255){
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
                    elseif(!is_email_exist($email, 'admin')){
                        echo json_encode(
                            array(
                                'type' => 'error',
                                'data' => 'Email address unrecognized!'
                            )
                        );
                    }
                    else{
                        $password = md5($password);

                        $query = $connect -> query(
                            "SELECT * FROM `admin` WHERE `email` = '$email' AND `password` = '$password'"
                        );

                        if($query && $query -> num_rows > 0){
                            $row = $query -> fetch_assoc();
                            $row['profile_img'] = (
                                (read_folder('../../../admin/images/' . $row['id']) !== [])
                                ? read_folder('../../../admin/images/' . $row['id'])
                                : ['../default.png']
                            );

                            echo json_encode(array(
                                'type' => 'success',
                                'data' => 'Admin logged in successfully, redirecting!',
                                'user_data' => $row
                            ));
                        }
                        else{
                            echo json_encode(array(
                                'type' => 'error',
                                'data' => 'One or more fields are empty!'
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
    $AdminLogin = new AdminLogin();

?>