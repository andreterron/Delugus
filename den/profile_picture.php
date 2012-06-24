<?php
	$file_maxsize = 10 * 1024 * 1024; // 10 MB

	$app = explode('/', str_replace('.php', '', $_SERVER['PHP_SELF']));
	if (!$ary[0]) {
		array_shift($app);
	}
	
	// sobe para a raiz do site
	$l = count($app);
	for ($i = 1; $i < $l; $i++) {
		chdir(".."); 
	}
	
	/* codigo geral para todas as paginas */
	include_once("functions.php");
	$redir_url = "http://www.delugus.com/profile.php";
	
	$loguser = userinfo(-1);
	$user = $loguser;

	$fum = "";
	if ($loguser && isset($_POST['submit']))
	{
		if ((($_FILES["file"]["type"] == "image/gif")
			|| ($_FILES["file"]["type"] == "image/jpeg")
			|| ($_FILES["file"]["type"] == "image/pjpeg"))
			&& ($_FILES["file"]["size"] < $file_maxsize))
		{
			if ($_FILES["file"]["error"] > 0)
			{
				//$fum .= "Return Code: " . $_FILES["file"]["error"] . "<br />";
			} else {
				/*$fum .= "Upload: " . $_FILES["file"]["name"] . "<br />";
				$fum .= "Type: " . $_FILES["file"]["type"] . "<br />";
				$fum .= "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
				$fum .= "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";*/

				/*if (file_exists("photo/" . $_FILES["file"]["name"]))
				{
					$fum .= $_FILES["file"]["name"] . " already exists. ";
				} else {*/
					
					
					
					// find type
					if ($_FILES["file"]["type"] == "image/gif") {
						$f_type = 'gif';
					} else if (($_FILES["file"]["type"] == "image/jpeg") ||
								($_FILES["file"]["type"] == "image/pjpeg")) {
						$f_type = 'jpg';
					}
					
					$f_size = $_FILES["file"]["size"];
					$f_owner = $loguser['id'];
					$img_info = getimagesize($_FILES['file']['tmp_name']);
					$f_width = $img_info[0];
					$f_height = $img_info[1];
					// create query
					$query = "INSERT INTO  `tzdelugusdata`.`photo` (
						`id`, `name`, `type`, `url`, `owner`, `size`, `width`, `height`
					)
					VALUES (
						NULL ,
						'' ,
						'$f_type',
						'' ,
						'$f_owner',
						'$f_size',
						'$f_width',
						'$f_height'
						
					);";
					
					dbconnect();
					
					$result = mysql_query($query, $con);
					if ($result) {
						$f_id = mysql_insert_id($con);
						$f_name = number_code($f_id);
						$i = $f_id;
						while (file_exists("photo/$f_name.$f_type")) {
							$i++;
							$f_name = number_code($i);
						}
						move_uploaded_file($_FILES["file"]["tmp_name"],
						"photo/$f_name.$f_type");
						//$fum .= "Stored in: photo/$f_name.$f_type";
						$query = "UPDATE  `tzdelugusdata`.`users`
						SET `photo` = '$f_id' WHERE `users`.`id` = '$f_owner' LIMIT 1 ;";
						$result = mysql_query($query, $con);
						if (!$result)
						{
							$fum .= "ERRO NA QUERY DE LINKAR COM O USER<br/>";
						}
						$query = "UPDATE `tzdelugusdata`.`photo`
						SET `id` = '$i', `name` = '$f_name', `url` = 'photo/$f_name.$f_type' WHERE `photo`.`id` = '$f_id' LIMIT 1 ;";
						$result = mysql_query($query, $con);
						if (!$result)
						{
							$fum .= "ERRO NA QUERY DE ATUALIZAR A FOTO<br/>";
						}
						$user['photo_url'] = $loguser['photo_url'] = "photo/$f_name.$f_type";
						$user['photo_w'] = $loguser['photo_w'] = $f_width;
						$user['photo_h'] = $loguser['photo_h'] = $f_height;
					} else {
						$fum .= "ERRO NA QUERY DE CRIACAO DE IMAGEM<br/>";
					}
					dbclose();
				//}
			}
		}
		else
		{
		$fum .= "Tipo de imagem inválido.";
		}
	}
	
	/*
// filename: upload.processor.php 

// first let's set some variables 

// make a note of the current working directory, relative to root. 
$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 

// make a note of the directory that will recieve the uploaded file 
$uploadsDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'uploaded_files/'; 

// make a note of the location of the upload form in case we need it 
$uploadForm = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . basename($_SERVER['PHP_SELF']); 

// make a note of the location of the success page 
$uploadSuccess = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . basename($_SERVER['PHP_SELF']); 

// fieldname used within the file <input> of the HTML form 
$fieldname = 'file'; 

// Now let's deal with the upload 

// possible PHP upload errors 
$errors = array(1 => 'php.ini max file size exceeded', 
                2 => 'html form max file size exceeded', 
                3 => 'file upload was only partial', 
                4 => 'no file was attached'); 

// check the upload form was actually submitted else print the form 
isset($_POST['submit']) 
    or error('the upload form is neaded', $uploadForm); 

// check for PHP's built-in uploading errors 
($_FILES[$fieldname]['error'] == 0) 
    or error($errors[$_FILES[$fieldname]['error']], $uploadForm); 
     
// check that the file we are working on really was the subject of an HTTP upload 
@is_uploaded_file($_FILES[$fieldname]['tmp_name']) 
    or error('not an HTTP upload', $uploadForm); 
     
// validation... since this is an image upload script we should run a check   
// to make sure the uploaded file is in fact an image. Here is a simple check: 
// getimagesize() returns false if the file tested is not an image. 
@getimagesize($_FILES[$fieldname]['tmp_name']) 
    or error('only image uploads are allowed', $uploadForm); 
     
// make a unique filename for the uploaded file and check it is not already 
// taken... if it is already taken keep trying until we find a vacant one 
// sample filename: 1140732936-filename.jpg 
$now = time(); 
while(file_exists($uploadFilename = $uploadsDirectory.$now.'-'.$_FILES[$fieldname]['name'])) 
{ 
    $now++; 
} 

// now let's move the file to its final location and allocate the new filename to it 
@move_uploaded_file($_FILES[$fieldname]['tmp_name'], $uploadFilename) 
    or error('receiving directory insuffiecient permission', $uploadForm); 
     
// If you got this far, everything has worked and the file has been successfully saved. 
// We are now going to redirect the client to a success page. 
header('Location: ' . $uploadSuccess); 

// The following function is an error handler which is used 
// to output an HTML error page if the file upload fails 
function error($error, $location, $seconds = 5) 
{ 
    header("Refresh: $seconds; URL=\"$location\""); 
    echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"'."\n". 
    '"http://www.w3.org/TR/html4/strict.dtd">'."\n\n". 
    '<html lang="en">'."\n". 
    '    <head>'."\n". 
    '        <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">'."\n\n". 
    '        <link rel="stylesheet" type="text/css" href="stylesheet.css">'."\n\n". 
    '    <title>Upload error</title>'."\n\n". 
    '    </head>'."\n\n". 
    '    <body>'."\n\n". 
    '    <div id="Upload">'."\n\n". 
    '        <h1>Upload failure</h1>'."\n\n". 
    '        <p>An error has occured: '."\n\n". 
    '        <span class="red">' . $error . '...</span>'."\n\n". 
    '         The upload form is reloading</p>'."\n\n". 
    '     </div>'."\n\n". 
    '</html>'; 
    exit; 
} // end error handler

*/
	
	
?>
<!DOCTYPE html>

