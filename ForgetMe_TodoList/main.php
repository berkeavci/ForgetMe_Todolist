<?php

// TODO: sanitizse, sql injection prevention, xss attack 

session_start(); // available to reading from file

require_once "./db.php";
require_once "./protect.php";

// TODO: protection add later

$user = $_SESSION["user"];



try {
  $list_ = "select * from listTable where owner = ?";
  $list_l = $db->prepare($list_);
  $list_l->execute([$user["id"]]);
  $list_table = $list_l->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
  echo "<p>", $ex->getMessage(), "</p>";
}



// if(isset($_GET)){
//   extract($_GET);
//   $url_holder = array("list" => $list);
//   var_dump($list);
//   $querystring = http_build_query($url_holder);
// }

// id of list
if (isset($_POST["list"])) {
  extract($_POST);
  //$list_array = explode$list_temp
  try {
    $owner = $user["id"]; // userin listesi oluyor 
    //var_dump($list_table[0]["id"]);
    $insert_list = "insert into listTable (list, owner) values (?, ?)";
    $insertion_l = $db->prepare($insert_list);
    $insertion_l->execute([$list, $owner]);
    //var_dump($insertion_l);
  } catch (PDOException $ex) {
    $errMsg = "Insertion Failled!";
  }
}

// id of task
if (isset($_POST["task"])) {
  extract($_POST);
  extract($_GET);
  try {
    $important = "no";
    $listowner = $owner;
    $listoftask = $list;
    $insert_task = "insert into tasktable (task, important, listowner, list) values (?, ?, ?, ?)";
    $insertion = $db->prepare($insert_task);
    $insertion->execute([$task, $important, $listowner, $listoftask]);
  } catch (PDOException $ex) {
    $errMsg = "Insertion Failled!";
  }
}

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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  <style>
    .container center {
      float: inline-end;

    }

    #leftDiv {
      float: left;
      width: 400px;
      height: 1200px;
      background-color: teal;
    }

    .listnames {
      text-align: center;
    }

    span {
      size: 35px;
      color: black;
    }

    nav {
      display: flex;
      justify-content: space-evenly;
      width: 400px;


    }

    #list {
      text-decoration: none;
      
    }
    #icon{
      color: #FFC900;
    }

    #rightDiv {
      float: right;
      width: 1400px;
      height: 1200px;
      background-color: wheat;
    }
    #Textures{
      color: white;
      font-weight:initial;
      font-family: Arial, Helvetica, sans-serif;
    }
  </style>
</head>

<body>

  <!-- Div on the Left to show Lists, Importants and Additions to list options -->
  <div id="leftDiv" class="container green">
    <table>
      <tr>
        <td>
          <?php
          $profile = $user["profile"] ?? "default_image.jpeg";   // Save it on session
          echo "<img src='Images/{$profile}' width='40' class='circle'>";
          echo " <span id=Textures>  {$user["name"]} <span>";
          ?>
        </td>
        <td>
          <ul>
            <li>
              <a href="logout.php"><i  id="icon" class="material-icons">exit_to_app</i></a>

            </li>
          </ul>
        </td>
      </tr>

      <tr>
        <td><i  class="material-icons">star_border</i> <span id="Textures">  Important </span></td>
      </tr>
      <?php
      if (isset($_POST["list"]) || isset($_GET["list"]) || !empty($list_table)) {
        extract($_POST);
        extract($_GET);
        //var_dump($list);
        //echo "<tr> {$list} </tr>";
        //var_dump($list_table);
        foreach ($list_table as $list_value) {
          $url_holder = array("owner" => $list_value["owner"], "list" => $list_value["list"]);
          $querystring = http_build_query($url_holder);
          echo "
            <tr> 
              <td>
              <a href='?$querystring'> 
              {$list_value["list"]} </a>
              </td>
            </tr>
          ";
        }
      }
      ?>


      <?php
      // When List clicked, we send list to url so that the right change according to the list 
      // $url_holder = array("list" => $list);
      // var_dump($url_holder);
      // $querystring = http_build_query($url_holder);
      ?>


      <tr>
        <td>
          <!-- Prompt Message / List Addition -->
          <button data-target="modal1" class="btn modal-trigger">New List</button>
          <div id="modal1" class="modal">
            <div class="modal-content">
              <form action="main.php " method="POST">
                <div class="input-field">
                  <input name="list" id="list" type="text" class="validate">
                  <label for="list"> </label>
                </div>
              </form>
            </div>
          </div>
        </td>
      </tr>
    </table>
  </div>

  <div id="rightDiv" class="container">

    <table>
      <?php
      if (isset($querystring)) {
        echo "<th class='listnames'>$list  </th>";
        $task_list = "select * from taskTable where list = ?";
        $task_l = $db->prepare($task_list);
        $task_l->execute([$list]);
        $tasklist = $task_l->fetchAll(PDO::FETCH_ASSOC);
        foreach ($tasklist as $tk) {
          $a = strval($tk["task"]);
          $ind = $tk["id"];
          var_dump($a);
          echo "" ?> <tr id="<?php echo $ind; ?>">
            <td>
              <nav>
                <div> <label>
                    <input type="checkbox" id="ckb" class="filled-in" value="<?php echo $ind; ?>" />
                    <span><?php echo $a; ?></span>
                  </label></div>
                <div class='nav-wrapper' id='tsk'></div>
              </nav>
            </td>
            <td><button onclick="deleteAjax('<?php echo $ind; ?>')" class="btn btn-danger">delete</button></td><?php "
       <td><i class='material-icons'>star</i></td> 
       </tr>";
                                                                                                                $ind++;
                                                                                                              }
                                                                                                            }
                                                                                                                ?>
        <form action="main.php?<?= $querystring ?>" method="POST">
          <tr>
            <div class="container center">
              <div class="input-field">
                <i class="material-icons prefix">control_point</i>
                <input name="task" id="task" type="text" class="validate">
                <label for="task" > Add a Task</label>
              </div>
            </div>
          </tr>

    </table>
    <script type="text/javascript">
      function deleteAjax(id) {

        $.ajax({
          type: 'post',
          url: 'delete.php',
          data: {
            task_n: id
          },
          success: function(data) {
            $('#' + id).hide(1000);
          }
        });

      }

      $('input:checkbox').on('change', function(e) {
        e.preventDefault();
        var input = $(this).next('span');
        if (this.checked) {
          $.ajax({
            type: "post",
            url: "checkbox.php",
            data: {
              id: $(this).val()
            },
            success: function(data) {
              $(input).css('textDecoration', 'line-through');
            }
          });
        } else {
          $(input).css('textDecoration', 'none');
        }
      })
    </script>
  </div>
  </form>

  <script>
    $(function() {

    })
    $(document).ready(function() {
      $('.modal').modal();
    });
  </script>


</body>

</html>