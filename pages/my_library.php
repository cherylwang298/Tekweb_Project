<?php

require_once "config/koneksi.php";
include "partials/navbar.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Biblios - Library Tracker</title>

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
               'primary-bg': '#F7F4EB',
                'accent-dark': '#52796F',
               'text-dark': '#2F3E46',
                'light-gray': '#E4E1D8',
                 'accent-hover': '#354F52',


              
              success: "#3C763D",
              "success-bg": "#D6E7D2",
              info: "#0E3C40",
              "info-bg": "#B2D8D8",
              warning: "#8A6D3B",
              "warning-bg": "#F8E3C5",
            },
            fontFamily: {
              serif: ["Playfair Display", "serif"],
              sans: ["Inter", "sans-serif"],
            },
          },
        },
      };
    </script>

     </script>
    <style>
        @keyframes blobMove1 { 0% { transform: translate(0, 0) scale(1); } 50% { transform: translate(40px, -40px) scale(1.1); } 100% { transform: translate(0, 0) scale(1); } }
        @keyframes blobMove2 { 0% { transform: translate(0, 0) scale(1); } 50% { transform: translate(-50px, 30px) scale(1.05); } 100% { transform: translate(0, 0) scale(1); } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.4); }
    </style>


<style>
        .star {
            position: relative;
            width: 32px;
            height: 32px;
            cursor: pointer;
            display: inline-block;
        }

        .star i {
            position: absolute;
            inset: 0;
            font-size: 1.75rem;
            line-height: 1;
        }

        /* empty outline */
        .star-empty {
            color: #d1d5db;
        }

        /* half fill */
        .star-half {
            color: #facc15;
            clip-path: inset(0 50% 0 0);
            display: none;
        }

        /* full fill */
        .star-full {
            color: #facc15;
            display: none;
        }

        #review-toast{
          backdrop-filter: none !important;
          filter: none !important;
        }
    </style>

    <link
      href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap"
      rel="stylesheet"
    />
  </head>
  
<body class="bg-primary-bg font-sans text-text-dark min-h-screen relative overflow-x-hidden">


<!-- BACKGROUND BLOBS -->
<div class="fixed top-0 left-0 w-80 h-80 bg-[#B7D1C3] rounded-full blur-3xl opacity-40 -z-10"
     style="animation: blobMove1 20s ease-in-out infinite;"></div>
<div class="fixed bottom-0 right-0 w-[30rem] h-[30rem] bg-[#84A98C] rounded-full blur-3xl opacity-30 -z-10"
     style="animation: blobMove2 26s ease-in-out infinite;"></div>
<div class="fixed top-1/3 right-10 w-64 h-64 bg-[#E9EDC9] rounded-full blur-3xl opacity-30 -z-10"
     style="animation: blobMove3 18s ease-in-out infinite;"></div>

<div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      

      <hr class="border-t border-light-gray mb-6 mt-0 sm:mt-0" />

     

    <!-- STATUS FILTER (STYLE SAMA DENGAN RATING FILTER) -->
   
  <div class="pb-8">
  <div class="flex items-center mb-6">

    <div
      id="status-filters"
      class="flex items-center gap-1.5
             bg-white/60 backdrop-blur-md
             p-2 rounded-2xl
             border border-light-gray
             shadow-sm"
    >

      <button
        data-filter="all"
        class="filter-btn
               px-4 py-2.5 rounded-xl
               text-sm font-bold
               transition-all
               bg-accent-dark text-white
               hover:scale-105 active:scale-95">
        All
      </button>

      <button
        data-filter="to_read"
        class="filter-btn
               px-4 py-2.5 rounded-xl
               text-sm font-bold
               text-gray-500
               hover:bg-white hover:text-accent-dark
               transition-all
               hover:scale-105 active:scale-95">
        To Read
      </button>

      <button
        data-filter="finished"
        class="filter-btn
               px-4 py-2.5 rounded-xl
               text-sm font-bold
               text-gray-500
               hover:bg-white hover:text-accent-dark
               transition-all
               hover:scale-105 active:scale-95">
        Finished
      </button>

      <button
        data-filter="reading"
        class="filter-btn
               px-4 py-2.5 rounded-xl
               text-sm font-bold
               text-gray-500
               hover:bg-white hover:text-accent-dark
               transition-all
               hover:scale-105 active:scale-95">
        Currently Reading
      </button>

      

    </div>
  </div>
