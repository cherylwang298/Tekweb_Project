<?php
session_start();
<<<<<<<< HEAD:api/users/create-account.php
require_once "../../config/koneksi.php";
========
require_once "db.php";
>>>>>>>> compare-cheryl:api/create-account.php

if (isset($_POST['register'])) {

  $username = trim($_POST['username']);
  $name     = trim($_POST['name']);
  $email    = trim($_POST['email']);
  $password = $_POST['password'];
  $confirm  = $_POST['confirm-pass'];

  $error = '';

  if ($password !== $confirm) {
    $error = "Password dan Confirm Password tidak sama!";
  } else {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Cek username / email
    $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
      $error = "Username atau Email sudah terdaftar!";
    } else {
      // Insert
      $stmt = $conn->prepare(
        "INSERT INTO users (username, name, email, password_hash)
         VALUES (?, ?, ?, ?)"
      );
      $stmt->bind_param("ssss", $username, $name, $email, $password_hash);

      if ($stmt->execute()) {
        header("Location: login.php");
        exit;
      } else {
        $error = "Gagal membuat akun!";
      }
      $stmt->close();
    }
  }
}
$conn->close();


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create Account</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap"
    rel="stylesheet" />
  <style>
    body {
      font-family: "Inter", sans-serif;
    }

    .font-serif {
      font-family: "Playfair Display", serif;
    }

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


    ::-webkit-scrollbar {
      width: 14px;
    }

    ::-webkit-scrollbar-track {
      background: #F7F4EB;
      border-radius: 999px;
    }

    ::-webkit-scrollbar-thumb {
      background: linear-gradient(180deg,
          #84A98C,
          #52796F);
      border-radius: 999px;
      border: 2px solid #F7F4EB;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: linear-gradient(180deg,
          #52796F,
          #354F52);
    }

    html {
      scroll-behavior: smooth;
    }
  </style>
</head>

<body
  class="min-h-screen flex flex-col items-center justify-center
         p-8 sm:p-4 bg-[#F7F4EB] relative overflow-x-hidden">

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
        errorAlert.classList.add('opacity-0');
        errorAlert.remove();
      }, 2000);
    }
  </script>

  <div class="absolute inset-0 overflow-hidden -z-10 pointer-events-none">
    <div
      class="absolute -top-20 -left-20 w-60 h-60 sm:w-80 sm:h-80
         bg-[#B7D1C3] rounded-full blur-3xl opacity-50"
      style="animation: blobMove1 20s ease-in-out infinite;">
    </div>

    <div
      class="absolute -bottom-24 -right-24 w-72 h-72 sm:w-[22rem] sm:h-[22rem]
         bg-[#84A98C] rounded-full blur-2xl opacity-45"
      style="animation: blobMove2 26s ease-in-out infinite;">
    </div>

    <div
      class="absolute top-1/4 right-10 sm:right-20 w-44 h-44 sm:w-60 sm:h-60
         bg-[#E9EDC9] rounded-full blur-xl opacity-40"
      style="animation: blobMove3 18s ease-in-out infinite;">
    </div>

  </div>

  <div
    class="w-full max-w-sm sm:max-w-lg bg-white rounded-3xl shadow-2xl
           p-5 sm:p-8 border border-[#E4E1D8]">

    <h1 class="text-2xl sm:text-3xl font-serif font-bold
               text-center mb-5 sm:mb-7 text-[#2F3E46]">
      Create Account
    </h1>

    <form class="space-y-4 sm:space-y-5" method="POST" action="">

      <div>
        <label class="block mb-1 text-sm sm:text-md md:text-base font-medium text-[#2F3E46]">
          Username
        </label>
        <input
          type="text" name="username"
          class="w-full p-2 sm:p-2.5 border border-[#A4B3A6]
                 rounded-xl focus:outline-none focus:ring-2
                 focus:ring-[#52796F] text-sm sm:text-md"
          placeholder="Enter username" required />
      </div>

      <div>
        <label class="block mb-1 text-sm sm:text-md md:text-base font-medium text-[#2F3E46]">
          Full Name
        </label>
        <input
          type="text" name="name"
          class="w-full p-2 sm:p-2.5 border border-[#A4B3A6]
                 rounded-xl focus:outline-none focus:ring-2
                 focus:ring-[#52796F] text-sm sm:text-md"
          placeholder="Enter your full name" required />
      </div>

      <div>
        <label class="block mb-1 text-sm sm:text-md md:text-base font-medium text-[#2F3E46]">
          Email
        </label>
        <input
          type="email" name="email"
          class="w-full p-2 sm:p-2.5 border border-[#A4B3A6]
                 rounded-xl focus:outline-none focus:ring-2
                 focus:ring-[#52796F] text-sm sm:text-md"
          placeholder="Enter your email" required />
      </div>

      <div>
        <label class="block mb-1 text-sm sm:text-md md:text-base font-medium text-[#2F3E46]">
          Password
        </label>
        <input
          type="password" name="password"
          class="w-full p-2 sm:p-2.5 border border-[#A4B3A6]
                 rounded-xl focus:outline-none focus:ring-2
                 focus:ring-[#52796F] text-sm sm:text-md"
          placeholder="Enter password" required />
      </div>

      <div>
        <label class="block mb-1 text-sm sm:text-md md:text-base font-medium text-[#2F3E46]">
          Confirm Password
        </label>
        <input
          type="password" name="confirm-pass"
          class="w-full p-2 sm:p-2.5 border border-[#A4B3A6]
                 rounded-xl focus:outline-none focus:ring-2
                 focus:ring-[#52796F] text-sm sm:text-md"
          placeholder="Re-enter password" required />
      </div>

      <button
        type="submit" name="register"
        class="w-full bg-[#52796F] hover:bg-[#354F52]
               text-white py-2 sm:py-2.5 rounded-xl
               font-semibold transition shadow-lg
               text-sm sm:text-md md:text-base">
        Create Account
      </button>
    </form>

    <div class="text-center mt-4 sm:mt-5 text-[#2F3E46] text-sm sm:text-md md:text-base">
      Already have an account?
      <a href="login.php"
        class="text-[#52796F] font-semibold hover:underline">
        Login
      </a>
    </div>

  </div>
</body>


</html>