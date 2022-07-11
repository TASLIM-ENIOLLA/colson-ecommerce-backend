<?php

    class MULTIPLE_FILES_UPLOAD{
        public function __construct($name, $upload_path, $file_ext_arr){
            if(isset($_FILES) && count($_FILES)){
                $this -> files = $_FILES[$name];
                $this -> error_count = 0;
                $this -> ext_arr = $file_ext_arr;
                $this -> responses_array = [];
                $this -> upload_path = $upload_path;

                if(!preg_match("/\/$/", $this -> upload_path)){
                    $this -> upload_path .= "/";
                }

                if(!file_exists($this -> upload_path)){
                    mkdir($this -> upload_path, 0777, true);
                }

                if(isset($this -> files["name"][0])){
                    $this -> files_length = count($this -> files["name"]);

                    for($x = 0; $x < $this -> files_length; $x++){
                        $file_name = $this -> files["name"][$x];
                        $file_tmp_name = $this -> files["tmp_name"][$x];
                        $file_upload_location = $this -> upload_path . $file_name;
                        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                        if(!in_array($file_ext, $this -> ext_arr)){
                            array_push(
                                $this -> responses_array,
                                array(
                                    "responseType"      => "error",
                                    "message"           => "File extension is unacceptable.",
                                    "file_name"         => $file_name,
                                    "new_file_location" => $file_upload_location,
                                    "file_size"         => $this -> files["size"][$x]
                                )
                            );
                            $this -> error_count++;
                        }
                        elseif(!move_uploaded_file($file_tmp_name, $file_upload_location)){
                            array_push(
                                $this -> responses_array,
                                array(
                                    "responseType"      => "error",
                                    "message"           => "Couldn't upload file, an error occured.",
                                    "file_name"         => $file_name,
                                    "new_file_location" => $file_upload_location,
                                    "file_size"         => $this -> files["size"][$x]
                                )
                            );
                            $this -> error_count++;
                        }
                        else{
                            array_push(
                                $this -> responses_array,
                                array(
                                    "responseType"      => "success",
                                    "message"           => "File upload successful.",
                                    "file_name"         => $file_name,
                                    "new_file_location" => $file_upload_location,
                                    "file_size"         => $this -> files["size"][$x]
                                )
                            );
                        }
                    }

                    $this -> response = json_encode(
                        array(
                            "error_count" => $this -> error_count,
                            "no_of_files" => count($this -> files["name"]),
                            "response" => $this -> responses_array
                        )
                    );
                }
            }
        }
    }

    // $MULTIPLE_FILES_UPLOAD = new MULTIPLE_FILES_UPLOAD(
    //     "files",
    //     "upload",
    //     ["jpeg", "jpg", "png", "gif"]
    // );

?>