</div>


        <main
          id="book-grid"
          class="grid gap-8 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
        ></main>
      </div>
    </div>

    <div
      id="book-modal"
      class="hidden fixed inset-0 z-10 overflow-auto bg-black bg-opacity-40 flex items-center justify-center p-4"
    >
     <div class="bg-white/90 backdrop-blur-xl
            rounded-[2.5rem] shadow-2xl
            w-full max-w-3xl max-h-[85vh] overflow-y-auto
            relative border border-white/40 p-8">

        <span
          class="close-btn text-gray-500 hover:text-gray-800 text-3xl font-bold float-right cursor-pointer"
          >&times;</span
        >
        <h2
          id="modal-title"
          class="font-serif text-xl sm:text-2xl text-accent-dark mb-4"
        >
          Add New Book
        </h2>

        <form id="book-form">
          <input type="hidden" id="book-id" name="id" />
          <input type="hidden" id="book-id-selected" name="book_id">

          <div class="mb-4">
            <label for="judul" class="block mb-1 font-semibold"
              >Book Title:</label
            >
            <input
              type="text"
              id="judul"
              name="judul"
              required
              class="w-full p-2 border border-light-gray rounded-md box-border focus:ring-accent-dark focus:border-accent-dark"
            />
                <ul
              id="title-dropdown"
              class="border border-light-gray rounded-md bg-white hidden absolute z-50 w-full"
            ></ul>
          </div>
          <div class="mb-4">
            <label for="penulis" class="block mb-1 font-semibold"
              >Author:</label
            >
            <input
              type="text"
              id="penulis"
              name="penulis"
              required
              class="w-full p-2 border border-light-gray rounded-md box-border focus:ring-accent-dark focus:border-accent-dark"
            />
          </div>
          <div class="mb-4">
            <label for="status" class="block mb-1 font-semibold">Status:</label>
            <select
              id="status"
              name="status"
              class="w-full p-2 border border-light-gray rounded-md box-border focus:ring-accent-dark focus:border-accent-dark"
            >
              <option value="to_read">To Read</option>
              <option value="reading">Currently Reading</option>
              <option value="finished">Finished</option>
            </select>
          </div>

          <button
            type="submit"
            class="bg-accent-dark text-white py-3 px-4 rounded-md font-semibold text-lg w-full hover:bg-[#0E3C40] transition"
          >
            Save Book
          </button>   
        </form>
      </div>
    </div>

    <div id="toast" class="fixed top-5 right-5 z-[1000] pointer-events-none bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transition-opacity duration-300 z-50">
  <!-- notif gabisa add soalnya ga ada di db -->
