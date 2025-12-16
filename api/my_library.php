<?php
session_start();
require_once "db.php";
include 'navbar.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
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
              "primary-bg": "#FEFAF1",
              "accent-dark": "#1D5C63",
              "text-dark": "#333333",
              "light-gray": "#EAEAEA",
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
    </style>

    <link
      href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap"
      rel="stylesheet"
    />
  </head>
  <body class="font-sans bg-primary-bg text-text-dark leading-relaxed">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
      

      <hr class="border-t border-light-gray mb-6 mt-0 sm:mt-0" />

      <div class="pb-12">
        <div
          class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center mb-6 space-y-4 sm:space-y-0"
        >
          <div id="status-filters" class="flex flex-wrap gap-2 sm:space-x-4">
            <button
              data-filter="all"
              class="filter-btn text-xs sm:text-sm font-semibold py-1 px-3 rounded-full border border-transparent transition active-filter"
            >
              All
            </button>
            <button
              data-filter="finished"
              class="filter-btn text-xs sm:text-sm font-semibold py-1 px-3 rounded-full border border-transparent transition hover:border-accent-dark"
            >
              Finished
            </button>
            <button
              data-filter="reading"
              class="filter-btn text-xs sm:text-sm font-semibold py-1 px-3 rounded-full border border-transparent transition hover:border-accent-dark"
            >
              Currently Reading
            </button>
            <button
              data-filter="to_read"
              class="filter-btn text-xs sm:text-sm font-semibold py-1 px-3 rounded-full border border-transparent transition hover:border-accent-dark"
            >
              To Read
            </button>
          </div>

          <button
            id="open-modal-btn"
            class="bg-accent-dark text-white py-2 px-4 rounded-md font-semibold text-sm hover:bg-[#0E3C40] transition w-full sm:w-auto"
          >
            + Add New Book
          </button>
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
      <div
        class="bg-primary-bg p-6 sm:p-8 rounded-xl shadow-2xl w-full sm:w-11/12 max-w-lg transform duration-300 max-h-[90vh] overflow-y-auto"
      >
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

    <div id="toast" class="fixed top-5 right-5 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transition-opacity duration-300 z-50">
  <!-- notif gabisa add soalnya ga ada di db -->
</div>

      <div
      id="edit-status-modal"
      class="hidden fixed inset-0 z-40 overflow-auto bg-black bg-opacity-40 flex items-center justify-center p-4"
    >
      <div
        class="bg-primary-bg p-6 sm:p-8 rounded-xl shadow-2xl w-full sm:w-11/12 max-w-lg transform duration-300 max-h-[90vh] overflow-y-auto"
      >
        <span
          class="close-btn text-gray-500 hover:text-gray-800 text-3xl font-bold float-right cursor-pointer"
          >&times;</span
        >
        <h2
          id="modal-title"
          class="font-serif text-xl sm:text-2xl text-accent-dark mb-4"
        >
          Edit Book Status
        </h2>

        <form id="edit-status-form">
          <input type="hidden" id="book-idEdit" name="id" />
          <input type="hidden" id="book-id-selectedEdit" name="book_id">

          <div class="mb-4">
            <label for="judul" class="block mb-1 font-semibold"
              >Book Title:</label
            >
            <input
              type="text"
              id="judulEdit"
              name="judul"
              required
              class="w-full p-2 border border-light-gray rounded-md box-border focus:ring-accent-dark focus:border-accent-dark"
              readonly
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
              id="penulisEdit"
              name="penulis"
              required
              class="w-full p-2 border border-light-gray rounded-md box-border focus:ring-accent-dark focus:border-accent-dark"
              readonly
              />
          </div>
          <div class="mb-4">
            <label for="status" class="block mb-1 font-semibold">Status:</label>
            <select
              id="statusEdit"
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
            Update
          </button>   
        </form>  
      </div>
    </div>

    <!-- BOOK DETAILS MODAL -->
     
