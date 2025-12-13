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
    <title>Biblios - Statistics</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />

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
              // Menggunakan nilai hex dari background card yang lebih terang
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

        <div
          id="summary-cards"
          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8"
        ></div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="bg-white p-6 rounded-lg shadow-md md:col-span-2">
            <h2 class="font-serif text-xl text-accent-dark mb-4 border-b pb-2">
              <i class="fas fa-chart-pie mr-2"></i> Status Distribution
            </h2>
            <div
              id="status-breakdown"
              class="flex justify-center items-center w-full p-4"
            >
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
      // Mobile Menu Elements
      const menuButton = document.getElementById("menu-button");
      const mobileMenu = document.getElementById("mobile-menu");

      // Event listener untuk Mobile Menu (NEW)
      menuButton.addEventListener("click", () => {
        mobileMenu.classList.toggle("hidden");
      });
      // --- Utility Functions ---

      function getBooks() {
        const booksJSON = localStorage.getItem("biblios_books");
        return booksJSON ? JSON.parse(booksJSON) : [];
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

      // DIKOREKSI: Menggunakan warna light/background dari definisis Tailwind
      function getStatusColor(status) {
        switch (status) {
          case "Sudah Dibaca":
            return "#D6E7D2"; // success-bg
          case "Sedang Dibaca":
            return "#B2D8D8"; // info-bg
          case "Ingin Dibaca":
            return "#F8E3C5"; // warning-bg
          default:
            return "#EAEAEA"; // light-gray
        }
      }

      function getStatusBorderColor(status) {
        switch (status) {
          case "Sudah Dibaca":
            return "#3C763D"; // success
          case "Sedang Dibaca":
            return "#0E3C40"; // info
          case "Ingin Dibaca":
            return "#8A6D3B"; // warning
          default:
            return "#9E9E9E"; // Light Gray
        }
      }

      function getCardColor(status) {
        switch (status) {
          case "total":
            return "bg-accent-dark text-white";
          case "Sudah Dibaca":
            return "bg-success text-white";
          case "Sedang Dibaca":
            return "bg-info text-white";
          case "Ingin Dibaca":
            return "bg-warning text-white";
          default:
            return "bg-gray-400 text-white";
        }
      }

      function goToLibraryAndFilter(filterValue) {
        window.location.href = `index.html?filter=${encodeURIComponent(
          filterValue
        )}`;
      }

      // --- Main Rendering Function ---
      function renderStatistics() {
        const books = getBooks();
        const totalBooks = books.length;
        const statuses = ["Sudah Dibaca", "Sedang Dibaca", "Ingin Dibaca"];

        // 1. Hitung Status Breakdown
        const statusCounts = books.reduce((acc, book) => {
          acc[book.status] = (acc[book.status] || 0) + 1;
          return acc;
        }, {});

        // 2. Hitung Author Ranking
        const authorCounts = books.reduce((acc, book) => {
          const author = book.penulis.trim();
          acc[author] = (acc[author] || 0) + 1;
          return acc;
        }, {});

        const sortedAuthors = Object.entries(authorCounts)
          .sort(([, a], [, b]) => b - a)
          .slice(0, 5);

        // --- Render Summary Cards (Top 4) ---
        const summaryCardsContainer = document.getElementById("summary-cards");
        summaryCardsContainer.innerHTML = "";

        // Card Total Books: Dibuat dapat diklik untuk filter 'Semua'
        summaryCardsContainer.innerHTML += `
                <div onclick="goToLibraryAndFilter('Semua')" 
                     class="bg-accent-dark text-white p-5 rounded-lg shadow-xl flex items-center justify-between cursor-pointer transform transition hover:scale-[1.02]">
                    <div>
                        <p class="text-3xl font-bold">${totalBooks}</p>
                        <p class="text-sm opacity-80">Total Books</p>
                    </div>
                    <i class="fas fa-book-reader text-4xl opacity-50"></i>
                </div>
            `;

        // Cards per Status (3 slots)
        statuses.forEach((status) => {
          const count = statusCounts[status] || 0;
          const colorClass = getCardColor(status);
          const icon =
            status === "Sudah Dibaca"
              ? "fas fa-check-circle"
              : status === "Sedang Dibaca"
              ? "fas fa-spinner"
              : "fas fa-bookmark";

          summaryCardsContainer.innerHTML += `
                    <div onclick="goToLibraryAndFilter('${status}')" 
                         class="${colorClass} p-5 rounded-lg shadow-xl flex items-center justify-between cursor-pointer transform transition hover:scale-[1.02]">
                        <div>
                            <p class="text-3xl font-bold">${count}</p>
                            <p class="text-sm opacity-80">${displayStatus(
                              status
                            )}</p>
                        </div>
                        <i class="${icon} text-4xl opacity-50"></i>
                    </div>
                `;
        });

        // --- Render Status Breakdown (CHART.JS PIE CHART) ---
        if (totalBooks === 0) {
          const chartContainer = document.getElementById("status-breakdown");
          chartContainer.innerHTML =
            '<p class="text-center text-gray-500 p-10">Add books to your library to see the status chart.</p>';
        } else {
          const chartLabels = [];
          const chartData = [];
          const chartColors = [];
          const chartBorderColors = [];

          statuses.forEach((status) => {
            const count = statusCounts[status] || 0;
            if (count > 0) {
              chartLabels.push(displayStatus(status));
              chartData.push(count);
              chartColors.push(getStatusColor(status));
              chartBorderColors.push(getStatusBorderColor(status));
            }
          });

          const ctx = document
            .getElementById("statusPieChart")
            .getContext("2d");

          if (window.statusChart) {
            window.statusChart.destroy();
          }

          // Daftarkan plugin datalabels secara global
          Chart.register(ChartDataLabels);

          window.statusChart = new Chart(ctx, {
            type: "pie",
            data: {
              labels: chartLabels,
              datasets: [
                {
                  data: chartData,
                  backgroundColor: chartColors,
                  borderColor: chartBorderColors,
                  borderWidth: 1,
                  hoverOffset: 20, // Ditingkatkan untuk jarak aman terbesar
                },
              ],
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  position: "right",
                  labels: {
                    font: { family: "Inter", size: 14 },
                  },
                  onClick: (e, legendItem, legend) => {
                    const selectedStatus = statuses.find(
                      (s) => displayStatus(s) === legendItem.text
                    );
                    if (selectedStatus) {
                      goToLibraryAndFilter(selectedStatus);
                    }
                  },
                },
                tooltip: {
                  callbacks: {
                    label: function (context) {
                      const label = context.label || "";
                      const value = context.parsed;
                      const total = context.dataset.data.reduce(
                        (a, b) => a + b,
                        0
                      );
                      const percentage =
                        ((value / total) * 100).toFixed(1) + "%";
                      return `${label}: ${value} books (${percentage})`;
                    },
                  },
                  titleFont: { family: "Inter" },
                  bodyFont: { family: "Inter" },
                },
                // Konfigurasi datalabels untuk menampilkan persentase DI DALAM potongan
                datalabels: {
                  formatter: (value, context) => {
                    const total = context.chart.data.datasets[0].data.reduce(
                      (a, b) => a + b,
                      0
                    );
                    const percentage = ((value / total) * 100).toFixed(0);
                    return percentage > 0 ? percentage + "%" : "";
                  },
                  color: "#1D5C63", // accent-dark
                  anchor: "center",
                  align: "center",
                  offset: 0,
                  font: {
                    weight: "bold",
                    size: 14,
                    family: "Inter",
                  },
                },
              },
              onClick: (e, elements) => {
                if (elements.length > 0) {
                  const firstElement = elements[0];
                  const index = firstElement.index;
                  const clickedLabel = chartLabels[index];
                  const selectedStatus = statuses.find(
                    (s) => displayStatus(s) === clickedLabel
                  );
                  if (selectedStatus) {
                    goToLibraryAndFilter(selectedStatus);
                  }
                }
              },
            },
            plugins: [ChartDataLabels],
          });
        }

        // --- Render Top Authors Section ---
        const topAuthorsContainer = document.getElementById("top-authors");
        topAuthorsContainer.innerHTML = "";

        if (sortedAuthors.length > 0) {
          sortedAuthors.forEach(([author, count], index) => {
            topAuthorsContainer.innerHTML += `
                        <li class="flex justify-between items-center p-3 rounded-md bg-light-gray/70">
                            <span class="font-bold text-lg text-accent-dark mr-3">${
                              index + 1
                            }.</span>
                            <span class="flex-grow font-semibold">${author}</span>
                            <span class="text-sm bg-accent-dark text-white rounded-full px-3 py-1">${count} Books</span>
                        </li>
                    `;
          });
        } else {
          topAuthorsContainer.innerHTML =
            '<p class="text-gray-500">No authors to rank yet.</p>';
        }
      }

      document.addEventListener("DOMContentLoaded", renderStatistics);
    </script>
  </body>
</html>
