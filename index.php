<?php

    $servername = "localhost";
    $username = "root";
    $password = "mysql";
    $dbname = "projectmanager";
    // connect 
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // $conn = mysqli_connect('db4free.net', 'bictest', 'mysql1234', 'bictest', '3306');

      // check connection
    if(!$conn) {
        echo 'Connection error: ' . mysqli_connect_error();
    }

    if(isset($_POST['delete'])){
        $id_to_delete = $_POST['id'];
        $from_table = $_POST['table'];

        $sql = "DELETE FROM $from_table WHERE id = $id_to_delete";

        $conn -> query($sql);
    }

    if(isset($_POST['insert'])){
        $name_to_insert = $_POST['new_item'];
        $to_table = $_POST['table'];

        $sql = "INSERT INTO $to_table (name)
                VALUES ('$name_to_insert')";

        $conn -> query($sql);
    }

    if(isset($_POST['update_project'])) {
        $name_to_update = $_POST['update_name'];
        $to_table = $_POST['table'];
        $id = $_POST['id'];

        $sql = "UPDATE $to_table SET name='$name_to_update' WHERE id=$id";
        
        $conn -> query($sql);
        // if (mysqli_query($conn, $sql)) {
        //     echo "Record updated successfully";
        // } else {
        // echo "Error updating record: " . mysqli_error($conn);
        // }
    }


    if(isset($_POST['update_staff'])) {
        $name_to_update = $_POST['update_name'];
        $to_table = $_POST['table'];
        $id = $_POST['id'];
        $update_project = $_POST['update_project'];

        $sql = "UPDATE $to_table SET name='$name_to_update' WHERE id=$id";
         
        $conn -> query($sql);

        if ($update_project > 0); {
            $sql = 'SELECT projectid, staffid FROM projectstaff';
            $result = mysqli_query($conn, $sql);
            $projectstaff = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_free_result($result);

            if (empty(search($projectstaff, 'staffid', $id))) {
                $sql = "INSERT INTO projectstaff (projectid, staffid)
                    VALUES ('$update_project', '$id')";
            } else {
                $sql = "UPDATE projectstaff SET projectid='$update_project' WHERE staffid=$id";
            }

            $conn -> query($sql);
        }
    }
        
    

    // write query for all projects
    $sql = 'SELECT id, name FROM projects ORDER BY id';

    // make query & get result
    $result = mysqli_query($conn, $sql);

    // fetch the resulting rows
    $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // free $result
    mysqli_free_result($result);

    //query staff list
    $sql = 'SELECT id, name FROM staff ORDER BY id';

    $result = mysqli_query($conn, $sql);

    $staff = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_free_result($result);

    //query project staff
    $sql = 'SELECT projectid, staffid FROM projectstaff';

    $result = mysqli_query($conn, $sql);

    $projectstaff = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_free_result($result);

    //close connection
    // mysqli_close($conn);
    
    $url = $_SERVER['QUERY_STRING'];

    if ($url == 'projects' | $url == '') {
        $sorp = 'Staff';
        $list = $projects;
        $list2 = $staff;
        $fkey = 'projectid';
        $pkey = 'staffid';
        $table = 'projects';
    } elseif ($url == 'staff') {
        $sorp = 'Projects';
        $list = $staff;
        $list2 = $projects;
        $fkey = 'staffid';
        $pkey = 'projectid';
        $table = 'staff';
    }

    function search($array, $key, $value) {
        $results = array();

        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }

            foreach ($array as $subarray) {
                $results = array_merge($results, search($subarray, $key, $value));
            }
        }

        return $results;
    }

    function getNames($array, $namearray, $key) {
        $result = "";
        foreach($array as $item) {
            $names = search($namearray, "id", $item[$key]); 
            foreach($names as $name) {
                $result .= $name["name"] . " ";
            }
        }
        return $result;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="favicon.png"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Project Management</title>
</head>
    <body>
        <div class="container">
            <nav>
                <div class="nav-wrapper">
                    <ul class="left hide-on-med-and-down">
                        <li><a href="?projects">Projects</a></li>
                        <li><a href="?staff">Staff</a></li>
                    </ul>
                </div>
            </nav>

            <table class="striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th><?php print($sorp); ?></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>    

                <?php foreach($list as $item): ?>
                        <tr>
                            <td> <?php print(htmlspecialchars($item["id"])); ?> </td>
                            <td> <?php print(htmlspecialchars($item["name"])); ?> </td>
                            <td> <?php print(getNames(search($projectstaff, $fkey, $item["id"]), $list2 ,$pkey)); ?> </td>
                            <td>
                                <form method='post' action='' style="display:inline-block">
                                    <input type='hidden' name='id' value='<?php print(htmlspecialchars($item["id"])); ?>'/>
                                    <input type='hidden' name='table' value='<?php print($table); ?>'/>
                                    <button id='delete-item' class='waves-effect waves-light btn red lighten-1' name='delete' type='submit'>delete</button> 
                                </form>
                                    <!-- <button id='update-item' class='waves-effect waves-light btn modal-trigger' name='update' type='submit' data-target='modal1'>update</button>  -->
                                    <button class='btn open-modal'
                                            style='display: inline-block'
                                            data-id='<?php print(htmlspecialchars($item["id"])); ?>'
                                            data-table='<?php print($table); ?>'
                                            data-name='<?php print(htmlspecialchars($item["name"])); ?>'
                                            >update</button>
                            </td>
                        </tr>
                <?php endforeach; ?>
                </tbody>

            </table>
            <form method="post" action="">
                <input type='hidden' name='table' value='<?php print($table); ?>'/>
                <div class="input-field inline">
                    <input id="new_item" type="text" name="new_item">
                    <label for="new_item">name</label>
                </div>
                <button class="btn waves-effect waves-light green" type="submit" name="insert">
                    <span>add</span>
                    <i class="material-icons left">add</i>
                </button>
            </form>

            <!-- Modal Structure -->
            <div id="modal1" class="modal" style="overflow-y: visible"> 
            </div>

        </div>
    </body>
    <script type="text/javascript"> var projects = <?php echo json_encode($projects); ?>; </script>
    <script src="actions.js"> </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</html>