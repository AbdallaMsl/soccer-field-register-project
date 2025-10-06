<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>حجز ملعب كرة القدم</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to bottom, #eafaf1, #c5f0d5);
      color: #155724;
      padding: 0;
    }

    header {
      background-color: #28a745;
      padding: 20px;
      text-align: center;
      color: white;
      font-size: 1.8rem;
      font-weight: bold;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    /* NAVBAR */
    nav.navbar {
      background-color: #19692c;
      box-shadow: 0 2px 6px rgba(0,0,0,0.15);
      margin-bottom: 20px;
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

    @media (max-width: 480px) {
      nav.navbar ul {
        flex-direction: column;
        gap: 10px;
      }

      nav.navbar ul li a {
        padding: 10px;
        text-align: center;
      }
    }

    main {
      padding: 20px;
      max-width: 500px;
      margin: auto;
    }

    h2 {
      font-size: 1.3rem;
      text-align: center;
      margin-bottom: 20px;
    }

    form {
      background: white;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    label {
      display: block;
      margin-bottom: 15px;
      font-weight: bold;
      color: #155724;
    }

    input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      margin-top: 5px;
    }

    button {
      width: 100%;
      padding: 14px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 1.1rem;
      font-weight: bold;
      cursor: pointer;
      margin-top: 10px;
      transition: background 0.3s ease;
    }

    button:hover {
      background-color: #0056b3;
    }

    .quote {
      text-align: center;
      margin-top: 30px;
      font-style: italic;
      font-size: 1rem;
      color: #444;
    }

    @media screen and (max-width: 600px) {
      header {
        font-size: 1.4rem;
      }

      form {
        padding: 15px;
      }

      button {
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>

<header>⚽️ احجز لعبتك الآن</header>

<nav class="navbar">
  <ul>
    <li><a href="index.php">الرئيسية</a></li>
    <li><a href="available.php">عرض الحجوزات</a></li>
  </ul>
</nav>

<main>
  <h2>املأ النموذج التالي لحجز وقتك في الملعب</h2>

  <form action="submit.php" method="post">
    <label>التاريخ:
      <input type="date" name="date" required>
    </label>

    <label>الوقت:
  <select name="time" required>
    <option value="">اختر الوقت</option>
    <!-- Options from 10:00 AM to 11:00 PM -->
    <option value="10:00">10:00 صباحًا</option>
    <option value="11:00">11:00 صباحًا</option>
    <option value="12:00">12:00 ظهرًا</option>
    <option value="13:00">1:00 مساءً</option>
    <option value="14:00">2:00 مساءً</option>
    <option value="15:00">3:00 مساءً</option>
    <option value="16:00">4:00 مساءً</option>
    <option value="17:00">5:00 مساءً</option>
    <option value="18:00">6:00 مساءً</option>
    <option value="19:00">7:00 مساءً</option>
    <option value="20:00">8:00 مساءً</option>
    <option value="21:00">9:00 مساءً</option>
    <option value="22:00">10:00 مساءً</option>
    <option value="23:00">11:00 مساءً</option>
  </select>
</label>


    <label>الاسم:
      <input type="text" name="name" required>
    </label>

    <label>رقم الهاتف:
      <input type="text" name="phone" required>
    </label>

    <button type="submit">احجز الآن</button>
  </form>

  <div class="quote">
    "كرة القدم لا تعرف المستحيل. العب، استمتع، وحقق المستحيل."
  </div>
  <div class="quote">
    "اللعب في الملعب هو بداية كل حلم كبير ⚽️"
  </div>
  
</main>

<footer style="text-align: center; padding: 20px; background-color: #f8f9fa; color: #6c757d; font-size: 0.9rem;">
  <p>للاستفسارات: </p>
  <p>📞 81 643 898</p>
  <img src="photo_6032967220562347534_x.jpg" alt="ملعب كرة القدم" style="width: 20%; border-radius: 15px; margin-top: 20px;">
</footer>

</body>
</html>
