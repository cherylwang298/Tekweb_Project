<?php
// Ambil page dari query string
$currentPage = $_GET['page'] ?? 'home';
?>

<header class="sticky top-0 z-50 w-full  backdrop-blur-lg border-b border-white/20">
  <div class="max-w-screen-xl mx-auto flex items-center py-4 px-6 sm:px-10">

    <div class="font-serif text-3xl font-bold text-[#2F3E46] tracking-wide select-none">
      Biblios
    </div>

    <nav class="hidden sm:flex items-center gap-3 ml-auto text-sm font-semibold">
      
      <?php
      $menus = [
          'home' => ['label' => 'Home', 'icon' => 'fa-home', 'link' => 'index.php?page=home'],
          'my_library' => ['label' => 'My Library', 'icon' => 'fa-book-open', 'link' => 'index.php?page=my_library'],
          'profile' => ['label' => 'Profile', 'icon' => 'fa-user', 'link' => 'index.php?page=profile'],
      ];

      foreach ($menus as $key => $menu): 
          $isActive = ($currentPage === $key);
      ?>
        <a href="<?= $menu['link'] ?>"
           class="group flex items-center gap-2 px-5 py-2.5 rounded-xl transition-all duration-300
           <?= $isActive 
               ? 'bg-[#1D5C63]/10 text-[#1D5C63] ring-1 ring-[#1D5C63]/20 shadow-sm backdrop-blur-md' 
               : 'text-[#2F3E46]/70 hover:text-[#1D5C63] hover:bg-white/50' ?>">
          <i class="fas <?= $menu['icon'] ?> text-xs transition-transform group-hover:scale-110"></i>
          <span><?= $menu['label'] ?></span>
        </a>
      <?php endforeach; ?>

    </nav>

    <button id="menu-button"
            class="ml-auto p-2 text-2xl text-[#2F3E46] sm:hidden hover:bg-[#1D5C63]/5 rounded-lg transition-colors">
      <i class="fas fa-bars-staggered"></i>
    </button>
  </div>
</header>

<div id="mobile-menu"
     class="hidden fixed inset-x-6 top-20 z-50 flex-col space-y-2 p-3 bg-white/80 backdrop-blur-xl shadow-2xl rounded-2xl border border-white/40 ring-1 ring-black/5">
  
  <?php foreach ($menus as $key => $menu): 
      $isActive = ($currentPage === $key);
  ?>
    <a href="<?= $menu['link'] ?>"
       class="flex items-center gap-3 py-3 px-4 rounded-xl transition-all
       <?= $isActive 
           ? 'bg-[#1D5C63] text-white shadow-lg shadow-[#1D5C63]/20' 
           : 'text-[#2F3E46] hover:bg-white/60' ?>">
      <i class="fas <?= $menu['icon'] ?> w-5"></i>
      <span class="font-medium"><?= $menu['label'] ?></span>
    </a>
  <?php endforeach; ?>
</div>

<script>
  const menuBtn = document.getElementById('menu-button');
  const mobileMenu = document.getElementById('mobile-menu');

  menuBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    mobileMenu.classList.toggle('hidden');
    mobileMenu.classList.toggle('flex');
  });

  document.addEventListener('click', (e) => {
    if (!menuBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
      mobileMenu.classList.add('hidden');
      mobileMenu.classList.remove('flex');
    }
  });
</script>