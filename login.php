<?php include 'header.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/footer.css">
</head>
<body>
    <div class="container">
        <h1 class="about-title">Σύνδεση</h1>
        <p class="subtitle">Παρακαλώ συνδεθείτε για να αποκτήσετε πρόσβαση στον λογαριασμό σας.</p>
        <form method="POST" action="loginProcess.php">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" placeholder="Εισάγετε το email σας" required>
            </div>
            <div class="form-group">
                <label for="password">Κωδικός:</label>
                <input type="password" name="password" id="password" placeholder="Εισάγετε τον κωδικό σας" required>
            </div>
            <button type="submit" name="login" class="btn-submit">Σύνδεση</button>
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
