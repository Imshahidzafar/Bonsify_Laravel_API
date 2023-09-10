<?php
namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

function make_thumb($src, $dest, $desired_width) {
    $src = $src;
    $dest = $dest;
    // echo "<pre>";print_r($src);echo "</pre>";exit;
    $info = getimagesize($src);
    
    if ($info['mime'] == 'image/jpeg'){
        $source_image = imagecreatefromjpeg($src);
    }elseif($info['mime'] == 'image/gif'){
        $source_image = imagecreatefromgif($src);
    }elseif($info['mime'] == 'image/png'){
        $source_image = imagecreatefrompng($src);
    }
    $width = $info[0];
    $height = $info[1];
    $desired_height = floor($height * ($desired_width / $width));
    $virtual_image = imagecreatetruecolor($desired_width, $desired_height);
    imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
    if ($info['mime'] == 'image/jpeg'){
        $okay = imagejpeg($virtual_image, $dest);
    }elseif($info['mime'] == 'image/gif'){
        $okay = imagegif($virtual_image, $dest);
    }elseif($info['mime'] == 'image/png'){
        $okay = imagepng($virtual_image, $dest);
    }
    if(!$okay){
        return false;
        // echo "<pre>";print_r($src);echo "</pre>";
    }
}