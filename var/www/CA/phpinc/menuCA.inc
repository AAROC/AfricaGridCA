<div id="container">

    <div id="header">
      <div class="textimage">
        <img src="https://<?php echo $_SERVER["SERVER_ADDR"];?>/CA/icons/<?php echo $image;?>" width=220px height=100px alt="">
        <h1><?php echo $CAname;?> Certification Authority</h1>
        <div class="clearer">&nbsp;</div>
      </div>
    </div>

    <div id="navigation">
        <ul>
            <li><a href="https://<?php echo $_SERVER["SERVER_ADDR"];?>/CA">Home</a></li>
            <li>&nbsp;</li>
            <li><a href="https://<?php echo $_SERVER["SERVER_ADDR"];?>/CA/mgt/getCA.php"><?php echo $CAname;?> CA certificate</a></li>
            <li>&nbsp;</li>
            <li><a href="https://<?php echo $_SERVER["SERVER_ADDR"];?>/CA/mgt/restricted/howto.php">Documentation</a></li>
            <li>&nbsp;</li>
            <li><a href="https://<?php echo $_SERVER["SERVER_ADDR"];?>/CA/mgt/restricted/ucert.php">Request a Personal certificate</a></li>
            <li>&nbsp;</li>
            <li><a href="https://<?php echo $_SERVER["SERVER_ADDR"];?>/CA/mgt/restricted/robot.php">Request a Robot certificate</a></li>
            <li>&nbsp;</li>
            <li><a href="https://<?php echo $_SERVER["SERVER_ADDR"];?>/CA/mgt/restricted/rencrt.php">Renew Personal or Robot certificate</a></li>
            <li>&nbsp;</li>
            <li><a href="https://<?php echo $_SERVER["SERVER_ADDR"];?>/CA/mgt/restricted/RA.php">Registration Authority (RA)</a></li>
            <li>&nbsp;</li>
            <li><a href="https://<?php echo $_SERVER["SERVER_ADDR"];?>/CA/mgt/restricted/RAlist.php">List of accredited RAs</a></li>
            <li>&nbsp;</li>
            <li><a href="https://<?php echo $_SERVER["SERVER_ADDR"];?>/CA/mgt/scert.php">Check certificates</a></li>
            <li>&nbsp;</li>
            <li><a href="https://<?php echo $_SERVER["SERVER_ADDR"];?>/CA/mgt/crl.php">Certificate Revocation List</a></li>
            <li>&nbsp;</li>
        </ul>

    </div>
    <div id="content">
