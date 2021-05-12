<?php
    require "./db.php" ;
    $id =strval($_POST["id"]);
    $rs = $db->prepare("update tasktable set important='yes' where id = :id");
    $rs->execute(["id" => $id]);
?>