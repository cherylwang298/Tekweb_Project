<?php
require_once "config/koneksi.php";
include "partials/navbar.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| FETCH DATA FROM DATABASE
| Disamakan FORMAT-NYA dengan localStorage lama
|--------------------------------------------------------------------------
*/
$sql = "
    SELECT rl.status, b.author
    FROM reading_lists rl
    JOIN books b ON rl.book_id = b.id
    WHERE rl.user_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$booksFromDB = [];

$statusMap = [
    'finished' => 'Sudah Dibaca',
    'reading'  => 'Sedang Dibaca',
    'to_read'  => 'Ingin Dibaca'
];

while ($row = $result->fetch_assoc()) {
    $booksFromDB[] = [
        'status'  => $statusMap[$row['status']],
        'penulis' => $row['author']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ================== HEAD ASLI (TIDAK DIUBAH) ================== -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Biblios - Statistics</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>

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
              info: "#0E3C40",
              warning: "#8A6D3B",
              "success-bg": "#D6E7D2",
              "info-bg": "#B2D8D8",
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

<main class="pb-12">
    <h1 class="font-serif text-2xl sm:text-3xl text-accent-dark mb-6">
        📚 Library Dashboard
    </h1>

    <div id="summary-cards"
         class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        <div class="bg-white p-6 rounded-lg shadow-md md:col-span-2">
            <h2 class="font-serif text-xl text-accent-dark mb-4 border-b pb-2">
                <i class="fas fa-chart-pie mr-2"></i> Status Distribution
            </h2>
            <div id="status-breakdown"
                 class="flex justify-center items-center w-full p-4">
                <div class="w-full max-w-xs sm:max-w-sm p-4">
                    <canvas id="statusPieChart"></canvas>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="font-serif text-xl text-accent-dark mb-4 border-b pb-2">
                <i class="fas fa-users mr-2"></i> Top Authors
            </h2>
            <ul id="top-authors" class="space-y-3"></ul>
        </div>
    </div>
</main>
</div>

<script>
/* =========================================================
   DATA FROM DATABASE (PHP ➜ JS)
   UI & LOGIC TETAP
========================================================= */
const booksFromDB = <?= json_encode($booksFromDB) ?>;

/* =========================================================
   GANTI DATA SOURCE SAJA (INI SATU-SATUNYA PERUBAHAN LOGIC)
========================================================= */
function getBooks() {
    return booksFromDB;
}

/* ===================== JS ASLI KAMU (TIDAK DIUBAH) ===================== */

function displayStatus(status) {
  switch (status) {
    case "Sudah Dibaca": return "Finished";
    case "Sedang Dibaca": return "Reading";
    case "Ingin Dibaca": return "To Read";
    default: return "Unknown";
  }
}

function getStatusColor(status) {
  switch (status) {
    case "Sudah Dibaca": return "#D6E7D2";
    case "Sedang Dibaca": return "#B2D8D8";
    case "Ingin Dibaca": return "#F8E3C5";
    default: return "#EAEAEA";
  }
}

function getStatusBorderColor(status) {
  switch (status) {
    case "Sudah Dibaca": return "#3C763D";
    case "Sedang Dibaca": return "#0E3C40";
    case "Ingin Dibaca": return "#8A6D3B";
    default: return "#9E9E9E";
  }
}

function getCardColor(status) {
  switch (status) {
    case "total": return "bg-accent-dark text-white";
    case "Sudah Dibaca": return "bg-success text-white";
    case "Sedang Dibaca": return "bg-info text-white";
    case "Ingin Dibaca": return "bg-warning text-white";
    default: return "bg-gray-400 text-white";
  }
}

function goToLibraryAndFilter(filterValue) {
  window.location.href = `index.php?filter=${encodeURIComponent(filterValue)}`;
}

/* ===================== renderStatistics() ASLI ===================== */

function renderStatistics() {
  const books = getBooks();
  const totalBooks = books.length;
  const statuses = ["Sudah Dibaca", "Sedang Dibaca", "Ingin Dibaca"];

  const statusCounts = books.reduce((acc, book) => {
    acc[book.status] = (acc[book.status] || 0) + 1;
    return acc;
  }, {});

  const authorCounts = books.reduce((acc, book) => {
    acc[book.penulis] = (acc[book.penulis] || 0) + 1;
    return acc;
  }, {});

  const sortedAuthors = Object.entries(authorCounts)
    .sort(([, a], [, b]) => b - a)
    .slice(0, 5);

  const summary = document.getElementById("summary-cards");
  summary.innerHTML = "";

  summary.innerHTML += `
    <div class="bg-accent-dark text-white p-5 rounded-lg shadow-xl">
      <p class="text-3xl font-bold">${totalBooks}</p>
      <p class="text-sm opacity-80">Total Books</p>
    </div>
  `;

  statuses.forEach(status => {
    const count = statusCounts[status] || 0;
    summary.innerHTML += `
      <div class="${getCardColor(status)} p-5 rounded-lg shadow-xl">
        <p class="text-3xl font-bold">${count}</p>
        <p class="text-sm opacity-80">${displayStatus(status)}</p>
      </div>
    `;
  });

  if (totalBooks === 0) return;

  Chart.register(ChartDataLabels);

  new Chart(document.getElementById("statusPieChart"), {
    type: "pie",
    data: {
      labels: statuses.map(displayStatus),
      datasets: [{
        data: statuses.map(s => statusCounts[s] || 0),
        backgroundColor: statuses.map(getStatusColor),
        borderColor: statuses.map(getStatusBorderColor),
      }]
    },
    options: {
      plugins: {
        datalabels: {
          formatter: (v, ctx) => {
            const total = ctx.chart.data.datasets[0].data.reduce((a,b)=>a+b,0);
            return total ? Math.round(v/total*100) + "%" : "";
          }
        }
      }
    }
  });

  const authorsEl = document.getElementById("top-authors");
  authorsEl.innerHTML = "";

  sortedAuthors.forEach(([author, count], i) => {
    authorsEl.innerHTML += `
      <li class="flex justify-between p-3 bg-light-gray/70 rounded">
        <span>${i+1}. ${author}</span>
        <span class="bg-accent-dark text-white px-3 py-1 rounded-full text-sm">${count} Books</span>
      </li>
    `;
  });
}

document.addEventListener("DOMContentLoaded", renderStatistics);
</script>

</body>
</html>
