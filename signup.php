<?php
$roles = ["Comprador", "Venedor", "Controlador"];
include_once("config/config.php");
include_once("components/elements.php");
$products = mysqli_query($GLOBALS["conn"], "SELECT * FROM Producte");
?>
<!DOCTYPE html>
<html>
<?php
include_once("config/head.php");
?>

<body class="h-screen overflow-hidden flex items-center justify-center"
  style="background-image: linear-gradient(45deg, #FC466B 0%, #3F5EFB 100%)">
  <div
    class="flex flex-col p-6 bg-black/70 w-full m-6 md:m-0 md:w-2/3 flex items-center justify-center rounded-lg max-w-md">
    <?php h1("REGISTRAR-SE", "text-white text-center") ?>
    <form action="query/register.php" method="POST" class="flex flex-col gap-4 w-full ">
      <?php
      $inClasses = 'focus:border-gray-200 focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition-colors duration-300';
      input("text", "name", $inClasses, "Nom Cognom");
      input("text", "nickname", $inClasses, "Nickname", true);
      input("password", "pwd", $inClasses, "Contrasenya", true);
      ?>
      <div>
        <?php
        select($roles, "role","bg-white/10 text-white", "text-black");
        ?>
      </div>
      <?php
      darkButton("submit", "w-full md:hidden", "Registrar-se")
        ?>
      <div class="hidden md:flex flex-row w-full justify-center">
        <?php darkButton("submit", "", "Registrar-se") ?>
      </div>
    </form>
    <div class="mt-4 text-sm text-white text-center">
      <p>Ja tens un compte? <a href="./signin.php" class="text-snow hover:underline">Iniciar sessió</a>
      </p>
    </div>
  </div>
  </div>
</body>

</html>