</div>


    <!-- <div id="review-toast" class = "fixed top-5 right-5 z-[9999] pointer-events-none bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transition-opacity duration-300">
      </div> -->
      
      <div id="edit-status-modal"
    class="hidden fixed inset-0 z-[70] flex items-center justify-center p-4 top-[-15rem] md:top-0">

    <!-- BACKDROP -->
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

    <!-- MODAL -->
    <div
        class="relative
               bg-white/90 backdrop-blur-xl
               rounded-[2.5rem] shadow-2xl
               w-full max-w-3xl max-h-[85vh] overflow-y-auto
               border border-white/40">

        <!-- CLOSE -->
        <span class="close-btn
            absolute right-6 top-6
            text-gray-400 hover:text-gray-900
            text-3xl cursor-pointer z-10"
            onclick="closeModal(document.getElementById('edit-status-modal'))">
            &times;
        </span>

        <div class="p-8 lg:p-10">

            <!-- HEADER -->
            <div class="mb-8">
                <h2 class="font-serif text-3xl font-extrabold mb-1">
                    Edit Book Status
                </h2>
                <p class="text-gray-500 text-sm">
                    Update your reading progress
                </p>
            </div>

            <hr class="my-8 border-0 h-[2px]
                bg-gradient-to-r from-transparent via-gray-300/80 to-transparent">

            <!-- FORM (LOGIC TETAP) -->
            <form id="edit-status-form"
                class="bg-white/70 backdrop-blur-md
                       border border-white/40
                       rounded-2xl p-6">

                <input type="hidden" id="book-idEdit" name="id">
                <input type="hidden" id="book-id-selectedEdit" name="book_id">

                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-1">
                        Book Title
                    </label>
                    <input
                        id="judulEdit"
                        name="judul"
                        readonly
                        class="w-full p-3 rounded-xl border
                               bg-gray-200 text-gray-600">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-1">
                        Author
                    </label>
                    <input
                        id="penulisEdit"
                        name="penulis"
                        readonly
                        class="w-full p-3 rounded-xl border
                               bg-gray-200 text-gray-600">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold mb-1">
                        Status
                    </label>
                    <select
                        id="statusEdit"
                        name="status"
                        class="w-full p-3 rounded-xl border
                               focus:ring-2 focus:ring-accent-dark">
                        <option value="to_read">To Read</option>
                        <option value="reading">Currently Reading</option>
                        <option value="finished">Finished</option>
                    </select>
                </div>

                <div class="flex justify-center">
                    <button
                        type="submit"
                        class="py-3 px-8 bg-accent-dark text-white rounded-2xl font-bold text-sm hover:bg-accent-hover transition-all shadow-md active:scale-95">
                        Update
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


    <!-- BOOK DETAILS MODAL -->
     <div id="details-modal"
    class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm
           flex items-center justify-center p-4 z-[100]">

    <div
        class="bg-white/90 backdrop-blur-xl
               rounded-[2.5rem] shadow-2xl
               w-full max-w-3xl h-[60vh] sm:h-auto max-h-[60vh] sm:max-h-[80vh] overflow-y-auto
               relative border border-white/40 top-[-8rem] sm:top-[-8rem] md:top-0">

        <!-- CLOSE -->
        <span id="close-details" class="absolute right-6 top-3 text-gray-600 hover:text-gray-900 text-3xl cursor-pointer font-light" 
        onclick="closeModal(document.getElementById('details-modal'))">&times;</span>

        <div class="p-8 lg:p-10">

            <!-- HEADER -->
            <div class="flex flex-col md:flex-row gap-8 mb-8">
                
                <!-- COVER -->
                <img id="details-cover"
                    class="w-full md:w-48 aspect-[3/4]
                           object-cover rounded-2xl shadow-lg"
                    alt="Book Cover">

                <!-- INFO -->
                <div class="flex-grow">

                    <!-- AVG RATING -->
                    <div id="details-rating"
                        class="inline-flex items-center gap-1
                               px-4 py-1.5
                               bg-yellow-400/10 text-yellow-700
                               rounded-full text-sm font-bold mb-3">
                        <i class="fas fa-star"></i> 0.0 / 5.0
                    </div>

                    <h2 id="details-title"
                        class="font-serif text-3xl font-extrabold mb-1">
                    </h2>

                    <p id="details-author"
                        class="text-lg text-gray-500 font-medium mb-5">
                    </p>


                        <button id="btn-rate-modal"
                            class="hidden bg-accent-dark text-white
                                   py-2.5 px-6 rounded-xl
                                   font-bold">
                            Rate This Book
                        </button>
                  
                </div>
            </div>

            <!-- DIVIDER -->
            <hr class="my-10 border-0 h-[2px]
                       bg-gradient-to-r
                       from-transparent via-gray-300/80 to-transparent">

            <!-- SYNOPSIS -->
            <h3 class="font-serif text-xl font-bold mb-2">Synopsis</h3>
            <p id="details-synopsis"
                class="text-gray-600 leading-relaxed mb-8">
            </p>

            <!-- REVIEWS -->
            <h3 class="font-serif text-xl font-bold mb-3">Reviews</h3>
            <div id="details-reviews" class="space-y-4 mb-10"></div>

            <!-- RATING FORM -->
            <form id="rating-form"
                class="hidden bg-white/70 backdrop-blur-md
                       border border-white/40
                       rounded-2xl p-6">

                <div id="star-rating" class="flex gap-1 mb-3">
                    <input type="hidden" id="user-rating" required>

                    <?php for ($i=1;$i<=5;$i++): ?>
                    <div class="star" data-value="<?= $i ?>">
                        <i class="far fa-star star-empty"></i>
                        <i class="fas fa-star star-half"></i>
                        <i class="fas fa-star star-full"></i>
                    </div>
                    <?php endfor; ?>
                </div>

                <span id="rating-preview"
                    class="block text-sm text-gray-600 mb-3">
                    Give your rating and review!
                </span>

                <textarea id="user-review"
                    rows="3"
                    class="w-full p-3 border rounded-xl mb-4 resize-none">
                </textarea>

                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-accent-dark text-white
                               px-6 py-2 rounded-xl
                               font-bold transition-all
                               hover:bg-accent-hover active:scale-95">
                        Submit Review
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


    <div 
  id="delete-modal" 
  class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-[100] p-4"
>
  <div class="bg-white/90 backdrop-blur-2xl rounded-[2.5rem] p-6 max-w-sm w-full text-center border border-white/50 shadow-2xl">
    
    <div class="w-14 h-14 bg-red-50 text-red-500 rounded-full flex items-center justify-center text-xl mx-auto mb-4">
      <i class="fas fa-trash-alt"></i>
    </div>

    <h3 class="text-lg font-bold mb-1">Confirm Deletion</h3>
    
    <p class="text-gray-500 text-sm mb-6">
      Are you sure you want to permanently delete 
      <strong id="book-title-to-delete" class="font-bold text-gray-800 italic"></strong>?
    </p>

    <div class="flex gap-2">
      <button 
        id="cancel-delete-btn" 
        class="flex-1 py-2.5 rounded-xl font-bold bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors"
      >
        Cancel
      </button>
      
      <button 
        id="confirm-delete-btn" 
        class="flex-1 py-2.5 rounded-xl font-bold bg-red-500 text-white hover:bg-red-600 transition-colors"
      >
        Yes, Delete
      </button>
    </div>

  </div>
