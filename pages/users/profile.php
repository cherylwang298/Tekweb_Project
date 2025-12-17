<?php
require 'config/koneksi.php';
include 'partials/navbar.php'; // Sesuaikan path jika perlu

/* ================= AUTH ================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success = false;
$error   = '';

/* ================= FETCH USER DATA ================= */
$stmtUser = $conn->prepare("SELECT id, username, name, email, created_at FROM users WHERE id = ?");
$stmtUser->bind_param("i", $user_id);
$stmtUser->execute();
$userData = $stmtUser->get_result()->fetch_assoc();

/* ================= CHANGE PASSWORD LOGIC ================= */
if (isset($_POST['change_password'])) {
    $current = $_POST['current_password'];
    $new     = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if (empty($current) || empty($new) || empty($confirm)) {
        $error = "All password fields are required.";
    } elseif ($new !== $confirm) {
        $error = "New password confirmation does not match.";
    } else {
        $stmt = $conn->prepare("SELECT password_hash FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if (!password_verify($current, $user['password_hash'])) {
            $error = "Current password is incorrect.";
        } else {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            $update->bind_param("si", $hash, $user_id);
            $update->execute();
            $success = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblios - My Profile</title>
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
        @keyframes blobMove1 { 0% { transform: translate(0, 0) scale(1); } 50% { transform: translate(40px, -40px) scale(1.1); } 100% { transform: translate(0, 0) scale(1); } }
        @keyframes blobMove2 { 0% { transform: translate(0, 0) scale(1); } 50% { transform: translate(-50px, 30px) scale(1.05); } 100% { transform: translate(0, 0) scale(1); } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.4); }
    </style>
</head>

<body class="bg-primary-bg font-sans text-text-dark min-h-screen relative overflow-x-hidden">

    <div class="fixed top-0 left-0 w-80 h-80 bg-[#B7D1C3] rounded-full blur-3xl opacity-40 -z-10" style="animation: blobMove1 20s infinite;"></div>
    <div class="fixed bottom-0 right-0 w-[30rem] h-[30rem] bg-[#84A98C] rounded-full blur-3xl opacity-30 -z-10" style="animation: blobMove2 26s infinite;"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 py-12">

         <div class="mb-8 animate-fade-in">

        <h1 class="font-serif text-3xl text-accent-dark font-extrabold tracking-tight">Account &  Profile Settings</h1>

    </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1 space-y-6 animate-fade-in" style="animation-delay: 0.1s;">
                <div class="glass rounded-[2.5rem] p-8 shadow-xl text-center">
                    <div class="w-24 h-24 bg-accent-dark/10 text-accent-dark rounded-full flex items-center justify-center text-3xl mx-auto mb-4 border border-accent-dark/20 shadow-inner">
                        <i class="fas fa-user"></i>
                    </div>
                    <h2 class="text-xl font-bold"><?= htmlspecialchars($userData['name']) ?></h2>
                    <p class="text-accent-dark font-medium text-sm">@<?= htmlspecialchars($userData['username']) ?></p>
                    
                    <div class="mt-8 pt-6 border-t border-light-gray/40 space-y-4">
                        <button onclick="openPasswordModal()" class="w-full py-3 bg-accent-dark text-white rounded-2xl font-bold text-sm hover:bg-accent-hover transition-all shadow-md active:scale-95">
                            <i class="fas fa-key mr-2"></i> Change Password
                        </button>
                        <button onclick="openLogoutModal()" class="w-full py-3 bg-white text-red-500 border border-red-50 rounded-2xl font-bold text-sm hover:bg-red-50 transition-all active:scale-95">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6 animate-fade-in" style="animation-delay: 0.2s;">
                <div class="glass rounded-[2.5rem] p-8 sm:p-10 shadow-xl">
                    <h3 class="font-serif text-2xl font-bold mb-8 flex items-center gap-3">
                        <i class="fas fa-id-card text-accent-dark"></i> Profile Details
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Full Name</p>
                            <p class="text-lg font-semibold text-text-dark"><?= htmlspecialchars($userData['name']) ?></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Username</p>
                            <p class="text-lg font-semibold text-text-dark">@<?= htmlspecialchars($userData['username']) ?></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Email Address</p>
                            <p class="text-lg font-semibold text-text-dark"><?= htmlspecialchars($userData['email']) ?></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Join Date</p>
                            <p class="text-lg font-semibold text-text-dark"><?= date("d F Y", strtotime($userData['created_at'])) ?></p>
                        </div>
                    </div>
                </div>

                <a href="index.php?page=statistik" class="glass rounded-[2.5rem] p-8 shadow-xl flex flex-col md:flex-row items-center justify-between gap-6 overflow-hidden relative group hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 block cursor-pointer">
                    <div class="relative z-10">
                        <h3 class="font-serif text-2xl font-bold mb-2 group-hover:text-accent-dark transition-colors">Reading Analytics</h3>
                        <p class="text-gray-500 text-sm max-w-sm">Discover your reading habits and visualize your book collection statistics.</p>
                        <span class="inline-flex items-center mt-6 text-accent-dark font-bold text-sm group-hover:gap-3 transition-all">
                            View My Statistics <i class="fas fa-arrow-right ml-2"></i>
                        </span>
                    </div>
                    <div class="text-accent-dark/10 text-9xl absolute -right-4 -bottom-4 rotate-12 group-hover:rotate-0 group-hover:scale-110 group-hover:text-accent-dark/20 transition-all duration-700">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div id="passwordModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-[100] p-4">
        <div class="bg-white/90 backdrop-blur-2xl rounded-[2.5rem] p-8 sm:p-10 max-w-md w-full border border-white/50 shadow-2xl animate-fade-in">
            <div class="mb-6">
                
                <h2 class="font-serif text-3xl font-extrabold mb-1">Change Password</h2>
                <p class="text-gray-500 text-sm">Please enter your current and new password.</p>
            </div>

            <form method="POST" id="passwordForm" class="space-y-4">
                <input type="password" name="current_password" placeholder="Current Password" required
                    class="w-full px-5 py-4 bg-white border border-light-gray rounded-2xl outline-none focus:ring-2 focus:ring-accent-dark transition-all">
                <input type="password" name="new_password" placeholder="New Password" required
                    class="w-full px-5 py-4 bg-white border border-light-gray rounded-2xl outline-none focus:ring-2 focus:ring-accent-dark transition-all">
                <input type="password" name="confirm_password" placeholder="Confirm New Password" required
                    class="w-full px-5 py-4 bg-white border border-light-gray rounded-2xl outline-none focus:ring-2 focus:ring-accent-dark transition-all">
                
                <div class="flex gap-3 pt-6">
                    <button type="button" onclick="closePasswordModal()" class="flex-1 py-4 bg-gray-100 text-gray-500 rounded-2xl font-bold hover:bg-gray-200 transition-all">Cancel</button>
                    <button type="button" onclick="submitPasswordForm()" class="flex-1 py-4 bg-accent-dark text-white rounded-2xl font-bold hover:bg-accent-hover shadow-md transition-all">Update</button>
                </div>
                <input type="hidden" name="change_password" value="1">
            </form>
        </div>
    </div>

<!-- 
    <div id="logoutModal" class="fixed inset-0 z-[100] hidden items-center justify-center px-4">
    <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" onclick="closeLogoutModal()"></div>
    
    <div class="relative bg-white p-8 rounded-2xl shadow-xl w-full max-w-[320px] text-center animate-fade-in">
        <h2 class="font-serif text-2xl text-accent-dark mb-4">Logout</h2>
        <p class="text-text-dark mb-8">Apakah anda yakin ingin keluar?</p>
        
        <div class="flex gap-3">
            <button onclick="closeLogoutModal()" 
                    class="flex-1 py-2.5 bg-light-gray text-text-dark rounded-xl font-semibold hover:bg-gray-300 transition-all">
                Batal
            </button>
            <a href="logout.php" 
               class="flex-1 py-2.5 bg-accent-dark text-white rounded-xl font-semibold hover:bg-accent-hover transition-all text-center">
                Keluar
            </a>
        </div>
    </div>
</div> -->

    <div id="logoutModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-[100] p-4">
  <div class="bg-white/90 backdrop-blur-2xl rounded-[2.5rem] p-6 max-w-sm w-full text-center border border-white/50 shadow-2xl">

    <div class="w-14 h-14 bg-red-50 text-red-500 rounded-full flex items-center justify-center text-xl mx-auto mb-4">
      <i class="fas fa-sign-out-alt"></i>
    </div>

    <h3 class="text-lg font-bold mb-1">Logout?</h3>
    <p class="text-gray-500 text-sm mb-6">Are you sure you want to leave?</p>

    <div class="flex gap-2">
      <button onclick="closeLogoutModal()" class="flex-1 py-2.5 rounded-xl font-bold bg-gray-100">
        Cancel
      </button>
      <a href="/tekweb_project/api/auth/logout.php" class="flex-1 py-2.5 rounded-xl font-bold bg-red-500 text-white flex items-center justify-center">
        Logout
      </a>
    </div>

  </div>
</div>


    <div id="toastSuccess" class="fixed top-8 right-8 bg-accent-dark text-white px-6 py-4 rounded-2xl shadow-2xl hidden z-[200] animate-fade-in">
        <i class="fas fa-check-circle mr-2"></i> Password updated successfully!
    </div>

    <script>
        function openPasswordModal() { document.getElementById('passwordModal').classList.replace('hidden', 'flex'); }
        function closePasswordModal() { document.getElementById('passwordModal').classList.replace('flex', 'hidden'); }
        function openConfirmModal() { document.getElementById('confirmModal').classList.replace('hidden', 'flex'); }
        function closeConfirmModal() { document.getElementById('confirmModal').classList.replace('flex', 'hidden'); }
        function submitPasswordForm() { document.getElementById('passwordForm').submit(); }
        function openLogoutModal() { document.getElementById('logoutModal').classList.replace('hidden', 'flex'); }
        function closeLogoutModal() { document.getElementById('logoutModal').classList.replace('flex', 'hidden'); }

        <?php if ($success): ?>
            window.onload = () => {
                const toast = document.getElementById('toastSuccess');
                toast.classList.remove('hidden');
                setTimeout(() => toast.classList.add('hidden'), 3000);
            }
        <?php endif; ?>
    </script>
</body>
</html>