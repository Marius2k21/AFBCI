<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code_admin = $_POST['code_admin'];

    

    if ($code_admin=="AFBCI2024ADMIN") {
        


        header('location:Admin/login.php');
        exit;
        
    }
    else if ($code_admin=="IUA2024SUP") {
        


        header('location:../SuperAdmin/login_supadmin.php');
        exit;
        
    }
    else if ($code_admin=="IUA2024SVC") {
        


        header('location:../ServiceClient/login_svc.php');
        exit;
        
    }
     else {
        $error_message = "Erreur: mot de passe incorrect.";
    }
}?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code Administrateur</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="img/logo1.png" type="image/x-icon">

</head>
<body>
<div class="container">
    <div class="row justify-content-center align-items-center vh-100">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="bg-primary p-5 m-3 form-container">
                <h2 class="card-title text-center mb-4">Code administrateur</h2>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
                <?php endif; ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="code_admin" class="form-label ">Code</label>
                        <input name="code_admin" required autofocus type="code" class="form-control py-3" id="code_admin" placeholder="Entrez votre email de récupération">
                    </div>
                    <button type="submit" class="btn btn-secondary w-100 py-3">Valider</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>