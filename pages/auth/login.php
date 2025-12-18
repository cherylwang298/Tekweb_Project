<?php
session_start();
require_once "../../config/koneksi.php";

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
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['name'] = $user['name'];

        header("Location: ../../index.php?page=home");
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

  <style>
    @keyframes blobMove1 {
      0% {
        transform: translate(0, 0) scale(1);
      }

      25% {
        transform: translate(60px, -40px) scale(1.1);
      }

      50% {
        transform: translate(-40px, 50px) scale(0.95);
      }

      75% {
        transform: translate(50px, 30px) scale(1.05);
      }

      100% {
        transform: translate(0, 0) scale(1);
      }
    }

    @keyframes blobMove2 {
      0% {
        transform: translate(0, 0) scale(1);
      }

      30% {
        transform: translate(-70px, 40px) scale(1.15);
      }

      60% {
        transform: translate(40px, -60px) scale(0.9);
      }

      100% {
        transform: translate(0, 0) scale(1);
      }
    }

    @keyframes blobMove3 {
      0% {
        transform: translateY(0) scale(1);
      }

      50% {
        transform: translateY(-80px) scale(1.1);
      }

      100% {
        transform: translateY(0) scale(1);
      }
    }
  </style>

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

  <div
    class="absolute top-0 left-0 w-60 h-60 sm:w-80 sm:h-80
         bg-[#B7D1C3] rounded-full blur-3xl opacity-50 -z-10"
    style="animation: blobMove1 20s ease-in-out infinite;">
  </div>


  <div
    class="absolute bottom-0 right-0 w-72 h-72 sm:w-[22rem] sm:h-[22rem]
         bg-[#84A98C] rounded-full blur-2xl opacity-45 -z-10"
    style="animation: blobMove2 26s ease-in-out infinite;">
  </div>


  <div
    class="absolute top-1/3 right-6 sm:right-16 w-44 h-44 sm:w-60 sm:h-60
         bg-[#E9EDC9] rounded-full blur-xl opacity-40 -z-10"
    style="animation: blobMove3 18s ease-in-out infinite;">
  </div>


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
      <a href="/tekweb_project/pages/auth/create-account.php"
        class="text-[#52796F] font-semibold hover:underline">
        Create Account
      </a>
    </div>
  </div>
</body>


</html>