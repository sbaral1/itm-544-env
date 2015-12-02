<?php

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css"/>
    <title>ITMO 544 - Index.php</title>
</head>
<body>

    <div id="main">
        <header>
            <h1>Picture Uploader</h1>

            <p>A mini project for Cloud Computing - Satyajit Baral</p>
            <p>Illinois Institute of Technology</p>
            <p><a href="https://github.com/gpuenteallott/itmo544-CloudComputing-mp1">Project in GitHub</a></p>
        </header>
        <div class="aside">
            <p>Don't have an image?</p>
            <p>Download this one</p>
            <img src="logo.jpg"/>
        </div>
        
        <h2>Fill the following form</h2>



        <form action="process.php" method="post" enctype="multipart/form-data">
          <p><label>Email: <input type="text" name="email" required/></label></p>
          <p><label>Cell Number: <input type="text" name="phone" placeholder="1-333-555-7777" required/></label></p>
          <p><label>Choose Image: <input type="file" name="uploaded_file" id="uploaded_file" required/></label></p>
          <input type="submit"  value="submit it!"/>
        </form>
        <p>Note: You may receive notifications via SMS</p>
    </div>
</body>

</html>
