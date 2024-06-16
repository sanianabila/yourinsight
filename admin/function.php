<?php

session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webpage_db";
$port = 3306;

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname, $port);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['btn_login'])) {
    $data_email = $_POST['email'];
    $data_password = md5($_POST['password']);

    $sql = "SELECT * FROM penulis WHERE email='$data_email' AND password='$data_password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $_SESSION['idpenulis'] = $row['id_penulis'];
        }
    } else {
        echo "0 results";
    }
    $_SESSION["email"] = $data_email;
    $_SESSION["password"] = $data_password;
    header('location:index.php');
}

if (isset($_POST['btn_hapus_artikel'])) {
    $id_hapus = $_POST['id_hapus_artikel'];
    // First, delete from kontributor table
    $sql_hapus_kontributor = "DELETE FROM kontributor WHERE id_kontributor = '$id_hapus'";

    if (mysqli_query($conn, $sql_hapus_kontributor)) {
        // Now delete from artikel table
        $sql_hapus_artikel = "DELETE FROM artikel WHERE id_artikel IN (
                                SELECT id_artikel
                                FROM kontributor
                                WHERE id_kontributor = '$id_hapus'
                              )";

        if (mysqli_query($conn, $sql_hapus_artikel)) {
            echo "<script>
                    setTimeout(function() {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Artikel berhasil dihapus',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'artikel.php';
                        });
                    }, 100);
                  </script>";
        } else {
            echo "Error deleting record from artikel: " . mysqli_error($conn);
        }
    } else {
        echo "Error deleting record from kontributor: " . mysqli_error($conn);
    }
}

if (isset($_POST['btn_simpan']) || isset($_POST['btn_ubah_artikel'])) {
    $is_update = isset($_POST['btn_ubah_artikel']);
    $target_dir = "gambar/";
    $target_file = $target_dir . basename($_FILES["gambar"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is uploaded
    if ($_FILES["gambar"]["tmp_name"]) {
        $check = getimagesize($_FILES["gambar"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["gambar"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                echo "The file " . htmlspecialchars(basename($_FILES["gambar"]["name"])) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Get data from form
    $data_tanggal = $_POST['tanggal'];
    $data_judul = $_POST['judul'];
    $data_isi = $_POST['isi'];
    $data_kategori = $_POST['kategori'];
    $data_gambar = $target_file;

    if ($is_update) {
        $id_kontributor = $_POST['id_kontributor'];

        // Update data in artikel table
        $sql_update_artikel = "UPDATE artikel 
                                INNER JOIN kontributor ON artikel.id_artikel = kontributor.id_artikel
                                SET
                                    artikel.judul = '$data_judul',
                                    artikel.isi = '$data_isi'";
        if ($data_gambar) {
            $sql_update_artikel .= ", artikel.gambar = '$data_gambar'";
        }
        $sql_update_artikel .= " WHERE kontributor.id_kontributor = '$id_kontributor'";

        $sql_update_kontributor = "UPDATE kontributor
                                    SET id_kategori = '$data_kategori'
                                    WHERE id_kontributor = '$id_kontributor'";

        if (mysqli_query($conn, $sql_update_artikel) && mysqli_query($conn, $sql_update_kontributor)) {
            echo "<script>
                    setTimeout(function() {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Artikel berhasil diubah',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'artikel.php';
                        });
                    }, 100);
                  </script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        // Insert data into artikel table
        $sql = "INSERT INTO artikel (tanggal, judul, isi, gambar)
                VALUES ('$data_tanggal', '$data_judul', '$data_isi', '$data_gambar')";

        if (mysqli_query($conn, $sql)) {
            // Get last inserted article ID
            $sql = "SELECT * FROM artikel ORDER BY id_artikel DESC LIMIT 1";
            $result = mysqli_query($conn, $sql);
            $data_id_artikel = "";

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $data_id_artikel = $row['id_artikel'];
                }
            } else {
                echo "0 results";
            }

            if (isset($_SESSION['idpenulis'])) {
                $data_id_penulis = $_SESSION['idpenulis'];

                $sql = "INSERT INTO kontributor (id_penulis, id_kategori, id_artikel)
                        VALUES ('$data_id_penulis', '$data_kategori', '$data_id_artikel')";

                if (mysqli_query($conn, $sql)) {
                    echo "<script>
                            setTimeout(function() {
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Artikel berhasil ditambahkan',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(function() {
                                    window.location = 'artikel.php';
                                });
                            }, 100);
                          </script>";
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
            } else {
                echo "Error: id_penulis is not set in session.";
            }
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}

if(isset($_POST['btn_simpan_kategori'])){
    $data_nama = $_POST['nama'];
    $data_keterangan = $_POST['keterangan'];

    $sql = "INSERT INTO kategori (nama_kategori, keterangan)
    VALUES ('$data_nama', '$data_keterangan')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                setTimeout(function() {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Kategori berhasil ditambahkan',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        window.location = 'kategori.php';
                    });
                }, 100);
              </script>";
    } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

if (isset($_POST['btn_ubah_kategori'])){
    $data_nama = $_POST['nama'];
    $data_keterangan = $_POST['keterangan'];
    $id_update = $_POST['id_kategori_update'];

    $sql = "UPDATE kategori SET nama_kategori='$data_nama', keterangan='$data_keterangan' WHERE id_kategori =' $id_update'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                setTimeout(function() {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Kategori berhasil diubah',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        window.location = 'kategori.php';
                    });
                }, 100);
              </script>";
    } else {
    echo "Error updating record: " . mysqli_error($conn);
    }
}

