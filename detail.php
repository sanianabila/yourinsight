<?php
require 'admin/function.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Detail Artikel - Your Insight</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
        <style>
            /* Add this style for the flexbox layout */
            body {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }
            .content {
                flex: 1;
            }
            .img-fluid {
                width: 100%;
                height: auto;
            }
        </style>
    </head>
    <body>
        <!-- Responsive navbar-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="http://localhost/belajar web">Your Insight</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="http://localhost/belajar web">Beranda</a></li>
                        <li class="nav-item"><a class="nav-link" href="http://localhost/belajar%20web#tentang">Tentang</a></li>
                        <li class="nav-item"><a class="nav-link" href="http://localhost/belajar%20web#kontak">Kontak</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Page content-->
        <div class="container mt-5 content">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Post content-->
                    <article>
                    <?php
                        // Get the id_kontributor and id_kategori from the URL
                        if (isset($_GET['id_kontributor']) && isset($_GET['id_kategori'])) {
                            $data_id_kontributor = $_GET['id_kontributor'];
                            $data_id_kategori = $_GET['id_kategori'];
                            
                            $sql = "SELECT 
                                    kontributor.id_kontributor, 
                                    kontributor.id_kategori,
                                    artikel.tanggal, 
                                    artikel.judul, 
                                    artikel.isi,
                                    penulis.nama_penulis, 
                                    kategori.nama_kategori, 
                                    kategori.id_kategori,
                                    artikel.id_artikel,
                                    artikel.gambar,
                                    artikel.views
                                    FROM kontributor 
                                    JOIN artikel ON kontributor.id_artikel = artikel.id_artikel 
                                    JOIN penulis ON kontributor.id_penulis = penulis.id_penulis 
                                    JOIN kategori ON kontributor.id_kategori = kategori.id_kategori
                                    WHERE kontributor.id_kontributor = '$data_id_kontributor' AND kontributor.id_kategori = '$data_id_kategori'";

                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $data_tanggal = $row['tanggal'];
                                    $data_judul = $row['judul'];
                                    $data_kategori = $row['nama_kategori'];
                                    $data_penulis = $row['nama_penulis'];
                                    $data_gambar = $row['gambar'];
                                    $data_isi = $row['isi'];
                                    $data_id_artikel = $row['id_artikel'];

                                    // Update views count
                                    $update_views_sql = "UPDATE artikel SET views = views + 1 WHERE id_artikel = '$data_id_artikel'";
                                    mysqli_query($conn, $update_views_sql);

                                    ?>
                        <!-- Post header-->
                        <header class="mb-4">
                            <!-- Post title-->
                            <h1 class="fw-bolder mb-1"><?php echo $data_judul; ?></h1>
                            <!-- Post meta content-->
                            <div class="text-muted fst-italic mb-2">Posted on <?php echo $data_tanggal; ?> by <?php echo $data_penulis; ?></div>
                            <!-- Post categories-->
                            <a class="badge bg-secondary text-decoration-none link-light" 
                            href="kategori.php?id_kategori=<?php echo $data_id_kategori; ?>"><?php echo $data_kategori; ?></a>
                        </header>
                        <!-- Preview image figure-->
                        <figure class="mb-4"><img class="img-fluid rounded" src="admin/<?php echo $data_gambar; ?>" alt="..." /></figure>
                        <!-- Post content-->
                        <section class="mb-5">
                            <?php echo $data_isi; ?>
                                    <div class="d-flex justify-content-end">
                                      <button class="btn btn-outline-primary" onclick="history.back()">Kembali</button>  
                                    </div>
                                    
                            </p>
                        </section>
                        <?php
                                }
                            } else {
                                echo "<p>Article not found.</p>";
                            }
                        } else {
                            echo "<p>Invalid article ID.</p>";
                        }
                        ?>
                    </article>
                </div>
                <!-- Side widgets-->
                <div class="col-lg-4">
                    <!-- Search widget-->
                    <div class="card mb-4">
                        <div class="card-header">Pencarian</div>
                        <div class="card-body">
                            <form action="index.php" method="GET">
                                <div class="input-group">
                                    <input class="form-control" name="search" type="text" placeholder="Masukkan kata kunci..." aria-label="Enter search term..." aria-describedby="button-search" />
                                    <button class="btn btn-primary" id="button-search" type="submit">Go!</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Categories widget-->
                    <div class="card mb-4">
                        <div class="card-header">Artikel Terkait</div>
                        <div class="card-body">
                            <div class="list-group">
                                <?php 
                                $data_id_kontributor = $_GET['id_kontributor'];
                                $data_id_kategori = $_GET['id_kategori'];

                                $sql = "SELECT 
                                        kontributor.id_kontributor, 
                                        kontributor.id_kategori,
                                        artikel.tanggal, 
                                        artikel.judul, 
                                        artikel.isi,
                                        penulis.nama_penulis, 
                                        kategori.nama_kategori, 
                                        kategori.id_kategori,
                                        artikel.gambar
                                        FROM kontributor 
                                        JOIN artikel ON kontributor.id_artikel = artikel.id_artikel 
                                        JOIN penulis ON kontributor.id_penulis = penulis.id_penulis 
                                        JOIN kategori ON kontributor.id_kategori = kategori.id_kategori
                                        WHERE kontributor.id_kategori = '$data_id_kategori' 
                                        AND kontributor.id_kontributor != '$data_id_kontributor'
                                        ORDER BY artikel.tanggal DESC
                                        LIMIT 5";  // Ambil 5 artikel terkait terbaru

                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $related_id_kontributor = $row['id_kontributor'];
                                        $related_id_kategori = $row['id_kategori'];
                                        $related_judul = $row['judul'];
                                ?>
                                        <a href="detail.php?id_kontributor=<?php echo $related_id_kontributor; ?>&id_kategori=<?php echo $related_id_kategori; ?>" 
                                           class="list-group-item list-group-item-action">
                                            <?php echo $related_judul; ?>
                                        </a>
                                <?php
                                    }
                                } else {
                                    echo "<p>No related articles found.</p>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- Side widget-->
                </div>
            </div>
        </div>
        <!-- Footer-->
        <footer class="py-5 bg-dark">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Insight 2024</p></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
