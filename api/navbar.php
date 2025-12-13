<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<header class="max-w-screen-xl mx-auto flex items-center py-4 px-4 sm:px-10">

  <!-- Logo (LEFT) -->
  <div class="font-serif text-3xl font-bold text-[#2F3E46] tracking-wide">
    Biblios
  </div>

  <!-- Desktop Navbar (RIGHT) -->
  <nav class="hidden sm:flex items-center text-sm font-semibold gap-8 ml-auto">

    <!-- Home -->
    <a href="home.php"
       class="relative group flex items-center text-base
       <?= $currentPage == 'home.php'
          ? 'text-[#52796F] font-bold'
          : 'text-[#2F3E46]' ?>">
      <i class="fas fa-home mr-1"></i> Home
      <?php if ($currentPage == 'home.php'): ?>
        <span class="absolute bottom-0 left-0 w-full bg-[#52796F] h-[2px]"></span>
      <?php else: ?>
        <span class="absolute bottom-0 left-0 w-0 group-hover:w-full h-[2px] bg-[#52796F] transition-all duration-300 rounded-full"></span>
      <?php endif; ?>
    </a>

    <!-- My Library -->
    <a href="my_library.php"
       class="relative group flex items-center text-base
       <?= $currentPage == 'my_library.php'
          ? 'text-[#52796F] font-bold'
          : 'text-[#2F3E46]' ?>">
      <i class="fas fa-book-open mr-1"></i> My Library
      <?php if ($currentPage == 'my_library.php'): ?>
        <span class="absolute bottom-0 left-0 w-full bg-[#52796F] h-[2px]"></span>
      <?php else: ?>
        <span class="absolute bottom-0 left-0 w-0 group-hover:w-full h-[2px] bg-[#52796F] transition-all duration-300 rounded-full"></span>
      <?php endif; ?>
    </a>

    <!-- Statistics -->
    <a href="statistik.php"
       class="relative group flex items-center text-base
       <?= $currentPage == 'statistik.php'
          ? 'text-[#52796F] font-bold'
          : 'text-[#2F3E46]' ?>">
      <i class="fas fa-chart-bar mr-1"></i> Statistics
      <?php if ($currentPage == 'statistik.php'): ?>
        <span class="absolute bottom-0 left-0 w-full bg-[#52796F] h-[2px]"></span>
      <?php else: ?>
        <span class="absolute bottom-0 left-0 w-0 group-hover:w-full h-[2px] bg-[#52796F] transition-all duration-300 rounded-full"></span>
      <?php endif; ?>
    </a>

    <!-- Profile -->
    <a href="profile.php"
       class="relative group flex items-center text-base
       <?= $currentPage == 'profile.php'
          ? 'text-[#52796F] font-bold'
          : 'text-[#2F3E46]' ?>">
      <i class="fas fa-user mr-1"></i> Profile
      <?php if ($currentPage == 'profile.php'): ?>
        <span class="absolute bottom-0 left-0 w-full bg-[#52796F] h-[2px]"></span>
      <?php else: ?>
        <span class="absolute bottom-0 left-0 w-0 group-hover:w-full h-[2px] bg-[#52796F] transition-all duration-300 rounded-full"></span>
      <?php endif; ?>
    </a>

  </nav>

  <!-- Mobile Button (RIGHT) -->
  <button
    id="menu-button"
    class="ml-auto text-3xl text-[#2F3E46] sm:hidden"
  >
    <i class="fas fa-bars"></i>
  </button>

</header>

<!-- Mobile Menu -->
<div
  id="mobile-menu"
  class="hidden flex flex-col space-y-2 p-4 bg-white shadow-xl rounded-xl border border-[#CAD2C5] absolute top-20 right-4 z-20 w-48"
>

  <a href="home.php"
     class="py-2 px-3 rounded-md flex items-center
     <?= $currentPage == 'home.php'
        ? 'bg-[#52796F] text-white'
        : 'hover:bg-[#F7F4EB]' ?>">
    <i class="fas fa-home mr-2 w-3"></i> Home
  </a>

  <a href="my_library.php"
     class="py-2 px-3 rounded-md flex items-center
     <?= $currentPage == 'my_library.php'
        ? 'bg-[#52796F] text-white'
        : 'hover:bg-[#F7F4EB]' ?>">
    <i class="fas fa-book-open mr-2 w-3"></i> My Library
  </a>

  <a href="statistik.php"
     class="py-2 px-3 rounded-md flex items-center
     <?= $currentPage == 'statistik.php'
        ? 'bg-[#52796F] text-white'
        : 'hover:bg-[#F7F4EB]' ?>">
    <i class="fas fa-chart-bar mr-2 w-3"></i> Statistics
  </a>

  <a href="profile.php"
     class="py-2 px-3 rounded-md flex items-center
     <?= $currentPage == 'profile.php'
        ? 'bg-[#52796F] text-white'
        : 'hover:bg-[#F7F4EB]' ?>">
    <i class="fas fa-user mr-2 w-3"></i> Profile
  </a>

</div>

