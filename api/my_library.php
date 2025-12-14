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
      const editStatusModal = document.getElementById('edit-status-modal');
      const editForm = document.getElementById('edit-status-form');

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
      ? `<img src="${book.book_cover}" class="w-full h-full object-cover">`
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

        <div class="mt-auto pt-3 flex gap-3">
          <button onclick="openEditModal(${book.reading_id})"
            class="text-gray-500 hover:text-accent-dark transition mr-3">
              <i class="fas fa-pencil-alt text-sm"></i>
            Edit
          </button>

          <button onclick="openDeleteModal(${book.reading_id})"
            class="text-gray-500 hover:text-red-600 transition">
             <i class="fas fa-trash-alt text-sm"></i>
            Delete
          </button>
        </div>
      </div>
    `;
  });
      }

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
    form.reset();
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

        if (bookToEdit.book_cover) {
          previewImg.src = bookToEdit.book_cover;
          coverPreview.classList.remove("hidden");
        }

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
    </script>
  </body>
</html>