if (isset($_POST['btn_hapus_kategori'])){
   $id_hapus = $_POST['id_hapus_kategori'];

   $sql = "DELETE FROM kategori WHERE id_kategori = '$id_hapus'";

    if(mysqli_query($conn, $sql)) {
        echo "<script>
                setTimeout(function() {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Kategori berhasil dihapus',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        window.location = 'kategori.php';
                    });
                }, 100);
              </script>";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}

if(isset($_POST ['btn_ubah_penulis'])){
    $data_nama = $_POST['nama'];
    $data_email = $_POST['email'];
    $data_password = md5($_POST['password']);
    $id_update = $_POST['id_penulis_update'];

    $sql = "UPDATE penulis SET nama_penulis='$data_nama', email='$data_email', password='$data_password' WHERE id_penulis='$id_update'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                setTimeout(function() {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Penulis berhasil diubah',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        window.location = 'penulis.php';
                    });
                }, 100);
              </script>";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

if (isset($_POST['btn_hapus_penulis'])){
    $id_hapus = $_POST['id_hapus_penulis'];
 
    $sql = "DELETE FROM penulis WHERE id_penulis = '$id_hapus'";
 
    if(mysqli_query($conn, $sql)) {
        echo "<script>
                setTimeout(function() {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Penulis berhasil dihapus',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        window.location = 'penulis.php';
                    });
                }, 100);
              </script>";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}

if(isset($_POST['btn_simpan_penulis'])){
    $data_nama = $_POST['nama'];
    $data_email = $_POST['email'];
    $data_password = md5($_POST['password']);
    
    // Debugging output
    echo "Password MD5 hash: " . $data_password . "<br>";

    $sql = "INSERT INTO penulis (nama_penulis, email, password)
    VALUES ('$data_nama', '$data_email', '$data_password')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                setTimeout(function() {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Penulis berhasil ditambahkan',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        window.location = 'penulis.php';
                    });
                }, 100);
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

function potong_artikel($isi_artikel, $jml_karakter){
    // Ensure the desired length is within the bounds of the string length
    if (strlen($isi_artikel) <= $jml_karakter) {
        return $isi_artikel; // Return the whole article if it's shorter than the cut length
    }
    
    // Adjust the desired length to a valid position within the string
    $jml_karakter = min($jml_karakter, strlen($isi_artikel));

    while ($jml_karakter > 0 && $isi_artikel[$jml_karakter] != " ") {
        --$jml_karakter;
    }
    
    if ($jml_karakter == 0) {
        return substr($isi_artikel, 0, $jml_karakter) . " ... ";
    }

    $potongan_isi_artikel = substr($isi_artikel, 0, $jml_karakter);
    $isi_artikel_terpotong = $potongan_isi_artikel . " ... ";
    return $isi_artikel_terpotong;
}

//terjemah hari
function hariIndonesia($namaHari){  
    $hari = $namaHari;
    switch ($hari) {
        case "Sunday":
            $hari = "Minggu";
            return $hari;
        break;
        case "Monday":
            $hari = "Senin";
            return $hari;
        break;
        case "Tuesday":
            $hari = "Selasa";
            return $hari;
        break;
        case "Wednesday":
            $hari = "Rabu";
            return $hari;
        break;
        case "Thursday":
            $hari = "Kamis";
            return $hari;
        break;
        case "Friday":
            $hari = "Jumat";
            return $hari;
        break;
        case "Saturday":
            $hari = "Sabtu";
            return $hari;
        break;
        default:
            $hari = "nama hari";
    }
    return $hari;
}

//nama bulan
function namaBulan($bulan){  
    $nama_bulan = $bulan;
    switch ($nama_bulan) {
        case "01":
            $nama_bulan = "Januari";
            return $nama_bulan;
        case "02":
            $nama_bulan = "Februari";
            return $nama_bulan;
        case "03":
            $nama_bulan = "Maret";
            return $nama_bulan;
        case "04":
            $nama_bulan = "April";
            return $nama_bulan;
        case "05":
            $nama_bulan = "Mei";
            return $nama_bulan;
        case "06":
            $nama_bulan = "Juni";
            return $nama_bulan;
        case "07":
            $nama_bulan = "Juli";
            return $nama_bulan;
        case "08":
            $nama_bulan = "Agustus";
            return $nama_bulan;
        case "09":
            $nama_bulan = "September";
            return $nama_bulan;
        case "10":
            $nama_bulan = "Oktober";
            return $nama_bulan;
        case "11":
            $nama_bulan = "November";
            return $nama_bulan;
        case "12":
            $nama_bulan = "Desember";
            return $nama_bulan;
        default:
            $nama_bulan = "nama bulan";
    }
    return $nama_bulan;
}
?>
