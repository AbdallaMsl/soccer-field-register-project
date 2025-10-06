<?php
require_once 'firebaseRDB.php';
require_once 'config.php';

$db = new firebaseRDB($databaseURL, null);

// تنقية البيانات (sanitize)
$date = str_replace([".", "#", "$", "[", "]", "/", ":"], "-", trim($_POST['date']));
$time = str_replace([".", "#", "$", "[", "]", "/", ":"], "-", trim($_POST['time']));
$name = trim($_POST['name']);
$phone = trim($_POST['phone']);

if (!$date || !$time || !$name || !$phone) {
    die("يرجى ملء جميع الحقول.");
}

// تحقق من وجود الحجز مسبقاً
$existingBookingJson = $db->retrieve("bookings/$date/$time");
$existingBooking = json_decode($existingBookingJson, true);

if ($existingBooking) {
    // الحجز موجود مسبقاً، اظهر رسالة خطأ وانهي التنفيذ
    die("<!DOCTYPE html>
<html lang='ar' dir='rtl'>
<head>
  <meta charset='UTF-8' />
  <meta name='viewport' content='width=device-width, initial-scale=1' />
  <title>خطأ في الحجز</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #fdecea;
      color: #b71c1c;
      padding: 20px;
      text-align: center;
    }
    h2 {
      font-size: 1.8rem;
      margin-bottom: 20px;
    }
    a {
      color: #b71c1c;
      text-decoration: underline;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <h2>⚠️ عذرًا، هذا الوقت محجوز بالفعل.</h2>
  <p>يرجى اختيار وقت آخر.</p>
  <a href='javascript:history.back()'>العودة إلى صفحة الحجز</a>
</body>
</html>");
}

// تحضير بيانات الحجز
$data = [
    "status" => "تم الحجز بواسطة $name",
    "phone" => $phone
];

// حفظ الحجز في Firebase
$response = $db->update("bookings/$date", $time, $data);

if ($response) {
    // عرض صفحة تأكيد الحجز
    echo '<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>تأكيد الحجز</title>
  <style>
    body {
      font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to bottom, #eafaf1, #c5f0d5);
      background-image: url(\'https://trackandturf.com/wp-content/uploads/2023/10/soccer-ball-in-center-of-turf-field.jpg\');
      background-size: cover;
      background-attachment: fixed;
      color: #155724;
      padding: 0;
      margin: 0;
      text-align: center;
    }
    header {
      background-color: #28a745;
      color: white;
      padding: 20px;
      font-size: 1.8rem;
      font-weight: bold;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    main {
      padding: 30px 20px;
      max-width: 600px;
      margin: auto;
    }
    h2 {
      background: #28a745;
      color: white;
      display: inline-block;
      padding: 15px 25px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      font-size: 1.5rem;
      margin: 30px 0;
    }
    a {
      display: inline-block;
      text-decoration: none;
      margin-top: 20px;
      padding: 12px 24px;
      background: #007bff;
      color: white;
      border-radius: 30px;
      font-weight: bold;
      transition: background 0.3s ease;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    a:hover {
      background: #0056b3;
    }
    .quote {
      margin-top: 40px;
      font-style: italic;
      color: #444;
      font-size: 1rem;
    }
    @media screen and (max-width: 600px) {
      header {
        font-size: 1.4rem;
      }
      h2 {
        font-size: 1.2rem;
        padding: 12px 20px;
      }
      a {
        font-size: 0.95rem;
        padding: 10px 20px;
      }
    }
  </style>
</head>
<body>
<header>⚽️ تم الحجز بنجاح</header>
<main>
  <h2>✅ تم تسجيل حجزك بنجاح!</h2>
  <a href="available.php">عرض الحجوزات المتاحة</a>
  <div class="quote">"كرة القدم تجمعنا. الحجز هو خطوتك الأولى للعب مثل المحترفين!"</div>
</main>
</body>
</html>';
} else {
    echo "حدث خطأ أثناء الحجز. حاول مرة أخرى.";
}
?>
