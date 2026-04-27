<?php 
session_start();
include 'logic.php'; 

// Security Check: If not logged in, go back to the home/login page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

$query = "SELECT * FROM reservations ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$total_bookings = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel | PICKLE YO!</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #00a884; /* Mint Green */
            --primary-light: #f0fdfa;
            --dark: #0f172a;
            --text-muted: #64748b;
            --bg-body: #f8fafc;
            --sidebar-width: 280px;
        }

        body {
            background: var(--bg-body);
            color: var(--dark);
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Design from Reference */
        aside {
            width: var(--sidebar-width);
            background: #fff;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
        }

        .sidebar-header {
            padding: 30px 25px;
            font-weight: 800;
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--dark);
        }
        .sidebar-header span { color: var(--primary); }

        nav { padding: 10px 15px; flex-grow: 1; }
        
        nav a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 600;
            border-radius: 12px;
            margin-bottom: 5px;
            transition: 0.2s;
            font-size: 0.95rem;
        }

        nav a.active {
            background: var(--primary-light);
            color: var(--primary);
        }

        nav a:hover:not(.active) {
            background: #f1f5f9;
            color: var(--dark);
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid #f1f5f9;
        }

        .btn-logout-sidebar {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 12px 15px;
            color: #ef4444;
            text-decoration: none;
            font-weight: 700;
            border-radius: 12px;
            transition: 0.2s;
        }
        .btn-logout-sidebar:hover { background: #fef2f2; }

        /* Main Content Area */
        main {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            padding: 40px 50px;
        }

        .header-main {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
        }

        .header-main h1 { font-weight: 800; font-size: 1.8rem; margin: 0; }

        /* Summary Cards added for better UI */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: #fff;
            padding: 25px;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
        }

        .stat-card span { color: var(--text-muted); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; }
        .stat-card h2 { font-size: 2rem; margin: 10px 0 0; font-weight: 800; }

        /* Table Design */
        .table-container {
            background: #fff;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }

        table { width: 100%; border-collapse: collapse; text-align: left; }

        th {
            background: #f8fafc;
            padding: 18px 25px;
            color: var(--text-muted);
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            border-bottom: 1px solid #e2e8f0;
        }

        td { padding: 18px 25px; border-bottom: 1px solid #f1f5f9; font-size: 0.95rem; }

        .status-badge {
            padding: 6px 12px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.75rem;
        }
        .status-paid { background: #dcfce7; color: #15803d; }
        .status-pending { background: #fef3c7; color: #b45309; }

    </style>
</head>
<body>

    <aside>
        <div class="sidebar-header">
            PICKLE <span>YO!</span>
        </div>
        
        <nav>
            <a href="#" class="active">Overview</a>
            <a href="#">Bookings</a>
            <a href="#">Court Management</a>
            <a href="#">Analytics</a>
            <a href="index.php">View Website</a>
        </nav>

        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout-sidebar">Logout</a>
        </div>
    </aside>

    <main>
        <div class="header-main">
            <div>
                <h1>Dashboard</h1>
                <p style="color: var(--text-muted); margin-top: 5px;">Manage your facility and reservations.</p>
            </div>
            <button style="background: var(--primary); color: white; border: none; padding: 12px 25px; border-radius: 12px; font-weight: 700; cursor: pointer;">+ New Reservation</button>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <span>Total Bookings</span>
                <h2><?php echo $total_bookings; ?></h2>
            </div>
            <div class="stat-card">
                <span>Active Courts</span>
                <h2>2</h2>
            </div>
            <div class="stat-card">
                <span>Today's Revenue</span>
                <h2 style="color: var(--primary);">₱0</h2>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Court</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td>
                            <div style="font-weight: 700;"><?php echo htmlspecialchars($row['customer_name']); ?></div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);"><?php echo htmlspecialchars($row['phone']); ?></div>
                        </td>
                        <td><span style="font-weight: 600;"><?php echo htmlspecialchars($row['court_name']); ?></span></td>
                        <td><?php echo strtoupper(htmlspecialchars($row['payment_method'])); ?></td>
                        <td>
                            <span class="status-badge <?php echo ($row['payment_method'] == 'gcash') ? 'status-paid' : 'status-pending'; ?>">
                                <?php echo ($row['payment_method'] == 'gcash') ? 'PAID' : 'PENDING'; ?>
                            </span>
                        </td>
                        <td style="color: var(--text-muted);">
                            <?php echo date('M d, g:i A', strtotime($row['created_at'])); ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>