<?PHP
	$host = "localhost";
	$user = "root";
	$pwd = "";
	$conn = mysqli_connect($host, $user, $pwd);
	
	if(!$conn){
		die("connection failed!".mysqli_connect_error()); 
	}
	mysqli_select_db($conn, "product_testing") or die ("Error in selecting db");
	#echo "connection successfully!";
	
	mysqli_set_charset($conn,"utf8");
?>
<?php
function msg_style($msg, $type) {
    switch ($type) {
        case 'success':
            return '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> ' . $msg . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            break;

        case 'info':
            return '<div class="alert alert-info alert-dismissible fade show" role="alert">
                        <strong>Info!</strong> ' . $msg . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            break;

        case 'warning':
            return '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Warning!</strong> ' . $msg . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            break;

        case 'danger':
            return '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> ' . $msg . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            break;
    }
}
?>

	
?> 