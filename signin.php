<?php
include_once("phpQs/config/config.php");
include_once("components/elements.php");
$products = mysqli_query($conn, "SELECT * FROM Producte");
?>
<!DOCTYPE html>
<html>
<?php
include_once("phpQs/config/head.php");
?>

<body class="h-screen overflow-hidden flex items-center justify-center"
  style="background-image: linear-gradient(45deg, #FC466B 0%, #3F5EFB 100%)">
  <div
    class="flex flex-col p-6 bg-black/70 w-full m-6 md:m-0 md:w-2/3 flex items-center justify-center rounded-lg max-w-md">
    <?php h1("INICIAR SESSIÓ", "text-white text-center") ?>
    <form action="#" method="POST" class="flex flex-col gap-4 w-full ">
      <?php
      $inClasses = 'focus:border-gray-200 focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition-colors duration-300';
      input("text", "nickname", $inClasses, "NickName");
      input("password", "pwd", $inClasses, "Contrasenya");
      ?>
      <?php darkButton("submit", "w-full md:hidden", "Entra") ?>
      <div class="hidden md:flex flex-row w-full justify-center">
        <?php darkButton("submit", "", "Entra") ?>
      </div>
    </form>
    <div class="mt-4 text-sm text-white text-center">
      <p>No tens compte? <a href="./signup.php" class="text-snow hover:underline">Registrar-se</a>
      </p>
    </div>
  </div>
  </div>
</body>

</html>