</div>

  </body>
</html>


    <script>

      let currentFilter = "all";
      // DOM Elements
      const bookGrid = document.getElementById("book-grid");
      const bookModal = document.getElementById("book-modal");
      const deleteModal = document.getElementById("delete-modal");
      const form = document.getElementById("book-form");
      const modalTitle = document.getElementById("modal-title");
      const openModalBtn = document.getElementById("open-modal-btn");
      const closeBtns = document.querySelectorAll(".close-btn");
      const filterBtns = document.querySelectorAll(".filter-btn");
      const confirmDeleteBtn = document.getElementById("confirm-delete-btn");
      const cancelDeleteBtn = document.getElementById("cancel-delete-btn");
      const editStatusModal = document.getElementById('edit-status-modal');
      const editForm = document.getElementById('edit-status-form');
  
      const currentUserId = <?= json_encode($_SESSION['user_id']) ?>;

      const titleInput = document.getElementById("judul");
      const authorInput = document.getElementById("penulis");
      const dropdown = document.getElementById("title-dropdown");

      // // Mobile Menu Elements
      // const menuButton = document.getElementById("menu-button");
      // const mobileMenu = document.getElementById("mobile-menu");

      // // Event listener untuk Mobile Menu (NEW)
      // menuButton.addEventListener("click", () => {
      //   mobileMenu.classList.toggle("hidden");
      // });

      
      let bookIdToDelete = null;

      function showToast(message, type = "error") {
  const toast = document.getElementById("toast");
  toast.textContent = message;

  // ganti warna background sesuai type
  toast.className = `fixed top-5 right-5 px-4 py-2 rounded-lg shadow-lg opacity-100 transition-opacity duration-300 z-50 ${
    type === "error" ? "bg-red-500" : "bg-green-500"
  }`;

  // hide otomatis setelah 2.5 detik
  setTimeout(() => {
    toast.classList.remove("opacity-100");
    toast.classList.add("opacity-0");
  }, 2500);
}


//     function showReviewToast(message, type = "success") {
//   const toast = document.getElementById("review-toast");
//   toast.textContent = message;

//   // ganti warna background sesuai type
//   toast.className = `fixed top-5 right-5 px-4 py-2 rounded-lg shadow-lg opacity-100 transition-opacity duration-300 z-50 ${
//     type === "success" ? "bg-green-500" : "bg-red-500"
//   }`;

