<?php

    include '../../init.php';

    class UpdateUserInfo{
        public function __construct(){
            if(isset($_POST)){
                global $connect;

                $f_name = filterInput($_POST['f_name']);
                $l_name = filterInput($_POST['l_name']);
                $email = filterInput($_POST['email']);
                $phone = filterInput($_POST['phone']);
                $country = filterInput($_POST['country']);
                $town_or_city = filterInput($_POST['town_or_city']);
                $state_or_region = filterInput($_POST['state_or_region']);
                $postal_or_zipcode = filterInput($_POST['postal_or_zipcode']);
                $address = filterInput($_POST['address']);
                $userID = filterInput($_POST['userID']);
                $profile_img = !empty($_FILES) ? $_FILES['profile_img'] : null;

                if(!empty($f_name) && !empty($l_name) && !empty($email) && !empty($phone) && !empty($country) && !empty($address) && !empty($town_or_city) && !empty($state_or_region) && !empty($postal_or_zipcode)){
                    if(strlen($f_name) > 50){
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
                        $ext = $profile_img ? explode('.' , $profile_img['name']) : "";
                        if($profile_img){
                            $upload_folder = '../../../customers/images/' . $userID . '/';
                            $upload_path = $upload_folder . $userID . '.' . end($ext);

                            file_exists($upload_folder) ? null : mkdir($upload_folder, 0777, true);
                            move_uploaded_file($profile_img['tmp_name'], $upload_path);
                        }

                        $query = $connect -> query(
                            "UPDATE  
                            `customer` 
                            SET 
                            `f_name` = '$f_name', 
                            `l_name` = '$l_name',
                            `email` = '$email',
                            `phone` = '$phone',
                            `address` = '$address',
                            `country` = '$country',
                            `town_or_city` = '$town_or_city',
                            `state_or_region` = '$state_or_region',
                            `postal_or_zipcode` = '$postal_or_zipcode'" . (
                                ($profile_img)
                                ? ", `profile_img` = '" . $userID . '.' . end($ext) . "'"
                                : ''
                            ) . " WHERE `id` = '$userID'"
                        );
    
                        if($query){
                            echo json_encode(
                                array(
                                    'type' => 'success',
                                    'data' => 'User data updated successfully!'
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
                    'data' => 'INCOMPLETE REQUEST'
                ));
            }
        }
    }

    $UpdateUserInfo = new UpdateUserInfo();

?>