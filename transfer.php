<?php
session_start();

if (!isset($_SESSION["kullanici"])) {
    $_SESSION["kullanici"] = [
        "isim" => "mehmet",
        "eposta" => "mehmet@example.com",
        "uye_tarih" => date("d.m.Y"),
        "rol" => "Üye"
    ];
}

$basarili = null;
$hata = null;

if ($_POST) {
    $gelen_isim = trim($_POST["isim"] ?? "");
    $gelen_eposta = trim($_POST["eposta"] ?? "");
    
    if (strlen($gelen_isim) < 2) {
        $hata = "İsim en az 2 karakter olmalıdır.";
    } elseif (!filter_var($gelen_eposta, FILTER_VALIDATE_EMAIL)) {
        $hata = "Geçerli bir e-posta adresi giriniz.";
    } else {
        $_SESSION["kullanici"]["isim"] = htmlspecialchars($gelen_isim, ENT_QUOTES, "UTF-8");
        $_SESSION["kullanici"]["eposta"] = htmlspecialchars($gelen_eposta, ENT_QUOTES, "UTF-8");
        $basarili = "Profil bilgileriniz güncellendi.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSRF vulnerability</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(125deg, #0a2a2a 0%, #1a4a3a 50%, #0d3d2f 100%);
            font-family: 'Segoe UI', 'Inter', system-ui, -apple-system, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .arkaplan {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
        }

        .arkaplan .gradient-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 30% 40%, rgba(20, 80, 70, 0), rgba(8, 38, 32, 0.7));
        }

        .container {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .box {
            width: 420px;
            max-width: 85%;
            background: rgba(25, 64, 85, 0.28);
            backdrop-filter: blur(2px);
            border-radius: 0px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 0, 0, 0.5);
            padding: 1.5rem 1.8rem 1.8rem 1.8rem;
            transition: all 0.25s ease;
        }

        .baslik {
            text-align: center;
            margin-bottom: 1.5rem;
        }


        .avatar {
            width: 80px;
            height: 80px;
            border-radius: 0px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.8rem auto;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            overflow: hidden;
            background: linear-gradient(135deg, #9c2d2d, #1e6b56);
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar span {
            font-size: 1.8rem;
            font-weight: 500;
            color: white;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .baslik h1 {
            color: white;
            font-weight: 600;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .baslik p {
            color: rgba(230, 255, 240, 0.85);
            font-size: 0.75rem;
            margin-top: 0.3rem;
        }

        .inputgrubu {
            margin-bottom: 1.2rem;
        }

        .inputgrubu label {
            display: block;
            color: #ddff00;
            font-weight: 650;
            margin-bottom: 0.4rem;
            font-size: 0.75rem;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .inputgrubu input {
            width: 100%;
            background: rgba(15, 45, 40, 0.6);
            border: 1px solid rgba(36, 36, 36, 0.6);
            padding: 0.7rem 0.9rem;
            font-size: 0.9rem;
            color: white;
            border-radius: 0px;
            transition: all 0.2s;
            font-weight: 450;
        }

        .inputgrubu input:focus {
            outline: none;
            border-color: #205967;
            background: rgba(10, 40, 35, 0.75);
            box-shadow: 0 0 0 2px rgba(74, 98, 104, 0.3);
        }

        .inputgrubu input::placeholder {
            color: rgba(200, 230, 215, 0.55);
        }

        .btn_guncelle {
            width: 100%;
            background: #3e7b974f;
            border: 1px solid rgba(255, 0, 0, 0.5);
            padding: 0.7rem;
            color: white;
            font-weight: 750;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 0.4rem;
            border-radius: 0px;
            letter-spacing: 0.5px;
        }

        .btn_guncelle:hover {
            background: #0000003a;
            transform: translateY(-1px);
            box-shadow: 0 8px 22px rgba(0, 0, 0, 0.2);
        }

        .mesaj {
            padding: 0.6rem 0.8rem;
            margin-bottom: 1.2rem;
            font-size: 0.8rem;
            font-weight: 500;
            border-left: 3px solid;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        .mesaj_basarili {
            border-left-color: #3bcb9f;
            color: #ccf7e8;
        }

        .mesaj_hata {
            border-left-color: #ff0000;
            color: #ffe0d9;
        }

        .bilgipaneli {
            margin-top: 1.5rem;
            background: rgba(10, 38, 32, 0.5);
            padding: 0.8rem 1rem;
            border: 1px solid rgba(70, 160, 135, 0.4);
        }

        .bilgi_satir {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px dashed rgba(100, 180, 155, 0.3);
            font-size: 0.75rem;
        }

        .bilgi_satir:last-child {
            border-bottom: none;
        }

        .bilgi_etiket {
            font-weight: 600;
            color: #bcdfd3;
        }

        .bilgi_deger {
            color: #e2f3ec;
        }
        .copyright {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.7rem;
            color: rgba(190, 225, 210, 0.5);
            letter-spacing: 0.5px;
        }

        .copyright a {
            color: rgba(190, 225, 210, 0.7);
            text-decoration: none;
            transition: color 0.2s;
        }

        .copyright a:hover {
            color: rgba(230, 255, 240, 0.9);
            text-decoration: underline;
        }
        @media (max-width: 520px) {
            .box {
                padding: 1.2rem 1.5rem 1.5rem 1.5rem;
                width: 90%;
            }
            .baslik h1 {
                font-size: 1.3rem;
            }
            .avatar {
                width: 50px;
                height: 50px;
            }
            .avatar span {
                font-size: 1.5rem;
            }
            .copyright {
                margin-top: 1rem;
                font-size: 0.6rem;
            }
        }
    </style>
</head>
<body>

<div class="arkaplan">
    <div style="position: absolute; top:0; left:0; width:100%; height:100%; background-image: url('https://images.pexels.com/photos/3811142/pexels-photo-3811142.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop'); background-size: cover; background-position: center; opacity: 0.4;"></div>
    <div class="gradient-overlay"></div>
</div>

<div class="container">
    <div class="box">
        <div class="baslik">
            <div class="avatar">
                <img src="shiba.png" alt="Profil Resmi">
            </div>
            <h1>CSRF KORUMASIZ</h1>
        </div>

        <?php if ($basarili): ?>
            <div class="mesaj mesaj_basarili">
                ✓ <?php echo $basarili; ?>
            </div>
        <?php elseif ($hata): ?>
            <div class="mesaj mesaj_hata">
                ⚠ <?php echo $hata; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="inputgrubu">
                <label>Kullanıcı Adı: </label>
                <input type="text" name="isim" value="<?php echo htmlspecialchars($_SESSION["kullanici"]["isim"]); ?>" required>
            </div>

            <div class="inputgrubu">
                <label>E-posta Adresi:</label>
                <input type="email" name="eposta" value="<?php echo htmlspecialchars($_SESSION["kullanici"]["eposta"]); ?>" required>
            </div>

            <button type="submit" class="btn_guncelle">
                ⟲ Güncelle
            </button>
        </form>

        <div class="bilgipaneli">
            <div class="bilgi_satir">
                <span class="bilgi_etiket">Date:</span>
                <span class="bilgi_deger"><?php echo $_SESSION["kullanici"]["uye_tarih"]; ?></span>
            </div>
            <div class="bilgi_satir">
                <span class="bilgi_etiket">Yetki:</span>
                <span class="bilgi_deger"><?php echo htmlspecialchars($_SESSION["kullanici"]["rol"]); ?></span>
            </div>
            <div class="bilgi_satir">
                <span class="bilgi_etiket">⚠ CSRF Koruması</span>
                <span class="bilgi_deger">DEVRE DIŞI</span>
            </div>
        </div>
    </div>

    <div class="copyright">
        © <?php echo date("Y"); ?> <a href="#">Developer by Muzaffer Bora PEKDUR</a> | Tüm hakları saklıdır.
    </div>
</div>

</body>
</html>