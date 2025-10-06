<?php
require_once 'firebaseRDB.php';
require_once 'config.php';

$db = new firebaseRDB($databaseURL, $authToken);

$bookingsJson = $db->retrieve("bookings");
$bookings = json_decode($bookingsJson, true);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>عرض جدول الحجوزات</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to bottom, #eafaf1, #c5f0d5);
      background-image: url('https://trackandturf.com/wp-content/uploads/2023/10/soccer-ball-in-center-of-turf-field.jpg');
      background-size: cover;
      background-attachment: fixed;
      color: #155724;
      padding: 20px;
    }

    h1 {
      text-align: center;
      font-size: 2rem;
      margin-bottom: 20px;
      color: #28a745;
      background: rgba(255,255,255,0.85);
      padding: 15px;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    #calendar {
      display: block;
      margin: 0 auto 30px;
      padding: 10px;
      font-size: 1rem;
      border: 1px solid #28a745;
      border-radius: 10px;
      background-color: #fff;
      color: #155724;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: rgba(255, 255, 255, 0.95);
      margin-top: 10px;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    th, td {
      padding: 15px;
      border-bottom: 1px solid #ddd;
      text-align: center;
    }

    th {
      background-color: #28a745;
      color: white;
    }

    tr:hover {
      background-color: #f1f1f1;
    }

    .empty-msg {
      text-align: center;
      margin-top: 50px;
      font-size: 1.2rem;
      color: #666;
    }

    /* NAVBAR STYLES */
    nav.navbar {
      background-color: #19692c;
      box-shadow: 0 2px 6px rgba(0,0,0,0.15);
      margin-bottom: 20px;
      border-radius: 10px;
    }

    nav.navbar ul {
      margin: 0;
      padding: 0 15px;
      list-style: none;
      display: flex;
      justify-content: flex-start;
      gap: 20px;
      direction: rtl;
    }

    nav.navbar ul li {
      display: inline;
    }

    nav.navbar ul li a {
      display: block;
      padding: 12px 18px;
      color: #d4f4d7;
      text-decoration: none;
      font-weight: 600;
      border-radius: 8px;
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    nav.navbar ul li a:hover,
    nav.navbar ul li a:focus {
      background-color: #28a745;
      color: #fff;
      outline: none;
    }

    @media screen and (max-width: 600px) {
      table, thead, tbody, th, td, tr {
        display: block;
      }

      thead {
        display: none;
      }

      tr {
        margin-bottom: 15px;
      }

      td {
        text-align: right;
        padding: 10px;
        border: none;
        border-bottom: 1px solid #eee;
        position: relative;
      }

      td::before {
        content: attr(data-label);
        font-weight: bold;
        position: absolute;
        right: 10px;
        top: 10px;
        color: #333;
      }

      nav.navbar ul {
        flex-direction: column;
        gap: 10px;
      }

      nav.navbar ul li a {
        padding: 10px;
        text-align: center;
      }
    }
  </style>
</head>
<body>

<nav class="navbar">
  <ul>
    <li><a href="index.php">الرئيسية</a></li>
    <li><a href="available.php">عرض الحجوزات</a></li>
  </ul>
</nav>

<h1>📅⚽ جدول الحجوزات</h1>

<input type="date" id="calendar" onchange="filterByDate()" />

<?php
if (!$bookings || !is_array($bookings)) {
    echo "<p class='empty-msg'>لا يوجد حجوزات حاليًا.</p>";
    exit;
}

echo "<table id='bookingTable'>";
echo "<thead><tr><th>التاريخ</th><th>الوقت</th><th>الاسم</th><th>رقم الهاتف</th></tr></thead><tbody>";

foreach ($bookings as $date => $times) {
    if (!is_array($times)) continue;

    foreach ($times as $time => $info) {
        if (is_array($info) && isset($info['status'], $info['phone'])) {
            $bookedBy = str_replace("تم الحجز بواسطة ", "", $info['status']);
            echo "<tr data-date='$date'>
                    <td data-label='التاريخ'>$date</td>
                    <td data-label='الوقت'>$time</td>
                    <td data-label='الاسم'>$bookedBy</td>
                    <td data-label='رقم الهاتف'>{$info['phone']}</td>
                  </tr>";
        }
    }
}

echo "</tbody></table>";
?>

<script>
function filterByDate() {
  const selectedDate = document.getElementById("calendar").value;
  const rows = document.querySelectorAll("#bookingTable tbody tr");

  rows.forEach(row => {
    const rowDate = row.getAttribute("data-date");
    if (!selectedDate || rowDate === selectedDate) {
      row.style.display = "";
    } else {
      row.style.display = "none";
    }
  });
}
</script>

</body>
</html>
