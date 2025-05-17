<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Loading - FitFusionAI.com</title>
  <meta http-equiv="refresh" content="5;url=index.php" /> <!-- Redirect after 5 sec -->
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      background: linear-gradient(135deg, #a8edea, #fed6e3);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #555;
    }

    .spinner-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 18px;
    }

    /* Rotating Shirt SVG */
    .spinner {
      width: 120px;
      height: 120px;
      animation: spin 2.5s linear infinite;
      filter: drop-shadow(0 4px 4px rgba(0,0,0,0.2));
    }

    @keyframes spin {
      0% { transform: rotateY(0deg); }
      100% { transform: rotateY(360deg); }
    }

    /* Loading text */
    .loading-text {
      font-size: 1.5rem;
      font-weight: 600;
      letter-spacing: 1.2px;
      color: #ff6f91;
      text-shadow: 1px 1px 4px rgba(255, 111, 145, 0.5);
      animation: pulse 1.8s ease-in-out infinite;
    }

    @keyframes pulse {
      0%, 100% {
        opacity: 1;
        transform: scale(1);
      }
      50% {
        opacity: 0.6;
        transform: scale(1.05);
      }
    }

    /* Branding text */
    .branding {
      margin-top: 14px;
      font-size: 0.9rem;
      font-weight: 500;
      color: #666;
      letter-spacing: 0.8px;
      font-style: italic;
      user-select: none;
      opacity: 0.8;
      transition: opacity 0.3s ease;
    }

    .branding:hover {
      opacity: 1;
      color: #ff6f91;
      cursor: default;
    }
     .content {
      opacity: 1;
      /* animation: showContent 0.5s ease-in 3.8s forwards; */
      text-align: center;
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
  <div class="spinner-container" role="img" aria-label="Loading animation">
    <!-- Shirt SVG Icon with simple 3D rotation -->
    <svg class="spinner" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" fill="#ff6f91" stroke="#d54a66" stroke-width="1.2" stroke-linejoin="round">
      <path d="M17 8l3 5h24l3-5-5-4h-20l-5 4zM15 13l-2 22h36l-2-22H15zM22 35l-3 19h26l-3-19H22z" />
      <path d="M21 33h22v2H21z" fill="#d54a66" />
    </svg>
     <div class="content">
        <h1>Redirecting to Home Page...</h1>
        <p>Please wait a moment.</p>
    </div>
    <div class="loading-text">Loading...</div>
    <div class="branding">Powered by FitFusionAI.com</div>
  </div>
  
</body>
</html>
