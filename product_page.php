<html>
  <head>
    <!--Mia and Alex-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
      
      /*This is all the styling stuff! Refer to usesrs_profile.php or orders.php for more clarification on what it's doing :) */
      body 
      {
        font-family: "Times New Roman", Times, serif;
        margin: 0;
        padding: 0;
      }

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
  </style>
  </head>


<body>
  <div class="topnav">
  <!-- Nav go back to main page or go to checkout-->
    <a href='main_store.php'>Homepage</a>
    <a href='checkout.php'>Checkout</a>
    <a href='cart.php'>Shopping Cart</a>
  </div>

 <?php
  // session start
  session_start();

  include("secrets.php");

if(!isset($_SESSION['user']) || $_SESSION['user'] != 'customer')
{
  echo "<center><h2><br><p>Please login to add products to your shopping cart!</p></h2></center>";
  echo "<center><h4><a href='login.php'>Login</a></h4></center>";
}
else
{
  try{
   $dsn = "mysql:host=courses;dbname=z1973307"; //make sure to switch the zID to your own, so that you dont get an error msg
   $pdo = new PDO($dsn, $username, $password);

    // Extract product ID from URL
    $prod_id = $_GET['id'];

    // Fetch product details based on the product ID
    $sql = "SELECT * FROM Product WHERE prod_id = $prod_id";
    $result = $pdo->query($sql);


    // Display product details
    if($row = $result->fetch(PDO::FETCH_ASSOC)) {
      echo "<h1><center>" . $row['prod_name'] . "</center></h1>";
      echo "<p><center>" . $row['prod_type'] . "</center></p>";
      echo "<p><center>Price: $" . $row['prod_price'] . "</center></p>";
      echo "<p><center>Genre: " . $row['genre'] . "</center></p>";

      // Youtube videos for each product! The youtube videos are essentially the description but i added a few more things 
      echo "<center>";
      // PowerWash Simulator
      if ($row['prod_id'] == '1480577991')
      {
        echo "Release Date: May 19, 2021 <br><br>"; 
        echo "Developer: FuturLab <br><br>"; 
        echo "Publishers: FutureLab, Square Enix <br><br>";
        echo "Platform: Steam <br><br>";
        echo '<iframe width="420" height="315" src="https://www.youtube.com/embed/k5G-2qTupCk"></iframe>';
      }

      // Project Zomboid
      if ($row['prod_id'] == '1898751726')
      {
        echo "Release Date: November 18, 2013 <br><br>"; 
        echo "Developer: The Indie Stone <br><br>"; 
        echo "Publisher: The Indie Stone <br><br>";
        echo "Platform: Steam <br><br>";
        echo '<iframe width="420" height="315" src="https://www.youtube.com/embed/YhSd39QqQUg"></iframe>';
      }

      // Mezmerize
      if ($row['prod_id'] == '1926090274')
      {
        echo "Artist: System of a Down<br><br>"; 
        echo "Song you may know: B.Y.O.B. <br><br>";
        echo '<iframe width="420" height="315" src="https://www.youtube.com/embed/zUzd9KyIDrM"></iframe>';
      }

      // La La Land
      if ($row['prod_id'] == '1948709850')
      {
        echo "Directed By: Damien Chazelle<br><br>"; 
        echo '<iframe width="420" height="315" src="https://www.youtube.com/embed/0pdqf4P9MB8"></iframe>';
      }

      // Purple
      if ($row['prod_id'] == '2271888804')
      {
        echo "Artist: Stone Temple Pilots<br><br>"; 
        echo "Song you may know: Creep <br><br>";
        echo '<iframe width="420" height="315" src="https://www.youtube.com/embed/sT1DdO3SISg"></iframe>';
      }

      // Baldurs Gate 3
      if ($row['prod_id'] == '2341487508')
      {
        echo "Release Date: August 3, 2023 <br><br>"; 
        echo "Developer: Larian Studios <br><br>"; 
        echo "Publisher: Larian Studios <br><br>";
        echo "Platform: Steam <br><br>";
        echo '<iframe width="420" height="315" src="https://www.youtube.com/embed/1T22wNvoNiU"></iframe>';
      }

      // Donnie Darko
      if ($row['prod_id'] == '3000451120')
      {
        echo "Directed By: Richard Kelly<br><br>"; 
        echo '<iframe width="420" height="315" src="https://www.youtube.com/embed/bzLn8sYeM9o"></iframe>';
      }

      // RimWorld
      if ($row['prod_id'] == '3063184236')
      {
        echo "Release Date: October 17, 2018 <br><br>"; 
        echo "Developer: Ludeon Studios <br><br>"; 
        echo "Publisher: Ludeon Studios <br><br>";
        echo "Platform: Steam <br><br>";
        echo '<iframe width="420" height="315" src="https://www.youtube.com/embed/3tDrxOASUog"></iframe>';
      }

      // Elden Ring
      if ($row['prod_id'] == '3217454108')
      {
        echo "Release Date: February 24, 2022 <br><br>"; 
        echo "Developer: FromSoftware Inc <br><br>"; 
        echo "Publisher: FromSoftware Inc., Brand Namco<br><br>";
        echo "Platform: Steam <br><br>";
        echo '<iframe width="420" height="315" src="https://www.youtube.com/embed/AKXiKBnzpBQ"></iframe>';
      }

      // Stardew Valley
      if ($row['prod_id'] == '3773245407')
      {
        echo "Release Date: February 26, 2016 <br><br>"; 
        echo "Developer: Concerned Ape <br><br>"; 
        echo "Publisher: Concerned Ape <br><br>";
        echo "Platform: Steam <br><br>";
        echo '<iframe width="420" height="315" src="https://www.youtube.com/embed/ot7uXNQskhs"></iframe>';
      }

      // The Prestige
      if ($row['prod_id'] == '3823637662')
      {
        echo "Directed By: Christopher Nolan<br><br>"; 
        echo '<iframe width="420" height="315" src="https://www.youtube.com/embed/RLtaA9fFNXU"></iframe>';
      }

      // Big Lebowski
      if ($row['prod_id'] == '5681457561')
      {
        echo "Directed By: Coen Brothers<br><br>"; 
        echo '<iframe width="420" height="315" src="https://www.youtube.com/embed/cd-go0oBF4Y"></iframe>';
      }

      //OkComputer
      if ($row['prod_id'] == '6569558771')
      {
        echo "Artist: Radiohead<br><br>"; 
        echo "Song you may know: Karma Police <br><br>";
        echo '<iframe width="420" height="315" src="https://www.youtube.com/embed/1uYWYWPc9HU"></iframe>';
      }

      // God of War
      if ($row['prod_id'] == '7215084108')
      {
        echo "Release Date: April 20, 2018 <br><br>"; 
        echo "Developer: Santa Monica Studio <br><br>"; 
        echo "Publisher: Playstation PC LLC <br><br>";
        echo "Platform: Steam <br><br>";
        echo '<iframe width="420" height="315" src="https://www.youtube.com/embed/K0u_kAWLJOA"></iframe>';
      }

      // Phasmophobia
      if ($row['prod_id'] == '7267548852')
      {
        echo "Release Date: September 18, 2020 <br><br>"; 
        echo "Developer: Kinetic Games <br><br>"; 
        echo "Publisher: Kinetic Games <br><br>";
        echo "Platform: Steam <br><br>";
        echo '<iframe width="420" height="315" src="https://www.youtube.com/embed/sRa9oeo5KiY"></iframe>';
      }

      // Parasite
      if ($row['prod_id'] == '7415304205')
      {
        echo "Directed By: Bong Joon-ho<br><br>"; 
        echo '<iframe width="420" height="315" src="https://www.youtube.com/embed/SWSDQfJQhUg"></iframe>';
      }

      // Monster Hunter: World
      if ($row['prod_id'] == '7577360094')
      {
        echo "Release Date: August 9, 2018 <br><br>"; 
        echo "Developer: CAPCOM Co., Ltd. <br><br>"; 
        echo "Publisher: CAPCOM Co., Ltd. <br><br>";
        echo "Platform: Steam <br><br>";
        echo '<iframe width="420" height="315" src="https://www.youtube.com/embed/Ro6r15wzp2o"></iframe>';
      }

      // Omori
      if ($row['prod_id'] == '7926870520')
      {
        echo "Release Date: December 25, 2020 <br><br>"; 
        echo "Developer: OMOCAT, LLC <br><br>"; 
        echo "Publisher: OMOCAT, LLC <br><br>";
        echo "Platform: Steam <br><br>";
        echo '<iframe width="420" height="315" src="https://www.youtube.com/embed/erzgjfU271g"></iframe>';
      }

      // FrostPunk
      if ($row['prod_id'] == '8257397556')
      {
        echo "Release Date: April 24, 2018 <br><br>"; 
        echo "Developer: 11 bit studios <br><br>"; 
        echo "Publisher: 11 bit studios <br><br>";
        echo "Platform: Steam <br><br>";
        echo '<iframe width="420" height="315" src="https://www.youtube.com/embed/qqEpSOFDXGA"></iframe>';
      }

      // Train to Busan
      if ($row['prod_id'] == '8700958003')
      {
        echo "Directed By: Yeon Sang-ho<br><br>"; 
        echo '<iframe width="420" height="315" src="https://www.youtube.com/embed/1ovgxN2VWNc"></iframe>';
      }

      echo "</center>";
    }
    else
    {
      echo "Product not found";
    }

/////////////////////////////////////////////////////////
    //Form to enter quantity. Using a single line textbox
    echo '<form method = "POST" action="">';
    echo '<p>';
    echo "<center>";
    echo '<label for="qty">Enter Quantity to add to cart: </label>';
    echo '<input type ="text" name="qty">';
    echo '</p>';
    echo "</center>";

    //Submits the entire form. clicking this submit button should add to cart?
    echo "<center>";
    echo '<input type="submit" name = "add_cart" value="Add to Cart"/>';
    echo "</center>";
    echo '</form>';
//////////////////////////////////////////////////////////

    if(!empty($_POST["qty"]))
    {
      $selectedQuan = $_POST["qty"];

      $quanInStock = $pdo->prepare("SELECT quan_in_stock FROM Holds WHERE prod_id=:prod_id;");
      $quanInStock->execute([":prod_id" => $prod_id]);
      $currQuan = (int)$quanInStock->fetchColumn();

      // if try to add more than whats in stock, error message
      if($currQuan < $selectedQuan)
      {
        echo "<center>Not enough product in stock.</center>";
      }
      else
      {
        //this adds a product to the cart but DOES NOT update the qty in inventory
        $cartID = $pdo->prepare('SELECT cart_id FROM Cart WHERE cust_id = (SELECT ID FROM User WHERE fname=:name);');
        $cartID->execute([":name" => $_SESSION["name"]]);
        $cID = (int)$cartID->fetchColumn();
        $priceProd = $pdo->prepare("SELECT prod_price FROM Product WHERE prod_id = $prod_id;");
        $priceProd->execute();
        $pProd = (float)$priceProd->fetchColumn();
        $ProdsPrice = $pProd * $selectedQuan;

        $addIntoCart = $pdo->prepare("INSERT INTO InCart VALUES (:cartid, :prodid, :quanprod, :priceprod);");
        $addIntoCart->execute([':cartid' => $cID, ':prodid' => $prod_id, ':quanprod' => $selectedQuan, ':priceprod' => $ProdsPrice]);
        echo "<center>Products have been added to your cart!</center>";
      }
    }
    else 
    {
      echo "<center>No quantity entered.</center>";
    }
  }
  catch(PDOexception $e){ //print an error if it fails to connect
   echo "Connection to database failed: " . $e->getMessage();
  }
}
?>
</body>
</html>