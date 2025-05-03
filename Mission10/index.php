<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link id="favicon" rel="shortcut icon" href="../assets/img/logo.png" type="image/x-png" />
    <title>LPFSClinique</title>
    <link rel="stylesheet" href="assets/style.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Roboto:ital,wght@1,300&display=swap"
        rel="stylesheet" />
    <style>
        :root {
            --primary-color: #4a6fa5;
            --secondary-color: #040404FF;
            --accent-color: #4fc3f7;
            --light-color: #f8f9fa;
            --dark-color: #151616FF;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1196fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: var(--dark-color);
            line-height: 1.6;
        }

        h1 {
            text-align: center;
            margin-bottom: 2rem;
            color: var(--secondary-color);
            font-size: 2.5rem;
            font-weight: 600;
        }

        h1 span {
            display: block;
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-top: 0.5rem;
            font-weight: 400;
        }

        .choice-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 2rem;
            width: 100%;
            max-width: 1200px;
        }

        .user-card {
            position: relative;
            width: 300px;
            height: 350px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            background: white;
        }

        .user-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .user-card img {
            width: 100%;
            height: 70%;
            object-fit: cover;
            filter: drop-shadow(20px 10px 0px #70707044);
            border-bottom: 2px solid var(--accent-color);
        }

        .user-card a {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            text-decoration: none;
            color: var(--dark-color);
            padding-bottom: 1.5rem;
            background: linear-gradient(to top, rgba(255, 255, 255, 0.9) 20%, transparent 70%);
        }

        .user-card a::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(79, 195, 247, 0.1);
            opacity: 0;
            transition: var(--transition);
        }

        .user-card:hover a::after {
            opacity: 1;
        }

        .user-card .label {
            font-size: 1.5rem;
            font-weight: 500;
            color: var(--secondary-color);
            margin-top: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .user-card .description {
            font-size: 0.9rem;
            color: #666;
            text-align: center;
            padding: 0 1rem;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }

            h1 span {
                font-size: 1.2rem;
            }

            .choice-container {
                flex-direction: column;
                align-items: center;
            }

            .user-card {
                width: 280px;
                height: 320px;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 1.8rem;
            }

            h1 span {
                font-size: 1rem;
            }

            .user-card {
                width: 100%;
                max-width: 280px;
                height: 300px;
            }
        }
    </style>
</head>

<body>
    <h1>Bienvenue à LPFSClinique <span>Vous êtes?</span></h1>

    <div class="choice-container">
        <div class="user-card">
            <img src="assets/img/users.png" alt="Utilisateur">
            <a href="Users/index.php">
                <span class="label">Patient</span>
                <span class="description">Accédez à votre espace personnel</span>
            </a>
        </div>

        <div class="user-card">
            <img src="assets/img/pro.png" alt="Professionnel">
            <a href="pro/index.php">
                <span class="label">Professionnel</span>
                <span class="description">Accédez à votre espace professionnel</span>
            </a>
        </div>
    </div>
</body>

</html>