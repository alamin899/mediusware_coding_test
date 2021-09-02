<?php

function getPagination(): int
{
    return config('constant.PAGINATE');
}

function combination($arrays)
{
    $result = array(array());
    foreach ($arrays as $property => $property_values) {
        $tmp = array();
        foreach ($result as $result_item) {
            foreach ($property_values as $property_value) {
                $tmp[] = array_merge($result_item, array($property => $property_value));
            }
        }
        $result = $tmp;
    }
    return $result;
}

function customFileUpload($imagBase64)
{
    $exploded = explode(',', $imagBase64);
    $decodedValue = base64_decode($exploded[1]);
    $strpos = strpos($imagBase64, ';');
    $sub = substr($imagBase64, 0, $strpos);
    $extention = explode('/', $sub)[1];
    $fileName = date('YmdHis') . '.' . $extention;
    $path = public_path() . '/images/' . $fileName;
    $uploaded = file_put_contents($path, $decodedValue);
    if ($uploaded) {
        return [
            'file_name' => $fileName,
            'full_path' => $path
        ];
    } else return '';
}