<div id="details-modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center p-4 z-30">
    <div class="bg-primary-bg rounded-2xl shadow-2xl w-full max-w-3xl h-[80vh] overflow-y-auto p-6 relative">

        <span id="close-details" class="absolute right-6 top-3 text-gray-600 hover:text-gray-900 text-3xl cursor-pointer font-light" onclick="closeModal(document.getElementById('details-modal'))">&times;</span>

        <div class="flex flex-col sm:flex-row gap-6 mb-6">
            
            <div class="flex-shrink-0 w-full sm:w-1/3 max-w-[200px] mx-auto sm:mx-0">
                <img id="details-cover" class="w-full h-80 object-cover rounded-xl shadow-lg" alt="Book Cover">
            </div>

            <div class="flex-grow pt-4">
                <h2 id="details-title" class="font-serif text-3xl font-bold text-accent-dark mb-1"></h2>
                <p id="details-author" class="text-lg text-gray-700 mb-3">By </p>
                <p id="details-rating" class="font-semibold text-xl text-yellow-600 mb-6 flex items-center">
                    <i class="fas fa-star mr-1"></i> 5.0 / 5.0
                </p>
                
                <!-- btn-rate-book -->
                <button id="btn-rate-modal" class=" hidden bg-accent-dark text-white py-2 px-4 rounded-md font-semibold text-sm hover:bg-[#0E3C40] transition">
                    Rate This Book
                </button>
            </div>
        </div>

        <hr class="border-t border-light-gray my-6">

        <h3 class="font-serif text-xl font-bold text-accent-dark mb-2">Synopsis</h3>
        <p id="details-synopsis" class="text-gray-700 mb-8 leading-relaxed"></p>

        <h3 class="font-serif text-xl font-bold text-accent-dark mb-4">Reviews</h3>
        
        <form id="rating-form" class="bg-light-gray p-6 rounded-xl shadow-inner mb-8 border border-gray-200">
            <h4 class="font-serif text-xl font-bold text-accent-dark mb-4">Add Your Rating & Review</h4>
            
            <div class="flex flex-col md:flex-row gap-4 mb-4">
                
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="user-rating">Rating (1-5):</label>
                    <!-- <input type="number" id="user-rating" min="0" max="5" step="0.5" placeholder="0.0 - 5.0"
                        class="w-full p-3 border border-light-gray rounded-lg focus:ring-accent-dark focus:border-accent-dark transition shadow-sm"> -->

                        <!-- input star -->

                          <div class="flex-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-3" for="user-rating">Rating:</label>
                    <!-- STAR RATING INPUT -->
                    <div id="star-rating" class="flex flex-row items-center gap-2 md:flex-col md:items-start md:gap-1" >
                        <input type="hidden" id="user-rating" required>

                        <div class="flex">
                            <div class="star" data-value="1">
                                <i class="far fa-star star-empty"></i>
                                <i class="fas fa-star star-half"></i>
                                <i class="fas fa-star star-full"></i>
                            </div>
                            <div class="star" data-value="2">
                                <i class="far fa-star star-empty"></i>
                                <i class="fas fa-star star-half"></i>
                                <i class="fas fa-star star-full"></i>
                            </div>
                            <div class="star" data-value="3">
                                <i class="far fa-star star-empty"></i>
                                <i class="fas fa-star star-half"></i>
                                <i class="fas fa-star star-full"></i>
                            </div>
                            <div class="star" data-value="4">
                                <i class="far fa-star star-empty"></i>
                                <i class="fas fa-star star-half"></i>
                                <i class="fas fa-star star-full"></i>
                            </div>
                            <div class="star" data-value="5">
                                <i class="far fa-star star-empty"></i>
                                <i class="fas fa-star star-half"></i>
                                <i class="fas fa-star star-full"></i>
                            </div>
                        </div>

                        <span id="rating-preview" class="ml-2 md-mt-1 text-sm text-gray-600">
                            0.0 / 5.0
                        </span>
                    </div>
                </div>

                <!-- input end -->

                </div>
                
                <div class="flex-1 md:flex-grow-[2]"> <label class="block text-sm font-semibold text-gray-700 mb-1" for="user-review">Your Review:</label>
                    <textarea id="user-review" rows="3"
                            class="w-full p-3 border border-light-gray rounded-lg focus:ring-accent-dark focus:border-accent-dark transition shadow-sm resize-none"></textarea>
                </div>
            </div>
            
            <button type="submit" class="bg-accent-dark text-white py-2 px-6 rounded-md font-semibold hover:bg-[#0E3C40] transition shadow-md">
                Submit Review
            </button>
        </form>

        <div id="details-reviews" class="space-y-4 mx-3"></div>

    </div>
 </div> 


    <div
      id="delete-modal"
      class="hidden fixed inset-0 z-20 overflow-auto bg-black bg-opacity-40 flex items-center justify-center p-4"
    >
      <div
        class="bg-white p-6 sm:p-8 rounded-xl shadow-2xl w-full max-w-sm text-center transform duration-300"
      >
        <h3
          class="font-serif text-xl sm:text-2xl text-red-600 mb-4 border-b border-light-gray pb-2"
        >
          Confirm Deletion
        </h3>

        <p class="mb-6 text-gray-700 text-sm sm:text-base">
          Are you sure you want to permanently delete the book "<strong
            id="book-title-to-delete"
            class="font-semibold italic text-lg text-text-dark"
          ></strong
          >"?
        </p>

        <div class="flex justify-center space-x-4">
          <button
            id="confirm-delete-btn"
            class="bg-red-600 text-white py-2 px-4 rounded-md font-semibold hover:bg-red-700 transition"
          >
            Yes, Delete
          </button>
          <button
            id="cancel-delete-btn"
            class="bg-gray-300 text-gray-700 py-2 px-4 rounded-md font-semibold hover:bg-gray-400 transition"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>

  </body>
