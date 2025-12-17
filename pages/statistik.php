<?php
require_once "config/koneksi.php";
include "partials/navbar.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* ================= FETCH DATA DARI DATABASE ================= */
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
    'finished' => 'Finished',
    'reading'  => 'Reading',
    'to_read'  => 'To Read'
];

while ($row = $result->fetch_assoc()) {
    $booksFromDB[] = [
        'status'  => $statusMap[$row['status']] ?? 'Unknown',
        'penulis' => $row['author']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Biblios - Statistics</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-bg': '#F7F4EB',
                        'accent-dark': '#52796F',
                        'accent-hover': '#354F52',
                        'text-dark': '#2F3E46',
                        'light-gray': '#E4E1D8',


                        /* ===== STATUS COLORS (FINAL) ===== */
  
                        // Status Colors
                        'finished-color': '#9db997ff', 
                        'reading-color':  '#8abdbdff', 
                        'toread-color': '#c9ac83ff',    
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
        @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.4); }
        
        /* Animasi melayang untuk kartu */
        .stat-card:hover { transform: translateY(-8px); box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1); }
    </style>
</head>

<body class="bg-primary-bg font-sans text-text-dark min-h-screen relative overflow-x-hidden">

<div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pb-24">

    <!-- HEADER -->
    <div class="mt-10 mb-8 animate-fade-in">
        <h1 class="font-serif text-3xl text-accent-dark font-extrabold tracking-tight">Library Analytics</h1>
        <p class="text-gray-500 font-medium mt-1">Visualizing your reading progress and habits.</p>
    </div>

        <div id="summary-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12 animate-fade-in" style="animation-delay: 0.1s;">
            </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate-fade-in" style="animation-delay: 0.2s;">
            
            <div class="lg:col-span-2 glass rounded-[2.5rem] p-8 sm:p-10 shadow-xl">
                <h3 class="font-serif text-2xl font-bold mb-8 flex items-center gap-3">
                    <i class="fas fa-chart-pie text-accent-dark"></i> Status Distribution
                </h3>
                <div class="flex flex-col md:flex-row items-center justify-around gap-10">
                    <div class="w-full max-w-[320px]">
                        <canvas id="statusPieChart"></canvas>
                    </div>
                    <div id="chart-legend" class="space-y-4 w-full md:w-auto">
                        </div>
                </div>
            </div>

            <div class="glass rounded-[2.5rem] p-8 sm:p-10 shadow-xl">
                <h3 class="font-serif text-2xl font-bold mb-8 flex items-center gap-3">
                    <i class="fas fa-feather-alt text-accent-dark"></i> Top Authors
                </h3>
                <div id="top-authors" class="space-y-4">
                    </div>
            </div>

        </div>
    </div>

    <script>
        const books = <?= json_encode($booksFromDB) ?>;

        // Fungsi Redirect ke My Library dengan parameter filter
        function goToLibrary(label) {
            let statusParam = 'all';
            if (label === 'Finished') statusParam = 'finished';
            if (label === 'Reading') statusParam = 'reading';
            if (label === 'To Read') statusParam = 'to_read';
            
            // Mengarahkan ke my_library.php dengan query string
            window.location.href = `index.php?page=my_library&status=${statusParam}`;

        }

        function renderStats() {
            const statusCounts = books.reduce((acc, b) => { acc[b.status] = (acc[b.status] || 0) + 1; return acc; }, {});
            const total = books.length;

            /* ================= 1. SUMMARY CARDS ================= */
            const summaryContainer = document.getElementById("summary-container");
            const cardConfigs = [
                { label: 'Total Books', count: total, color: 'bg-accent-dark text-white', icon: 'fa-book', key: 'Total' },
                { label: 'Finished', count: statusCounts['Finished'] || 0, color: 'bg-finished-color text-white', icon: 'fa-check-circle', key: 'Finished' },
                { label: 'Reading', count: statusCounts['Reading'] || 0, color: 'bg-reading-color text-white', icon: 'fa-book-open', key: 'Reading' },
                { label: 'To Read', count: statusCounts['To Read'] || 0, color: 'bg-toread-color text-white', icon: 'fa-bookmark', key: 'To Read' }
            ];

            summaryContainer.innerHTML = cardConfigs.map(item => `
                <div onclick="goToLibrary('${item.key}')" 
                     class="${item.color} stat-card p-8 rounded-[2.5rem] transition-all duration-500 cursor-pointer group relative overflow-hidden shadow-md">
                    <div class="relative z-10">
                        <p class="text-[11px] font-bold uppercase tracking-widest opacity-80 mb-1">${item.label}</p>
                        <p class="text-4xl font-black">${item.count}</p>
                    </div>
                    <i class="fas ${item.icon} absolute -right-4 -bottom-4 text-7xl opacity-20 group-hover:scale-110 group-hover:rotate-12 transition-transform duration-500"></i>
                </div>
            `).join('');

            /* ================= 2. PIE CHART ================= */
            if (total > 0) {
                const ctx = document.getElementById("statusPieChart").getContext('2d');
                new Chart(ctx, {
                    type: "pie",
                    data: {
                        labels: ['Finished', 'Reading', 'To Read'],
                        datasets: [{
    data: [
        statusCounts['Finished'] || 0,
        statusCounts['Reading'] || 0,
        statusCounts['To Read'] || 0
    ],
    backgroundColor: [
        '#D6E7D2', // finished
        '#B2D8D8', // reading
        '#F8E3C5'  // to read
    ],
    borderWidth: 4,
    borderColor: '#ffffff'
}]

                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            datalabels: {
                                color: (ctx) => (ctx.dataIndex === 2 ? '#2F3E46' : '#fff'),
                                font: {
                                    size: 18, // Teks dalam Pie Chart diperbesar
                                    weight: 'bold',
                                    family: 'Inter'
                                },
                                formatter: (value, ctx) => {
                                    let percentage = Math.round(value / total * 100);
                                    return percentage > 0 ? percentage + '%' : '';
                                }
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });

                // Custom Legend for Pie Chart
                const legend = document.getElementById("chart-legend");
                const labels = ['Finished', 'Reading', 'To Read'];
                const colors = ['bg-finished-color', 'bg-reading-color', 'bg-toread-color'];
                
                legend.innerHTML = labels.map((label, i) => `
                    <div class="flex items-center justify-between gap-10 p-3 hover:bg-white/60 rounded-2xl transition-all cursor-default">
                        <div class="flex items-center gap-3">
                            <div class="w-4 h-4 rounded-full ${colors[i]}"></div>
                            <span class="font-bold text-gray-700">${label}</span>
                        </div>
                        <span class="text-sm font-semibold text-accent-dark/60">${statusCounts[label] || 0} Books</span>
                    </div>
                `).join('');
            } else {
                document.getElementById("statusPieChart").parentElement.innerHTML = "<p class='text-gray-400'>No data available yet.</p>";
            }

            /* ================= 3. TOP AUTHORS ================= */
            const authorMap = {};

            books.forEach(b => {
                if (!b.penulis) return;

                const key = b.penulis.trim().toLowerCase(); // case-insensitive key

                if (!authorMap[key]) {
                    authorMap[key] = {
                        displayName: b.penulis.trim(), // simpan versi pertama
                        count: 1
                    };
                } else {
                    authorMap[key].count++;
                }
            });

            const sortedAuthors = Object.values(authorMap)
                .sort((a, b) => b.count - a.count)
                .slice(0, 5);

            const authorsEl = document.getElementById("top-authors");

            if (sortedAuthors.length > 0) {
                authorsEl.innerHTML = sortedAuthors.map(author => `
                    <div class="flex justify-between items-center p-3 bg-white/40 rounded-2xl border border-white/20 hover:bg-white/80 transition-all">
                        <span class="font-extrabold text-base text-text-dark">
                            ${author.displayName}
                        </span>
                        <span class="bg-accent-dark/10 text-accent-dark px-4 py-1.5 rounded-lg text-xs font-black uppercase">
                            ${author.count} Books
                        </span>
                    </div>
                `).join('');
            } else {
                authorsEl.innerHTML = `<p class="text-gray-400 text-center py-10 italic">Belum ada data penulis.</p>`;
            }

        }

        document.addEventListener("DOMContentLoaded", renderStats);
    </script>
</body>
</html>