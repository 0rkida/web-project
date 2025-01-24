<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="../../public/index.php">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <title>Admin Dashboard Panel</title>
</head>
<body>
<nav>
    <div class="logo-name">
        <div class="logo-image">
            <img src="/assets/img/logo.png" height="50" width="50" alt="logo"/>
        </div>
        <span class="logo_name">Mish&Thu</span>
    </div>
    <div class="menu-items">
        <ul class="nav-links">
            <li><a href="../../public/index.php">
                    <i class="uil uil-estate"></i>
                    <span class="link-name">Dashboard</span>
                </a></li>
            <li><a href="/admin/admin-reports.html">
                    <i class="uil uil-comment-block"></i>
                    <span class="link-name">Reports</span>
                </a></li>
        </ul>
        <ul class="logout()">
            <li><a href="login">
                    <i class="uil uil-signout"></i>
                    <span class="link-name">Logout</span>
                </a></li>
            <li class="mode">
                <a href="#">
                    <i class="uil uil-moon"></i>
                    <span class="link-name">Dark Mode</span>
                </a>
                <div class="mode-toggle">
                    <span class="switch"></span>
                </div>
            </li>
        </ul>
    </div>
</nav>
<section class="dashboard">
    <div class="top">
        <i class="uil uil-bars sidebar-toggle"></i>
        <div class="search-box">
            <i class="uil uil-search"></i>
            <input type="text" placeholder="Search here...">
        </div>
        <img src="/assets/img/default-avatar.jpeg" height="40" width="40" alt="default profile"/>
    </div>
    <div class="dash-content">
        <div class="overview">
            <div class="title">
                <i class="uil uil-tachometer-fast-alt"></i>
                <span class="text">Dashboard</span>
            </div>
            <div class="boxes">
                <div class="box box1">
                    <i class="uil uil-user"></i>
                    <span class="text">Total Users</span>
                    <span class="number"><?php echo htmlspecialchars($userCount); ?></span>
                </div>
                <div class="box box2">
                    <i class="uil uil-thumbs-up"></i>
                    <span class="text">Likes</span>
                    <span class="number"><?php echo htmlspecialchars($likesCount); ?></span>
                </div>
                <div class="box box3">
                    <i class="uil uil-envelope-heart"></i>
                    <span class="text">Total Matches</span>
                    <span class="number"><?php echo htmlspecialchars($matchesCount); ?></span>
                </div>
            </div>
        </div>
        <div class="activity">
            <div class="title">
                <i class="uil uil-clock-three"></i>
                <span class="text">Recent Activity</span>
            </div>
            <div class="activity-data">
                <div class="data names">
                    <span class="data-title">Name</span>
                    <?php foreach ($users as $user): ?>
                        <span class="data-list"><?php echo htmlspecialchars($user['full_name']); ?></span>
                    <?php endforeach; ?>
                </div>
                <div class="data email">
                    <span class="data-title">Email</span>
                    <?php foreach ($users as $user): ?>
                        <span class="data-list"><?php echo htmlspecialchars($user['email']); ?></span>
                    <?php endforeach; ?>
                </div>
                <div class="data joined">
                    <span class="data-title">Joined</span>
                    <?php foreach ($users as $user): ?>
                        <span class="data-list"><?php echo htmlspecialchars($user['created_at']); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="../../public/index.php"></script>
</body>
</html>