</html>


    <script>
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

      // Mobile Menu Elements
      const menuButton = document.getElementById("menu-button");
      const mobileMenu = document.getElementById("mobile-menu");

      // Event listener untuk Mobile Menu (NEW)
      menuButton.addEventListener("click", () => {
        mobileMenu.classList.toggle("hidden");
      });

      let currentFilter = "all";
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

  // load user rating

  async function loadUserRating(bookId) {
    const res = await fetch(`get_user_rating.php?book_id=${bookId}&user_id=${currentUserId}`);
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
       const res = await fetch("get_reading_lists.php");

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

    const cover = book.book_cover
      ? `<img src="../${book.book_cover}" class="w-full h-full object-cover"
      onerror="this.src='../images/default-book-cover.png'">`
      : `<span class="text-sm text-gray-500">No Cover</span>`;

    bookGrid.innerHTML += `
      <div class="bg-white rounded-lg shadow-md p-5 flex flex-col">
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


               <button 
          data-book-id="${book.book_id}"
          onclick="openDetailsModal(${book.book_id})" class="btn-rate-book bg-accent-dark text-white py-2 px-4 rounded-md font-semibold text-xs hover:bg-[#0E3C40] transition">
                  Details
                </button>


                </div>
      </div>
    `;
  });
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
          const res = await fetch(`get_book_details.php?id=${id}`);
          
          const text = await res.text();
          console.log(text);
          
          const data = JSON.parse(text);

          // document.getElementById("details-cover").src =
          //   data.book_cover || "images/default-book-cover.png";

          const coverImg = document.getElementById("details-cover");
            coverImg.src = data.book_cover ? `../${data.book_cover}` : "../images/default-book-cover.png";
            coverImg.onerror = () => {
                coverImg.src = "../images/default-book-cover.png";
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
                  <div class="border p-3 rounded-lg">
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

        const res = await fetch("rate_book.php", {
            method: "POST",
            body: formData,
        });

        // const text = await res.text();
        // console.log(text);
        // console.log("Book ID: ", bookId);

        const data = await res.json();

        if (!data.success) {
            alert(data.message || "Failed to submit review");
            return;
        }

        // After submit:
        document.getElementById("rating-form").classList.add("hidden");
        document.getElementById("btn-rate-modal").classList.add("hidden");
        //document.getElementById("btn-rate-book").classList.add("hidden");

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

  const res = await fetch("add_to_readlist.php", {
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

  const res = await fetch("edit_status.php", {
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
  const res = await fetch("deleteBook_readingList.php", {
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
  filterBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      filterBtns.forEach((b) =>
        b.classList.remove(
          "active-filter",
          "border-accent-dark",
          "bg-accent-dark/10"
        )
      );
      this.classList.add(
        "active-filter",
        "border-accent-dark",
        "bg-accent-dark/10"
      );

      currentFilter = this.dataset.filter;
      renderBooks();
    });
  });

  // Buka tutup modal
  function openModal(modalEl) {
    modalEl.classList.remove("hidden");
  }

  function closeModal(modalEl) {
    modalEl.classList.add("hidden");
    //form.reset();
  }


  //ini buka tutup modal function:
      // Add Book modal (open)
      openModalBtn.addEventListener("click", () => openCreateModal());

      // Tutup modal ------> buat fungsi buat semua modal sekalian
      closeBtns.forEach((btn) =>
        btn.addEventListener("click", () => {
          closeModal(bookModal);
          closeModal(deleteModal);
          closeModal(editStatusModal);
        })
      );

      window.addEventListener("click", (event) => {
        if (event.target == bookModal) closeModal(bookModal);
        if (event.target == editStatusModal) closeModal(editStatusModal);
        if (event.target == deleteModal) closeModal(deleteModal);
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

  const res = await fetch(`search_books.php?q=${encodeURIComponent(q)}`);
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
            '.filter-btn[data-filter="Semua"]'
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
            '.filter-btn[data-filter="Semua"]'
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




    </script>