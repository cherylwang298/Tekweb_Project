<?php
session_start();
include 'koneksi.php';

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
        errorAlert.classList.add('opacity-0');
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