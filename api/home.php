<?php
session_start();
require_once "db.php";
include "navbar.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblios - Home</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-bg': '#FEFAF1',
                        'accent-dark': '#1D5C63',
                        'text-dark': '#333333',
                        'light-gray': '#EAEAEA'
                    },
                    fontFamily: {
                        'serif': ['Playfair Display', 'serif'],
                        'sans': ['Inter', 'sans-serif'],
                    },
                }
            }
        }
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
</head>

<body class="bg-primary-bg font-sans text-text-dark leading-relaxed">

<div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

    <hr class="border-t border-light-gray mb-6">

    <!-- SEARCH + FILTER + ADD -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-10 mb-6 mx-5">

        <div class="flex items-center w-full sm:w-3/4 gap-4">
            <div class="flex-grow">
                <div class="relative">
                    <input id="search-input" type="text" placeholder="Search by title or author..." 
                        class="w-full p-2 border border-light-gray rounded-md focus:ring-accent-dark focus:border-accent-dark shadow-sm pr-10">
                    
                    <i class="fas fa-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                </div>
            </div>

            <div id="rating-filter" class="flex items-center gap-2 bg-white border border-light-gray rounded-xl p-1 shadow-sm">
                <button data-rating="all" class="rating-btn px-3 py-1.5 rounded-lg text-sm font-semibold
                        bg-accent-dark text-white transition">
                    All</button>

                <button data-rating="1"
                    class="rating-btn px-3 py-1.5 rounded-lg text-sm font-semibold
                        text-gray-500 hover:bg-accent-dark/10 hover:text-accent-dark transition">
                    ★</button>

                <button data-rating="2"
                    class="rating-btn px-3 py-1.5 rounded-lg text-sm font-semibold
                        text-gray-500 hover:bg-accent-dark/10 hover:text-accent-dark transition">
                    ★★</button>

                <button data-rating="3"
                    class="rating-btn px-3 py-1.5 rounded-lg text-sm font-semibold
                        text-gray-500 hover:bg-accent-dark/10 hover:text-accent-dark transition">
                    ★★★</button>

                <button data-rating="4"
                    class="rating-btn px-3 py-1.5 rounded-lg text-sm font-semibold
                        text-gray-500 hover:bg-accent-dark/10 hover:text-accent-dark transition">
                    ★★★★</button>

                <button data-rating="5"
                    class="rating-btn px-3 py-1.5 rounded-lg text-sm font-semibold
                        text-gray-500 hover:bg-accent-dark/10 hover:text-accent-dark transition">
                    ★★★★★</button>
            </div>  
        </div>

        <!-- Add Book Button -->
        <button id="open-modal-btn" class="bg-accent-dark text-white py-2 px-4 rounded-md font-semibold text-sm hover:bg-[#0E3C40] transition w-full sm:w-auto">
            + Add New Book
        </button>
    </div>

    <!-- BOOK GRID -->
    <div class="font-sans text-3xl font-bold mt-10 mb-5">Discover new books!</div>
    <main id="book-grid" class="grid gap-8 mx-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"></main>

</div>

<!-- ADD BOOK MODAL -->
<div id="book-modal" class="hidden fixed inset-0 !bg-black/50 flex items-center justify-center p-4 z-20">
    <!-- Modal Card -->
    <div class="bg-primary-bg rounded-lg p-8 shadow-2xl w-11/12 max-w-lg max-h-full lg:max-h-[85vh] overflow-y-auto">

        <span id="close-modal" class="text-gray-500 hover:text-gray-800 text-3xl font-bold float-right cursor-pointer">&times;</span>
        <h2 class="font-serif text-2xl text-accent-dark mb-4">Add New Book</h2>

        <form id="book-form">
            <div class="mb-4">
                <label class="font-semibold">Title:</label>
                <input id="title-input" required type="text" class="w-full p-2 border border-light-gray rounded-md">
            </div>

            <div class="mb-4">
                <label class="font-semibold">Author:</label>
                <input id="author-input" required type="text" class="w-full p-2 border border-light-gray rounded-md">
            </div>

            <div class="mb-4">
                <label class="font-semibold">Synopsis:</label>
                <textarea id="synopsis-input" rows="3" class="w-full p-2 border border-light-gray rounded-md"></textarea>
            </div>

            <div class="mb-4">
                <label class="font-semibold">Book Cover Image:</label>
                <!--<input id="cover-input" type="text" placeholder="https://..." class="w-full p-2 border border-light-gray rounded-md"> -->
                <input id="cover-input" type="file" placeholder="" class="w-full p-2 border border-light-gray rounded-md">

            </div>

            <button class="bg-accent-dark text-white py-2 px-4 rounded-md font-semibold hover:bg-[#0E3C40] transition w-full" type="submit">
                Add Book
            </button>
        </form>
    </div>
