<html>
  <head>
    <!--Ally-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <style>
      
      /*font and stuff*/
      body 
      {
        font-family: "Times New Roman", Times, serif;
        margin: 0;
        padding: 0;
      }

      /*hover!!*/
      .topnav a:hover 
      {
        background-color: #D3D3D3;
        color: black;
      }

        /*nav bar*/
        .topnav 
        {
          background-color: black;
          overflow: hidden;
          text-align: center;
          padding: 10px;
        }

        /*style for everything except welcome*/
        .topnav a 
        {
            text-decoration: none;
            color: white;
            padding: 15px 35px;
            display: inline-block;
            font-size: 17px;
        }

        /*this is all for the tables generated*/
        table 
        {
            color: black; 
            background-color: white; 
            border-collapse: collapse;
            width: 70%;
            margin-top: 50px;
            margin-left: 75px;
        }

        table, th, td 
        {
            border: 1px solid black;
        }

        th, td 
        {
            padding: 10px;
            text-align: center;
        }

        th 
        {
            background-color: #D3D3D3; 
        }

    </style>
    </head>

  <body>

  <!-- Nav go back to main page or go to checkout-->
  <div class="topnav">
        <a href='main_store.php'>Homepage</a>
        <a href='logout.php'>Logout</a>
  </div>

  <?php
    // start session
    session_start();


    // display name and logout option to end session
    if(!isset($_SESSION['user']))
    {
      echo "<center><h2>Please login to work on and view any outstanding orders.</h2></center>";
      echo "<center><h4><a href='login.php'>Login</a></h4></center>";
    }
    else if($_SESSION['user'] != 'employee')
    {
      echo "<center><h3>You're not allowed back here.</h3></center>";
    }
    else
    {
      include("secrets.php");

      try
      {
        $dsn = "mysql:host=courses;dbname=z1973307"; //make sure to switch the zID to your own, so that you dont get an error msg
        $pdo = new PDO($dsn, $username, $password);


        // sql table to grab orders placing. Joins placingorder and order together to get first name and last name 
        $order_placed = $pdo->query("SELECT User.fname, User.lname, PlacingOrder.order_num, PlacingOrder.total_cart_price, Orders.ship_addr, Orders.billing_info, Orders.date_placed, Orders.order_status FROM PlacingOrder JOIN User ON PlacingOrder.cust_id = User.ID LEFT JOIN Orders ON PlacingOrder.order_num = Orders.order_num WHERE PlacingOrder.order_num != 0");
        $order_table = $order_placed->fetchAll(PDO::FETCH_ASSOC);

        echo "<h3><center><p>Orders Placed</center></p></h3>";
        echo "<center><table border=1>";
        echo "<tr><th>First Name</th><th>Last Name</th><th>Order Number</th><th>Total Price</th><th>Address</th><th>Billing Information</th><th>Date Placed</th><th>Order Status</th></tr>";

        foreach($order_table as $row)
        {
            // if its not equal to default in database, print orders as row 
            if ($row['order_num'] !== '0' && strcasecmp($row['order_status'], 'Processing') == 0)
            {
                echo "<tr>";
                foreach($row as $val)
                {
                    echo "<td>$val</td>";
                }
                echo "</tr>";
            }
        }
        echo "</table></center>";

        // form for drop down boxes on processing orders
        echo '<form method ="POST" action="">'; 
        echo "<p>";
        echo "<center>";
        echo "<h3>Select an order to fufill</h3>";
        echo "<label for='complete_orders'></label>";
        echo "<select name='complete_orders' id='complete_orders'>";
        echo "<option value='default'>Processing</option>"; 
        echo "</center>";

        // Grabbing info from database Orders for dropdown box to display processing orders 
        $orders_placed = "SELECT order_num, order_status FROM Orders"; 
        $orders_placed_sql = $pdo->prepare($orders_placed);
        $orders_placed_sql->execute();
        
        while ($row = $orders_placed_sql->fetch(PDO::FETCH_ASSOC))
        {
            // print everything in orders table as a drop down box except for default. also does not print fufilled orders in drop down table. 
            if ($row['order_num'] !== '0' && strcasecmp($row['order_status'], 'Processing') == 0)
            {
                echo "<option value={$row['order_num']}>" . $row['order_num'] . "</option>";
            }
        }

        echo '</select>';
        echo "</p>";

        //multiline textbook to leave a note on order
        echo "<p>";
        echo "<h3>Leave a note for the customer?</h3>";
        echo "<textarea id='cust_note' name='cust_note' rows='5' cols='50'>";
        echo "</textarea>";
        echo "</p>";
        echo "<p>";
        echo "<input type='submit' value='Fufill Order'/>";
        echo "</p>";
        echo "</form>";

        $complete_orders = '';
        $cust_node = '';

        // this is when the default (processing) tab is shown when form is submitted. i.e, no order was selected
        if (isset($_POST['complete_orders']) && $_POST['complete_orders'] === 'default')
        {
            echo "No orders selected.";
        }
        else if (isset($_POST['complete_orders']) && $_POST['cust_note'] != null) // if the order was selected and a note was left 
        {   
            $complete_orders = $_POST['complete_orders'];
            $cust_note = $_POST['cust_note'];

            // update note in sql database 
            $update_note = "UPDATE Orders SET note = :cust_note WHERE order_num = :complete_orders";
            $update_note_sql = $pdo->prepare($update_note);
            $update_note_sql->execute([':cust_note' => $cust_note, ':complete_orders' => $complete_orders]);

            // update order_status in sql database
            $update = "UPDATE Orders SET order_status = 'Fulfilled' WHERE order_num = :complete_orders";
            $update_sql = $pdo->prepare($update);
            $update_sql->execute([':complete_orders' => $complete_orders]);
            
            // checking if theres already a tracking number for an order. this prevents it from generating another tracking number when refreshing the page. 
            $tracking_exists = "SELECT tracking_num FROM PlacingOrder WHERE order_num = :complete_orders";
            $tracking_exists_sql = $pdo->prepare($tracking_exists); 
            $tracking_exists_sql->execute([':complete_orders' => $complete_orders]);
            $exists = $tracking_exists_sql->fetchColumn(); 

            // tracking number already exists. 
            if ($exists)
            {
                $tracking = $exists; 
            }
            else 
            {
                // generate a random tracking number. 
                $tracking = '';
                for ($i = 0; $i < 15; $i++)
                {
                  $tracking .= rand(0, 9);
                }
    
                // set a tracking number in PlacingOrder sql 
                $tracking_sql = "UPDATE PlacingOrder SET tracking_num = :tracking WHERE order_num = :complete_orders"; 
                $tracking_sql_rows = $pdo->prepare($tracking_sql);
                $tracking_sql_rows->execute([':tracking' => $tracking, ':complete_orders' => $complete_orders]);
            }

            // just a statement on page itself to indicate that both the completed order and note went through 
            echo "You have completed order number: $complete_orders, and left the note: $cust_note.";
        }
        else if (isset($_POST['complete_orders']) && $_POST['cust_note'] == null) // if order was selected but no note was left 
        {   
            // set variable
            $complete_orders = $_POST['complete_orders'];
            $cust_note = $_POST['cust_note'];
            
            // update order_status in sql database
            $update = "UPDATE Orders SET order_status = 'Fulfilled' WHERE order_num = $complete_orders";
            $update_sql = $pdo->prepare($update);
            $update_sql->execute();

            // checking if theres already a tracking number for an order. this prevents it from generating another tracking number when refreshing the page. 
            $tracking_exists = "SELECT tracking_num FROM PlacingOrder WHERE order_num = :complete_orders";
            $tracking_exists_sql = $pdo->prepare($tracking_exists); 
            $tracking_exists_sql->execute([':complete_orders' => $complete_orders]);
            $exists = $tracking_exists_sql->fetchColumn(); 

            // tracking number already exists. 
            if ($exists)
            {
                $tracking = $exists; 
            }
            else 
            {
                // generate random tracking number
                $tracking = '';
                for ($i = 0; $i < 15; $i++)
                {
                  $tracking .= rand(0, 9);
                }
    
                // set a tracking number in PlacingOrder sql 
                $tracking_sql = "UPDATE PlacingOrder SET tracking_num = :tracking WHERE order_num = :complete_orders"; 
                $tracking_sql_rows = $pdo->prepare($tracking_sql);
                $tracking_sql_rows->execute([':tracking' => $tracking, ':complete_orders' => $complete_orders]);
            }

            // just a statement on page itself to indicate that both the completed order and note went through 
            echo "You have completed order number: $complete_orders, and have chosen not to leave a note."; 
        }

        // select statement to print a table of joined and all customer information. this one included a tracking number 
        $placed_order = $pdo->query("SELECT User.fname, User.lname, User.email, PlacingOrder.order_num, PlacingOrder.tracking_num, PlacingOrder.total_cart_price, Orders.ship_addr, Orders.billing_info, Orders.date_placed, Orders.order_status FROM PlacingOrder JOIN User ON PlacingOrder.cust_id = User.ID JOIN Orders ON PlacingOrder.order_num = Orders.order_num WHERE PlacingOrder.order_num != 0");
        $placed_table = $placed_order->fetchAll(PDO::FETCH_ASSOC);
        
        // prints table of fufilled orders. now with tracking number
        echo "<h3><p>Orders Fulfilled and Customer Information</p></h3>";
        echo "<table border=1>";
        echo "<tr><th>First Name</th><th>Last Name</th><th>User Email</th><th>Order Number</th><th>Tracking Number</th><th>Total Cart Price</th><th>Billing Address</th><th>Billing Information</th><th>Date Placed</th><th>Order Status</th></tr>";
        foreach($placed_table as $row)
        {
            if (strcasecmp($row['order_status'], 'Fulfilled') == 0)
            {
                echo "<tr>";
                foreach($row as $val)
                {
                    echo "<td>$val</td>";
                }
                echo "</tr>";
            }
        }
        echo "</table>";
      }
      catch(PDOexception $e)
      {
        echo "Connection to database failed: " . $e->getMessage();
      }
    }
  ?>
</body>
</html>