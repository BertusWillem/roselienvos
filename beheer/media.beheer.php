<?php
session_start();
$page = "Media";
include 'header.beheer.php';
include '../includes/dbh.php';
$uitvoering = $_GET['uitvoering'];

$stmt = $dbh->prepare("SELECT * FROM afbeelding;");
$stmt->execute();
$rows = $stmt->fetch();
?>

<section class="body-container">
  <section class="container">

    <?php
      // Afbeelding uploaden
      Print('
      <div class="input-window" id="box">
        <form action="includes/afbeeldingmedia.inc.php?uitvoering='.$uitvoering.'" method="post" enctype="multipart/form-data">
          <input type="file" name="fileToUpload" id="fileToUpload" style="color: black; margin-bottom: 0;">
          <input type="submit" name="submit" value="Afbeelding uploaden">
        </form>
      </div> ');
    ?>

    <div class="recensie-container">

    <?php
    // Geen afbeelding selecteren, laat een knop zien waarbij je geen afbeelding kunt kiezen.
    if ($uitvoering == 'kiezen'){
      $pagina = $_GET['pagina'];

      print('
      <div class="block">
      <div class="image-view-container">
        <h2>Geen afbeelding</h2>
        <a href="includes/updateafbeelding.inc.php?uitvoering=kiezen&&tabel='.$_GET['tabel'].'&&pagina='.$_GET['pagina'].'&&afbeelding=0&&page='.$_SERVER['HTTP_REFERER'].'"><div class="image-view">
          <p>KIEZEN</p>
        </div></a>
        </div>
      </div>');
    }

    // Afbeelding kiezen
    while ($uitvoering == 'kiezen' && $rows = $stmt->fetch()){
    print('
      <div class="block" id="imageblock">
      <div class="image-view-container">
        <img src="'.$rows['afbeelding'].'" alt="Plaatje" />
        <a href="includes/updateafbeelding.inc.php?uitvoering=kiezen&&tabel='.$_GET['tabel'].'&&pagina='.$_GET['pagina'].'&&afbeelding='.$rows['afbeeldingid'].'&&page='.$_SERVER['HTTP_REFERER'].'"><div class="image-view">
          <p>KIEZEN</p>
        </div></a>
        </div>
      </div>
    ');}

    // laad de plaatjes
    while ($uitvoering == 'beheer' && $rows = $stmt->fetch()){
    // Afbeelding verwijderen, deze optie werkt alleen in de beheer verzie
    print('
      <div class="block" id="imageblock">
      <div class="image-view-container">
        <img src="'.$rows['afbeelding'].'" alt="Plaatje" />
        <a href="includes/afbeeldingmedia.inc.php?uitvoering=verwijderen&&afbeelding='.$rows['afbeeldingid'].'"><div class="image-view">
          <p id="important">VERWIJDEREN!</p>
        </div></a>
        </div>
      </div>
    ');
    }?>

    </div>
  </section>
</section>

<?php include ('../footer.php');
