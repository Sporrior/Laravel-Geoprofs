<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .sidebar {
            width: 250px;
            background-color: #fefefe;
            height: 100vh;
            padding: 20px;
            position: fixed;
            left: 0;
            top: 0;
        }

        .sidebar img {
            width: 120px;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            margin: 20px 0;
            display: flex;
            align-items: center;
            color: #d98a62;
            font-weight: bold;
        }

        .sidebar ul li svg {
            margin-right: 10px;
        }

        .sidebar ul li.active {
            color: #d98a62;
        }

        .topbar {
            background-color: #fff;
            height: 60px;
            padding: 0 20px;
            margin-left: 250px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .topbar .search-box {
            width: 300px;
            background-color: #f5f5f5;
            border-radius: 10px;
            padding: 10px;
            display: flex;
            align-items: center;
        }

        .topbar .search-box input {
            border: none;
            background: none;
            outline: none;
            margin-left: 10px;
            width: 100%;
        }

        .topbar .user-info {
            font-weight: bold;
            font-size: 16px;
        }

        .main-content {
            margin-left: 250px;
            padding: 40px;
            background-color: #e3eef1;
            min-height: 100vh;
        }

        .main-content h1 {
            font-size: 36px;
            margin-bottom: 10px;
            color: #fff;
        }

        .main-content p {
            color: #ccc;
            margin-bottom: 20px;
        }

        .section {
            display: flex;
            justify-content: space-between;
        }

        .rooster, .team {
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 48%;
        }

        .rooster h3, .team h3 {
            margin-bottom: 20px;
        }

        .rooster .days, .team .members {
            display: flex;
            justify-content: space-between;
        }

        .day, .member {
            background-color: #fdf6f0;
            padding: 10px;
            border-radius: 8px;
            width: 18%;
            text-align: center;
        }

        .team .members .member {
            background-color: #f5f5f5;
            margin-bottom: 10px;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .view-all {
            text-align: right;
            margin-top: 10px;
            color: #666;
            font-size: 12px;
        }

    </style>
</head>
<body>

    <div class="sidebar">
        <img src="/path/to/logo.png" alt="Geoprofs Logo">
        <ul>
            <li class="active">
                <span>🏠</span> Home
            </li>
            <li>
                <span>👥</span> Team
            </li>
            <li>
                <span>⚙️</span> Settings
            </li>
        </ul>
    </div>

    <div class="topbar">
        <div class="search-box">
            <span>🔍</span>
            <input type="text" placeholder="Search...">
        </div>
        <div class="user-info">
            Damien Engelen
        </div>
    </div>

    <div class="main-content">
        <h1>Hi Damien,</h1>
        <p>Do 19 September.</p>

        <div class="section">
            <div class="rooster">
                <h3>Rooster</h3>
                <div class="days">
                    <div class="day">Maandag</div>
                    <div class="day">Dinsdag</div>
                    <div class="day">Woensdag</div>
                    <div class="day">Donderdag</div>
                    <div class="day">Vrijdag</div>
                </div>
            </div>

            <div class="team">
                <h3>Team</h3>
                <div class="members">
                    <div class="member">
                        <span>Ahmed M.</span>
                        <span>💬</span>
                    </div>
                    <div class="member">
                        <span>Wessam N.</span>
                        <span>💬</span>
                    </div>
                </div>
                <div class="view-all">
                    <a href="#">View All</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>