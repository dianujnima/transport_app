<?php

if (!function_exists('get_fulltime')) {

    function get_fulltime($date, $format = 'd, M Y @ h:i a')
    {
        $new_date = new \DateTime($date);
        return $new_date->format($format);
    }
}


if (!function_exists('get_date')) {

    function get_date($date)
    {
        return get_fulltime($date, 'd, M Y');
    }
}


if (!function_exists('get_time')) {

    function get_time($date, $format = 'h:i A')
    {
        $new_date = new \DateTime($date);
        return $new_date->format($format);
    }
}

if (!function_exists('get_price')) {

    function get_price($price)
    {
        return '$ ' . number_format($price, 2);
    }
}

if (!function_exists('currency_symbol')) {

    function currency_symbol()
    {
        return '$ ';
    }
}

if (!function_exists("newCount")) {

    function newCount($array)
    {
        if (is_array($array) || is_object($array)) {
            return count($array);
        } else {
            return 0;
        }
    }
}

if (!function_exists('dummy_image')) {

    function dummy_image($type = null)
    {
        switch ($type) {
            case 'user':
                return asset('admin_assets/images/users/user_img.png');
            case 'file':
                    return asset('admin_assets/images/file_icon.png');
            default:
                return asset('admin_assets/images/404.png');
        }
    }
}

if (!function_exists('check_file')) {

    function check_file($file = null, $type = null)
    {
        if ($file && $file != '' && file_exists($file)) {
            return asset($file);
        } else {
            return dummy_image($type);
        }
    }
}

if (!function_exists('hashids_encode')) {

    function hashids_encode($str)
    {
        return \Hashids::encode($str);
    }
}

if (!function_exists('hashids_decode')) {

    function hashids_decode($str)
    {
        try {
            return \Hashids::decode($str)[0];
        } catch (Exception $e) {
            return abort(404);
        }
    }
}

if (!function_exists('get_provinces')) {

    function get_provinces()
    {
        return [
            'Azad Kashmir',
            'Balochistan',
            'FATA',
            'Gilgit-Baltistan',
            'Khyber Pakhtunkhwa',
            'Punjab',
            'Sindh'
        ];
    }
}


if (!function_exists('download_file')) {
    function download_file($file){
        if(file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            flush(); // Flush system output buffer
            readfile($file);
            die();
        }
        abort(404);
    }
}


if(!function_exists('api_response')){
    function api_response($status = false, $data = null, $msg = null){
        $data = [
            'status' => $status,
            'data' => $data ?? [],
            'msg' => $msg
        ];
        return response()->json($data, 200);
    }
}