//   // hide otomatis setelah 2.5 detik
//   setTimeout(() => {
//     toast.classList.remove("opacity-100");
//     toast.classList.add("opacity-0");
//   }, 2500);
// }

  function showReviewToast(message, type = "success") {
  const toast = document.createElement("div");

  toast.className = `fixed top-5 right-5 px-4 py-2 rounded-lg shadow-lg opacity-0 transition-opacity duration-300 ${type === "error" ? "bg-red-500" : "bg-green-500"}`;

  toast.textContent = message;
  toast.style.zIndex = "2147483647";
  toast.style.backdropFilter = "none";
  toast.style.filter = "none";

  document.body.appendChild(toast);

  requestAnimationFrame(() => {
    toast.style.opacity = "1";
  });

  setTimeout(() => {
    toast.style.opacity = "0";
    setTimeout(() => toast.remove(), 300);
  }, 2500);
}

  // load user rating

  async function loadUserRating(bookId) {
    const res = await fetch(`/tekweb_project/api/users/get_user_rating.php?book_id=${bookId}&user_id=${currentUserId}`);
    const data = await res.json();

    const rateBtn = document.getElementById("btn-rate-modal");
    const ratingForm = document.getElementById("rating-form");


    if(!data || data.rating === null) {
      rateBtn.classList.remove("hidden");
      ratingForm.classList.add("hidden");
      return;
    }


    rateBtn.classList.add("hidden");
    //ratingForm.reset();
    ratingForm.classList.add("hidden");
  }



      // --- Utility Functions ---
     async function getBooks() {
       const res = await fetch("/tekweb_project/api/reading_list/get_reading_lists.php");

  if (!res.ok) {
    console.error("Failed to fetch reading list");
    return [];
  }

  return await res.json();
      }

      function getStatusClasses(status) {
        switch (status) {
          case "finished":
            return "bg-success-bg text-success border border-success";
          case "reading":
            return "bg-info-bg text-info border border-info";
          case "to_read":
            return "bg-warning-bg text-warning border border-warning";
          default:
            return "bg-light-gray text-gray-600 border border-gray-400";
        }
      }

      function displayStatus(status) {
        switch (status) {
          case "finished":
            return "Finished";
          case "reading":
            return "Reading";
          case "to_read":
            return "To Read";
          default:
            return "Unknown";
        }
      }

      function convertToBase64(file) {
        return new Promise((resolve, reject) => {
          const reader = new FileReader();
          reader.onload = () => resolve(reader.result);
          reader.onerror = reject;
          reader.readAsDataURL(file);
        });
      }

      function getUrlParameter(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        const regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
        const results = regex.exec(location.search);
        return results === null
          ? ""
          : decodeURIComponent(results[1].replace(/\+/g, " "));
      }

      // --- CRUD READ & Filter ---
      async function renderBooks() {
       const allBooks = await getBooks();

  const filteredBooks =
    currentFilter === "all"
      ? allBooks
      : allBooks.filter(book => book.status === currentFilter);

  bookGrid.innerHTML = "";

  if (filteredBooks.length === 0) {
    bookGrid.innerHTML = `
      <p class="col-span-full text-center p-12 text-gray-500">
        No books in this category.
      </p>`;
    return;
  }

  filteredBooks.forEach(book => {
    const statusClasses = getStatusClasses(book.status);

//     let coverSrc = "";

// if (!book.book_cover || book.book_cover.trim() === "") {
//     coverSrc = "/tekweb_project/images/default-book-cover.png";
// } else if (book.book_cover.startsWith("http")) {
//     coverSrc = book.book_cover; // URL eksternal
// } else if (book.book_cover.startsWith("/")) {
//     coverSrc = "/tekweb_project" + book.book_cover; // path absolut
// } else {
//     coverSrc = "/tekweb_project/" + book.book_cover; // path relatif
// }


    // const cover = book.book_cover
    //   ? `<img src="/tekweb_project/${coverPath}" class="w-full h-full object-cover"
    //   onerror="this.src='/tekweb_project/images/default-book-cover.png'">`
    //   : `<span class="text-sm text-gray-500">No Cover</span>`;

    const coverSrc = getCoverSrc(book);

    const cover = `<img src="${coverSrc}" class="w-full h-full object-cover"
               onerror="this.src='/tekweb_project/images/default-book-cover.png'">`;

    // const cover = `<img src="tekweb_project/${book.book_cover}" class="w-full h-full object-cover"
    //             onerror="this.src='/tekweb_project/images/default-book-cover.png'">`;
    
               console.log("book: ", book.title, "Cover:", coverSrc);

    bookGrid.innerHTML += `
      <div
  class="group relative bg-white/70 backdrop-blur-sm
         rounded-[2rem] p-5 shadow
         transition-all duration-300 ease-out
         hover:-translate-y-2 hover:shadow-2xl
         active:scale-95 cursor-pointer"
  onclick="openDetailsModal(${book.book_id})">

        <div class="text-xs font-semibold px-3 py-1 rounded-md w-fit ${statusClasses}">
          ${displayStatus(book.status)}
        </div>

        <div class="w-full h-40 bg-light-gray rounded-md my-3 flex items-center justify-center overflow-hidden">
          ${cover}
        </div>

        <h3 class="font-serif text-xl text-accent-dark">
          ${book.title}
        </h3>

        <p class="text-sm text-gray-600 mb-2">
          By ${book.author}
        </p>

        <div class="mt-auto mx-auto pt-3 flex gap-3">
        ${book.status === "finished"
            ? `<span class="text-gray-400 italic text-sm">Book finished.</span>`
            : `
              <button onclick="openEditModal(${book.reading_id}); event.stopPropagation()"
            class="text-gray-500 hover:text-accent-dark transition mr-3">
              <i class="fas fa-pencil-alt text-sm"></i>
            Edit
          </button>

          <button onclick="openDeleteModal(${book.reading_id}); event.stopPropagation()"
            class="text-gray-500 hover:text-red-600 transition mr-4">
             <i class="fas fa-trash-alt text-sm"></i>
            Delete
          </button>
            `
        }
      </div>

      </div>
    `;
  });
      }

      function getCoverSrc(book) {
    if (!book.book_cover || book.book_cover.trim() === "") {
        return "/tekweb_project/images/default-book-cover.png";
    }

    // URL eksternal
    if (book.book_cover.startsWith("http://") || book.book_cover.startsWith("https://")) {
        return book.book_cover;
    }

    // Path absolut mulai dengan /
    if (book.book_cover.startsWith("/")) {
        return "/tekweb_project" + book.book_cover;
    }

    // Path relatif (../ atau langsung nama file)
    // Encode URI untuk spasi/karakter aneh
    return "/tekweb_project/" + encodeURI(book.book_cover);
}


      // function openDetailsModal(id){
      //  const modal = document.getElementById("details-modal");
      //   modal.dataset.bookId = id;
      //   modal.classList.remove("hidden");

      //   loadUserRating(id);
      // }

      async function openDetailsModal(id){
        const modal = document.getElementById("details-modal");
        modal.dataset.bookId = id;
      

        try{
          const res = await fetch(`/tekweb_project/api/books/get_book_details.php?id=${id}`);
          
          const text = await res.text();
          console.log(text);
          
          const data = JSON.parse(text);

          // document.getElementById("details-cover").src =
          //   data.book_cover || "images/default-book-cover.png";

          const coverImg = document.getElementById("details-cover");
            coverImg.src = data.book_cover ? `/tekweb_project/${data.book_cover}` : "/tekweb_project/images/default-book-cover.png";
            coverImg.onerror = () => {
                coverImg.src = "/tekweb_project/images/default-book-cover.png";
            }

            document.getElementById("details-title").textContent = data.title;
            document.getElementById("details-author").textContent = "By " + data.author;

            const avg = data.avg_rating ? parseFloat(data.avg_rating).toFixed(1) : "0.0";
            document.getElementById("details-rating").innerHTML = `<i class="fas fa-star mr-1"></i> ${avg} / 5.0`;

            document.getElementById("details-synopsis").textContent = data.synopsis?.trim() ? data.synopsis : "Synopsis not available.";

            const reviewContainer = document.getElementById("details-reviews");
            reviewContainer.innerHTML = "";

            if(!data.reviews || data.reviews.length === 0) {
              reviewContainer.innerHTML = "";

            } else {
              data.reviews.forEach(r => {
                reviewContainer.innerHTML += `
                  <div class="bg-white/60 backdrop-blur-md
            p-4 rounded-2xl border border-white/50 shadow-sm">

                    <p class="font-semibold">⭐ ${r.rating} — ${r.username}</p>
                    <p class="text-gray-700">${r.review}</p>
                    <p class="text-xs text-gray-500">${r.created_at}</p>
                  </div>
                `;
              });
            }

            loadUserRating(id);

              modal.classList.remove("hidden");
        } catch (error) {
          console.error(error);
          alert("Failed to load book details")
        }
      }



      document.getElementById("btn-rate-modal").addEventListener("click", () => {
        document.getElementById("rating-form").classList.remove("hidden");
        initHalfStarRating(); //add baru
      });

      // submit
 document.getElementById("rating-form").addEventListener("submit", async (e) => {
        e.preventDefault();

        const ratingInput = document.getElementById("user-rating").value;
        const rating = ratingInput === "" ? null : parseFloat(ratingInput);
        const review = document.getElementById("user-review").value;
        const bookId = document.getElementById("details-modal").dataset.bookId;

        const formData = new FormData();
        //formData.append("user_id", currentUserId);
        formData.append("book_id", bookId);
        formData.append("rating", rating);
        formData.append("review", review);

        const res = await fetch("/tekweb_project/api/books/rate_book.php", {
            method: "POST",
            body: formData,
        });

        // const text = await res.text();
        // console.log(text);
        // console.log("Book ID: ", bookId);

        const data = await res.json();

        if (!data.success) {
            //alert(data.message || "Failed to submit review");
            showToast("Failed to submit review", "error");
            return;
        }

        // After submit:
        document.getElementById("rating-form").classList.add("hidden");
        document.getElementById("btn-rate-modal").classList.add("hidden");
        //document.getElementById("btn-rate-book").classList.add("hidden");

        showReviewToast("Review submitted successfully!", "success");

        // Reload modal content
        openDetailsModal(bookId);
    });

      // end



      //disuru komen

      // bookGrid.addEventListener("click", (e)=> {
      //   const btn = e.target.closest(".btn-rate-book");
      //   if(!btn) return;

      //   const bookId = btn.dataset.bookId;

      //   const modal = document.getElementById("details-modal");
      //   modal.dataset.bookId = bookId;
      //   modal.classList.remove("hidden");

      //     loadUserRating(bookId);
      //   }
      // )

      // // --- CRUD CREATE
