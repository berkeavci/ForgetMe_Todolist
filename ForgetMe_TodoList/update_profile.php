<?php

session_start();

require_once "./protect.php"; // Authentication

$id = $_SESSION["user"]["id"];

if (!empty($_POST)) {
    extract($_POST);
    require_once "./db.php";
    require_once "./Upload.php";
    $upload = new Upload("profile", "images");

    if($upload->file()){
        // If Updated, delete the previous one
        $rs = $db->prepare("update user set name=?, bday=?, profile=? where id = ? ");
        $rs->execute([$username, $bday, $upload->file(), $id]);
        // delete the old photo under the Images Folder
        $old_profile_img = $_SESSION["user"]["profile"] ;
        if ($old_profile_img){
            unlink("Images/$old_profile_img"); 
        }
        // updated values put in to session
        $_SESSION["user"]["profile"] = $upload->file(); 
    } else{
        $rs = $db->prepare("update user set name=?, bday=?, where id = ? ");
        $rs->execute([$username, $bday, $id]);
    }
    $_SESSION["user"]["name"] = $username;
    $_SESSION["user"]["bday"] = $bday;
    // we update username and bday 
}

$user = $_SESSION["user"]; 

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Title of the document</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <style>
        .container {
            margin-top: 100px;
        }

        .input-field {
            width: 60%;
            margin: 30px auto;
        }
    </style>
</head>

<body>


    <nav>
        <div class="nav-wrapper">
            <a href="#" class="brand-logo">Profile Edit</a>
            <ul id="nav-mobile" class="right">
          <li><a href="main.php">Main</a></li>
        </ul>
        </div>
    </nav>

    <div class="container">
        <div class="center">
        <?php
            $profile = $user["profile"] ?? "avatar.png";   // Save it on session
            echo "<img src='Images/{$profile}' width='80' class='circle'>";
        ?>
        </div>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="input-field">
                <input name="username" id="username" type="text" class="validate"
                    value="<?= $user["name"]?>"
                >
                <label for="username">Name Lastname</label>
            </div>

            <div class="input-field">
                <input name="email" id="email" type="text" class="validate"
                    value="<?= $user["email"]?>" disabled
                >
                <label for="email">Email</label>
            </div>

            <div class="input-field">
                <input name="bday" id="bday" type="text" class="validate"
                    value="<?= $user["bday"]?>" 
                >
                <label for="bday">Birthday</label>
            </div>

            <div class="file-field input-field">
                <div class="btn">
                    <span>File</span>
                    <input type="file" name="profile">
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text">
                </div>
            </div>

            <div class="center">
                <button class="btn waves-effect waves-light" type="submit" name="action">Update
                    <i class="material-icons right">send</i>
                </button>
            </div>
        </form>
    </div>

    <script>
        $(function() {

        })
    </script>
</body>

</html>