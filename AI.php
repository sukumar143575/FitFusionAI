<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="refresh" content="7;url=loading.php"> <!-- Auto redirect after 5s -->
  <title>Welcome to FitFusionAI.com</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #d9afd9, #97d9e1);
      height: 100vh;
      overflow: hidden;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .welcome {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) scale(0.9);
      font-size: 32px;
      font-weight: bold;
      color: #fff;
      background: rgba(0, 0, 0, 0.5);
      padding: 30px 50px;
      border-radius: 20px;
      text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.3);
      animation: popIn 1.2s ease-out, fadeOut 1.5s ease 2s forwards;
      z-index: 1000;
    }

    @keyframes popIn {
      0% {
        opacity: 0;
        transform: translate(-50%, -30%) scale(0.9);
      }
      100% {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
      }
    }

    @keyframes fadeOut {
      to {
        opacity: 0;
        transform: translate(-50%, -50%) scale(1);
        visibility: hidden;
      }
    }

    .content {
      opacity: 0;
      animation: showContent 0.5s ease-in 3.5s forwards;
      text-align: center;
    }

    @keyframes showContent {
      to {
        opacity: 1;
      }
    }

    .content h1 {
      color: #333;
      font-size: 28px;
      margin-bottom: 20px;
    }

    .content p {
      font-size: 18px;
      color: #555;
    }
  </style>
</head>
<body>

  <!-- Welcoming Message -->
  <div class="welcome">
    👋 Welcome to <strong>FitFusionAI.com</strong>
  </div>

  <!-- Main Page Content -->
  <div class="content">
    <h1>Explore Our AI-Powered Clothing Recommendation System</h1>
    <p>Login to get started or browse all collections!</p>
  </div>

</body>
</html>