</div>

<!-- BOOK DETAILS MODAL -->
<div id="details-modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center p-4 z-30">
    <div class="bg-primary-bg rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto p-6 relative">

        <span id="close-details" class="absolute right-6 top-3 text-gray-600 hover:text-gray-900 text-3xl cursor-pointer font-light">&times;</span>

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
                
                <button id="btn-add-readinglist" onclick="addToReadingList(document.getElementById('details-modal').dataset.bookId)" class=" bg-accent-dark text-white py-2 px-4 rounded-md font-semibold text-sm hover:bg-[#0E3C40] transition">
                    Add to Reading List
                </button>

                <button id="btn-rate-book" onclick="scrollToRating()" class="hidden bg-accent-dark text-white py-2 px-4 rounded-md font-semibold text-sm hover:bg-[#0E3C40] transition">
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
                <div class="flex-1 md:flex-grow-[2]"> <label class="block text-sm font-semibold text-gray-700 mb-1" for="user-review">Review:</label>
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


<!-- JAVASCRIPT -->
<script>
    window.allBooks = [];

    const currentUserId = <?= (int)$_SESSION['user_id'] ?>;

    /* ----------------------------------------------------
    FETCH BOOK LIST FROM DATABASE
    ---------------------------------------------------- */
    async function loadBooks() {
        try {
            const res = await fetch("get_books.php");
            const books = await res.json();
            window.allBooks = books;
            renderBooks(books);
        } catch (error) {
            console.error("Error loading books:", error);
            alert("Failed to load books. Please refresh the page.");
        }
    }

    // ADD BOOK
    async function addBook(data) {
        const form = new FormData();
        for (const key in data) form.append(key, data[key]);

        const res = await fetch("add_book.php", {
            method: "POST",
            body: form,
            credentials: "same-origin"
        });

        return res.json();
    }

    // UPDATE BOOK
    async function updateBook(data) {
        const form = new FormData();
        for (const key in data) form.append(key, data[key]);

        const res = await fetch("update_book.php", {
            method: "POST",
            body: form
        });

        return res.json();
    }

    // SEARCH FUNCTION
    function filterBooks() {
        const q = document.querySelector("#search-input").value.toLowerCase();

        const filtered = window.allBooks.filter(b =>
            b.title.toLowerCase().includes(q) ||
            b.author.toLowerCase().includes(q)
        );

        renderBooks(filtered);
    }
    document.getElementById('search-input').addEventListener('input', filterBooks);


    // RENDER BOOKS
    function renderBooks(books) {
        const container = document.getElementById("book-grid");
        container.innerHTML = "";

        if (books.length === 0) {
            container.innerHTML = '<p class="text-gray-500 col-span-full text-center py-8">No books found. Be the first to add your book!</p>';
            return;
        }

        books.forEach(book => {
            //const coverUrl = book.book_cover || 'images/default-book-cover.png';
            
            const coverUrl = book.book_cover ? `../${book.book_cover}` : '../images/default-book-cover.png';
            console.log(book.book_cover);

            container.innerHTML += `
                <div class="group bg-white rounded-2xl p-5 shadow
                       transition-all duration-300 ease-out
                       hover:-translate-y-2 hover:shadow-2xl
                       active:scale-95 cursor-pointer"
                    onclick="handleBookClick(${book.id})">
                    <img src="${coverUrl}" alt="${book.title}"
                        class="w-full h-64 object-cover rounded-xl mb-3" 
                        onerror="this.src='../images/default-book-cover.png'">

                    <h3 class="font-bold text-lg">${book.title}</h3>
                    <p class="text-sm text-gray-600 mt-1">${book.author}</p>
                </div>`;
        });
    }

    function handleBookClick(bookId) {
        setTimeout(() => {
            openDetailsModal(bookId);
        }, 120);
    }

    // LOAD ALL
    async function loadHome() {
        await loadBooks();
    }
    loadHome();


    // RATING FILTER (STAR BUTTONS)
    const ratingButtons = document.querySelectorAll(".rating-btn");

    ratingButtons.forEach(button => {
        button.addEventListener("click", () => {

            // Reset all buttons
            ratingButtons.forEach(b => {
                b.classList.remove("bg-accent-dark", "text-white");
                b.classList.add("text-gray-500");
            });

            // Activate clicked button
            button.classList.add("bg-accent-dark", "text-white");
            button.classList.remove("text-gray-500");

            const value = button.dataset.rating;

            // Show all books
            if (value === "all") {
                renderBooks(window.allBooks);
                return;
            }

            const minRating = parseFloat(value);

            const filtered = window.allBooks.filter(b => {
                const rating = parseFloat(b.avg_rating);
                return !isNaN(rating) && rating >= minRating;
            });

            renderBooks(filtered);
        });
    });


    // BOOK DETAILS MODAL OPEN - CLOSE
    const modal = document.getElementById('book-modal');
    document.getElementById('open-modal-btn').onclick = () => modal.classList.remove('hidden');
    document.getElementById('close-modal').onclick = () => modal.classList.add('hidden');

    // Close modal when clicking outside
    modal.onclick = (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    };


    // ADD BOOKS FORM
    document.getElementById('book-form').addEventListener('submit', async e => {
        e.preventDefault();

    const formData = new FormData();
    formData.append("title", document.getElementById('title-input').value.trim());
    formData.append("author", document.getElementById('author-input').value.trim());
    formData.append("synopsis", document.getElementById('synopsis-input').value.trim());

    const coverInput = document.getElementById("cover-input");
    if (coverInput.files.length > 0) {
        formData.append("book_cover", coverInput.files[0]);
    }

    try {
        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Adding...';

        const res = await fetch("add_book.php", {
            method: "POST",
            body: formData,
            credentials: "same-origin"
        });

        const result = await res.json();

        if (!result.success) {
            alert(result.message || "Failed to add book");
        } else {
            e.target.reset();
            document.getElementById('book-modal').classList.add('hidden');
            await loadHome();
            alert("Book added successfully!");
        }

        submitBtn.disabled = false;
        submitBtn.textContent = originalText;

    } catch (err) {
        console.error(err);
        alert("An error occurred. Please try again.");
    }
});


   // ADD TO READING LIST
