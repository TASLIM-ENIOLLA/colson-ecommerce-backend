<?php
    function filterInput($var){
        global $connect;
        $var = trim($var);
        $var = htmlspecialchars($var);
        $var = stripslashes($var);
        $var = strip_tags($var);
        $var = mysqli_real_escape_string($connect, $var);
        $var = strtolower($var);
        return $var;
    }
    function forbiddenChars($string, $type = null){
        if($type == "name" || $type == null){
            return preg_match("/[^0-9a-zA-Z_\-\s]/", $string);
        }
        elseif($type == "text"){
            return preg_match("/[^0-9a-zA-Z_\/\r\s\-\+\,\!\.]/", $string);
        }
        elseif($type == "number"){
            return preg_match("/[^0-9\,\.]/", $string);
        }
        elseif($type == "password"){
            return preg_match("/[^0-9a-zA-Z_]/", $string);
        }
    }
    function clear_folder($path){
        if(file_exists($path)){
            foreach(scandir($path) as $file){
                if($file != "." && $file != ".."){
                    unlink($path . $file);
                }
            }
        }
    }
    function read_folder($path){
        $arr = [];
        
        if(file_exists($path)){
            foreach(scandir($path) as $file){
                if($file != "." && $file != ".."){
                    array_push($arr, $file);
                }
            }
        }
        
        return $arr;
    }
    function random_text_gen($no_of_chars){
        $string = '';
        do{
            $string .= sha1(time());
        } while(strlen($string) < $no_of_chars);
        
        return substr($string, 0, $no_of_chars);
    }
    function format_timestamp_for_messages($vara){
        $today = date("Y-m-d h:m:s");
        $day_diff = (strtotime($today) - strtotime($vara)) / (60 * 60 * 24);

        if($day_diff <= 1){
            $new_date = date("h:ia", strtotime($vara));
            return $new_date;
        }
        elseif($day_diff > 1 && $day_diff < 2){
            return "Yesterday";
        }
        elseif($day_diff >= 2 && $day_diff <= 7){
            // $new_date = date("M d", strtotime($vara));
            return floor($day_diff) . ' days ago';
            // return $new_date;
        }
        elseif($day_diff > 7 && $day_diff <= 30){
            return floor($day_diff / 7) . ' week' . (floor($day_diff / 7) > 1 ? 's' : '') . ' ago';
            // // return $day_diff . ' week' . $day_diff % 7 > 1 ? 's' : '' . ' ago';
            // return $new_date;
        }
        else{
            return date("M d", strtotime($vara));
        }
    }
    function generate_customer_id(){
        global $connect;
        $id = 'cust-' . random_text_gen(4) . '-' . random_text_gen(5) . '-' . random_text_gen(4);

        $query = $connect -> query(
            "SELECT * FROM `customer` WHERE `id` = '$id'"
        );

        if($query && $query -> num_rows > 0){
            generate_customer_id();
        }
        else{
            return $id;
        }
    }
    function generate_product_id(){
        global $connect;
        $id = 'prd-' . substr(md5(time()), 0, 16);

        $query = $connect -> query(
            "SELECT * FROM `products` WHERE `id` = '$id'"
        );

        if($query && $query -> num_rows > 0){
            generate_product_id();
        }
        else{
            return $id;
        }
    }
    function generate_order_id(){
        global $connect;
        $id = 'ord-' . substr(md5(time()), 0, 16);

        $query = $connect -> query(
            "SELECT * FROM `orders` WHERE `id` = '$id'"
        );

        if($query && $query -> num_rows > 0){
            generate_order_id();
        }
        else{
            return $id;
        }
    }
    function is_email_exist($email, $table = 'customer'){
        global $connect;

        $query = $connect -> query(
            "SELECT COUNT(*) AS 'count' FROM `$table` WHERE `email` = '$email'"
        );

        if($query && $query -> fetch_assoc()['count'] > 0){
            return true;
        }
        else{
            return false;
        }
    }
?>
