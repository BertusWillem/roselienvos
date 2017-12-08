<?php

/*
###############################################################################
#                                                                             #
#           LOGIN FUNCTIE. KIJKT OF DE GEBRUIK BESTAAT EN MAAKT SESSIE AAN    #
#                                                                             #
###############################################################################
*/

function loginRequest($email, $password)
{
  //Als wel alles goed is ingevuld wordt er een select statement gedaan met het
  //ingevulde email adres.
  include 'dbh.php';
  $stmt = $dbh->prepare("SELECT * FROM gebruikers WHERE email = :email");
  $stmt->execute(array(':email' => $email));
  $rows = $stmt ->fetch();
  $validPassword = password_verify($password, $rows['password']);
  //Het wachtwoord wordt geconteroleerd met de hash in de database
    if (!$validPassword)
    {
    header("Location: ../login.php?error=incorrect");
    exit();
    //Als dit niet overeen komt, wordt er een error message gegenereerd
    }
    else
    {
      $_SESSION['userid'] = $rows['userid'];
      $_SESSION['role'] = $rows['rol'];
      $_SESSION['firstname'] = ucfirst($rows['firstname']);
      $_SESSION['email'] = $rows['email'];
      //Als wel alles goed is, worden er een aantal sessie variabelen gedefinieerd

      header("Location: ../inloggen.php");
    }
}

/*
###############################################################################
#                                                                             #
#           REGISTREER FUNCTIE. MAAKT GEBRUIKER AAN IN DE DATABASE            #
#                                                                             #
###############################################################################
*/

function registerRequest($firstname, $lastname, $email, $password, $adres, $postcode, $woonplaats)
{
include 'dbh.php';
$stmt = $dbh->prepare("SELECT email FROM gebruikers WHERE email = :email");
$stmt->execute(array(':email' => $email));
$rows = $stmt ->fetch(); //Alle gebruikersnamen worden uit de database gehaald

if (!empty($rows)) //Als er gebruikersnamen overeen komen wordt de bezoek teruggestuurd omdat
{                  //de gebruikersnaam al bezet is.
header("Location:../register.php?error=known");
exit();
}

else
{
$passwordHash = password_hash($password, PASSWORD_BCRYPT, array("cost" => 12));
$stmt = $dbh->prepare("INSERT INTO gebruikers (email, password, firstname, lastname)
                       VALUES (:email, :password, :firstname, :lastname);");
//De ingevulde gegevens worden in de database gestopt
$stmt->execute(array(':email' => $email, ':password' => $passwordHash,
                     ':firstname' => $firstname, ':lastname' => $lastname));
//De variabelen die worden hier gedefinieerd

$stmt = $dbh->prepare("SELECT userid FROM gebruikers WHERE email = :email");
$stmt->execute(array(':email' => $email));
$rows_u = $stmt ->fetch(); //Alle gebruikersnamen worden uit de database gehaald
$userid = $rows_u['userid'];

$stmt = $dbh->prepare("INSERT INTO gegevens (added_by, adres, postcode, woonplaats)
                       VALUES (:added_by, :adres, :postcode, :woonplaats);");
  //De ingevulde gegevens worden in de database gestopt
$stmt->execute(array(':added_by' => $userid, ':adres' => $adres,
                     ':postcode' => $postcode, ':woonplaats' => $woonplaats));

  //De variabelen die worden hier gedefinieerd
header("Location:../login.php?message=created");
  //De gebruikers wordt teruggestuurd naar login.php met de melding dat zijn account is aangemaakt
  //het account is met de gegevens in de database gezet
  }
}

###############################################################################
#                                                                             #
#           PROFIEL INFO FUNCTIE. VRAAGT ALLE INFORMATIE VAN EEN GEBRUIKER OP #
#                                                                             #
###############################################################################

function requestProfile($userid, $optie)
{
  if ($optie === "fullProfile"){
  global $email, $firstname,$lastname,$adres,$postcode,$woonplaats,$page;
  }
  if ($optie === "changeInfo"){
  global $firstname,$lastname,$adres,$postcode,$woonplaats;
  }
  include 'includes/dbh.php';
  $stmt = $dbh->prepare("SELECT email, firstname, lastname FROM gebruikers WHERE userid = :userid");
  $stmt->execute(array(':userid' => $userid));
  $rows = $stmt ->fetch();
  // Hier haalt php de accountgegevens uit de database

  $stmt = $dbh->prepare("SELECT * FROM gegevens WHERE added_by = :userid;");
  $stmt->execute(array(':userid' => $userid));
  $adrow = $stmt ->fetch();
  // Hier haalt php de persoonsgegevens uit de database

  $email = $rows['email'];
  $firstname = ucfirst($rows['firstname']);
  $lastname= ucfirst($rows['lastname']);
  $adres = $adrow['adres'];
  $postcode = $adrow['postcode'];
  $woonplaats = $adrow['woonplaats'];
  $page = ($firstname ." " . $lastname);


}





?>