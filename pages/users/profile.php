<?php
session_start();
require '../../config/koneksi.php';

/* ================= AUTH ================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$success = false;
$error   = '';

/* ================= FETCH USER DATA ================= */
$stmtUser = $conn->prepare("
    SELECT id, username, name, email, created_at
    FROM users
    WHERE id = ?
");
$stmtUser->bind_param("i", $user_id);
$stmtUser->execute();
$userData = $stmtUser->get_result()->fetch_assoc();

/* ================= CHANGE PASSWORD ================= */
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
<title>Biblios | Profile</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<script>
tailwind.config = {
    theme: {
        extend: {
            colors: {
                'primary-bg': '#FEFAF1',
                'accent-dark': '#1D5C63',
                'light-gray': '#EAEAEA',
                'error-soft': '#E76F51'
            },
            fontFamily: {
                serif: ['Playfair Display', 'serif'],
                sans: ['Inter', 'sans-serif'],
            },
        }
    }
}
</script>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>

<body class="font-sans bg-primary-bg text-gray-800">

<div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

<!-- NAVBAR -->
<header class="py-5">
    <?php include 'navbar.php'; ?>
</header>

<hr class="border-light-gray mb-8">

<h1 class="font-serif text-2xl sm:text-3xl text-accent-dark mb-8">
    Account & Profile Settings
</h1>

<!-- PROFILE + SETTINGS -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">

    <!-- ACCOUNT INFO -->
    <div class="md:col-span-2 bg-white p-6 sm:p-8 rounded-lg shadow-lg">
        <h2 class="font-serif text-xl sm:text-2xl text-accent-dark mb-4 border-b pb-2">
            Account Information
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">

            <div>
                <p class="text-sm text-gray-500">Username</p>
                <p class="font-semibold"><?= htmlspecialchars($userData['username']) ?></p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Full Name</p>
                <p class="font-semibold"><?= htmlspecialchars($userData['name']) ?></p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Email Address</p>
                <p class="font-semibold"><?= htmlspecialchars($userData['email']) ?></p>
            </div>

            <div class="sm:col-span-2">
                <p class="text-sm text-gray-500">Account Created</p>
                <p class="font-semibold">
                    <?= date("d F Y", strtotime($userData['created_at'])) ?>
                </p>
            </div>
        </div>

        <button onclick="openPasswordModal()"
                class="bg-accent-dark text-white px-4 py-2 rounded
                       hover:opacity-90 transition">
            Change Password
        </button>
    </div>

    <!-- OTHER SETTINGS -->
    <div class="bg-white p-6 sm:p-8 rounded-lg shadow-lg">
        <h2 class="font-serif text-xl sm:text-2xl text-accent-dark mb-4 border-b pb-2">
            Other Settings
        </h2>

        <div class="flex justify-between items-center mb-6">
            <span>Dark Mode</span>
            <input type="checkbox" disabled>
        </div>

        <button onclick="openLogoutModal()"
        class="inline-block text-red-600 border border-red-600
               px-4 py-2 rounded font-semibold hover:bg-red-50 transition">
            Logout
        </button>

    </div>
</div>

<!-- STATISTICS CTA -->
<div class="bg-white p-6 sm:p-8 rounded-lg shadow-lg mb-12">
    <h2 class="font-serif text-xl sm:text-2xl text-accent-dark mb-3">
        Reading Statistics
    </h2>

    <p class="text-gray-600 mb-4">
        View insights about your reading activity and book status.
    </p>

    <a href="statistik.php"
       class="inline-flex items-center bg-accent-dark text-white
              px-5 py-2.5 rounded-lg font-semibold hover:opacity-90 transition">
        <i class="fas fa-chart-bar mr-2"></i>
        View My Statistics
    </a>
</div>
</div>

<!-- ================= MODALS ================= -->

<!-- CHANGE PASSWORD MODAL -->
<div id="passwordModal"
     class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
        <h2 class="text-xl font-semibold text-accent-dark mb-4">
            Change Password
        </h2>

        <form method="POST" id="passwordForm" class="space-y-4">
            <input type="password" name="current_password"
                   placeholder="Current Password"
                   class="w-full p-2 border rounded" required>

            <input type="password" name="new_password"
                   placeholder="New Password"
                   class="w-full p-2 border rounded" required>

            <input type="password" name="confirm_password"
                   placeholder="Confirm New Password"
                   class="w-full p-2 border rounded" required>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closePasswordModal()"
                        class="px-4 py-2 border rounded">
                    Cancel
                </button>

                <button type="button" onclick="openConfirmModal()"
                        class="bg-accent-dark text-white px-4 py-2 rounded">
                    Update Password
                </button>
            </div>

            <input type="hidden" name="change_password" value="1">
        </form>
    </div>
</div>

<!-- CONFIRM MODAL -->
<div id="confirmModal"
     class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-sm p-6 text-center">
        <h3 class="text-lg font-semibold mb-3">
            Confirm Password Change
        </h3>

        <p class="text-gray-600 mb-5">
            Are you sure you want to update your password?
        </p>

        <div class="flex justify-center gap-4">
            <button onclick="closeConfirmModal()"
                    class="px-4 py-2 border rounded">
                Cancel
            </button>

            <button onclick="submitPasswordForm()"
                    class="bg-accent-dark text-white px-4 py-2 rounded">
                Yes, Update
            </button>
        </div>
    </div>
</div>

<!-- LOGOUT CONFIRM MODAL -->
<div id="logoutModal"
     class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

    <div class="bg-white rounded-xl shadow-xl w-full max-w-sm p-6 text-center">
        <h3 class="text-lg font-semibold mb-3 text-red-600">
            Confirm Logout
        </h3>

        <p class="text-gray-600 mb-5">
            Are you sure you want to log out from your account?
        </p>

        <div class="flex justify-center gap-4">
            <button onclick="closeLogoutModal()"
                    class="px-4 py-2 border rounded">
                Cancel
            </button>

            <a href="logout.php"
               class="bg-red-600 text-white px-4 py-2 rounded
                      hover:bg-red-700 transition">
                Yes, Logout
            </a>
        </div>
    </div>
</div>


<!-- SUCCESS TOAST -->
<div id="toastSuccess"
     class="fixed top-6 right-6 bg-emerald-600 text-white
            px-4 py-3 rounded-lg shadow-lg hidden z-50">
    Password updated successfully.
</div>

<!-- ERROR TOAST -->
<div id="toastError"
     class="fixed top-6 right-6 bg-error-soft text-white
            px-4 py-3 rounded-lg shadow-lg hidden z-50">
</div>

<!-- ================= JS ================= -->
<script>
function openPasswordModal() {
    document.getElementById('passwordModal').classList.remove('hidden');
    document.getElementById('passwordModal').classList.add('flex');
}
function closePasswordModal() {
    document.getElementById('passwordModal').classList.add('hidden');
}
function openConfirmModal() {
    document.getElementById('confirmModal').classList.remove('hidden');
    document.getElementById('confirmModal').classList.add('flex');
}
function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
}
function submitPasswordForm() {
    closeConfirmModal();
    document.getElementById('passwordForm').submit();
}
function showSuccessToast() {
    const t = document.getElementById('toastSuccess');
    t.classList.remove('hidden');
    setTimeout(() => t.classList.add('hidden'), 2500);
}
function showErrorToast(msg) {
    const t = document.getElementById('toastError');
    t.innerText = msg;
    t.classList.remove('hidden');
    setTimeout(() => t.classList.add('hidden'), 2500);
}
function openLogoutModal() {
    document.getElementById('logoutModal').classList.remove('hidden');
    document.getElementById('logoutModal').classList.add('flex');
}

function closeLogoutModal() {
    document.getElementById('logoutModal').classList.add('hidden');
}
</script>

<?php if ($success): ?>
<script>
window.onload = () => {
    showSuccessToast();
    closePasswordModal();
};
</script>
<?php endif; ?>

<?php if (!empty($error)): ?>
<script>
window.onload = () => {
    showErrorToast("<?= htmlspecialchars($error) ?>");
};
</script>
<?php endif; ?>

</body>
</html>
