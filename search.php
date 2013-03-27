<?php
//search.php
session_start();
include "functions.php";
include "regex_validate.php";
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] != true)
{
  header("Location: login.php");
  exit();
}

$ids=array();
$contact = array();
$result = getContacts();
$friends = getMyFriends($_SESSION['current_user']['id']);

if (isset($_GET['search']) && !empty($_GET['search']))
{
  $searchstring = "{$_GET['search']}";
  $searchstring = explode(" ", $searchstring);
  foreach ($searchstring as $searchfragment) 
  {
    $search[] = searchUsers($searchfragment);
  }
  foreach ($search as $index => $idlist) {
    foreach ($idlist as $id) {
      if (!in_array($id, $ids)){
        $ids[] = $id;
      }
    }
  }


  /*
  $contacts = loadJson("contactlist.txt");
  foreach ($contacts as $contact => $fields) 
  {
    
    $exit = false;
    foreach ($fields as $fieldvalue)
    {
      if ($exit)
        break;

      foreach ($search as $searchvalue)
      {
        if ($exit)
          break;

        if (preg_match($searchvalue, $fieldvalue))
        {
          $search_list[] = $contacts[$contact];
          $exit = true; 
          break;
        }
      }
    }
  }*/
}
else
{
  // display "no contacts found" error below, by default because $search_list isn't set
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Search</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  </head>
  <body>
    <?php
    
    if (!empty($ids))
    {
      echo "<div class = 'row'>
              <div class = 'span6 offset1'>
                <h3>Searching for \"{$_GET['search']}\"</h3>
              
                <table class = 'table table-striped'>
                  <tr>
                
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th></th>
                    
                  </tr>";
      
      while($item = $result->fetch_assoc()){
        $contact[] = $item;      
      }
      //print_r($contact);
      foreach ($ids as $id) 
      {
        //echo "ID: $id";
        foreach ($contact as $key => $value) {
          
          if ($contact[$key]['id'] == $id){
            echo "<tr>
              
              <td>{$contact[$key]['firstName']}</td>
              <td>{$contact[$key]['lastName']}</td>";
              if (in_array($id, $friends)){
                echo "<td><button type = 'button' onclick = 'viewContact($id)' class = 'btn btn-primary'>View</td>";
              }else if ($id != $_SESSION['current_user']['id']){
                echo "<td><button type = 'button' onclick = 'addFriend($id)' class = 'btn'>Add Friend</td>";
              }else{
                echo "<td></td>";
              }
              
            echo "</tr>";
          }
        }
        
        //displayContact($contact['id']);
        //echo "<br>";
      }
    }
    else
    {
      echo "<div class = 'row'>
              <div class = 'offset1'>
                <h3>No contacts found that match the search term</h3>
              </div>
            </div>";
    }
    ?>
        </table>
      </div>
    </div>
    <div class = 'row'>
      <div class = 'offset1'>
        <a href = 'main_menu.php' class = 'btn btn-primary'>Return to Menu</a>
      </div>
    </div>
    <script type="text/javascript">
      // Some functions to activate the buttons above
      function viewContact(pageid)
      {
        var location = "view_contact.php?id=" + pageid;
        window.location = location;
      }
    </script>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>