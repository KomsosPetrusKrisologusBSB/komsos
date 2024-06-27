<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// Cek apakah user adalah admin (misalnya berdasarkan username atau role)
$isAdmin = ($_SESSION['username'] == 'admin'); // Sesuaikan ini dengan logika aplikasi Anda

// Jika bukan admin, redirect ke halaman utama
if (!$isAdmin) {
    header("Location: http://localhost/my_website/my_website/index.html");
    exit();
}

// Jika admin, tampilkan halaman untuk menambah berita
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Welcome to the Admin Dashboard, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <p>This is a protected area only accessible to logged in users.</p>

        <!-- Form untuk menambah berita baru -->
        <h3>Add a New Article</h3>
        <form action="add_article.php" method="post">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Article</button>
        </form>

        <a href="logout.php" class="mt-3 d-block">Logout</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// add_article.php (ini adalah file terpisah)

session_start();
include 'config.php';

// Cek apakah user sudah login dan adalah admin
if (!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Escape input untuk mencegah SQL injection
    $title = $conn->real_escape_string($title);
    $content = $conn->real_escape_string($content);

    // Masukkan berita baru ke database
    $sql = "INSERT INTO articles (title, content) VALUES ('$title', '$content')";
    if ($conn->query($sql) === TRUE) {
        echo "New article added successfully!";
        header("Location: dashboard.php"); // Redirect kembali ke dashboard
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
