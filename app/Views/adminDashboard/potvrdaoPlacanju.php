<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
<style>@import url('https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400;1,700&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap');
</style>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>	
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">	
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">	
<link href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400;1,700&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">	    <style>
	html {
		font-family: "Courier Prime";
	}        .page-content {
            padding: 10px;
            text-align: center;
        }
        .signature-box {
            margin-top: 20px;
        }
        .signature-left {
            float: left;
            width: 45%;
            text-align: center;
        }
        .signature-right {
            float: right;
            width: 45%;
            text-align: center;
        }
        .clear-float {
            clear: both;
        }
        .striped-line {
            margin-top: 20px;
            margin-bottom: 20px;
            position: relative;
            text-align: center;
            padding-top: 10px;
        }
        .scissors {
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
        }
    </style>
</head>
<body>

<div>
    <!-- First section -->
    <div class="page-content">
        <p>
            <?php echo $tvrtka['naziv']; ?>, <?php echo $tvrtka['adresa']; ?>, <?php echo $tvrtka['grad']; ?>, OIB: <?php echo $tvrtka['OIB']; ?>, zastupan po <?php echo $tvrtka['direktor']; ?> izdaje:
        </p>
        <h2>POTVRDA O UPLATI GOTOVINE</h2>
        <p>
            Zaposleniku <?php echo $driver['vozac']; ?>, <?php echo $driver['adresa']; ?>, iz <?php echo $driver['grad']; ?>, OIB: <?php echo $driver['oib']; ?>
        </p>
        <p>
            Ovom potvrdom se potvrđuje da je Zaposlenik <?php echo $vozac; ?> na dan <?php echo $datum; ?> godine u poslovnici u <?php echo $tvrtka['grad']; ?>, predao prikupljenu gotovinu u iznosu od <strong><?php echo $iznos; ?> €</strong>.
        </p>
  		<p> Gotovinu je preuzeo zaposlenik <?=$user?></p>
      <p>U Zagrebu, <?php echo $datum; ?></p>
        <div class="signature-box">
            <div class="signature-left">
                <h4><?php echo $user; ?><br><br>_______________________</h4>
            </div>
            <div class="signature-right">
                <h4><?php echo $vozac; ?><br><br>_______________________</h4>
            </div>
        </div>

        <!-- Clear float after signature boxes -->
        <div class="clear-float"></div>
    </div>

    <!-- Scissors line for cutting -->
    <div class="striped-line">
       - - - - - - - - &#9986; - - - - - - - - - - - - - - - - - &#9986; - - - - - - - - - - - - - - - - - - - - &#9986; - - - - - - - -
    </div>

    <!-- Second section with signature boxes -->
    <div class="page-content">
        <p>
            <?php echo $tvrtka['naziv']; ?>, <?php echo $tvrtka['adresa']; ?>, <?php echo $tvrtka['grad']; ?>, OIB: <?php echo $tvrtka['OIB']; ?>, zastupan po <?php echo $tvrtka['direktor']; ?> izdaje:
        </p>
        <h2>POTVRDA O UPLATI GOTOVINE</h2>
        <p>
            Zaposleniku <?php echo $driver['vozac']; ?>, <?php echo $driver['adresa']; ?>, iz <?php echo $driver['grad']; ?>, OIB: <?php echo $driver['oib']; ?>
        </p>
        <p>
            Ovom potvrdom se potvrđuje da je Zaposlenik <?php echo $vozac; ?> na dan <?php echo $datum; ?> godine u poslovnici u <?php echo $tvrtka['grad']; ?> predao prikupljenu gotovinu u iznosu od <strong><?php echo $iznos; ?> €</strong>.
        </p>
		<p> Gotovinu je preuzeo zaposlenik <?=$user?></p>
        <p>U Zagrebu,  <?php echo $datum; ?></p>
        
        <!-- Signature sections aligned left and right -->
        <div class="signature-box">
            <div class="signature-left">
                <h4><?php echo $user; ?><br><br>_______________________</h4>
            </div>
            <div class="signature-right">
                <h4><?php echo $vozac; ?><br><br>_______________________</h4>
            </div>
        </div>

        <!-- Clear float after signature boxes -->
        <div class="clear-float"></div>
    </div>
</div>

</body>
</html>
