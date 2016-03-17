<?php

$time = time();
$filename = $_FILES['file']['name'];
$x = ;
$y = ;

$allowed_extensions = array('jpg', 'jpeg', 'pjpeg', 'png');
for($i = 0; $i < count($allowed_extensions); $i++){
	$allowed_extensions[$i] = strtolower($allowed_extensions[$i]);
}
$file_extension = strtolower(end(explode('.', $filename)));
$new_file_name = time() . '-' $x . 'x' . $y . '-' . $filename . '.' . $extension;

$url_upload = 'images/users/upload/' . $new_file_name;
$url_resized = 'images/users/resized/' . $new_file_name;

if(($_FILES['file']['type'] == 'image/jpeg' || $_FILES['file']['type'] == 'image/png' || $_FILES['file']['type'] == 'image/pjpeg') && in_array($file_extension, $allowed_extensions)){
		if($_FILES['file']['error'] != UPLOAD_ERR_OK){
			die('Error: file upload error (' . $_FILES['file']['error'] . ')<br />');
		}
		else{
			//echo 'Upload: ' . $_FILES['file']['name'] . '<br>';
			//echo 'Type: ' . $_FILES['file']['type'] . '<br>';
			//echo 'Size: ' . ($_FILES['file']['size'] / 1024) . ' kB<br>';
			//echo 'Temp file: ' . $_FILES['file']['tmp_name'] . '<br>';
			if(file_exists($url_upload)){
				die('Error: a file by the name of <i>' . $_FILES['file']['name'] . '</i> already exists.<br />');
			}
			else {
				move_uploaded_file($_FILES['file']['tmp_name'], $url_upload);
				list($width, $height, $type, $attr) = getimagesize($url_upload);
				$exif = exif_read_data($url_upload);
				if(!empty($exif['Orientation'])){
					switch($exif['Orientation']){
					case 8:
						//$src_img = imagerotate($src_img,90,0);
						$tempwidth=$width;
						$width=$height;
						$height=$tempwidth;
						break;
					case 6:
						//$src_img = imagerotate($src_img,-90,0);
						$tempwidth=$width;
						$width=$height;
						$height=$tempwidth;
						break;
					} 
				}
				if($height>$width){
					$srcw=$width;
					$srch=$width;
					$srcx=0;
					$srcy=($height-$width)/2;
					$lw=800*$width/$height;
					$lh=800;
				}
				elseif($height<$width){
					$srcw=$height;
					$srch=$height;
					$srcx=($width-$height)/2;
					$srcy=0;
					$lw=800;
					$lh=800*$height/$width;
				}
				elseif($height=$width){
					$srcw=$width;
					$srch=$height;
					$srcx=0;
					$srcy=0;
					$lw=800;
					$lh=800;
				}
				else{
					die('There was an error determining image dimensions.');
				}
				
				if($_FILES['file']['type'] == 'image/jpeg' || $_FILES['file']['type'] == 'image/pjpeg'){
					//profile
					/*
					$exif = exif_read_data($src_img);
					if(!empty($exif['Orientation'])){
						switch($exif['Orientation']){
						case 8:
							$im1 = imagerotate($im1,90,0);
							break;
						case 3:
							$im1 = imagerotate($im1,180,0);
							break;
						case 6:
							$im1 = imagerotate($im1,-90,0);
							break;
						} 
					}
					*/
					$src_img = imagecreatefromjpeg($url_upload);
					if(!$src_img){
					    die('Error when reading the source image.');
					}
					$exif = exif_read_data($url_upload);
					if(!empty($exif['Orientation'])){
						switch($exif['Orientation']){
						case 8:
							$src_img = imagerotate($src_img,90,0);
							break;
						case 3:
							$src_img = imagerotate($src_img,180,0);
							break;
						case 6:
							$src_img = imagerotate($src_img,-90,0);
							break;
						} 
					}
					$thumbnail = imagecreatetruecolor(40, 40);
					if(!$thumbnail){
					    die('Error when creating the destination image.');
					}
					$result = imagecopyresampled($thumbnail, $src_img, 0, 0, $srcx, $srcy, 40, 40, $srcw, $srch);
					if(!$result){
					    die('Error when generating the thumbnail.');
					}
					$result = imagejpeg($thumbnail, $purl);
					if(!$result){
					    die('Error when saving the thumbnail.');
					}
					$result = imagedestroy($thumbnail);
					if(!$result){
					    die('Error when destroying the image.');
					}
					unset($result);
					//mini
					$src_img = imagecreatefromjpeg($url_upload);
					if(!$src_img){
					    die('Error when reading the mini source image.');
					}
					$exif = exif_read_data($url_upload);
					if(!empty($exif['Orientation'])){
						switch($exif['Orientation']){
						case 8:
							$src_img = imagerotate($src_img,90,0);
							break;
						case 3:
							$src_img = imagerotate($src_img,180,0);
							break;
						case 6:
							$src_img = imagerotate($src_img,-90,0);
							break;
						} 
					}
					$minithumbnail = imagecreatetruecolor(40, 40);
					if(!$minithumbnail){
					    die('Error when creating the mini destination image.');
					}
					$miniresult = imagecopyresampled($minithumbnail, $src_img, 0, 0, $srcx, $srcy, 40, 40, $srcw, $srch);
					if(!$miniresult){
					    die('Error when generating the mini thumbnail.');
					}
					$miniresult = imagejpeg($minithumbnail, $miniurl);
					if(!$miniresult){
					    die('Error when saving the mini thumbnail.');
					}
					$miniresult = imagedestroy($minithumbnail);
					if(!$miniresult){
					    die('Error when destroying the mini image.');
					}
					unset($miniresult);
					//small
					$src_img = imagecreatefromjpeg($url_upload);
					if(!$src_img){
					    die('Error when reading the source image.');
					}
					$exif = exif_read_data($url_upload);
					if(!empty($exif['Orientation'])){
						switch($exif['Orientation']){
						case 8:
							$src_img = imagerotate($src_img,90,0);
							break;
						case 3:
							$src_img = imagerotate($src_img,180,0);
							break;
						case 6:
							$src_img = imagerotate($src_img,-90,0);
							break;
						} 
					}
					$sthumbnail = imagecreatetruecolor(120, 120);
					if(!$sthumbnail){
					    die('Error when creating the small destination image.');
					}
					$sresult = imagecopyresampled($sthumbnail, $src_img, 0, 0, $srcx, $srcy, 120, 120, $srcw, $srch);
					if(!$sresult){
					    die('Error when generating the small image.');
					}
					$sresult = imagejpeg($sthumbnail, $surl);
					if(!$sresult){
					    die('Error when saving the small image.');
					}
					$sresult = imagedestroy($sthumbnail);
					if(!$sresult){
					    die('Error when destroying the small image.');
					}
					unset($sresult);
					//medium
					$src_img = imagecreatefromjpeg($url_upload);
					if(!$src_img){
					    die('Error when reading the medium source image.');
					}
					$exif = exif_read_data($url_upload);
					if(!empty($exif['Orientation'])){
						switch($exif['Orientation']){
						case 8:
							$src_img = imagerotate($src_img,90,0);
							break;
						case 3:
							$src_img = imagerotate($src_img,180,0);
							break;
						case 6:
							$src_img = imagerotate($src_img,-90,0);
							break;
						} 
					}
					$mthumbnail = imagecreatetruecolor(300, 300);
					if(!$mthumbnail){
					    die('Error when creating the medium destination image.');
					}
					$mresult = imagecopyresampled($mthumbnail, $src_img, 0, 0, $srcx, $srcy, 300, 300, $srcw, $srch);
					if(!$mresult){
					    die('Error when generating the medium image.');
					}
					$mresult = imagejpeg($mthumbnail, $murl);
					if(!$mresult){
					    die('Error when saving the medium image.');
					}
					$mresult = imagedestroy($mthumbnail);
					if(!$mresult){
					    die('Error when destroying the medium image.');
					}
					unset($mresult);
					//large
					$src_img = imagecreatefromjpeg($url_upload);
					if(!$src_img){
					    die('Error when reading the large source image.');
					}
					$exif = exif_read_data($url_upload);
					if(!empty($exif['Orientation'])){
						switch($exif['Orientation']){
						case 8:
							$src_img = imagerotate($src_img,90,0);
							break;
						case 3:
							$src_img = imagerotate($src_img,180,0);
							break;
						case 6:
							$src_img = imagerotate($src_img,-90,0);
							break;
						} 
					}
					$lthumbnail = imagecreatetruecolor($lw, $lh);
					if(!$lthumbnail){
					    die('Error when creating the large destination image.');
					}
					$lresult = imagecopyresampled($lthumbnail, $src_img, 0, 0, 0, 0, $lw, $lh, $width, $height);
					if(!$lresult){
					    die('Error when generating the large image.');
					}
					$lresult = imagejpeg($lthumbnail, $lurl);
					if(!$lresult){
					    die('Error when saving the large image.');
					}
					$lresult = imagedestroy($lthumbnail);
					if(!$lresult){
					    die('Error when destroying the large image.');
					}
					unset($lresult);
				}
				elseif(($_FILES['file']['type'] == 'image/png')){
					//profile
					$src_img = imagecreatefrompng($url_upload);
					if(!$src_img){
					    die('Error when reading the source image.');
					}
					$thumbnail = imagecreatetruecolor(40, 40);
					if(!$thumbnail){
					    die('Error when creating the destination image.');
					}
					$result = imagecopyresampled($thumbnail, $src_img, 0, 0, $srcx, $srcy, 40, 40, $srcw, $srch);
					if(!$result){
					    die('Error when generating the thumbnail.');
					}
					$result = imagepng($thumbnail, $purl);
					if(!$result){
					    die('Error when saving the thumbnail.');
					}
					$result = imagedestroy($thumbnail);
					if(!$result){
					    die('Error when destroying the image.');
					}
					unset($result);
					//mini
					$src_img = imagecreatefrompng($url_upload);
					if(!$src_img){
					    die('Error when reading the mini source image.');
					}
					$minithumbnail = imagecreatetruecolor(40, 40);
					if(!$minithumbnail){
					    die('Error when creating the mini destination image.');
					}
					$miniresult = imagecopyresampled($minithumbnail, $src_img, 0, 0, $srcx, $srcy, 40, 40, $srcw, $srch);
					if(!$miniresult){
					    die('Error when generating the mini thumbnail.');
					}
					$miniresult = imagepng($minithumbnail, $miniurl);
					if(!$miniresult){
					    die('Error when saving the mini thumbnail.');
					}
					$miniresult = imagedestroy($minithumbnail);
					if(!$miniresult){
					    die('Error when destroying the mini image.');
					}
					unset($miniresult);
					//small
					$src_img = imagecreatefrompng($url_upload);
					if(!$src_img){
					    die('Error when reading the source image.');
					}
					$sthumbnail = imagecreatetruecolor(120, 120);
					if(!$sthumbnail){
					    die('Error when creating the small destination image.');
					}
					$sresult = imagecopyresampled($sthumbnail, $src_img, 0, 0, $srcx, $srcy, 120, 120, $srcw, $srch);
					if(!$sresult){
					    die('Error when generating the small image.');
					}
					$sresult = imagepng($sthumbnail, $surl);
					if(!$sresult){
					    die('Error when saving the small image.');
					}
					$sresult = imagedestroy($sthumbnail);
					if(!$sresult){
					    die('Error when destroying the small image.');
					}
					unset($sresult);
					//medium
					$src_img = imagecreatefrompng($url_upload);
					if(!$src_img){
					    die('Error when reading the medium source image.');
					}
					$mthumbnail = imagecreatetruecolor(300, 300);
					if(!$mthumbnail){
					    die('Error when creating the medium destination image.');
					}
					$mresult = imagecopyresampled($mthumbnail, $src_img, 0, 0, $srcx, $srcy, 300, 300, $srcw, $srch);
					if(!$mresult){
					    die('Error when generating the medium image.');
					}
					$mresult = imagepng($mthumbnail, $murl);
					if(!$mresult){
					    die('Error when saving the medium image.');
					}
					$mresult = imagedestroy($mthumbnail);
					if(!$mresult){
					    die('Error when destroying the medium image.');
					}
					unset($mresult);
					//large
					$src_img = imagecreatefrompng($url_upload);
					if(!$src_img){
					    die('Error when reading the large source image.');
					}
					$lthumbnail = imagecreatetruecolor($lw, $lh);
					if(!$lthumbnail){
					    die('Error when creating the large destination image.');
					}
					$lresult = imagecopyresampled($lthumbnail, $src_img, 0, 0, 0, 0, $lw, $lh, $width, $height);
					if(!$lresult){
					    die('Error when generating the large image.');
					}
					$lresult = imagepng($lthumbnail, $lurl);
					if(!$lresult){
					    die('Error when saving the large image.');
					}
					$lresult = imagedestroy($lthumbnail);
					if(!$lresult){
					    die('Error when destroying the large image.');
					}
					unset($lresult);
				}
			}
		}
	}
	else {
		die('Invalid file');
	}

?>