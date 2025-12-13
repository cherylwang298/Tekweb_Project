<?php
session_start();
include 'koneksi.php';
include 'navbar.php';

?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Biblios - Profil Pengguna</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              "primary-bg": "#FEFAF1",
              "accent-dark": "#1D5C63",
              "text-dark": "#333333",
              "light-gray": "#EAEAEA",
            },
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
      rel="stylesheet"
    />
  </head>
  <body class="font-sans bg-primary-bg text-text-dark leading-relaxed">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <hr class="border-t border-light-gray mb-6 mt-0 sm:mt-0" />

      <h1 class="font-serif text-2xl sm:text-3xl text-accent-dark mb-8">
        Pengaturan Akun dan Profil
      </h1>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-2 bg-white p-6 sm:p-8 rounded-lg shadow-lg">
          <h2
            class="font-serif text-xl sm:text-2xl text-accent-dark mb-4 border-b pb-2"
          >
            Informasi Akun
          </h2>

          <div class="mb-4">
            <p class="text-sm text-gray-500">Nama Pengguna:</p>
            <p class="font-semibold text-base sm:text-lg">PembacaSetia_99</p>
          </div>

          <div class="mb-4">
            <p class="text-sm text-gray-500">Email:</p>
            <p class="font-semibold text-base sm:text-lg">
              pembaca.setia@mail.com
            </p>
          </div>

          <button
            class="bg-gray-200 text-gray-700 py-2 px-4 rounded-md font-semibold hover:bg-gray-300 transition mt-4 text-sm sm:text-base"
          >
            Ubah Kata Sandi
          </button>
        </div>

        <div class="bg-white p-6 sm:p-8 rounded-lg shadow-lg">
          <h2
            class="font-serif text-xl sm:text-2xl text-accent-dark mb-4 border-b pb-2"
          >
            Pengaturan Lain
          </h2>

          <div
            class="flex justify-between items-center mb-4 text-sm sm:text-base"
          >
            <p>Mode Gelap (Dark Mode)</p>
            <label class="switch relative inline-block w-12 h-6">
              <input type="checkbox" class="opacity-0 w-0 h-0" disabled />
              <span
                class="slider round absolute cursor-pointer top-0 left-0 right-0 bottom-0 bg-gray-300 rounded-full transition duration-400 before:absolute before:content-[''] before:h-5 before:w-5 before:left-0.5 before:bottom-0.5 before:bg-white before:rounded-full before:transition duration-400"
              ></span>
            </label>
          </div>

          <button
            class="text-red-600 border border-red-600 py-2 px-4 rounded-md font-semibold hover:bg-red-50 transition mt-4 text-sm sm:text-base"
          >
            Keluar / Logout
          </button>
        </div>
      </div>
    </div>

    <script>
      // Mobile Menu Elements
      const menuButton = document.getElementById("menu-button");
      const mobileMenu = document.getElementById("mobile-menu");

      // Event listener untuk Mobile Menu (NEW)
      menuButton.addEventListener("click", () => {
        mobileMenu.classList.toggle("hidden");
      });
    </script>

    <style>
      :root {
        --color-accent-dark: #1d5c63; /* Define CSS Variable for Switch */
      }
      .switch input:checked + .slider {
        background-color: var(--color-accent-dark);
      }

      .switch input:checked + .slider:before {
        transform: translateX(24px);
      }
    </style>
  </body>
</html>
