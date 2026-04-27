<?php 
include 'logic.php'; 
session_start(); 
$f = getFacility($conn); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PICKLE YO! | Kamagayan Pickleball</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Outfit:wght@700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #00a884; 
            --primary-light: #f0fdfa; 
            --accent: #f59e0b; 
            --dark: #0f172a; 
            --white: #ffffff;
        }

        body { font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; background: var(--primary-light); color: var(--dark); scroll-behavior: smooth; }
        
        nav { display: flex; justify-content: space-between; align-items: center; padding: 20px 8%; background: var(--white); position: sticky; top: 0; z-index: 1000; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
        .logo { font-weight: 800; font-size: 1.6rem; color: var(--dark); text-decoration: none; display: flex; align-items: center; gap: 10px; }
        .logo span { color: var(--primary); }
        
        .nav-links { display: flex; align-items: center; gap: 25px; }
        .nav-links a { text-decoration: none; color: var(--dark); font-weight: 600; font-size: 0.9rem; }
        .btn-login { background: var(--primary); color: #fff; padding: 10px 25px; border-radius: 30px; font-weight: 700; cursor: pointer; border: none; text-decoration: none; }

        .hero { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; padding: 120px 8%; align-items: center; }
        .hero-text h1 { font-size: 4.8rem; line-height: 1; margin: 20px 0; font-weight: 800; }
        .hero-text h1 span { color: var(--primary); display: block; }
        .btn-reserve { background: var(--primary); color: var(--white); padding: 20px 40px; border-radius: 60px; text-decoration: none; font-weight: 700; display: inline-block; transition: 0.3s; border: none; cursor: pointer; }
        .btn-reserve:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(0,168,132,0.2); }

        .hero-card { background: var(--white); border-radius: 40px; padding: 15px; box-shadow: 0 30px 60px rgba(0,0,0,0.08); transition: 0.3s; opacity: 0; animation: fadeIn 1s ease forwards; }
        .hero-card img { width: 100%; border-radius: 30px; height: 480px; object-fit: contain; background: #f8fafc; }
        .hero-card div { padding: 25px; }
        .hero-card strong { font-size: 1.4rem; font-weight: 800; color: var(--dark); }
        .hero-card span { color: #64748b; font-size: 1rem; margin-top: 5px; display: block; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .court-section { padding: 80px 8%; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(380px, 1fr)); gap: 30px; }
        .card { background: var(--white); border-radius: 32px; overflow: hidden; border: 1px solid #e2e8f0; transition: 0.3s; }
        .card:hover { transform: translateY(-10px); }
        .card-body { padding: 35px; }

        .pricing-box { background: #fffbeb; border: 1px solid #fef3c7; border-radius: 20px; padding: 20px; margin-top: 20px; }
        .price-line { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.9rem; font-weight: 600; }
        
        .modal { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(5px); align-items: center; justify-content: center; }
        .modal-content { background: white; padding: 40px; border-radius: 30px; width: 90%; max-width: 500px; position: relative; animation: slideUp 0.3s ease; }
        
        .admin-modal { background: var(--primary-light) !important; text-align: center; border: none; }
        .admin-modal input { background: #fff; border: 1px solid #e2e8f0; color: var(--dark); width: 100%; padding: 15px; border-radius: 12px; box-sizing: border-box; }
        .btn-signin { background: var(--primary); color: #fff; font-weight: 800; width: 100%; padding: 18px; border: none; border-radius: 15px; cursor: pointer; margin-top: 25px; font-size: 1rem; }

        footer { padding: 40px 8%; background: var(--white); text-align: center; font-weight: 600; margin-top: 80px; }
    </style>
</head>
<body>

<nav>
    <a href="#" class="logo">PICKLE <span>YO!</span></a>
    <div class="nav-links">
        <a href="#courts">Courts</a>
        <a href="#">Kamagayan Branch</a>
        
        <?php if(!isset($_SESSION['admin_logged_in'])): ?>
            <button onclick="openModal('loginModal')" class="btn-login">Login</button>
        <?php else: ?>
            <a href="admin_dashboard.php" style="color:var(--primary); font-weight:800;">Admin Panel</a>
            <a href="logout.php" style="color: #ef4444; font-size: 0.8rem; font-weight: 700;">Logout</a>
        <?php endif; ?>
    </div>
</nav>

<section class="hero">
    <div class="hero-text">
        <span style="color: var(--primary); font-weight: 800; letter-spacing: 2px;">📍 NOW IN CEBU CITY</span>
        <h1>Book your next <span>PICKLE YO!</span></h1>
        <p style="font-size: 1.1rem; color: #64748b; margin-bottom: 40px; max-width: 580px;">Premium courts, professional lighting, and the best pickleball community in Kamagayan.</p>
        <a href="#courts" class="btn-reserve">Reserve Your Court →</a>
    </div>
    <div class="hero-card">
        <img src="home.jpg" alt="Kamagayan Courts Hub">
        <div>
            <strong>Kamagayan Hub</strong>
            <span>The Home of Pickle Yo!</span>
        </div>
    </div>
</section>

<section id="courts" class="court-section">
    <h2 style="font-size: 2.5rem; text-align: center; margin-bottom: 60px;">Available Courts in Kamagayan</h2>
    <div class="grid">
        <?php 
        $courts = getCourts($conn);
        while($c = $courts->fetch_assoc()): 
            $imageFile = ($c['court_name'] == 'Court 1') ? 'yo_court1.jpg' : $c['image_url'];
        ?>
        <div class="card">
            <div style="height: 280px; background: #cbd5e1; overflow: hidden;">
                <img src="<?php echo $imageFile; ?>" alt="<?php echo htmlspecialchars($c['court_name']); ?>" style="width: 100%; height:100%; object-fit: cover;">
            </div>
            <div class="card-body">
                <span style="float: right; color: var(--accent); font-weight: 800; font-size: 1.5rem;"><?php echo formatPHP($c['morning_rate']); ?> <small>/hr</small></span>
                <h3 style="margin: 0; font-size: 1.6rem;"><?php echo htmlspecialchars($c['court_name']); ?></h3>
                <p style="color: #64748b; font-size: 0.9rem; margin: 10px 0;">📍 Kamagayan Hub</p>
                
                <div class="pricing-box">
                    <p style="margin: 0 0 10px 0; font-size: 0.75rem; font-weight: 800; color: var(--accent); text-transform: uppercase;">Time-Based Rates</p>
                    <div class="price-line"><span>Morning (06:00 AM - 03:00 PM)</span><span><?php echo formatPHP($c['morning_rate']); ?>/hr</span></div>
                    <div class="price-line" style="color: var(--primary);"><span>Peak (04:00 PM - 10:00 PM)</span><span><?php echo formatPHP($c['peak_rate']); ?>/hr</span></div>
                </div>

                <button class="btn-reserve" style="width: 100%; margin-top: 25px; border-radius: 20px;">Reserve This Court</button>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</section>

<div id="loginModal" class="modal">
    <div class="modal-content admin-modal">
        <span class="close-btn" onclick="closeModal('loginModal')" style="cursor:pointer; float:right; font-size: 1.5rem;">&times;</span>
        <div style="margin-bottom: 30px;">
            <div class="logo" style="justify-content: center; font-size: 2.2rem;">PICKLE <span style="color:var(--primary)">YO!</span></div>
            <p style="color: #64748b; font-size: 0.85rem; font-weight: 700;">ADMINISTRATOR PORTAL</p>
        </div>
        <form action="admin_login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required style="margin-bottom:10px;">
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn-signin">SIGN IN</button>
        </form>
    </div>
</div>

<footer>
    © 2026 PICKLE YO! | <?php echo htmlspecialchars($f['full_address']); ?>
</footer>

<script>
function openModal(id) { document.getElementById(id).style.display = "flex"; }
function closeModal(id) { document.getElementById(id).style.display = "none"; }
window.onclick = function(event) { if (event.target.className === 'modal') event.target.style.display = "none"; }
</script>

</body>
</html>