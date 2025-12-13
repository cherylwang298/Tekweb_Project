<?php
session_start();
include 'koneksi.php';

if (isset($_POST['submit'])) {
  $username = trim($_POST['username']);
  $password = $_POST['password'];

  if (empty($username) || empty($password)) {
    $error = "Username dan password wajib diisi!";
  } else {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
      $user = $result->fetch_assoc();

      if (password_verify($password, $user['password_hash'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['name']     = $user['name'];

        header("Location: index.php");
        exit;
      } else {
        $error = "Password salah!";
      }
    } else {
      $error = "Username tidak ditemukan!";
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Biblios | Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background: #f3f4f6;
    }
  </style>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            serif: ["Playfair Display", "serif"],
            sans: ["Inter", "sans-serif"],
          },
        },
      },
    };
  </script>
  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap"
    rel="stylesheet" />
</head>

<body
  class="min-h-screen flex flex-col items-center justify-center
         p-8 sm:p-4 bg-[#F7F4EB] relative overflow-hidden">

  <?php if (!empty($error)): ?>
    <div id="error-alert"
      class="mb-3 sm:mb-4 rounded-xl border border-red-400
                bg-red-100 px-3 py-2 sm:px-4 sm:py-3
                text-red-700 text-xs sm:text-sm font-sans">
      <?= $error ?>
    </div>
  <?php endif; ?>

  <script>
    const errorAlert = document.getElementById('error-alert');
    if (errorAlert) {
      setTimeout(() => {
        errorAlert.remove();
      }, 2000);
    }
  </script>

  <div class="absolute top-0 left-0 w-56 h-56 sm:w-72 sm:h-72
              bg-[#D9E4DD] rounded-full blur-3xl opacity-40 -z-10"></div>

  <div class="absolute bottom-0 right-0 w-72 h-72 sm:w-96 sm:h-96
              bg-[#CAD2C5] rounded-full blur-2xl opacity-40 -z-10"></div>

  <div class="absolute top-1/3 right-6 sm:right-10 w-40 h-40 sm:w-52 sm:h-52
              bg-[#84A98C] rounded-full blur-xl opacity-30 -z-10"></div>

  <div
    class="w-full max-w-sm sm:max-w-md bg-white rounded-3xl
           shadow-2xl p-5 sm:p-8 border border-[#E4E1D8]">

    <h1
      class="font-serif text-3xl sm:text-4xl font-extrabold
             text-center mb-5 sm:mb-6 text-[#2F3E46]">
      Biblios
    </h1>

    <form class="space-y-4 sm:space-y-5" method="POST" action="">

      <div>
        <label
          class="font-sans block mb-1 text-sm sm:text-base
                 font-semibold text-[#2F3E46]">
          Username
        </label>
        <input
          type="text" name="username"
          class="font-sans w-full p-2.5 sm:p-3
                 border border-[#A4B3A6] rounded-xl
                 focus:outline-none focus:ring-2
                 focus:ring-[#52796F] bg-white
                 text-sm sm:text-base"
          placeholder="Enter your username" />
      </div>

      <div>
        <label
          class="font-sans block mb-1 text-sm sm:text-base
                 font-semibold text-[#2F3E46]">
          Password
        </label>
        <input
          type="password" name="password"
          class="font-sans w-full p-2.5 sm:p-3
                 border border-[#A4B3A6] rounded-xl
                 focus:outline-none focus:ring-2
                 focus:ring-[#52796F] bg-white
                 text-sm sm:text-base"
          placeholder="Enter your password" />
      </div>

      <button
        type="submit" name="submit"
        class="font-sans w-full bg-[#52796F]
               hover:bg-[#354F52] text-white
               py-2.5 sm:py-3 rounded-xl
               font-semibold transition shadow-lg
               text-sm sm:text-base">
        Login
      </button>
    </form>

    <div
      class="font-sans text-center mt-4 sm:mt-6
             text-sm sm:text-base text-[#2F3E46]">
      Don't have an account?
      <a href="create-account.php"
        class="text-[#52796F] font-semibold hover:underline">
        Create Account
      </a>
    </div>
  </div>
</body>


</html>