<?php
require 'admin/function.php';

$search_keyword = "";
if (isset($_GET['search'])) {
    $search_keyword = $_GET['search'];
}

$search_query = "";
if (!empty($search_keyword)) {
    $search_query = " AND (artikel.judul LIKE '%$search_keyword%' OR kategori.nama_kategori LIKE '%$search_keyword%')";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Home - Your Insight Blog</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
    <style type="text/css">
        .tentang {
            text-align: justify;
        }
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card.mb-4 {
            display: flex;
            flex-direction: column;
        }
        .nested-row .col-lg-6 {
            display: flex;
            flex-direction: column;
        }
        .widget-category {
            margin-top: 20px;
        }
        .widget-category h5 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            padding-bottom: 0.5rem;
            color: #000;
        }
        .widget-category .category-container {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
        }
        .widget-category .category-link {
            text-decoration: none;
            font-weight: bold;
            color: #000;
            display: block;
            margin-bottom: 0.5rem;
        }
        .widget-category .category-link:hover {
            text-decoration: underline;
            color: #007bff;
        }
        .widget-category .list-group-item {
            border: none;
            padding: 0.25rem 0.75rem;
            transition: background-color 0.3s, color 0.3s;
        }
        .widget-category .list-group-item:hover {
            background-color: #343a40;
            color: #ffffff;
        }
        .article-list .card {
            display: flex;
            flex-direction: row;
            align-items: center;
            margin-bottom: 1rem;
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }

        .article-list .card-img-container {
            flex: 0 0 200px; /* Adjust the width as needed */
            margin-right: 1rem;
        }

        .article-list .card-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 0.25rem;
        }

        .article-list .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .article-list .card-title {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }

        .article-list .card-text {
            flex: 1;
        }

        .article-list .btn {
            margin-top: auto;
        }

        .article-list .small {
            margin-bottom: 0.5rem;
            color: #6c757d;
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
                    <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Page header with logo and tagline-->
    <header class="py-5 bg-light border-bottom mb-4">
        <div class="container">
            <div class="text-center my-5">
                <h1 class="fw-bolder">Selamat datang di blog kami!</h1>
                <p class="lead mb-0">Blog insight terkini</p>
            </div>
        </div>
    </header>
    <!-- Page content-->
    <div class="container">
        <div class="row">
            <!-- Blog entries-->
            <div class="col-lg-8">
                
                <!-- Nested row for non-featured blog posts-->
                <div class="article-list">
                <?php
                $sql_post = "SELECT kontributor.id_kontributor, 
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
                        WHERE 1=1
                        $search_query
                        ORDER BY kontributor.id_kontributor DESC ";

                $result_post = mysqli_query($conn, $sql_post);
                $nomor_urut = 0;

                if (mysqli_num_rows($result_post) > 0) {
                    while ($row = mysqli_fetch_assoc($result_post)) {
                        $nomor_urut++;
                        $data_tanggal = $row['tanggal'];
                        $data_judul = $row['judul'];
                        $data_kategori = $row['nama_kategori'];
                        $data_id_kategori = $row['id_kategori'];
                        $data_penulis = $row['nama_penulis'];
                        $data_gambar = $row['gambar'];
                        $data_id_kontributor = $row['id_kontributor'];
                        $data_idkategori = $row['id_kategori'];
                        $data_isi = $row['isi'];
                        $data_potongan_artikel = potong_artikel($data_isi, 125);
                ?>
                    <div class="card">
                        <div class="card-img-container">
                            <img class="card-img-top" src="admin/<?php echo $data_gambar;?>" alt="..." />
                        </div>
                        <div class="card-body">
                            <div class="small text-muted"><?php echo $data_tanggal; ?></div>
                            <h2 class="card-title h4"><?php echo $data_judul; ?></h2>
                            <p class="card-text"><?php echo $data_potongan_artikel; ?></p>
                            <a class="btn btn-primary mt-auto" href="detail.php?id_kontributor=<?php echo $data_id_kontributor; ?>&id_kategori=<?php echo $data_id_kategori; ?>">Selengkapnya →</a>
                        </div>
                    </div>
                <?php
                    }
                } else {
                    echo "<p>No more articles found.</p>";
                }
                ?>
                </div>
                
            </div>
            <!-- Side widgets-->
            <div class="col-lg-4">
                <!-- Search widget-->
                <div class="card mb-4">
                    <div class="card-header">Pencarian</div>
                    <div class="card-body">
                        <form action="index.php" method="GET">
                            <div class="input-group">
                                <input class="form-control" name="search" type="text" placeholder="Masukkan kata kunci..." aria-label="Enter search term..." aria-describedby="button-search" value="<?php echo htmlspecialchars($search_keyword); ?>" />
                                <button class="btn btn-primary" id="button-search" type="submit">Go!</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Categories widget-->
                <div class="card mb-4">
                    <div class="card-header">Kategori</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="list-group">
                                <?php 
                                    $sql = "SELECT id_kategori, nama_kategori, keterangan FROM kategori ORDER BY id_kategori DESC";
                                    $result = mysqli_query($conn, $sql);

                                    if (mysqli_num_rows($result) > 0) {
                                        $nomor_urut = 0;
                                        // output data of each row
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $nomor_urut++;
                                            $data_id_kategori = $row['id_kategori'];
                                            $data_nama = $row['nama_kategori'];
                                            $data_keterangan = $row['keterangan'];
                                            ?>
                                        
                                        <a href="kategori.php?id_kategori=<?php echo $data_id_kategori; ?>" class="list-group-item list-group-item-action"><?php echo $data_nama;?></a>
                                        <?php
                                        }
                                    } else {
                                        echo "<p>No categories found.</p>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Side widget-->
                <div id="tentang" class="card mb-4">
                    <div class="card-header">Tentang</div>
                    <div class="card-body tentang">Selamat datang di Your Insight, situs yang menyajikan artikel-artikel terkini dan relevan mengenai berbagai isu.
                         Kami berusaha memberikan informasi yang akurat dan bermanfaat untuk membantu Anda tetap terupdate dengan perkembangan terbaru.</div>
                </div>
                <div id="kontak" class="card mb-4">
                    <div class="card-header">Kontak</div>
                    <div class="card-body tentang">Jika Anda memiliki pertanyaan, saran, atau masukan, 
                        jangan ragu untuk menghubungi kami. 
                        <br> 
                        Nomor : +6285740871136
                        <br>
                        Email : yourinsight@gmail.com
                    </div>
                </div>
                <!-- Popular Blog Posts widget-->
                <div id="populer" class="card mb-4">
                    <div class="card-header">Artikel Populer</div>
                    <div class="card-body">
                        <div class="list-group">
                            <?php 
                            $sql_popular = "SELECT kontributor.id_kontributor, kontributor.id_kategori, artikel.judul, artikel.views 
                                            FROM kontributor
                                            JOIN artikel ON kontributor.id_artikel = artikel.id_artikel 
                                            ORDER BY artikel.views DESC 
                                            LIMIT 5";

                            $result_popular = mysqli_query($conn, $sql_popular);

                            if (mysqli_num_rows($result_popular) > 0) {
                                while ($row = mysqli_fetch_assoc($result_popular)) {
                                    $popular_id_kontributor = $row['id_kontributor'];
                                    $popular_id_kategori = $row['id_kategori'];
                                    $popular_judul = $row['judul'];
                                    $popular_views = $row['views'];
                            ?>
                                    <a href="detail.php?id_kontributor=<?php echo $popular_id_kontributor; ?>&id_kategori=<?php echo $popular_id_kategori; ?>" class="list-group-item list-group-item-action">
                                        <?php echo $popular_judul; ?> (<?php echo $popular_views; ?> views)
                                    </a>
                            <?php
                                }
                            } else {
                                echo "<p>No popular articles found.</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <!-- Articles by Category widget -->
                <div id="artikel_per_kategori" class="card mb-4">
                    <div class="card-header">Artikel Per Kategori</div>
                    <div class="card-body">
                        <div class="widget-category">
                            <?php 
                            $sql_categories = "SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori ASC";
                            $result_categories = mysqli_query($conn, $sql_categories);

                            if (mysqli_num_rows($result_categories) > 0) {
                                while ($category = mysqli_fetch_assoc($result_categories)) {
                                    $category_id = $category['id_kategori'];
                                    $category_name = $category['nama_kategori'];

                                    echo "<div class='category-container'>";
                                    echo "<a href='kategori.php?id_kategori=$category_id' class='category-link'>$category_name</a>";

                                    $sql_articles = "SELECT kontributor.id_kontributor, kontributor.id_kategori, artikel.judul 
                                                     FROM kontributor
                                                     JOIN artikel ON kontributor.id_artikel = artikel.id_artikel 
                                                     WHERE kontributor.id_kategori = $category_id
                                                     ORDER BY artikel.tanggal DESC 
                                                     LIMIT 2";

                                    $result_articles = mysqli_query($conn, $sql_articles);

                                    if (mysqli_num_rows($result_articles) > 0) {
                                        while ($article = mysqli_fetch_assoc($result_articles)) {
                                            $article_id_kontributor = $article['id_kontributor'];
                                            $article_id_kategori = $article['id_kategori'];
                                            $article_title = $article['judul'];

                                            echo "<a href='detail.php?id_kontributor=$article_id_kontributor&id_kategori=$article_id_kategori' class='list-group-item list-group-item-action'>$article_title</a>";
                                        }
                                    } else {
                                        echo "<p>No articles found in this category.</p>";
                                    }
                                    echo "</div>";
                                }
                            } else {
                                echo "<p>No categories found.</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer-->
    <footer class="py-5 bg-dark mt-auto">
        <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Insight 2024</p></div>
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
</body>
</html>
