<?php
include_once("components/reusable.php");
include_once("config/config.php");
if (!empty($_SESSION)) {
  header('location: /Marcazon/main.php');
  exit;
}
$products = mysqli_query($GLOBALS["conn"], "SELECT * FROM Producte");
?>
<!DOCTYPE html>
<html>
<?php head(); ?>

<body class="h-screen overflow-hidden flex items-center justify-center"
  style="background-image: linear-gradient(45deg, #FC466B 0%, #3F5EFB 100%)">
  <div
    class="flex flex-col p-6 bg-black/70 w-full m-6 md:m-0 md:w-2/3 flex items-center justify-center rounded-lg max-w-md">
    <?php
    a("./main.php", "➥ Inici", "flex px-2 py-2 bg-black text-xl text-white justify-center w-full md:w-auto self-start rounded-lg hover:bg-gray-900");
    h1("REGISTRAR-SE", "text-white text-center");
    ?>
    <form action="query/register.php" method="POST" class="flex flex-col gap-4 w-full ">
      <?php
      $inClasses = 'p-2 mt-1 focus:border-gray-200 focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition-colors duration-300';
      input("text", "name", $inClasses, "Nom Cognom");
      input("text", "nickname", $inClasses, "Nickname", true);
      input("password", "pwd", $inClasses, "Contrasenya", true);
      select($GLOBALS["roles"], "role", "p-2 mt-1 bg-white/10 text-white", "text-black");
      darkButton("submit", "flex justify-center self-center w-full md:w-40", "Registrar-se");
      ?>
    </form>
    <div class="mt-4 text-sm text-white text-center">
      <p>Ja tens un compte?
        <?php a("./signin.php", "Iniciar Sessió", "text-snow hover:underline"); ?>
      </p>
    </div>
  </div>
  </div>
</body>

</html>