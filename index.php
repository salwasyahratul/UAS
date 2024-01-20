<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Mata Kuliah dan SKS</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }

        h2 {
            color: #333;
            text-align: center;
        }

        form {
            max-width: 400px;
            margin: 20px auto;
            padding: 15px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        select,
        input[type="text"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        select {
            cursor: pointer;
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        a {
            display: block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #333;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
    
</head>
<body>
<a href="list_data.php">Lihat Daftar Data</a>
<br>
    <h2>Input Mata Kuliah dan SKS</h2>
    <form action="" method="post">
        <label for="nama">Nama Mahasiswa:</label>
        <select name="nama" required>
            <?php
            // Ambil data matkul dari database
            include 'koneksi.php';
            $result = $koneksi->query("SELECT * FROM mahasiswa");

            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['nama']}</option>";
            }

            $koneksi->close();
            ?>
            </select>
            <br>
            <label for="matkul">Mata Kuliah:</label>
            <select name="matkul" id="matkulSelect" required onchange="updateSks()">
                <?php
                // Ambil data matkul dari database
                include 'koneksi.php';
                $result = $koneksi->query("SELECT matkul.*, sks.jml_sks FROM matkul INNER JOIN sks ON matkul.id_sks = sks.id  ");

                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}' data-sks='{$row['id_sks']}'>{$row['nama']}</option>";
                }

                $koneksi->close();
                ?>
            </select>
            <br>
            <label for="jml_sks">Jumlah SKS:</label>
            <input type="text" name="jml_sks" id="jmlSks" readonly>

            <script>
                function updateSks() {
                    var matkulSelect = document.getElementById("matkulSelect");
                    var jmlSksInput = document.getElementById("jmlSks");

                    // Mengambil nilai SKS dari atribut data-sks option yang dipilih
                    var selectedOption = matkulSelect.options[matkulSelect.selectedIndex];
                    var sks = selectedOption.getAttribute("data-sks");

                    // Menyimpan nilai SKS ke dalam input "Jumlah SKS"
                    jmlSksInput.value = sks;
                }
            </script>

        <br>
        <input type="submit" value="Submit">
    </form>
    <?php
    // Periksa apakah formulir telah disubmit
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Koneksi ke database
        include 'koneksi.php';

        // Periksa koneksi
        if ($koneksi->connect_error) {
            die("Connection failed: " . $koneksi->connect_error);
        }

        // Ambil data dari formulir
        $nama = $_POST['nama'];
        $matkul_id = $_POST['matkul'];
        $jml_sks = $_POST['jml_sks'];

        // Simpan data ke dalam tabel mhs_mtkl
        $sql = "INSERT INTO mhs_mtkl (nama, id_mtkul, id_sks) VALUES (?, ?, ?)";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("sii", $nama, $matkul_id, $jml_sks);

        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil disimpan.');</script>";
            header("Location: list_data.php");
            exit();
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        $stmt->close();
        $koneksi->close();
    }
    ?>
</body>
</html>
