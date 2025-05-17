<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FitFusionAI - AI Clothes Recommendation</title>
  <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    body {
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #d9afd9, #97d9e1);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 0 20px 40px;
        animation: fadeInBody 1.2s ease-in;
    }

    @keyframes fadeInBody {
        0% { opacity: 0; transform: translateY(-30px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    header {
        width: 100%;
        max-width: 1200px;
       background-color: rgba(255, 255, 255, 0);
        padding: 15px 30px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-radius: 12px;
        margin-top: 30px;
        animation: slideInHeader 1s ease-out;
    }

    @keyframes slideInHeader {
        0% { opacity: 0; transform: translateY(-50px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .logo-area {
        display: flex;
        align-items: center;
    }

    .logo-area img {
        height: 80px;
        width: 80px;
        object-fit: contain;
        margin-right: 15px;
        transition: transform 0.4s ease;
    }

    .logo-area img:hover {
        transform: scale(1.1);
    }

    .logo-area h1 {
        font-size: 24px;
        font-weight: 700;
        color: #333;
    }

    .page-title {
        text-align: center;
        font-size: 20px;
        color: #666;
        animation: zoomIn 1.5s ease;
    }
    @keyframes zoomIn {
        0% { transform: scale(0.95); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    .container {
        display: flex;
        gap: 40px;
        flex-wrap: wrap;
        justify-content: center;
        margin-top: 40px;
    }

    .card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        width: 280px;
        height: 280px;
        text-align: center;
        padding: 30px 20px;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-decoration: none;
        color: #333;
        animation: fadeInCard 0.8s ease-out;
    }

    .card:nth-child(1) { animation-delay: 0.2s; }
    .card:nth-child(2) { animation-delay: 0.4s; }
    .card:nth-child(3) { animation-delay: 0.6s; }

    @keyframes fadeInCard {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        background: #f0f8ff;
    }

    .card img {
        width: 80px;
        height: 80px;
        margin-bottom: 20px;
    }

    .card h2 {
        font-size: 22px;
        margin-bottom: 10px;
    }

    .card p {
        font-size: 15px;
        color: #555;
    }

    .description {
        margin-top: 60px;
        max-width: 900px;
        background: rgba(255, 255, 255, 0.85);
        padding: 25px 30px;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        text-align: center;
        font-size: 16px;
        line-height: 1.6;
        color: #444;
        animation: slideUp 1.2s ease-in-out;
    }

    @keyframes slideUp {
        0% { opacity: 0; transform: translateY(40px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
        header {
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 20px;
        }
        .logo-area {
            justify-content: center;
            margin-bottom: 10px;
        }
        .container {
            flex-direction: column;
            gap: 20px;
        }
    }
</style>

</head>
<body>

    <header>
        <div class="logo-area">
            <img src="logo.png" alt="FitFusionAI Logo">
            <h1>FitFusionAI</h1>
        </div>
        <div class="page-title">👗 AI-Powered Clothes Recommendation & Shopping System</div>
    </header>

    <div class="container">
        <a href="user/login.php" class="card">
            <img src="https://cdn-icons-png.flaticon.com/512/102/102276.png" alt="User Cart Icon">
            <h2>User Cart</h2>
            <p>Login to access your personalized clothing cart</p>
        </a>
        <a href="admin/login.php" class="card">
            <img src="https://cdn-icons-png.flaticon.com/512/1041/1041916.png" alt="Admin Cart Icon">
            <h2>Admin Cart</h2>
            <p>Manage clothes and system settings</p>
        </a>
        <a href="allclothes.php" class="card">
            <img src="https://cdn-icons-png.flaticon.com/512/1075/1075431.png" alt="All Clothes Icon">
            <h2>All Clothes</h2>
            <p>View the full collection of clothes</p>
        </a>
    </div>

    <div class="description">
        <h3>About This Project</h3>
        <p>
            This <strong>AI-powered Clothes Recommendation and Shopping System</strong> offers a smart and interactive experience for users to discover clothing items tailored to their preferences.
            <br><br>
            With separate modules for <strong>users</strong> and <strong>admins</strong>, the system supports:
        </p>
        <ul style="margin: 15px 0; padding-left: 20px; text-align: left;">
            <li>👤 Personalized user carts</li>
            <li>📦 Dynamic inventory management</li>
            <li>✏️ Real-time item editing</li>
            <li>🔐 Secure login authentication</li>
        </ul>
        <p>
            Leveraging clean UI design and smooth animations, it provides an intuitive platform that mimics real-world online shopping — making fashion discovery more <strong>engaging</strong> and <strong>convenient</strong>.
        </p>
    </div>

</body>
</html>
