<?php
session_start();
require_once "db.php";
include 'navbar.php';

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
              data-filter="Semua"
              class="filter-btn text-xs sm:text-sm font-semibold py-1 px-3 rounded-full border border-transparent transition active-filter"
            >
              All
            </button>
            <button
              data-filter="Sudah Dibaca"
              class="filter-btn text-xs sm:text-sm font-semibold py-1 px-3 rounded-full border border-transparent transition hover:border-accent-dark"
            >
              Finished
            </button>
            <button
              data-filter="Sedang Dibaca"
              class="filter-btn text-xs sm:text-sm font-semibold py-1 px-3 rounded-full border border-transparent transition hover:border-accent-dark"
            >
              Currently Reading
            </button>
            <button
              data-filter="Ingin Dibaca"
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
              <option value="Ingin Dibaca">To Read</option>
              <option value="Sedang Dibaca">Currently Reading</option>
              <option value="Sudah Dibaca">Finished</option>
            </select>
          </div>
          <div class="mb-4">
            <label for="catatan" class="block mb-1 font-semibold"
              >Notes (Optional):</label
            >
            <textarea
              id="catatan"
              name="catatan"
              rows="3"
              class="w-full p-2 border border-light-gray rounded-md box-border resize-none focus:ring-accent-dark focus:border-accent-dark"
            ></textarea>
          </div>

          <div class="mb-6 border-t pt-4 border-light-gray">
            <label for="cover" class="block mb-1 font-semibold"
              >Cover Image (Optional):</label
            >
            <input
              type="file"
              id="cover"
              name="cover"
              accept="image/*"
              class="w-full p-2 border border-light-gray rounded-md box-border focus:ring-accent-dark focus:border-accent-dark text-sm"
            />
            <div
              id="cover-preview"
              class="mt-2 w-20 h-20 border border-light-gray rounded-md bg-gray-100 flex items-center justify-center overflow-hidden hidden"
            >
              <img
                id="preview-img"
                class="max-w-full max-h-full object-cover"
                alt="Cover Preview"
              />
            </div>
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

      // Mobile Menu Elements
      const menuButton = document.getElementById("menu-button");
      const mobileMenu = document.getElementById("mobile-menu");

      // Event listener untuk Mobile Menu (NEW)
      menuButton.addEventListener("click", () => {
        mobileMenu.classList.toggle("hidden");
      });

      // NEW: Cover Elements
      const coverInput = document.getElementById("cover");
      const coverPreview = document.getElementById("cover-preview");
      const previewImg = document.getElementById("preview-img");

      let currentFilter = "Semua";
      let bookIdToDelete = null;

      // --- Utility Functions ---
      function getBooks() {
        const booksJSON = localStorage.getItem("biblios_books");
        if (!booksJSON) {
          const initialBooks = [
            // Tambahkan cover: "" ke dummy data
            {
              id: 1,
              judul: "Atomic Habits",
              penulis: "James Clear",
              status: "Sudah Dibaca",
              catatan: "Great book for building small habits.",
              cover: "",
            },
            {
              id: 2,
              judul: "The Name of the Wind",
              penulis: "Patrick Rothfuss",
              status: "Sedang Dibaca",
              catatan: "Immersive and detailed fantasy.",
              cover: "",
            },
            {
              id: 3,
              judul: "Moby Dick",
              penulis: "Herman Melville",
              status: "Ingin Dibaca",
              catatan: "A classic that needs to be read.",
              cover: "",
            },
          ];
          saveBooks(initialBooks);
          return initialBooks;
        }
        return JSON.parse(booksJSON);
      }

      function saveBooks(books) {
        localStorage.setItem("biblios_books", JSON.stringify(books));
      }

      function getStatusClasses(status) {
        switch (status) {
          case "Sudah Dibaca":
            return "bg-success-bg text-success border border-success";
          case "Sedang Dibaca":
            return "bg-info-bg text-info border border-info";
          case "Ingin Dibaca":
            return "bg-warning-bg text-warning border border-warning";
          default:
            return "bg-light-gray text-gray-600 border border-gray-400";
        }
      }

      function displayStatus(status) {
        switch (status) {
          case "Sudah Dibaca":
            return "Finished";
          case "Sedang Dibaca":
            return "Reading";
          case "Ingin Dibaca":
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
      function renderBooks() {
        const allBooks = getBooks();

        const filteredBooks =
          currentFilter === "Semua"
            ? allBooks
            : allBooks.filter((book) => book.status === currentFilter);

        bookGrid.innerHTML = "";

        if (filteredBooks.length === 0) {
          const filterLabel = document.querySelector(
            `.filter-btn[data-filter="${currentFilter}"]`
          ).textContent;
          bookGrid.innerHTML = `<p class="col-span-full text-center p-12 text-gray-500">There are no books in the **${filterLabel}** category.</p>`;
          return;
        }

        filteredBooks.forEach((book) => {
          const statusClasses = getStatusClasses(book.status);

          // NEW: Logika untuk menampilkan cover
          let coverContent;
          if (book.cover && book.cover.startsWith("data:image")) {
            // Jika ada data cover Base64
            coverContent = `<img src="${book.cover}" alt="${book.judul} Cover" class="w-full h-full object-cover">`;
          } else {
            // Jika tidak ada cover
            coverContent = `<span class="text-sm text-gray-500 font-serif">Book Cover</span>`;
          }

          const card = document.createElement("div");
          card.classList.add(
            "book-card",
            "bg-white",
            "rounded-lg",
            "shadow-md",
            "p-5",
            "flex",
            "flex-col",
            "transition",
            "duration-300",
            "hover:shadow-lg",
            "hover:-translate-y-1"
          );

          card.innerHTML = `
                        <div class="book-status self-start py-1 px-3 rounded-md text-xs font-semibold uppercase ${statusClasses}">${displayStatus(
            book.status
          )}</div>
                        <div class="w-full h-40 bg-light-gray rounded-md mb-4 flex justify-center items-center overflow-hidden">
                            ${coverContent}
                        </div>
                        <h3 class="font-serif text-xl mt-0 mb-1 text-accent-dark">${
                          book.judul
                        }</h3>
                        <p class="text-sm text-gray-600 mb-4">By: ${
                          book.penulis
                        }</p>
                        
                        <div class="book-actions pt-2 mt-auto">
                            <button onclick="openEditModal(${
                              book.id
                            })" class="text-gray-500 hover:text-accent-dark transition mr-3">
                                <i class="fas fa-pencil-alt text-sm"></i> Edit
                            </button>
                            <button onclick="openDeleteModal(${
                              book.id
                            })" class="text-gray-500 hover:text-red-600 transition">
                                <i class="fas fa-trash-alt text-sm"></i> Delete
                            </button>
                        </div>
                    `;
          bookGrid.appendChild(card);
        });
      }

      // --- CRUD CREATE & UPDATE ---
      form.addEventListener("submit", async function (e) {
        e.preventDefault();
        const books = getBooks();
        const bookId = document.getElementById("book-id").value;
        const coverFile = coverInput.files[0]; // Ambil file dari input
        let coverBase64 = "";

        if (coverFile) {
          // Konversi file ke Base64 jika ada file baru
          coverBase64 = await convertToBase64(coverFile);
        }

        const newBook = {
          judul: document.getElementById("judul").value,
          penulis: document.getElementById("penulis").value,
          status: document.getElementById("status").value,
          catatan: document.getElementById("catatan").value,
          // Gunakan coverBase64 jika ada, jika tidak, akan dihandle di logika UPDATE/CREATE
          cover: coverBase64,
        };

        if (bookId) {
          // UPDATE
          const index = books.findIndex((b) => b.id === parseInt(bookId));
          if (index !== -1) {
            // Penting: Jika tidak ada file baru (coverBase64 kosong), pertahankan cover yang lama.
            newBook.cover = coverBase64 || books[index].cover;
            books[index] = { ...books[index], ...newBook };
          }
        } else {
          // CREATE
          const newId =
            books.length > 0 ? Math.max(...books.map((b) => b.id)) + 1 : 1;
          newBook.id = newId;
          // Jika membuat baru dan tidak ada cover, set ke string kosong
          if (!newBook.cover) newBook.cover = "";
          books.push(newBook);
        }

        saveBooks(books);
        closeModal(bookModal);
        renderBooks();
      });

      // Event listener untuk Cover Preview (NEW)
      coverInput.addEventListener("change", function () {
        const file = this.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function (e) {
            previewImg.src = e.target.result;
            coverPreview.classList.remove("hidden");
          };
          reader.readAsDataURL(file);
        } else {
          // Jika file dibatalkan atau dihapus
          coverPreview.classList.add("hidden");
          previewImg.src = "";
          // Jika sedang mode edit, tampilkan cover lama (jika ada)
          const bookId = document.getElementById("book-id").value;
          if (bookId) {
            const books = getBooks();
            const bookToEdit = books.find(
              (book) => book.id === parseInt(bookId)
            );
            if (bookToEdit && bookToEdit.cover) {
              previewImg.src = bookToEdit.cover;
              coverPreview.classList.remove("hidden");
            }
          }
        }
      });

      // --- CRUD DELETE (Menggunakan Modal) ---
      function deleteBook(id) {
        let books = getBooks();
        books = books.filter((book) => book.id !== id);
        saveBooks(books);
        renderBooks();
      }

      // --- Filter Logic ---
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

      // --- Modal Control Functions ---

      function openModal(modalEl) {
        modalEl.classList.remove("hidden");
      }

      function closeModal(modalEl) {
        modalEl.classList.add("hidden");
        // Reset input file dan preview saat modal ditutup
        coverInput.value = "";
        previewImg.src = "";
        coverPreview.classList.add("hidden");
        form.reset();
      }

      // Buka modal Tambah/Edit
      openModalBtn.addEventListener("click", () => openCreateModal());

      // Tutup modal (untuk kedua modal)
      closeBtns.forEach((btn) =>
        btn.addEventListener("click", () => {
          closeModal(bookModal);
          closeModal(deleteModal);
        })
      );

      window.addEventListener("click", (event) => {
        if (event.target == bookModal) closeModal(bookModal);
        if (event.target == deleteModal) closeModal(deleteModal);
      });

      // Modal Tambah (CREATE)
      function openCreateModal() {
        modalTitle.textContent = "Add New Book";
        form.reset();
        document.getElementById("book-id").value = "";
        coverInput.value = "";
        previewImg.src = "";
        coverPreview.classList.add("hidden"); // Sembunyikan preview
        openModal(bookModal);
      }

      // Modal Edit (UPDATE)
      function openEditModal(id) {
        const books = getBooks();
        const bookToEdit = books.find((book) => book.id === id);

        if (bookToEdit) {
          modalTitle.textContent = "Edit Book: " + bookToEdit.judul;
          document.getElementById("book-id").value = bookToEdit.id;
          document.getElementById("judul").value = bookToEdit.judul;
          document.getElementById("penulis").value = bookToEdit.penulis;
          document.getElementById("status").value = bookToEdit.status;
          document.getElementById("catatan").value = bookToEdit.catatan;

          // Tampilkan Cover lama di preview (NEW)
          coverInput.value = ""; // Kosongkan input file
          if (bookToEdit.cover) {
            previewImg.src = bookToEdit.cover;
            coverPreview.classList.remove("hidden");
          } else {
            previewImg.src = "";
            coverPreview.classList.add("hidden");
          }

          openModal(bookModal);
        }
      }

      // Modal Hapus (DELETE)
      function openDeleteModal(id) {
        bookIdToDelete = id;
        const books = getBooks();
        const book = books.find((b) => b.id === id);
        document.getElementById("book-title-to-delete").textContent = book
          ? book.judul
          : "Unknown";

        openModal(deleteModal);
      }

      confirmDeleteBtn.addEventListener("click", () => {
        if (bookIdToDelete !== null) {
          deleteBook(bookIdToDelete);
          closeModal(deleteModal);
        }
      });

      cancelDeleteBtn.addEventListener("click", () => {
        closeModal(deleteModal);
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
    </script>
  </body>
</html>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>

    <!-- tailwind cdn lnk -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!--css kepisah nek perlu  -->
    <link rel="stylesheet" href="styles.css" />
    <script src="script.js"></script>
  </head>
  <body>
    <p>Test</p>
    <h1>check</h1>
  </body>
</html>
