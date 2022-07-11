<?php

    include '../../init.php';

    class UpdateProduct{
        function upload_images($name, $upload_path, $ext_arr){
            $images = !empty($_FILES) ? $_FILES[$name] : null;

            if($images){
                $images_name = $images['name'];

                foreach($images_name as $key => $file){
                    $file_name = $file;
                    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                    $file_tmp_name = $images['tmp_name'][$key];
                    $file_upload_path = $upload_path . '/' . ++$key . '.' . $file_ext;

                    !file_exists($upload_path) ? mkdir($upload_path, 0777, true) : null;
                    move_uploaded_file($file_tmp_name, $file_upload_path);
                }
            }
        }
        function __construct(){
            global $connect;

            if(isset($_POST) && !empty($_POST)){
                $id = filterInput($_POST['id']);
                $name = filterInput($_POST['name']);
                $quantity = filterInput($_POST['quantity']);
                $category = filterInput($_POST['category']);
                $price = filterInput($_POST['price']);
                $gender = filterInput($_POST['gender']);
                $description = filterInput($_POST['description']);

                if(strlen($name) > 255){
                    echo json_encode(
                        array(
                            'type' => 'error',
                            'data' => 'Product name is too long, max. of 255 characters!'
                        )
                    );
                }
                elseif(!preg_match("/^[a-zA-Z ]*$/", $name)) {
                    echo json_encode(
                        array(
                            'type' => 'error',
                            'data' => 'Product name contains unwanted characters, only letters and whitespace are allowed!'
                        )
                    );
                }
                elseif(!preg_match("/[0-9]/", $quantity)){
                    echo json_encode(
                        array(
                            'type' => 'error',
                            'data' => 'Product quantity contains unwanted characters!'
                        )
                    );
                }
                elseif(!preg_match("/[0-9]/", $price)){
                    echo json_encode(
                        array(
                            'type' => 'error',
                            'data' => 'Product price contains unwanted characters!'
                        )
                    );
                }
                elseif(strlen($description) > 255){
                    echo json_encode(
                        array(
                            'type' => 'error',
                            'data' => 'Product description is too long, max. of 255 characters!'
                        )
                    );
                }
                else{
                    $query = $connect -> query(
                        "UPDATE `products` SET `name` = '$name', `quantity` = '$quantity', `category` = '$category', `price` = '$price', `gender` = '$gender', `description` = '$description' WHERE id = '$id'"
                    );
                    
                    if($query){
                        $this -> upload_images('images', '../../../products/images/' . $id, []);
                        
                        echo json_encode(
                            array(
                                'type' => 'success',
                                'data' => 'Product data updated successfully!',
                                'product_data' => array(
                                    'id' => $id,
                                    'name' => $name,
                                    'quantity' => $quantity,
                                    'category' => $category,
                                    'price' => $price,
                                    'description' => $description,
                                )
                            )
                        );
                    }
                    else{
                        echo json_encode(
                            array(
                                'type' => 'error',
                                'data' => 'An error occured!'
                            )
                        );
                    }
                }
            }
            else{
                echo json_encode(
                    array(
                        'type' => 'error',
                        'data' => 'Incomplete request!'
                    )
                );
            }
        }
    }

    $UpdateProduct = new UpdateProduct();

?>