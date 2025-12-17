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
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Biblios - Home</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">

<script>
tailwind.config = {
    theme: {
        extend: {
            colors: {
                'primary-bg': '#F7F4EB',
                'accent-dark': '#52796F',
                'accent-hover': '#354F52',
                'text-dark': '#2F3E46',
                'light-gray': '#E4E1D8'
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
@keyframes blobMove1 {
    0% { transform: translate(0, 0) scale(1); }
    25% { transform: translate(60px, -40px) scale(1.1); }
    50% { transform: translate(-40px, 50px) scale(0.95); }
    75% { transform: translate(50px, 30px) scale(1.05); }
    100% { transform: translate(0, 0) scale(1); }
}
@keyframes blobMove2 {
    0% { transform: translate(0, 0) scale(1); }
    30% { transform: translate(-70px, 40px) scale(1.15); }
    60% { transform: translate(40px, -60px) scale(0.9); }
    100% { transform: translate(0, 0) scale(1); }
}
@keyframes blobMove3 {
    0% { transform: translateY(0) scale(1); }
    50% { transform: translateY(-80px) scale(1.1); }
    100% { transform: translateY(0) scale(1); }
}

.star { position: relative; width: 32px; height: 32px; cursor: pointer; }
.star i { position: absolute; inset: 0; font-size: 1.75rem; }
.star-empty { color: #d1d5db; }
.star-half { color: #facc15; clip-path: inset(0 50% 0 0); display: none; }
.star-full { color: #facc15; display: none; }

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }

.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
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

    <!-- HEADER -->
    <div class="mt-10 mb-8 animate-fade-in">
        <h1 class="font-serif text-3xl text-accent-dark font-extrabold tracking-tight">Discover new books!</h1>
        <p class="text-gray-500 font-medium mt-1">Explore our collection and share your thoughts.</p>
    </div>

    <!-- SEARCH / FILTER / ADD -->
    <div class="flex flex-col lg:flex-row items-center gap-4 mb-10 animate-fade-in">
    
    <div class="relative w-full lg:flex-1 group transition-all duration-300 hover:-translate-y-1">
        <input id="search-input" type="text" placeholder="Search by title or author..."
            class="w-full pl-12 pr-4 py-3 bg-white/80 border border-light-gray rounded-2xl focus:ring-2 focus:ring-accent-dark outline-none shadow-sm transition-all group-hover:shadow-md group-hover:border-accent-dark/30">
        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 transition-colors group-hover:text-accent-dark"></i>
    </div>

    <div id="rating-filter" class="flex shrink-0 items-center gap-1 bg-white/60 p-1.5 rounded-2xl border border-light-gray shadow-sm transition-all hover:shadow-md">
        <button data-rating="all"
            class="rating-btn px-4 py-2 rounded-xl text-xs font-bold bg-accent-dark text-white transition-all hover:scale-105 active:scale-95 shadow-sm">
            All
        </button>
        <button data-rating="1" class="rating-btn px-3 py-2 rounded-xl text-xs font-bold text-gray-500 hover:bg-white hover:text-accent-dark hover:scale-110 transition-all active:scale-90">★</button>
        <button data-rating="2" class="rating-btn px-3 py-2 rounded-xl text-xs font-bold text-gray-500 hover:bg-white hover:text-accent-dark hover:scale-110 transition-all active:scale-90">★★</button>
        <button data-rating="3" class="rating-btn px-3 py-2 rounded-xl text-xs font-bold text-gray-500 hover:bg-white hover:text-accent-dark hover:scale-110 transition-all active:scale-90">★★★</button>
        <button data-rating="4" class="rating-btn px-3 py-2 rounded-xl text-xs font-bold text-gray-500 hover:bg-white hover:text-accent-dark hover:scale-110 transition-all active:scale-90">★★★★</button>
        <button data-rating="5" class="rating-btn px-3 py-2 rounded-xl text-xs font-bold text-gray-500 hover:bg-white hover:text-accent-dark hover:scale-110 transition-all active:scale-90">★★★★★</button>
    </div>

    <button id="open-modal-btn"
        class="bg-accent-dark text-white py-3 px-6 rounded-xl font-bold text-sm hover:bg-accent-hover transition-all shadow-md hover:shadow-lg hover:-translate-y-1 active:scale-95 w-full lg:w-auto whitespace-nowrap flex items-center justify-center gap-2">
        <i class="fas fa-plus transition-transform group-hover:rotate-90"></i> 
        <span>Add New Book</span>
    </button>
</div>

    <!-- BOOK GRID -->
    <main id="book-grid"
        class="grid gap-8 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 mb-20"></main>

</div>

<!-- ADD BOOK MODAL -->
<div id="book-modal"
    class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center p-4 z-[60]">
    <div class="bg-white rounded-[2rem] p-8 shadow-2xl w-full max-w-lg relative border border-light-gray">
        <span id="close-modal"
            class="absolute right-6 top-5 text-gray-400 hover:text-gray-800 text-3xl cursor-pointer">&times;</span>

        <h2 class="font-serif text-2xl font-bold text-accent-dark mb-6">Add New Book</h2>

        <form id="book-form" class="space-y-4">
            <input id="title-input" required placeholder="Title"
                class="w-full p-3 bg-gray-50 border rounded-xl">
            <input id="author-input" required placeholder="Author"
                class="w-full p-3 bg-gray-50 border rounded-xl">
            <textarea id="synopsis-input" rows="3" placeholder="Synopsis"
                class="w-full p-3 bg-gray-50 border rounded-xl resize-none"></textarea>
            <input id="cover-input" type="file"
                class="w-full text-sm text-gray-500">
            <button type="submit"
                class="bg-accent-dark text-white py-4 rounded-xl font-bold w-full">
                Add Book
            </button>
        </form>
    </div>
</div>

<!-- DETAILS MODAL -->
<!-- DETAILS MODAL (SAFE VERSION) -->
<div id="details-modal"
    class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center p-4 z-[70]">

    <div
        class="bg-white/90 backdrop-blur-xl
               rounded-[2.5rem] shadow-2xl
               w-full max-w-3xl max-h-[85vh] overflow-y-auto
               relative border border-white/40">

        <!-- CLOSE -->
        <span id="close-details"
            class="absolute right-6 top-6 text-gray-400 hover:text-gray-900 text-3xl cursor-pointer">
            &times;
        </span>

        <div class="p-8 lg:p-10">

            <!-- HEADER -->
            <div class="flex flex-col md:flex-row gap-8 mb-8">
                <img id="details-cover"
                    class="w-full md:w-48 aspect-[3/4] object-cover rounded-2xl shadow-lg">

                <div class="flex-grow">

                    <!-- 🔴 INI HARUS ADA & TIDAK DIUBAH -->
                    <div id="details-rating"
                        class="inline-flex items-center px-4 py-1.5
                               bg-yellow-400/10 text-yellow-700
                               rounded-full text-sm font-bold mb-3">
                        <i class="fas fa-star mr-1"></i> 0.0 / 5.0
                    </div>

                    <h2 id="details-title"
                        class="font-serif text-3xl font-extrabold mb-1"></h2>

                    <p id="details-author"
                        class="text-lg text-gray-500 font-medium mb-5"></p>

                    <!-- 🔴 TOMBOL LAMA HARUS ADA -->
                    <button id="btn-add-readinglist"
                        class="bg-accent-dark text-white py-2.5 px-6
                               rounded-xl font-bold mb-3">
                        Add to Reading List
                    </button>

                    <button id="btn-rate-book"
                        class="hidden bg-accent-dark text-white py-2.5 px-6
                               rounded-xl font-bold">
                        Rate This Book
                    </button>

                </div>
            </div>

            <hr class="my-10 border-0 h-[2px] bg-gradient-to-r from-transparent via-gray-300/80 to-transparent">



            <!-- SYNOPSIS -->
            <h3 class="font-serif text-xl font-bold mb-2">Synopsis</h3>
            <p id="details-synopsis"
                class="text-gray-600 leading-relaxed mb-8"></p>

            <!-- REVIEWS -->
            <h3 class="font-serif text-xl font-bold mb-3">Reviews</h3>
            <div id="details-reviews" class="space-y-4 mb-10"></div>

            <!-- 🔴 FORM HARUS TETAP STRUKTURNYA -->
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
                    class="block text-sm text-gray-600 mb-3"> Give your rating and review!
                </span> 
                

                <textarea id="user-review"
                    class="w-full p-3 border rounded-xl mb-4"
                    rows="3"></textarea>

                <div class="flex justify-end mt-4">
                    <button type="submit"
                            class="bg-accent-dark text-white px-6 py-2 rounded-xl font-bold transition-all hover:bg-accent-hover active:scale-95">
                        Submit Review
                    </button>
                </div>
            </form>

        </div>
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
            const res = await fetch("/tekweb_project/api/books/get_books.php");
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

        const res = await fetch("/tekweb_project/api/books/add_book.php", {
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

        const res = await fetch("/tekweb_project/api/books/update_book.php", {
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
            const rating = parseFloat(book.avg_rating || 0).toFixed(1);
            const coverUrl = book.book_cover ? `/tekweb_project/${book.book_cover}` : '/tekweb_project/images/default-book-cover.png';
            console.log(book.book_cover);

            container.innerHTML += `
                <div
                class="group relative bg-white/70 backdrop-blur-sm
                        rounded-[2rem] p-5 shadow
                        transition-all duration-300 ease-out
                        hover:-translate-y-2 hover:shadow-2xl
                        active:scale-95 cursor-pointer"
                data-book-id="${book.id}"
                onclick="handleBookClick(${book.id})">

                <!-- COVER -->
                <img src="${coverUrl}" alt="${book.title}"
                    class="w-full h-64 object-cover rounded-xl mb-3"
                    onerror="this.src='/tekweb_project/images/default-book-cover.png'">

                <!-- AVG RATING BADGE -->
                <div class="absolute top-3 right-3 bg-white/95 backdrop-blur-md
                            px-3 py-1.5 rounded-full shadow-md flex items-center gap-1.5 z-10">
                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                    <span class="text-xs font-bold text-[#2F3E46]">${rating}</span>
                </div>

                <!-- TITLE -->
                <h3 class="font-bold text-lg">${book.title}</h3>
                <p class="text-sm text-gray-600 mt-1">${book.author}</p>

            
                </div>
                `;

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

        const res = await fetch("/tekweb_project/api/books/add_book.php", {
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

   document.getElementById("btn-add-readinglist").addEventListener("click", async () => {
    const modal = document.getElementById("details-modal");
    const bookId = modal.dataset.bookId;

    if (!bookId) {
        console.warn("No bookId found in modal");
        return;
    }

    await addToReadingList(bookId);
});

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

        const res = await fetch("/tekweb_project/api/reading_list/add_to_readlist.php", {
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

        openDetailsModal(bookId);
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
            const res = await fetch(`/tekweb_project/api/books/get_book_details.php?id=${id}`);
            const data = await res.json();
            

            if (data.error) {
                alert(data.error);
                return;
            }

            

            const coverImg = document.getElementById("details-cover");
            coverImg.src = data.book_cover ? `/tekweb_project/${data.book_cover}` : "/tekweb_project/images/default-book-cover.png";
            coverImg.onerror = () => {
                coverImg.src = "/tekweb_project/images/default-book-cover.png";
            }

            // Fill modal
            //edit
            //document.getElementById("details-cover").src = data.book_cover ? `../${data.book_cover}` : "../images/default-book-cover.png";
            //end edit
            document.getElementById("details-title").textContent = data.title;
            document.getElementById("details-author").textContent = "By " + data.author;

            document.getElementById("details-rating").innerHTML =
            `<i class="fas fa-star mr-1"></i> ${parseFloat(data.avg_rating).toFixed(1)} / 5.0`;


            document.getElementById("details-synopsis").textContent =
                data.synopsis?.trim() ? data.synopsis : "No synopsis available for this book.";

            const reviewContainer = document.getElementById("details-reviews");
            reviewContainer.innerHTML = "";

            if (data.reviews.length === 0) {
                reviewContainer.innerHTML = `<p class="text-gray-500">No reviews yet. Be the first!</p>`;
            } else {
                data.reviews.forEach(r => {
                   reviewContainer.innerHTML += `
                    <div class="bg-white/60 p-4 rounded-2xl border border-white/50 shadow-sm">
                        
                        <!-- USERNAME + RATING -->
                        <div class="flex items-center gap-2 mb-1">
                            <p class="font-semibold text-base text-[#2F3E46]">
                                ${r.username}
                            </p>
                            <span class="inline-flex items-center gap-1
                                        text-sm font-semibold
                                        bg-yellow-100 text-yellow-700
                                        px-2 py-0.5 rounded-md">
                                <i class="fas fa-star text-[11px]"></i>
                                ${r.rating}
                            </span>
                        </div>

                        <!-- REVIEW TEXT -->
                        <p class="text-gray-700 text-sm leading-relaxed mb-1">
                            ${r.review}
                        </p>

                        <!-- TIMESTAMP -->
                        <p class="text-xs text-gray-500">
                            ${r.created_at}
                        </p>
                    </div>
                `;

                });
            }

            document.getElementById("details-modal").dataset.bookId = id;
            await checkReadingList(id);
            await loadUserRating(id);

            document.getElementById("details-modal").classList.remove("hidden");

        } catch (err) {
            console.error(err);
            alert("Could not load book details.");
        }
    }


    // LOAD USER RATING
    async function loadUserRating(bookId) {
        const res = await fetch(`/tekweb_project/api/users/get_user_rating.php?book_id=${bookId}&user_id=${currentUserId}`);
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
    const ratingForm = document.getElementById("rating-form");
    if (!ratingForm) return;

    // 1. munculkan form
    ratingForm.classList.remove("hidden");

    // 2. init star (logic lama)
    initHalfStarRating();

    // 3. scroll modal content (BUKAN window)
    const scrollContainer = ratingForm.closest(".overflow-y-auto");

    if (scrollContainer) {
        scrollContainer.scrollTo({
            top: ratingForm.offsetTop - 24,
            behavior: "smooth"
        });
    }
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
    
function prependNewReview(r) {
    const container = document.getElementById("details-reviews");

    // Hapus teks "No reviews yet"
    if (container.querySelector("p.text-gray-500")) {
        container.innerHTML = "";
    }

    const div = document.createElement("div");
    div.className =
        "bg-white/60 p-4 rounded-2xl border border-white/50 shadow-sm animate-fade-in";

    div.innerHTML = `
        <div class="flex items-center gap-2 mb-1">
            <p class="font-semibold text-base text-[#2F3E46]">${r.username}</p>
            <span class="inline-flex items-center gap-1
                         text-sm font-semibold
                         bg-yellow-100 text-yellow-700
                         px-2 py-0.5 rounded-md">
                <i class="fas fa-star text-[11px]"></i>
                ${r.rating}
            </span>
        </div>
        <p class="text-gray-700 text-sm leading-relaxed mb-1">
            ${r.review}
        </p>
        <p class="text-xs text-gray-500">
            ${r.created_at}
        </p>
    `;

    container.prepend(div);
}
function updateModalAvgRating(avg) {
    const badge = document.getElementById("details-rating");
    badge.innerHTML = `
        <i class="fas fa-star mr-1"></i> ${parseFloat(avg).toFixed(1)} / 5.0
    `;
}


function updateBookCardRating(bookId, avg) {
    // Update data source
    const book = window.allBooks.find(b => b.id == bookId);
    if (book) book.avg_rating = avg;

    // Update UI badge
    const card = document.querySelector(`[data-book-id="${bookId}"]`);
    if (!card) return;

    const badge = card.querySelector(".absolute.top-3.right-3 span");
    if (badge) {
        badge.textContent = parseFloat(avg).toFixed(1);
    }
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

        const res = await fetch("/tekweb_project/api/books/rate_book.php", {
            method: "POST",
            body: formData,
        });

        const data = await res.json();

        if (!data.success) {
            alert(data.message || "Failed to submit review");
            return;
        }

        // After submit:
        if (!data.success) {
            alert(data.message || "Failed to submit review");
            return;
}

        // 1. Tambah review ke UI
        prependNewReview(data.new_review);

        // 2. Update avg rating (modal)
        updateModalAvgRating(data.new_avg_rating);

        // 3. Update avg rating (book card di home)
        updateBookCardRating(bookId, data.new_avg_rating);

        // 4. Hide form & button
        document.getElementById("rating-form").classList.add("hidden");
        document.getElementById("btn-rate-book").classList.add("hidden");

        // 5. Reset form
        document.getElementById("rating-form").reset();

    });

    // CLOSE MODAL BOOK DETAILS
    document.getElementById("close-details").onclick = () => document.getElementById("details-modal").classList.add("hidden");

    document.getElementById("details-modal").onclick = (e) => {
        if (e.target.id === "details-modal")
            document.getElementById("details-modal").classList.add("hidden");
    };


    async function checkReadingList(bookId) {
    const res = await fetch(`/tekweb_project/api/reading_list/get_reading_list_status.php?book_id=${bookId}`);
    const data = await res.json();

    const btn = document.getElementById("btn-add-readinglist");

    if (data.in_list) {
        btn.classList.add("hidden");
    } else {
        btn.classList.remove("hidden");
    }
}

</script>

</body>
</html>