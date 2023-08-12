<?php
    require_once "Scripts/Database/CoursesRepository.php";

use Scripts\Database\CoursesRepository;

?>

<!DOCTYPE html>
<html lang='pl'>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="Stylesheets/Main.css">
    <link rel="stylesheet" href="Stylesheets/SignUp.css">
    <title>Szkoła Języka Angielskiego Lightning</title>
</head>
<body>
<?php echo file_get_contents("Templates/Navbar.html") ?>

<section id="signup-form">
    <form action="Scripts/FormHandlers/SignUp.php" method="post">
        <label for="name"> Imię
            <input type="text" name="name" required>
        </label>

        <label for="name"> Nazwisko
            <input type="text" name="surname" required>
        </label>

        <label for="age">Czy uczestnik jest pełnoletni?
            <select id="age" name="age" required>
                <option value="yes">Tak</option>
                <option value="no">Nie</option>
            </select>
        </label>

        <div id="guardianField" style="display: none;">
            <label for="guardian">Rodzic/Opiekun prawny
                <input type="text" name="guardian">
            </label>
        </div>

        <label for="email">Adres e-mail
            <input type="email" name="email" required>
        </label>

        <label>Numer telefonu
            <input type="tel" name="phone" required>
        </label>

        <label for="course">Kurs
            <select id="course" name="course" required>
                <?php
                    $courses = CoursesRepository::getSingleton()->getCourses();

                    foreach($courses as $id => $name)
                        echo "<option value='$id'>$name</option>";
                ?>
            </select>
        </label>

        <label for="experience">Doświadczenie z językiem angielskim
            <textarea name="experience" id="" cols="30" rows="10" required></textarea>
        </label>


        <input type="submit" value="Zarejestruj się">
    </form>
</section>

</body>
</html>

<script>
    const guardianField = document.getElementById('guardianField');

    document.getElementById('age').addEventListener('change', function () {
        if (this.value === 'no') {
            guardianField.style.display = 'block';
            guardianField.children[0].children[0].required = true;
        } else {
            guardianField.style.display = 'none';
            guardianField.children[0].children[0].required = false;
        }
    });
</script>