//craye
  form.addEventListener("submit", async function (e) {
  e.preventDefault();

  const formData = new FormData(form);
  for (const [key, value] of formData.entries()) {
  console.log(key, value);
}

  const res = await fetch("/tekweb_project/api/reading_list/add_to_readlist.php", {
    method: "POST",
    body: formData,
  });

  const result = await res.json();

  if (!result.success) {
    alert(result.message || "Failed to save book");
    showToast(result.message, "error")
    return;
  }

  showToast("Book added successfully", "success")
  closeModal(bookModal);
  renderBooks();
});

  // update status:
editForm.addEventListener("submit", async function(e) {
  e.preventDefault();
  const formData = new FormData(editForm);

  const res = await fetch("/tekweb_project/api/reading_list/edit_status.php", {
    method: "POST",
    body: formData
  });

  const result = await res.json();
  if (!result.success) {
    alert(result.message || "Failed to update book");
    return;
  }

  closeModal(editStatusModal);
  renderBooks();
});

      // --- CRUD DELETE (Menggunakan Modal) ---
  async function deleteBook(id) {
  const res = await fetch("/tekweb_project/api/reading_list/deleteBook_readingList.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id }),
  });

  const result = await res.json();

  if (!result.success) {
    // alert("Failed to delete book");
    showToast("Failed to delete book", "error")
    return;
  }

  showToast('Book deleted successfully', 'success')
  renderBooks();
}

  //fungsi delete book dari readlin g list + back
      confirmDeleteBtn.addEventListener("click", () => {
        if (bookIdToDelete !== null) {
          deleteBook(bookIdToDelete);
          closeModal(deleteModal);
        }
      });

      cancelDeleteBtn.addEventListener("click", () => {
        closeModal(deleteModal);
      });



  // Filtering sesuai status
  filterBtns.forEach(btn => {
  btn.addEventListener("click", () => {
    currentFilter = btn.dataset.filter;

    history.replaceState(
      null,
      "",
      `index.php?page=my_library&status=${currentFilter}`
    );

    setActiveFilterButton(currentFilter);
    renderBooks();
  });
});