<html>
<head>

<title><?php if ($loguser) echo $loguser['fullname'] . " - Imagem de exibição - "; ?>Delugus</title>
<base href="http://www.delugus.com/"/>
<link rel="stylesheet" href="style.css" type = "text/css" />
<link rel="shortcut icon" href="http://www.delugus.com/favicon.ico" />
<script type="text/javascript" src="javascript.js"></script>

<?php
	/* checa se o usuario esta logado, caso nao esteja, redireciona para a home,
	imprime o resto das tags e termina o script
	$user = userinfo(2);
	isset($_POST['submit'])*/
	if (!$loguser)
	{
		die("<meta http-equiv='Refresh' content='0;url=$redir_url' /></head><body></body></html>");
	}/**/
?>
</head>

<body>

<?php
	include("topbar.php");
	
	//if (isset($_POST['submit']))
	
	if ($error != null)
	{
		echo "<div class='error_msg'>$error</div>";
		die("</body></html>");
	}
	
	
?>

<div class='contents-container'>
<div class='contents'>
	<div class='main-contents'>
		<div class="maincontainer">
			<div class='main-col'>
				<div class="main-header">
					<?php
						if ($fum)
						{
							echo "<div class='warn_msg'>$fum</div>";
						}
						echo "<div>" . get_user_photo(null, 128) . "</div>";
					?>
					<form action='den/profile_picture.php' enctype='multipart/form-data' method='post'>
						<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $file_maxsize; ?>"> 
						Escolha sua foto:<br/>
						<input id="file" type="file" name="file" /><br/>
						<input id="submit" type="submit" name="submit" value="Submeter foto"> 
					</form>
				</div>
			</div>
		</div>
	</div>
	<div id='pagefooter'>
		<?php include("pagefooter.php"); ?>
	</div>
</div>
</div>

</body>
</html>