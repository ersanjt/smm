<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMM Turk - World's Best Cheap & Easy SMM Panel</title>
    
    <!-- Modern CSS Framework -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #e74c3c;
            --primary-dark: #c0392b;
            --secondary-color: #2c3e50;
            --accent-color: #f39c12;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
            --text-color: #2c3e50;
            --text-muted: #7f8c8d;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
        }
        
        /* Header Styles */
        .header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 1rem 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        
        .header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            text-decoration: none;
            color: white;
        }
        
        .nav {
            display: flex;
            gap: 2rem;
            align-items: center;
        }
        
        .nav a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .nav a:hover {
            color: var(--accent-color);
        }
        
        .auth-buttons {
            display: flex;
            gap: 1rem;
        }
        
        .btn-auth {
            padding: 0.5rem 1.5rem;
            border: 2px solid white;
            background: transparent;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-auth:hover {
            background: white;
            color: var(--primary-color);
        }
        
        .btn-auth.primary {
            background: white;
            color: var(--primary-color);
        }
        
        .btn-auth.primary:hover {
            background: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 8rem 0 4rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="white" opacity="0.1"><polygon points="0,0 1000,0 1000,100 0,50"/></svg>');
            background-size: cover;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-hero {
            padding: 1rem 2rem;
            border: none;
            border-radius: 30px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }
        
        .btn-hero.primary {
            background: white;
            color: var(--primary-color);
        }
        
        .btn-hero.primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .btn-hero.secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
        
        .btn-hero.secondary:hover {
            background: white;
            color: var(--primary-color);
        }
        
        /* Stats Section */
        .stats {
            background: white;
            padding: 4rem 0;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.1);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            text-align: center;
        }
        
        .stat-item {
            padding: 2rem;
            border-radius: 15px;
            background: var(--light-color);
            transition: transform 0.3s ease;
        }
        
        .stat-item:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: var(--text-muted);
            font-weight: 500;
        }
        
        /* Features Section */
        .features {
            padding: 6rem 0;
            background: var(--light-color);
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 4rem;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 1rem;
        }
        
        .section-title p {
            font-size: 1.1rem;
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
        }
        
        .feature-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-color);
        }
        
        .feature-card p {
            color: var(--text-muted);
            line-height: 1.6;
        }
        
        /* Services Section */
        .services {
            padding: 6rem 0;
            background: white;
        }
        
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .service-card {
            background: var(--light-color);
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .service-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-5px);
        }
        
        .service-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .service-card h4 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-color);
        }
        
        .service-card p {
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        
        /* FAQ Section */
        .faq {
            padding: 6rem 0;
            background: var(--light-color);
        }
        
        .faq-item {
            background: white;
            margin-bottom: 1rem;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .faq-question {
            padding: 1.5rem;
            background: var(--primary-color);
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .faq-question:hover {
            background: var(--primary-dark);
        }
        
        .faq-answer {
            padding: 1.5rem;
            color: var(--text-color);
            line-height: 1.6;
        }
        
        /* Footer */
        .footer {
            background: var(--dark-color);
            color: white;
            padding: 3rem 0 1rem;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .footer-section h4 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--accent-color);
        }
        
        .footer-section p,
        .footer-section a {
            color: #bdc3c7;
            text-decoration: none;
            line-height: 1.6;
        }
        
        .footer-section a:hover {
            color: var(--accent-color);
        }
        
        .footer-bottom {
            border-top: 1px solid #34495e;
            padding-top: 1rem;
            text-align: center;
            color: #bdc3c7;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .nav {
                display: none;
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn-hero {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <a href="#" class="logo">SMM Turk</a>
            <nav class="nav">
                <a href="#home">Home</a>
                <a href="#services">Services</a>
                <a href="#faq">FAQ</a>
                <a href="#contact">Contact</a>
            </nav>
            <div class="auth-buttons">
                <a href="<?php echo base_url('login'); ?>" class="btn-auth">Sign In</a>
                <a href="<?php echo base_url('register'); ?>" class="btn-auth primary">Sign Up</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content">
                <h1>SMM Turk</h1>
                <p>World's Best Cheap & Easy SMM Panel</p>
                <p>Our SMM Panel lets you promote your business on social networks. It's high quality and affordable. SMM Turk is the fastest and cheapest SMM Panel.</p>
                <p>You can earn money by joining our affiliate program. Or start your own SMM panel with our SMM reseller panel.</p>
                <p>Join SMM Turk, which is your partner in business expansion for the last 8 years.</p>
                <p>Do not miss the opportunity to develop your business in ways that have never been available before!</p>
                
                <div class="hero-buttons">
                    <a href="<?php echo base_url('register'); ?>" class="btn-hero primary">Get Started</a>
                    <a href="#services" class="btn-hero secondary">Our Services</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                    <div class="stat-number">0.3Sec</div>
                    <div class="stat-label">An Order is made every</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-number">56,655,995</div>
                    <div class="stat-label">Orders Completed</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                    <div class="stat-number">$0.001/1K</div>
                    <div class="stat-label">Prices Starting From</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="benefits">
        <div class="container">
            <div class="section-title">
                <h2>Benefit of using our Cheap SMM Services</h2>
                <p>We offer several advantages that make us the best SMM panel. Our SMM services are diverse, making us the cheapest around. Many agencies and freelancers worldwide rely on us. We're here to answer all your questions 24/7.</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-dollar-sign"></i></div>
                    <h3>Cheapest SMM Panel</h3>
                    <p>Our prices are the cheapest in the market, starting at 0.01$.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-bolt"></i></div>
                    <h3>Fastest SMM Panel</h3>
                    <p>We promise to deliver your order quickly, any time of day or night.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-mouse-pointer"></i></div>
                    <h3>Easy SMM Panel</h3>
                    <p>We have a simple and updated SMM panel dashboard.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                    <h3>SMM Panel with real time data</h3>
                    <p>Our data is updated instantly. This means you'll always find the best deals on SMM Turk.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-users"></i></div>
                    <h3>SMM Reseller Panel</h3>
                    <p>We are also a provider. Our goal is to help you resell our SMM services globally at low prices.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                    <h3>SMM Panel with Secure Payment</h3>
                    <p>Credit card / Debit card, PayPal, Stripe, CoinPayments, Perfect Money, and more secure payment methods.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="services">
        <div class="container">
            <div class="section-title">
                <h2>SMM Panel Services</h2>
                <p>We provide many services for social networks and SEO. You can find an SMM panel for every social network.</p>
            </div>
            
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon"><i class="fab fa-facebook"></i></div>
                    <h4>Facebook Services</h4>
                    <p>Likes, Followers, Shares, Comments, Page Likes</p>
                </div>
                <div class="service-card">
                    <div class="service-icon"><i class="fab fa-instagram"></i></div>
                    <h4>Instagram Services</h4>
                    <p>Followers, Likes, Views, Comments, Story Views</p>
                </div>
                <div class="service-card">
                    <div class="service-icon"><i class="fab fa-twitter"></i></div>
                    <h4>Twitter Services</h4>
                    <p>Followers, Retweets, Likes, Views</p>
                </div>
                <div class="service-card">
                    <div class="service-icon"><i class="fab fa-youtube"></i></div>
                    <h4>YouTube Services</h4>
                    <p>Views, Subscribers, Likes, Comments</p>
                </div>
                <div class="service-card">
                    <div class="service-icon"><i class="fab fa-linkedin"></i></div>
                    <h4>LinkedIn Services</h4>
                    <p>Connections, Profile Views, Endorsements</p>
                </div>
                <div class="service-card">
                    <div class="service-icon"><i class="fab fa-tiktok"></i></div>
                    <h4>TikTok Services</h4>
                    <p>Views, Followers, Likes, Shares</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq" id="faq">
        <div class="container">
            <div class="section-title">
                <h2>FAQ SMM Turk</h2>
                <p>Questions and Answers</p>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">Register in our SMM Panel?</div>
                <div class="faq-answer">Simply click on the "Sign Up" button, fill in your details, and you're ready to start using our services.</div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">Add fund Via PayPal or Debit/Credit Card?</div>
                <div class="faq-answer">Yes, we accept multiple payment methods including PayPal, Credit/Debit cards, and many other secure payment options.</div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">Place Your order</div>
                <div class="faq-answer">After adding funds, simply select your desired service, enter the target URL, and place your order. It's that simple!</div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">How we can help you make money?</div>
                <div class="faq-answer">Through our affiliate program and reseller panel, you can earn money by promoting our services to others.</div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">How do I track the progress of my order?</div>
                <div class="faq-answer">You can track all your orders in real-time through your dashboard. We provide detailed progress updates for each order.</div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>SMM Turk</h4>
                    <p>World's Best Cheap & Easy SMM Panel. Your partner in business expansion for the last 8 years.</p>
                </div>
                <div class="footer-section">
                    <h4>Services</h4>
                    <p><a href="#">Facebook Services</a></p>
                    <p><a href="#">Instagram Services</a></p>
                    <p><a href="#">YouTube Services</a></p>
                    <p><a href="#">Twitter Services</a></p>
                </div>
                <div class="footer-section">
                    <h4>Support</h4>
                    <p><a href="#">Live Chat 24/7</a></p>
                    <p><a href="#">FAQ</a></p>
                    <p><a href="#">Contact Us</a></p>
                    <p><a href="#">Help Center</a></p>
                </div>
                <div class="footer-section">
                    <h4>Contact</h4>
                    <p>Email: contact@smmturk.com</p>
                    <p>Support: support@smmturk.com</p>
                    <p>Open a ticket for immediate assistance</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; Copyright 2025. All Rights Reserved by SMM Turk.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // FAQ Toggle
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', function() {
                const answer = this.nextElementSibling;
                const isOpen = answer.style.display === 'block';
                
                // Close all other FAQ items
                document.querySelectorAll('.faq-answer').forEach(ans => {
                    ans.style.display = 'none';
                });
                
                // Toggle current FAQ item
                answer.style.display = isOpen ? 'none' : 'block';
            });
        });
    </script>
</body>
</html>
