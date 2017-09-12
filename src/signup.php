<html>


<?php

session_start();

// If the session is already set then go back to the main page
if (isset($_SESSION["user_id"])) {
   header("Location: people.php");
   die();
}

function error($string) {
    echo "Error: " . $string . "</br>";
}

/* 

    First comment 8:27 11/9/2017 
    Ayman and Jon's app

*/


/* User is brought to this page first where they connect to the database (if they can) */

$conn = mysqli_connect("localhost","root","password","app");

/* Try to connect to the database */

if (!$conn) {
    error("Unable to connect to MySQL");
    error(mysqli_connect_errno());
    error(mysqli_connect_error());
    die;
} else {
    echo "We're in... </br>";

    

    /* Retrieved through $_POST and htmlspecialcharred + stripslashed */
    $device_id = "sfjsdf";
    $name = "jonksuth";
    $age = 1995;
    $sex = 0;
    $country = 7;
    $region = 12;
    $introduction = "Yeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee";
    
    // Server side hard-coded
    $last_seen = time();
    $hide_age = 0;
    $profile_access = 1;
    $premium = 0;


    // All the user supplied details will be encrypted and check-summed to prevent tampering
    // Any non-sensical input will result in a blank page (error page)

    define("DEVICE_MAX",8);
    define("NAME_MAX",20);
    define("AGE_MIN",16);
    define("AGE_MAX",90);
    define("INTRODUCTION_MAX",512);

    // 1. For device ID use a regex?
    $device_id = substr($device_id,0,DEVICE_MAX);
    $name = substr($name,0,NAME_MAX);
    $current_age = date("Y") - $age;
    if ($current_age < AGE_MIN or $current_age > AGE_MAX) {
        error("Incorrect age -> " . $current_age);
    }
    if ($sex < 0 or $sex > 1) {
        error("Incorrect sex -> " . $sex);
    }
    // Country and region validation go here
    $introduction = substr($introduction,0,INTRODUCTION_MAX);

    //Photo upload needed

    // Set the session ID
    $_SESSION["user_id"] = $device_id;


    /* 
        Sex: 0 Male
             1 Female
    */

    /* 
        Country: 
            0 UK
            1 US
            2 France
            3 India
            4 Canada
            5 China
            6 Korea
            7 Japan

            Add more here!
    */

    /* Region defined per country */
    
    /* Checks if the user's device is already in the db (through the device ID */
    $stmt = $conn->prepare("SELECT UserID FROM user WHERE UserID = ?");
    $stmt->bind_param('s',$device_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id);
    $stmt->fetch();


    /* Cookies include 

        Anti-CSRF token

    */
           
    if ($stmt->num_rows) {
        /* Hand existing user their cookie */
        echo "Hello " . $device_id . "</br>";
    } else {
        /* Insert a new user into the db */
        echo "Inserting new user " . $device_id . "</br>";
        $stmt2 = $conn->prepare("INSERT INTO user VALUES(?,?,?,?,?,?,?,?,?,?,?)");
        $stmt2->bind_param('ssiiiisiiii',$device_id,$name,$age,$sex,$country,$region,$introduction,$last_seen,$premium,$hide_age,$profile_access);
        $stmt2->execute();
        echo "Done</br>";
    }

   $stmt->free_result();
   $stmt->close();

   /* Direct to main "People" page */
   header("Location: people.php");
   die();
 
}


?>


</html>
