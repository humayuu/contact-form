<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <h1 class="text-center mb-5">Send Us a Message</h1>

    <div class="container">
        <div class="form-container">
            <form action="process.php" method="POST">
                <input type="hidden" name="__csrf" value="<?php echo htmlspecialchars($_SESSION['__csrf']) ?>">
                <!-- Show Error or Success Message -->
                <?php
                if (isset($_GET['error']) && $_GET['error'] == 'InvalidCSRF') {
                    echo '<div class="alert alert-danger" role="alert">
                    Invalid CSRF token. Please try again.
                    </div>';
                } elseif (isset($_GET['error']) && $_GET['error'] == 'EmptyFields') {
                    echo '<div class="alert alert-danger" role="alert">
                    All fields are required.
                    </div>';
                } elseif (isset($_GET['error']) && $_GET['error'] == 'InvalidEmail') {
                    echo '<div class="alert alert-danger" role="alert">
                    Invalid email address. Please enter a valid email.
                    </div>';
                } elseif (isset($_GET['success']) && $_GET['success'] == 'MessageSent') {
                    echo '<div class="alert alert-success" role="alert">
                    Your message has been sent successfully!
                    </div>';
                } elseif (isset($_GET['error']) && $_GET['error'] == 'SendFailed') {
                    echo '<div class="alert alert-danger" role="alert">
                    Failed to send message. Please try again later.
                    </div>';
                }
                ?>
                <div class="mb-3">
                    <label for="name" class="form-label">Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" autofocus
                        placeholder="Enter your full name">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address <span class="required">*</span></label>
                    <input type="email" id="email" name="email" class="form-control"
                        placeholder="your.email@example.com">
                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>

                <div class="mb-3">
                    <label for="subject" class="form-label">Subject <span class="required">*</span></label>
                    <input type="text" id="subject" name="subject" class="form-control"
                        placeholder="What is this about?">
                </div>

                <div class="mb-4">
                    <label for="message" class="form-label">Message <span class="required">*</span></label>
                    <textarea class="form-control" id="message" name="message"
                        placeholder="Write your message here..."></textarea>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
        </script>
</body>

</html>