<?php
require 'function.php';
require 'ceksession.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - Kelola Konten</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style type="text/css">
        .ck-editor__editable[role="textbox"] {
            /* Editing area */
            min-height: 350px;
        }
    </style>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.php">MENU UTAMA</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto me-0 me-md-3 my-2 my-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                    </div>
                    <div class="nav">
                        <a class="nav-link" href="artikel.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-file-earmark-text"></i></div>
                            Artikel
                        </a>
                    </div>
                    <div class="nav">
                        <a class="nav-link" href="kategori.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-bookmark-check-fill"></i></div>
                            Kategori
                        </a>
                    </div>
                    <div class="nav">
                        <a class="nav-link" href="penulis.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-person-fill"></i></div>
                            Penulis
                        </a>
                    </div>
                    <div class="nav">
                        <a class="nav-link" href="logout.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-door-closed-fill"></i></div>
                            Logout
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?php echo $_SESSION["email"] ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Artikel</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Silahkan kelola artikel</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="bi bi-file-earmark-text"></i>
                            <button type="button" class="btn btn-primary" name="btn_artikel_baru" id="btn_artikel_baru" data-bs-toggle="modal" data-bs-target="#modalFormArtikel">
                                Artikel Baru
                            </button>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Judul</th>
                                        <th>Kategori</th>
                                        <th>Penulis</th>
                                        <th>Gambar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT kontributor.id_kontributor, 
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
                                            ORDER BY id_kontributor DESC";

                                    $result = mysqli_query($conn, $sql);
                                    $nomor_urut = 0;

                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $nomor_urut++;
                                            $data_tanggal = $row['tanggal'];
                                            $data_judul = $row['judul'];
                                            $data_kategori = $row['nama_kategori'];
                                            $data_penulis = $row['nama_penulis'];
                                            $data_gambar = $row['gambar'];
                                            $data_id_kontributor = $row['id_kontributor'];
                                            $data_idkategori = $row['id_kategori'];
                                            $data_isi = $row['isi'];
                                    ?>
                                            <tr>
                                                <td><?php echo $nomor_urut; ?></td>
                                                <td><?php echo $data_tanggal; ?></td>
                                                <td><?php echo $data_judul; ?></td>
                                                <td><?php echo $data_kategori; ?></td>
                                                <td><?php echo $data_penulis; ?></td>
                                                <td><?php echo $data_gambar; ?></td>
                                                <td>
                                                    <button class="btn btn-warning btn-sm" name="btn_ubah" data-bs-toggle="modal" data-bs-target="#modalUbahArtikel<?php echo $data_id_kontributor; ?>">Ubah</button>

                                                    <button class="btn btn-danger btn-sm" name="btn_hapus" data-bs-toggle="modal" data-bs-target="#modalHapusArtikel<?php echo $data_id_kontributor; ?>">Hapus</button>
                                                </td>
                                            </tr>
                                            <!-- Modal Hapus Artikel -->
                                            <div class="modal fade" id="modalHapusArtikel<?php echo $data_id_kontributor; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Hapus Data</h4>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <!-- Modal body -->
                                                        <form method="POST">
                                                            <div class="modal-body mb-3">
                                                                Apakah artikel dengan judul: <?php echo "<b>" . $data_judul . "</b>"; ?> akan dihapus?
                                                                <div class="mb-3 mt-3 d-flex justify-content-end gap-2">
                                                                    <button class="btn btn-danger" name="btn_hapus_artikel">Hapus</button>
                                                                    <input type="hidden" name="id_hapus_artikel" value="<?php echo $data_id_kontributor; ?>">
                                                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Batal</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Form Ubah Artikel -->
                                            <div class="modal fade" data-bs-backdrop="static" id="modalUbahArtikel<?php echo $data_id_kontributor; ?>">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content">
                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Ubah Artikel</h4>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <!-- Modal Form Ubah artikel -->
                                                        <div class="modal-body">
                                                            <form method="POST" enctype="multipart/form-data">
                                                                <div class="mb-3 mt-3">
                                                                    <label for="ubah_tanggal<?php echo $data_id_kontributor; ?>" class="form-label">Tanggal:</label>
                                                                    <input type="text" class="form-control" id="ubah_tanggal<?php echo $data_id_kontributor; ?>" name="tanggal" value="<?php echo $data_tanggal; ?>" readonly>
                                                                </div>
                                                                <div class="mb-3 mt-3">
                                                                    <label for="ubah_judul<?php echo $data_id_kontributor; ?>" class="form-label">Judul:</label>
                                                                    <input type="text" class="form-control" id="ubah_judul<?php echo $data_id_kontributor; ?>" name="judul" value="<?php echo $data_judul; ?>">
                                                                </div>
                                                                <div class="mb-3 mt-3">
                                                                    <label for="ubah_kategori<?php echo $data_id_kontributor; ?>" class="form-label">Kategori:</label>
                                                                    <select class="form-select" id="ubah_kategori<?php echo $data_id_kontributor; ?>" name="kategori">
                                                                        <?php
                                                                        $sql_kategori = "SELECT id_kategori, nama_kategori FROM kategori";
                                                                        $result_kategori = mysqli_query($conn, $sql_kategori);

                                                                        if (mysqli_num_rows($result_kategori) > 0) {
                                                                            while ($row_kategori = mysqli_fetch_assoc($result_kategori)) {
                                                                                $data_id_kategori = $row_kategori['id_kategori'];
                                                                                $data_nama_kategori = $row_kategori['nama_kategori'];
                                                                        ?>
                                                                                <option value="<?php echo $data_id_kategori ?>" <?php if ($data_id_kategori == $data_idkategori) echo "selected"; ?>>
                                                                                    <?php echo $data_nama_kategori; ?>
                                                                                </option>
                                                                        <?php
                                                                            }
                                                                        } else {
                                                                            echo "0 results";
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3 mt-3">
                                                                    <label for="ubah_isi<?php echo $data_id_kontributor; ?>" class="form-label">Isi artikel:</label>
                                                                    <textarea class="form-control" rows="5" id="ubah_isi<?php echo $data_id_kontributor; ?>" name="isi"><?php echo $data_isi; ?></textarea>
                                                                </div>
                                                                <div class="mb-3 mt-3">
                                                                    <div class="mb-3">
                                                                        <label for="ubah_gambar<?php echo $data_id_kontributor; ?>" class="form-label">Gambar:</label>
                                                                        <input class="form-control" type="file" id="ubah_gambar<?php echo $data_id_kontributor; ?>" name="gambar">
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <input type="hidden" name="id_kontributor" 
                                                                    value="<?php echo $data_id_kontributor;?>">
                                                                </div>
                                                                <div class="mb-3 mt-3 d-flex justify-content-end gap-2">
                                                                    <button class="btn btn-primary" name="btn_ubah_artikel">Ubah</button>
                                                                    <button class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    } else {
                                        echo "0 results";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/super-build/ckeditor.js"></script>

    <!-- Modal Form Artikel Baru -->
    <div class="modal fade" data-bs-backdrop="static" id="modalFormArtikel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Artikel Baru</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <!-- Modal Form artikel -->
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data">
                    <?php
                    date_default_timezone_set('Asia/Jakarta');
                    $hari = date("l");
                    $hari_indonesia = hariIndonesia($hari);
                    $tahun = date("Y");
                    $bulan = date("m");
                    $nama_bulan = namaBulan($bulan);
                    $tanggal = date("d");
                    $tanggal_lengkap = $tanggal . " " . $nama_bulan . " ". $tahun;
                    $hari_tanggal = $hari_indonesia . ", ".  $tanggal_lengkap;
                    $waktu = date("H:i");
                    $hari_tanggal_waktu = $hari_tanggal . " | ". $waktu;
                ?>
                    <div class="mb-3 mt-3">
                        <label for="tanggal" class="form-label">Tanggal:</label>
                        <input type="text" class="form-control" id="tanggal"  name="tanggal" 
                        value="<?php echo $hari_tanggal_waktu; ?>" readonly>
                    </div>
                        <div class="mb-3 mt-3">
                            <label for="judul" class="form-label">Judul:</label>
                            <input type="text" class="form-control" id="judul" name="judul">
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="kategori" class="form-label">Kategori:</label>
                            <select class="form-select" id="kategori" name="kategori">
                                <?php
                                $sql_kategori = "SELECT id_kategori, nama_kategori FROM kategori";
                                $result_kategori = mysqli_query($conn, $sql_kategori);

                                if (mysqli_num_rows($result_kategori) > 0) {
                                    while ($row_kategori = mysqli_fetch_assoc($result_kategori)) {
                                        $data_id_kategori = $row_kategori['id_kategori'];
                                        $data_nama_kategori = $row_kategori['nama_kategori'];
                                ?>
                                        <option value="<?php echo $data_id_kategori ?>">
                                            <?php echo $data_nama_kategori; ?>
                                        </option>
                                <?php
                                    }
                                } else {
                                    echo "0 results";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="isi" class="form-label">Isi artikel:</label>
                            <textarea class="form-control" rows="5" id="isi" name="isi"></textarea>
                        </div>
                        <div class="mb-3 mt-3">
                            <div class="mb-3">
                                <label for="gambar" class="form-label">Gambar:</label>
                                <input class="form-control" type="file" id="gambar" name="gambar">
                            </div>
                        </div>
                        <div class="mb-3 mt-3 d-flex justify-content-end gap-2">
                            <button class="btn btn-primary" name="btn_simpan">Simpan</button>
                            <button class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize CKEditor for creating a new article
        CKEDITOR.ClassicEditor.create(document.getElementById("isi"), {
            toolbar: {
                items: [
                    'exportPDF', 'exportWord', '|',
                    'findAndReplace', 'selectAll', '|',
                    'heading', '|',
                    'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
                    'bulletedList', 'numberedList', 'todoList', '|',
                    'outdent', 'indent', '|',
                    'undo', 'redo',
                    '-',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                    'alignment', '|',
                    'link', 'uploadImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed', '|',
                    'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                    'textPartLanguage', '|',
                    'sourceEditing'
                ],
                shouldNotGroupWhenFull: true
            },
            placeholder: 'Tulis artikel di sini....!',
            fontFamily: {
                options: [
                    'default',
                    'Arial, Helvetica, sans-serif',
                    'Courier New, Courier, monospace',
                    'Georgia, serif',
                    'Lucida Sans Unicode, Lucida Grande, sans-serif',
                    'Tahoma, Geneva, sans-serif',
                    'Times New Roman, Times, serif',
                    'Trebuchet MS, Helvetica, sans-serif',
                    'Verdana, Geneva, sans-serif'
                ],
                supportAllValues: true
            },
            fontSize: {
                options: [10, 12, 14, 'default', 18, 20, 22],
                supportAllValues: true
            },
            htmlSupport: {
                allow: [
                    {
                        name: /.*/,
                        attributes: true,
                        classes: true,
                        styles: true
                    }
                ]
            },
            htmlEmbed: {
                showPreviews: true
            },
            link: {
                decorators: {
                    addTargetToExternalLinks: true,
                    defaultProtocol: 'https://',
                    toggleDownloadable: {
                        mode: 'manual',
                        label: 'Downloadable',
                        attributes: {
                            download: 'file'
                        }
                    }
                }
            },
            mention: {
                feeds: [
                    {
                        marker: '@',
                        feed: [
                            '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
                            '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
                            '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
                            '@sugar', '@sweet', '@topping', '@wafer'
                        ],
                        minimumCharacters: 1
                    }
                ]
            },
            removePlugins: [
                'AIAssistant',
                'CKBox',
                'CKFinder',
                'EasyImage',
                'Base64UploadAdapter',
                'MultiLevelList',
                'RealTimeCollaborativeComments',
                'RealTimeCollaborativeTrackChanges',
                'RealTimeCollaborativeRevisionHistory',
                'PresenceList',
                'Comments',
                'TrackChanges',
                'TrackChangesData',
                'RevisionHistory',
                'Pagination',
                'WProofreader',
                'MathType',
                'SlashCommand',
                'Template',
                'DocumentOutline',
                'FormatPainter',
                'TableOfContents',
                'PasteFromOfficeEnhanced',
                'CaseChange'
            ]
        });

        // Initialize CKEditor for updating an article
        document.querySelectorAll('[id^="ubah_isi"]').forEach(editor => {
            CKEDITOR.ClassicEditor.create(editor, {
                toolbar: {
                    items: [
                        'exportPDF', 'exportWord', '|',
                        'findAndReplace', 'selectAll', '|',
                        'heading', '|',
                        'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
                        'bulletedList', 'numberedList', 'todoList', '|',
                        'outdent', 'indent', '|',
                        'undo', 'redo',
                        '-',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                        'alignment', '|',
                        'link', 'uploadImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed', '|',
                        'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                        'textPartLanguage', '|',
                        'sourceEditing'
                    ],
                    shouldNotGroupWhenFull: true
                },
                placeholder: 'Tulis artikel di sini....!',
                fontFamily: {
                    options: [
                        'default',
                        'Arial, Helvetica, sans-serif',
                        'Courier New, Courier, monospace',
                        'Georgia, serif',
                        'Lucida Sans Unicode, Lucida Grande, sans-serif',
                        'Tahoma, Geneva, sans-serif',
                        'Times New Roman, Times, serif',
                        'Trebuchet MS, Helvetica, sans-serif',
                        'Verdana, Geneva, sans-serif'
                    ],
                    supportAllValues: true
                },
                fontSize: {
                    options: [10, 12, 14, 'default', 18, 20, 22],
                    supportAllValues: true
                },
                htmlSupport: {
                    allow: [
                        {
                            name: /.*/,
                            attributes: true,
                            classes: true,
                            styles: true
                        }
                    ]
                },
                htmlEmbed: {
                    showPreviews: true
                },
                link: {
                    decorators: {
                        addTargetToExternalLinks: true,
                        defaultProtocol: 'https://',
                        toggleDownloadable: {
                            mode: 'manual',
                            label: 'Downloadable',
                            attributes: {
                                download: 'file'
                            }
                        }
                    }
                },
                mention: {
                    feeds: [
                        {
                            marker: '@',
                            feed: [
                                '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
                                '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
                                '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
                                '@sugar', '@sweet', '@topping', '@wafer'
                            ],
                            minimumCharacters: 1
                        }
                    ]
                },
                removePlugins: [
                    'AIAssistant',
                    'CKBox',
                    'CKFinder',
                    'EasyImage',
                    'Base64UploadAdapter',
                    'MultiLevelList',
                    'RealTimeCollaborativeComments',
                    'RealTimeCollaborativeTrackChanges',
                    'RealTimeCollaborativeRevisionHistory',
                    'PresenceList',
                    'Comments',
                    'TrackChanges',
                    'TrackChangesData',
                    'RevisionHistory',
                    'Pagination',
                    'WProofreader',
                    'MathType',
                    'SlashCommand',
                    'Template',
                    'DocumentOutline',
                    'FormatPainter',
                    'TableOfContents',
                    'PasteFromOfficeEnhanced',
                    'CaseChange'
                ]
            });
        });
    </script>
</body>
</html>