function setActiveFilterButton(filter) {
  filterBtns.forEach(btn => {
    const isActive = btn.dataset.filter === filter;

    btn.classList.toggle("bg-accent-dark", isActive);
    btn.classList.toggle("text-white", isActive);

    btn.classList.toggle("text-gray-500", !isActive);
  });
}


document.addEventListener("DOMContentLoaded", () => {
  const statusFromUrl = getUrlParameter("status");

  // default state
  currentFilter = "all";

  if (statusFromUrl && ["finished", "reading", "to_read"].includes(statusFromUrl)) {
    currentFilter = statusFromUrl;
  }

  // update UI tab
  setActiveFilterButton(currentFilter);

  // render langsung sesuai state
  renderBooks();
});


  // Buka tutup modal
  function openModal(modalEl) {
    modalEl.classList.remove("hidden");
  }

  function closeModal(modalEl) {
    modalEl.classList.add("hidden");
    //form.reset();
  }

  // CLOSE MODAL VIA X BUTTON
document.getElementById("close-details").addEventListener("click", () => {
    document.getElementById("details-modal").classList.add("hidden");
    document.body.classList.remove("overflow-hidden");
});

// CLOSE MODAL WHEN CLICKING OUTSIDE CONTENT
document.getElementById("details-modal").addEventListener("click", (e) => {
    if (e.target.id === "details-modal") {
        e.currentTarget.classList.add("hidden");
        document.body.classList.remove("overflow-hidden");
    }
});



  //ini buka tutup modal function:
      // Add Book modal (open)
      openModalBtn.addEventListener("click", () => openCreateModal());

      // Tutup modal ------> buat fungsi buat semua modal sekalian
      document.querySelectorAll(".close-btn").forEach(btn => {
  btn.addEventListener("click", (e) => {
    const modal = btn.closest('[id$="modal"]');
    if (modal) closeModal(modal);
  });
});



      

      document.querySelectorAll('[id$="modal"]').forEach(modal => {
  modal.addEventListener("click", (e) => {
    if (e.target === modal) {
      closeModal(modal);
    }
  });
});

      // Modal Tambah (CREATE)
      function openCreateModal() {
        modalTitle.textContent = "Add New Book";
        form.reset();
        document.getElementById("book-id").value = "";
        openModal(bookModal);
      }

      // Modal Edit -> open
      async function openEditModal(id) {
        const books = await getBooks();
        const bookToEdit = books.find((book) => book.reading_id === id);

        if (!bookToEdit) return;

        modalTitle.textContent = "Edit Book: " + bookToEdit.title;
        document.getElementById("book-idEdit").value = bookToEdit.reading_id;
        document.getElementById("judulEdit").value = bookToEdit.title;
        document.getElementById("penulisEdit").value = bookToEdit.author;
        document.getElementById("statusEdit").value = bookToEdit.status;

        // if (bookToEdit.book_cover) {
        //   previewImg.src = bookToEdit.book_cover;
        //   coverPreview.classList.remove("hidden");
        // }

        openModal(editStatusModal);
      }


    //open delete modal
      async function openDeleteModal(id) {
        bookIdToDelete = id;
        const books = await getBooks();
        const book = books.find(b => b.reading_id === id);

        document.getElementById("book-title-to-delete").textContent =
        book ? book.title : "Unknown";

        openModal(deleteModal);
      }


  // aautocom
  titleInput.addEventListener("input", async () => {
  const q = titleInput.value.trim();

  if (q.length < 2) {
    dropdown.classList.add("hidden");
    dropdown.innerHTML = "";
    return;
  }

  const res = await fetch(`/tekweb_project/api/books/search_books.php?q=${encodeURIComponent(q)}`);
  const books = await res.json();

  dropdown.innerHTML = "";

  if (books.length === 0) {
    dropdown.classList.add("hidden");
    return;
  }

  books.forEach(book => {
    const li = document.createElement("li");
    li.textContent = `${book.title} — ${book.author}`;
    li.className = "px-3 py-2 hover:bg-light-gray cursor-pointer";

    li.onclick = () => {
  titleInput.value = book.title;
  authorInput.value = book.author;

  document.getElementById("book-id-selected").value = book.id;

  dropdown.classList.add("hidden");
};


    dropdown.appendChild(li);
  });

  dropdown.classList.remove("hidden");
});





      // Inisialisasi: Tampilkan semua buku saat halaman dimuat
      document.addEventListener("DOMContentLoaded", () => {
        // NEW LOGIC: Cek filter dari URL
        const filterFromUrl = getUrlParameter("filter");

        if (filterFromUrl) {
          currentFilter = filterFromUrl;

          // Hapus penyorotan default
          const allButton = document.querySelector(
            '.filter-btn[data-filter="all"]'
          );
          if (allButton) {
            allButton.classList.remove(
              "active-filter",
              "border-accent-dark",
              "bg-accent-dark/10"
            );
          }

          // Terapkan penyorotan pada tombol filter yang sesuai
          const targetButton = document.querySelector(
            `.filter-btn[data-filter="${currentFilter}"]`
          );
          if (targetButton) {
            targetButton.classList.add(
              "active-filter",
              "border-accent-dark",
              "bg-accent-dark/10"
            );
          }
        } else {
          // Logika default jika tidak ada filter di URL
          const allButton = document.querySelector(
            '.filter-btn[data-filter="all"]'
          );
          if (allButton) {
            allButton.classList.add(
              "active-filter",
              "border-accent-dark",
              "bg-accent-dark/10"
            );
          }
        }

        renderBooks();
      });






      // init half star


       function initHalfStarRating(containerId = "star-rating") {
        const container = document.getElementById(containerId);
        if (!container) return;

        const stars = container.querySelectorAll(".star");
        const hiddenInput = container.querySelector("#user-rating");
        const preview = container.querySelector("#rating-preview");

        let lockedRating = 0;

        function render(rating) {
            stars.forEach(star => {
                const value = parseInt(star.dataset.value);
                const empty = star.querySelector(".star-empty");
                const half = star.querySelector(".star-half");
                const full = star.querySelector(".star-full");

                empty.style.display = "block";
                half.style.display = "none";
                full.style.display = "none";

                if (rating >= value) {
                    empty.style.display = "none";
                    full.style.display = "block";
                } else if (rating === value - 0.5) {
                    empty.style.display = "block";
                    half.style.display = "block";
                }
            });

            preview.textContent = `${rating.toFixed(1)} / 5.0`;
        }

        stars.forEach(star => {
            const value = parseInt(star.dataset.value);

            star.addEventListener("mousemove", e => {
                const rect = star.getBoundingClientRect();
                const isLeft = e.clientX < rect.left + rect.width / 2;
                render(isLeft ? value - 0.5 : value);
            });

            star.addEventListener("mouseleave", () => {
                render(lockedRating);
            });

            star.addEventListener("click", e => {
                const rect = star.getBoundingClientRect();
                const isLeft = e.clientX < rect.left + rect.width / 2;

                lockedRating = isLeft ? value - 0.5 : value;
                hiddenInput.value = lockedRating;
                render(lockedRating);
            });
        });

        render(0);
    }

    function getUrlParameter(name) {
  const params = new URLSearchParams(window.location.search);
  return params.get(name);
} 


document.addEventListener("keydown", (e) => {
  if (e.key === "Escape") {
    document.querySelectorAll('[id$="modal"]').forEach(m => closeModal(m));
  }
});


    </script>