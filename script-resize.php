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
$url_resized = 'images/users/resized/';

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
				/*
				if($height>$width){
					$srcw=$width;
					$srch=$width;
					0=0;
					0=($height-$width)/2;
					$lw=800*$width/$height;
					$lh=800;
				}
				elseif($height<$width){
					$srcw=$height;
					$srch=$height;
					0=($width-$height)/2;
					0=0;
					$lw=800;
					$lh=800*$height/$width;
				}
				elseif($height=$width){
					$srcw=$width;
					$srch=$height;
					0=0;
					0=0;
					$lw=800;
					$lh=800;
				}
				else{
					die('There was an error determining image dimensions.');
				}
				*/
				
				$srcw = $width;
				$srch = $height;
				$src_ratio = $width/$height;
				$l = 800;
				$lw = $l*$src_ratio;
				$lh = $l;
				
				$mini = 120;
				$miniw = $mini*$src_ratio;
				$minih = $mini;
				
				$miniurl = $url_resized . time() . '-' $minix . 'x' . $miniy . '-' . $filename . '.' . $extension;
				$lurl = $url_resized . time() . '-' $lx . 'x' . $ly . '-' . $filename . '.' . $extension;
				
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
					$minithumbnail = imagecreatetruecolor($minix, $miniy);
					if(!$minithumbnail){
					    die('Error when creating the mini destination image.');
					}
					$miniresult = imagecopyresampled($minithumbnail, $src_img, 0, 0, 0, 0, $minix, $miniy, $srcw, $srch);
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
				elseif(($_FILES['file']['type'] == 'image/png')){]
					//mini
					$src_img = imagecreatefrompng($url_upload);
					if(!$src_img){
					    die('Error when reading the mini source image.');
					}
					$minithumbnail = imagecreatetruecolor($minix, $miniy);
					if(!$minithumbnail){
					    die('Error when creating the mini destination image.');
					}
					$miniresult = imagecopyresampled($minithumbnail, $src_img, 0, 0, 0, 0, $minix, $miniy, $srcw, $srch);
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