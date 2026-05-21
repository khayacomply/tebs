<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Plans | TEBS</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            color: #fff;
            padding: 20px;
        }
        .plans-container {
            max-width: 900px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        .plan-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 2.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }
        .plan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        .plan-card.popular {
            border: 2px solid #d4af37;
            background: rgba(212, 175, 55, 0.15);
            position: relative;
        }
        .plan-card.popular::before {
            content: 'MOST POPULAR';
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: #d4af37;
            color: #0f172a;
            padding: 4px 16px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .plan-name {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #fff;
        }
        .price {
            font-size: 3rem;
            font-weight: 700;
            color: #d4af37;
            margin: 1.5rem 0;
        }
        .price span {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.7);
        }
        .features {
            list-style: none;
            padding: 0;
            margin: 2rem 0;
            text-align: left;
        }
        .features li {
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1rem;
        }
        .features li::before {
            content: '✓';
            color: #22c55e;
            font-weight: bold;
            font-size: 1.2rem;
        }
        .btn-select {
            background: #d4af37;
            color: #0f172a;
            border: none;
            padding: 1rem 2rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            width: 100%;
            transition: all 0.2s;
        }
        .btn-select:hover {
            background: #b8952a;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.4);
        }
        .back-link {
            display: inline-block;
            color: #fff;
            text-decoration: none;
            margin-bottom: 2rem;
            font-weight: 500;
        }
        .back-link:hover {
            color: #d4af37;
        }
        .header-section {
            text-align: center;
            padding: 2rem 0;
        }
        .header-section h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #d4af37;
        }
        .header-section p {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }
        @media (max-width: 768px) {
            .plans-container {
                grid-template-columns: 1fr;
            }
            .plan-card {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="header-section">
        <a href="../index.html" class="back-link">← Back to Home</a>
        <h1>Choose Your Job Seeker Package</h1>
        <p>Get access to exclusive job opportunities and professional support to land your dream job</p>
    </div>
    
    <div class="plans-container">
        <!-- Standard Plan -->
        <div class="plan-card">
            <div class="plan-name">Standard</div>
            <div class="price">R550</div>
            <ul class="features">
                <li>Regular job updates via email</li>
                <li>Access to job placement services</li>
                <li>CV database inclusion</li>
                <li>Basic job matching</li>
                <li>Monthly newsletter</li>
            </ul>
            <button class="btn-select" onclick="selectPlan('standard')">Select Standard</button>
        </div>
        
        <!-- Premium Plan -->
        <div class="plan-card popular">
            <div class="plan-name">Premium</div>
            <div class="price">R600</div>
            <ul class="features">
                <li>Regular job updates via email</li>
                <li>Access to job placement services</li>
                <li>CV database inclusion</li>
                <li>Priority job matching</li>
                <li><strong>Interview preparation</strong></li>
                <li><strong>Professional coaching sessions</strong></li>
                <li>CV review & optimization</li>
                <li>Priority support</li>
            </ul>
            <button class="btn-select" onclick="selectPlan('premium')">Select Premium</button>
        </div>
    </div>

    <script>
        function selectPlan(planType) {
            // For now, show alert - later integrate with PayFast
            const plans = {
                'standard': {
                    name: 'Standard',
                    price: 'R550',
                    features: 'Job updates + Placement'
                },
                'premium': {
                    name: 'Premium',
                    price: 'R600',
                    features: 'Job updates + Placement + Interview Coaching'
                }
            };
            
            const selected = plans[planType];
            alert(`You selected: ${selected.name} Package\n\nPrice: ${selected.price}\nIncludes: ${selected.features}\n\nPayment integration coming soon!`);
            
            // Later: Redirect to PayFast checkout
            // window.location.href = `checkout.php?plan=${planType}`;
        }
    </script>
</body>
</html>