async function addToReadingList(bookId, title = "", author = "", status = "to_read") {
    try {
        const formData = new FormData();
        if (bookId) {
            formData.append("book_id", bookId);
        } else {
            formData.append("judul", title);
            formData.append("penulis", author);
        }
        formData.append("status", status);

        const res = await fetch("add_to_readingList.php", {
            method: "POST",
            body: formData,
            credentials: "same-origin"
        });

        const data = await res.json();

        if (data.success) {
            alert("Book added to your reading list!");
            // opsional: bisa update UI misal tombol disable atau teks berubah
        } else {
            alert(data.message || "Failed to add to reading list");
        }
    } catch (err) {
        console.error(err);
        alert("An error occurred. Please try again.");
    }
}


    function scrollToRating() {
        const modal = document.getElementById("details-modal");
        modal.classList.remove("hidden"); // ensure visible

        requestAnimationFrame(() => {
            const target = document.getElementById("rating-form");
            if (!target) return;

            target.scrollIntoView({
                behavior: "smooth",
                block: "start"
            });
        });
    }

    // OPEN DETAIL MODAL
    async function openDetailsModal(id) {
        try {
            const res = await fetch(`get_book_details.php?id=${id}`);
            const data = await res.json();

            if (data.error) {
                alert(data.error);
                return;
            }

            

            const coverImg = document.getElementById("details-cover");
            coverImg.src = data.book_cover ? `../${data.book_cover}` : "../images/default-book-cover.png";
            coverImg.onerror = () => {
                coverImg.src = "../images/default-book-cover.png";
            }

            // Fill modal
            //edit
            //document.getElementById("details-cover").src = data.book_cover ? `../${data.book_cover}` : "../images/default-book-cover.png";
            //end edit
            document.getElementById("details-title").textContent = data.title;
            document.getElementById("details-author").textContent = "By " + data.author;

            document.getElementById("details-rating").textContent =
                `⭐ ${parseFloat(data.avg_rating).toFixed(1)} / 5.0`;

            document.getElementById("details-synopsis").textContent =
                data.synopsis?.trim() ? data.synopsis : "Synopsis not available.";

            const reviewContainer = document.getElementById("details-reviews");
            reviewContainer.innerHTML = "";

            if (data.reviews.length === 0) {
                reviewContainer.innerHTML = `<p class="text-gray-500">No reviews yet.</p>`;
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

            document.getElementById("details-modal").dataset.bookId = id;
            await loadUserRating(id);

            document.getElementById("details-modal").classList.remove("hidden");

        } catch (err) {
            console.error(err);
            alert("Could not load book details.");
        }
    }

    // LOAD USER RATING
    async function loadUserRating(bookId) {
        const res = await fetch(`get_user_rating.php?book_id=${bookId}&user_id=${currentUserId}`);
        const data = await res.json();

        const rateButton = document.getElementById("btn-rate-book");
        const ratingForm = document.getElementById("rating-form");

        if (!data || data.rating === null) {
            // User hasn't rated: show button, hide form
            rateButton.classList.remove("hidden");
            ratingForm.classList.add("hidden");
            return;
        }

        // User already rated: hide button and form
        rateButton.classList.add("hidden");
        ratingForm.reset();               
        ratingForm.classList.add("hidden");
    }

    // USER CLICKS RATE -> SHOW FORM
    document.getElementById("btn-rate-book").addEventListener("click", () => {
        document.getElementById("rating-form").classList.remove("hidden");
        initHalfStarRating(); // show star rating
    });

    /* ----------------------------------------------------
    HALF STAR RATING (REUSABLE)
    ---------------------------------------------------- */
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

    // SUBMITTING RATING / REVIEW
    document.getElementById("rating-form").addEventListener("submit", async (e) => {
        e.preventDefault();

        const ratingInput = document.getElementById("user-rating").value;
        const rating = ratingInput === "" ? null : parseFloat(ratingInput);
        const review = document.getElementById("user-review").value;
        const bookId = document.getElementById("details-modal").dataset.bookId;

        const formData = new FormData();
        formData.append("user_id", currentUserId);
        formData.append("book_id", bookId);
        formData.append("rating", rating);
        formData.append("review", review);

        const res = await fetch("rate_book.php", {
            method: "POST",
            body: formData,
        });

        const data = await res.json();

        if (!data.success) {
            alert(data.message || "Failed to submit review");
            return;
        }

        // After submit:
        document.getElementById("rating-form").classList.add("hidden");
        document.getElementById("btn-rate-book").classList.add("hidden");

        // Reload modal content
        openDetailsModal(bookId);
    });

    // CLOSE MODAL BOOK DETAILS
    document.getElementById("close-details").onclick = () => document.getElementById("details-modal").classList.add("hidden");

    document.getElementById("details-modal").onclick = (e) => {
        if (e.target.id === "details-modal")
            document.getElementById("details-modal").classList.add("hidden");
    };


</script>

</body>
</html>