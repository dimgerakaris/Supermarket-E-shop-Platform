<?php include 'header.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="css/footer.css">
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script src="script.js"></script>
</head>
<body>
    <div class="container">
        <h1 class="register-title">Φόρμα Εγγραφής</h1>
        <p class="form-description">Παρακαλώ συμπληρώστε τα παρακάτω πεδία για να δημιουργήσετε έναν λογαριασμό.</p>
        <form id="registerForm" method="POST" action="register.php">
            <div class="form-group">
                <label for="fName"><strong>Όνομα:</strong></label>
                <input type="text" name="fName" id="fName" placeholder="Το Όνομα σας" required>
            </div>
            <div class="form-group">
                <label for="lName"><strong>Επώνυμο:</strong></label>
                <input type="text" name="lName" id="lName" placeholder="Το Επώνυμο σας" required>
            </div>
            <div class="form-group">
                <label for="username"><strong>Username:</strong></label>
                <input type="text" name="username" id="username" placeholder="Το Username σας" required>
            </div>
            <div class="form-group">
                <label for="email"><strong>Email:</strong></label>
                <input type="email" name="email" id="email" placeholder="Το Email σας" required>
            </div>
            <div class="form-group">
                <label for="password"><strong>Κωδικός:</strong></label>
                <input type="password" name="password" id="password" placeholder="Κωδικός Πρόσβασης" required>
            </div>
            <div class="form-group">
                <label for="repeat"><strong>Επανάληψη Κωδικού:</strong></label>
                <input type="password" name="repeat" id="repeat" placeholder="Επανάληψη Κωδικού Πρόσβασης" required>
            </div>
            <div class="form-group">
                <label for="number"><strong>Τηλέφωνο:</strong></label>
                <input type="text" name="number" id="number" placeholder="Τηλέφωνο Επικοινωνίας" required pattern="\d{10}" maxlength="10" title="Enter exactly 10 digits">
                <span id="error-message" class="error-message"></span>
            </div>
            <div class="form-group">
                <label for="odos"><strong>Διεύθυνση:</strong></label>
                <input type="text" name="odos" id="odos" placeholder="Διεύθυνση" required>
            </div>
            <div class="form-group">
                <label for="polh"><strong>Πόλη:</strong></label>
                <input type="text" name="polh" id="polh" placeholder="Πόλη" required>
            </div>
            <div class="form-group">
                <label for="tk"><strong>Τ.Κ.:</strong></label>
                <input type="text" name="tk" id="tk" placeholder="Ταχυδρομικός Κώδικας" required pattern="\d{5}" maxlength="5" title="Enter exactly 5 digits">
            </div>
            <button type="submit" name="submit" class="btn-submit">Εγγραφή</button>
        </form>
    </div>

    <div id="successModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Επιτυχής εγγραφή</h2>
            <div class="icon">&#10004;</div>
            <p>Η εγγραφή σας ήταν επιτυχής! Θα ανακατευθυνθείτε στη σελίδα σύνδεσης.</p>
        </div>
    </div>

    <script>
        // Περιορίζει το πεδίο "Phone Number" μόνο σε αριθμούς και μέγιστο 10 ψηφία
        document.getElementById('number').addEventListener('input', function (e) {
            this.value = this.value.replace(/\D/g, ''); // Επιτρέπει μόνο αριθμούς
        });

        // Modal script
        var modal = document.getElementById("successModal");
        var span = document.getElementsByClassName("close")[0];

        function showModal() {
            modal.style.display = "block";
            setTimeout(function() {
                window.location.href = "login.php";
            }, 3000);
        }

        span.onclick = function() {
            modal.style.display = "none";
            window.location.href = "login.php";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                window.location.href = "login.php";
            }
        }

        // Custom validation messages
        document.getElementById('registerForm').addEventListener('submit', function(event) {
            var inputs = document.querySelectorAll('input[required]');
            var valid = true;
            inputs.forEach(function(input) {
                if (!input.value) {
                    alert('Παρακαλώ συμπληρώστε το πεδίο: ' + input.previousElementSibling.innerText);
                    input.focus();
                    valid = false;
                    event.preventDefault();
                    return false;
                }
            });
            return valid;
        });
    </script>
    <?php include 'footer.php'; ?>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'DB_connection.php'; // Include your database connection file

    $fName = $conn->real_escape_string($_POST['fName']);
    $lName = $conn->real_escape_string($_POST['lName']);
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $number = $conn->real_escape_string($_POST['number']);
    $odos = $conn->real_escape_string($_POST['odos']);
    $polh = $conn->real_escape_string($_POST['polh']);
    $tk = $conn->real_escape_string($_POST['tk']);

    // Έλεγχος αν το email υπάρχει ήδη στη βάση δεδομένων
    $check_email_sql = "SELECT id FROM registration WHERE email = ?";
    $stmt = $conn->prepare($check_email_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Το email υπάρχει ήδη. Παρακαλώ χρησιμοποιήστε άλλο email.";
    } else {
        $stmt->close();

        $sql = "INSERT INTO registration (fName, lName, username, email, password, number, odos, polh, tk) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssss", $fName, $lName, $username, $email, $password, $number, $odos, $polh, $tk);

        if ($stmt->execute()) {
            $user_id = $stmt->insert_id; // Λήψη του ID του νέου χρήστη

            // Αποστολή μηνύματος καλωσορίσματος στον χρήστη
            $message_sql = "INSERT INTO messages (user_id, message, is_read) VALUES (?, ?, 0)";
            $message = "Καλώς ήρθατε στο σούπερ μάρκετ μας! Για οποιαδήποτε πληροφορία επικοινωνήστε μαζί μας!";
            $stmt = $conn->prepare($message_sql);
            $stmt->bind_param("is", $user_id, $message);
            $stmt->execute();
            $stmt->close();

            echo "<script>showModal();</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    $stmt->close();
    $conn->close();
}
?>