<?php
require_once '../classes/Product.php';

$product = new Product();
$message = '';

// Handle form actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $product->save(
            $_POST['name'],
            $_POST['price'],
            $_POST['stock'],
            $_POST['description']
        );
        $message = "Produk berhasil ditambahkan!";
    }

    if ($action === 'update') {
        $product->update(
            $_POST['id'],
            $_POST['name'],
            $_POST['price'],
            $_POST['stock'],
            $_POST['description']
        );
        $message = "Produk berhasil diupdate!";
    }

    if ($action === 'delete') {
        $product->delete($_POST['id']);
        $message = "Produk berhasil dihapus!";
    }
}

// Ambil data untuk edit (jika ada)
$editProduct = null;
if (isset($_GET['edit'])) {
    $editProduct = $product->findById($_GET['edit']);
}

$products = $product->findAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Produk</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 40px auto; padding: 0 20px; }
        h1 { color: #333; }
        .message { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        form { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        input, textarea { width: 100%; padding: 8px; margin: 5px 0 15px; box-sizing: border-box; }
        button { background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #007bff; color: white; }
        .btn-edit { background: #ffc107; color: #000; padding: 5px 10px; text-decoration: none; border-radius: 3px; }
        .btn-delete { background: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Sistem Manajemen Produk</h1>

    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- Form Create / Update -->
    <form method="POST">
        <h2><?= $editProduct ? 'Edit Produk' : 'Tambah Produk Baru' ?></h2>
        
        <?php if ($editProduct): ?>
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?= $editProduct['id'] ?>">
        <?php else: ?>
            <input type="hidden" name="action" value="create">
        <?php endif; ?>

        <label>Nama Produk</label>
        <input type="text" name="name" required 
               value="<?= $editProduct['name'] ?? '' ?>">

        <label>Harga</label>
        <input type="number" name="price" step="0.01" required 
               value="<?= $editProduct['price'] ?? '' ?>">

        <label>Stok</label>
        <input type="number" name="stock" required 
               value="<?= $editProduct['stock'] ?? '' ?>">

        <label>Deskripsi</label>
        <textarea name="description" rows="3"><?= $editProduct['description'] ?? '' ?></textarea>

        <button type="submit">
            <?= $editProduct ? 'Update Produk' : 'Tambah Produk' ?>
        </button>
        
        <?php if ($editProduct): ?>
            <a href="index.php">Batal Edit</a>
        <?php endif; ?>
    </form>

    <!-- Tabel Daftar Produk -->
    <h2>Daftar Produk</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($products)): ?>
                <tr><td colspan="6">Belum ada data produk.</td></tr>
            <?php else: ?>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= htmlspecialchars($p['name']) ?></td>
                        <td>Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
                        <td><?= $p['stock'] ?></td>
                        <td><?= htmlspecialchars($p['description']) ?></td>
                        <td>
                            <a class="btn-edit" href="?edit=<?= $p['id'] ?>">Edit</a>
                            <form method="POST" style="display:inline;" 
                                  onsubmit="return confirm('Yakin hapus?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <button type="submit" class="btn-delete">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>