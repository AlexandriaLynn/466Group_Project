<!DOCTYPE html>
<head>
    <!--Ally & Alex-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Media Entertainment</title>
    <style>
        /* All styles for product page */
        body 
        {
            font-family: "Times New Roman", Times, serif;
            margin: 0;
            padding: 0;
            background-image: url('https://static.vecteezy.com/system/resources/previews/004/690/499/large_2x/cd-or-dvd-storage-data-information-technology-music-and-movie-record-holographic-side-of-the-compact-disc-a-compact-disc-isolated-on-black-background-free-photo.jpg');
            background-size: cover;
            background-position: center;
        }

        /* Hover nav */
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

        /*welcome*/
        .topnav .welcome 
        {
            color: white;
            display: inline-block;
            font-size: 20px;
            font-weight: bold;
        }

        /*logout*/
        .topnav .logout 
        {
            position: absolute;
            right: 20px;
        }

        h1 
        {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: white;
        }

        /* All of the product boxes formatting and the product header.*/
        .product 
        {
            display: inline-block;
            width: 200px;
            margin: 10px;
            padding: 10px;
            border: 4px solid black;
            text-align: center;
            background-color: #fff; 
            margin-left: 70px;
        }

        .product a 
        {
            text-decoration: none;
            color: #000;
            font-weight: bold;
        }

        .product p 
        {
            margin-top: 10px;
        }

        .products-h 
        {
            background-color: black;
            font-size: 50px;
            padding: 15px 32px;
            color: white;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <?php
        // start session
        session_start();

        include("secrets.php");

        // display name and logout option to end session
        if (isset($_SESSION['user']) && $_SESSION['user'] == 'customer')
        {
            // top nav 
            echo '<div class="topnav">';

            // display my account 
            echo '<a href="user_profile.php" class="account">My Account</a>';
            
            // display name. only shows when logged in 
            echo '<span class="welcome">Welcome, ' . $_SESSION['name'] . '!</span>';

            // display cart
            echo '<a href="cart.php" class="cart">Shopping Cart</a>';

            // display logout option. only shows when logged in 
            echo '<a href="logout.php" class="logout">Logout</a>';
            echo '</div>';
        }
        else
        {
            // NOT LOGGED IN 

            // displays everything as above except for logout and welcome.
            echo '<div class="topnav">';
            echo '<a href="login.php" class="login">Login</a>';
            echo '<a href="user_profile.php" class="account">My Account</a>';
            echo '<a href="cart.php" class="cart">Shopping Cart</a>';
            echo '</div>';
        }
    ?>

    <h1 class="products-h">Products</h1>

    <?php

  try{
    $dsn = "mysql:host=courses;dbname=z1973307"; //make sure to switch the zID to your own, so that you dont get an error msg
    $pdo = new PDO($dsn, $username, $password);


        // Fetch product data from database
        $sql = "SELECT prod_id, prod_name, prod_price, prod_type FROM Product";
        $result = $pdo->query($sql);

    // Display product list with links to product pages
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        if ($row['prod_type'] !== 'Def') {
            echo "<div class=\"product\">";
            echo "<p>" . $row['prod_type'] . "</p>";
            echo "<a href=\"product_page.php?id=" . $row['prod_id'] . "\"><h2>" . $row['prod_name'] . "</h2></a>";
            echo "<p>Price: $" . $row['prod_price'] . "</p>";

            // Adding images to each product
            // Powerwash simulator
            if ($row['prod_id'] == '1480577991')
            {
                echo "<img src='https://image.api.playstation.com/vulcan/ap/rnd/202301/1117/mOtdY7LHEsMrtMp0VvTyXeiE.png' height='187' width='200'>";
            }

            // Project Zomboid
            if ($row['prod_id'] == '1898751726')
            {
                echo "<img src='https://upload.wikimedia.org/wikipedia/en/0/0c/Boxshot_of_video_game_Project_zomboid.jpg' height='216' width='200'>";
            }

            // Mezmerize
            if ($row['prod_id'] == '1926090274')
            {
                echo "<img src='https://m.media-amazon.com/images/I/71Mby0cpvxL._UF1000,1000_QL80_.jpg' height='216' width='200'>";
            }

            // LaLa Land
            if ($row['prod_id'] == '1948709850')
            {
                echo "<img src='https://m.media-amazon.com/images/M/MV5BMzUzNDM2NzM2MV5BMl5BanBnXkFtZTgwNTM3NTg4OTE@._V1_.jpg' height='216' width='200'>";
            }

            // Purple
            if ($row['prod_id'] == '2271888804')
            {
                echo "<img src='https://i.scdn.co/image/ab67616d0000b273fc7df879208b362bb1ce1499' height='216' width='200'>";
            }

            // Balders Gate
            if ($row['prod_id'] == '2341487508')
            {
                echo "<img src='https://image.api.playstation.com/vulcan/ap/rnd/202302/2321/3098481c9164bb5f33069b37e49fba1a572ea3b89971ee7b.jpg' height='216' width='200'>";
            }

            // Donnie Darko
            if ($row['prod_id'] == '3000451120')
            {
                echo "<img src='https://m.media-amazon.com/images/M/MV5BZjZlZDlkYTktMmU1My00ZDBiLWFlNjEtYTBhNjVhOTM4ZjJjXkEyXkFqcGdeQXVyMTMxODk2OTU@._V1_FMjpg_UX1000_.jpg' height='216' width='200'>";
            }

            // Rim World
            if ($row['prod_id'] == '3063184236')
            {
                echo "<img src='https://cdn1.epicgames.com/7051eadbb8c2435caf32a9bc0dc17936/offer/EGS_RimWorld_LudeonStudios_S1-2560x1440-410a62ec21d44260409182e1174cce2e.jpg' height='216' width='200'>";
            }

            // Elden Ring
            if ($row['prod_id'] == '3217454108')
            {
                echo "<img src='https://image.api.playstation.com/vulcan/ap/rnd/202110/2000/aGhopp3MHppi7kooGE2Dtt8C.png' height='216' width='200'>";
            }

            // Stardew Valley
            if ($row['prod_id'] == '3773245407')
            {
                echo "<img src='https://m.media-amazon.com/images/M/MV5BYmZiMWNlOWMtODMyNi00ZThiLTk0ZjYtOTQwMGRiNzE2NjFhXkEyXkFqcGdeQXVyNTgyNTA4MjM@._V1_.jpg' height='216' width='200'>";
            }

            // The Prestige
            if ($row['prod_id'] == '3823637662')
            {
                echo "<img src='https://m.media-amazon.com/images/M/MV5BMjA4NDI0MTIxNF5BMl5BanBnXkFtZTYwNTM0MzY2._V1_.jpg' height='216' width='200'>";
            }

            // Big Lebowski
            if ($row['prod_id'] == '5681457561')
            {
                echo "<img src='https://resizing.flixster.com/-XZAfHZM39UwaGJIFWKAE8fS0ak=/v3/t/assets/p20484_v_v8_bc.jpg' height='216' width='200'>";
            }

            //OkComputer
            if ($row['prod_id'] == '6569558771')
            {
                echo "<img src='https://i.cbc.ca/1.4163488.1497585293!/fileImage/httpImage/image.jpg_gen/derivatives/16x9_940/radiohead.jpg' height='216' width='200'>";
            }

            // God of War
            if ($row['prod_id'] == '7215084108')
            {
                echo "<img src='https://image.api.playstation.com/vulcan/img/rnd/202010/2217/p3pYq0QxntZQREXRVdAzmn1w.png' height='216' width='200'>";
            }

            // Phasmophobia
            if ($row['prod_id'] == '7267548852')
            {
                echo "<img src='https://cdn.akamai.steamstatic.com/steam/apps/739630/capsule_616x353.jpg?t=1699959522' height='216' width='200'>";
            }

            // Parasite
            if ($row['prod_id'] == '7415304205')
            {
                echo "<img src='https://m.media-amazon.com/images/M/MV5BYWZjMjk3ZTItODQ2ZC00NTY5LWE0ZDYtZTI3MjcwN2Q5NTVkXkEyXkFqcGdeQXVyODk4OTc3MTY@._V1_.jpg' height='216' width='200'>";
            }

            // Monster Hunter World
            if ($row['prod_id'] == '7577360094')
            {
                echo "<img src='https://image.api.playstation.com/vulcan/img/rnd/202008/1803/UIULUSArKlniav8B2FjWWXYo.png' height='187' width='200'>";
            }

            // Omori
            if ($row['prod_id'] == '7926870520')
            {
                echo "<img src='https://upload.wikimedia.org/wikipedia/en/c/cd/Omori_cover.jpg' height='216' width='200'>";
            }

            // Frostpunk
            if ($row['prod_id'] == '8257397556')
            {
                echo "<img src='https://cdn1.epicgames.com/salesEvent/salesEvent/EGS_FrostpunkGameofTheYearEdition_11bitstudios_Bundles_S1_2560x1440-904a427fe3afc998cd7cb8b96d304fa0' height='216' width='200'>";
            }

            // Train to Busan
            if ($row['prod_id'] == '8700958003')
            {
                echo "<img src='https://m.media-amazon.com/images/M/MV5BMTkwOTQ4OTg0OV5BMl5BanBnXkFtZTgwMzQyOTM0OTE@._V1_.jpg' height='216' width='200'>";
            }

            echo "</div>";
        }
    }
  }
  catch(PDOexception $e){ //print an error if it fails to connect
   echo "Connection to database failed: " . $e->getMessage();
  }
  ?>
</body>
</html>