
<?php
$studentName = $studentId = $course = "";
$grades = ["", "", ""];
$errors = [];
$result = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $studentName = trim($_POST["student_name"] ?? "");
  $studentId = trim($_POST["student_id"] ?? "");
  $course = trim($_POST["course"] ?? "");

  $rawGrades = [
    $_POST["grade1"] ?? "",
    $_POST["grade2"] ?? "",
    $_POST["grade3"] ?? ""
  ];

  if ($studentName === "") $errors[] = "Student name is required.";
  if ($studentId === "") $errors[] = "Student ID is required.";
  if ($course === "") $errors[] = "Course is required.";

  foreach ($rawGrades as $i => $grade) {
    if ($grade === "" ||!is_numeric($grade) ||!filter_var($grade, FILTER_VALIDATE_INT) && (string)(int)$grade !== (string)$grade ||$grade < 0 ||$grade > 100) {
      $errors[] = "Grade " . ($i + 1) . " must be a whole number from 0 to 100.";
    }
    else {
      $grades[$i] = (int)$grade;
    }
  }

  if (!$errors) {
    $average = array_sum($grades) / count($grades);

    $result = [
      "average" => $average,
      "status" => $average >= 75 ? "Passed" : "Failed"
    ];
  }
}

function e($value) {
  return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Student Grade Calculator</title>

  <link rel="stylesheet" href="css/style.css">
  <script src="js/app.js" defer></script>
</head>

<body>
  <main class="appShell">

    <header class="pageIntro">
      <h1 class="pageTitle">Student Grade Calculator</h1>
    </header>

    <section class="calculator">
      <header class="calculatorHeader">
        <h2 class="sectionTitle">Student details</h2>
      </header>

      <?php if ($errors): ?>
        <div class="alert errorAlert" role="alert">
          <strong class="alertTitle">Please check your entries:</strong>

          <ul class="errorList">
            <?php foreach ($errors as $error): ?>
              <li class="errorItem"><?= e($error) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form class="gradeForm" method="POST" action="index.php" id="gradeForm">

        <div class="studentFields">
          <label class="formField">
            <span class="fieldLabel">Student name</span>
            <input
              class="formInput"
              type="text"
              name="student_name"
              value="<?= e($studentName) ?>"
              required
            >
          </label>

          <label class="formField">
            <span class="fieldLabel">Student ID</span>
            <input
              class="formInput"
              type="text"
              name="student_id"
              value="<?= e($studentId) ?>"
              required
            >
          </label>

          <label class="formField">
            <span class="fieldLabel">Course</span>
            <input
              class="formInput"
              type="text"
              name="course"
              value="<?= e($course) ?>"
              required
            >
          </label>
        </div>

        <section class="gradesSection">
          <header class="gradesHeader">
            <h3 class="sectionTitle">Grades</h3>
          </header>

          <div class="gradeFields">
            <?php for ($i = 0; $i < 3; $i++): ?>
              <label class="formField gradeField">
                <span class="fieldLabel">Subject <?= $i + 1 ?></span>

                <input
                  class="formInput gradeInput"
                  type="number"
                  name="grade<?= $i + 1 ?>"
                  min="0"
                  max="100"
                  step="1"
                  value="<?= e($grades[$i]) ?>"
                  required
                >
              </label>
            <?php endfor; ?>
          </div>
        </section>

        <div class="formActions">
          <button class="button primaryButton" type="submit">
            Calculate Result
          </button>
        </div>

      </form>

      <?php if ($result): ?>
        <section
          class="resultPanel <?= strtolower($result["status"]) ?>"
          aria-live="polite"
        >
          <div class="averageRow">
            <div class="averageDetails">
              <span class="resultStatus">
                <?= e($result["status"]) ?>
              </span>

              <strong class="averageValue">
                <?= number_format($result["average"], 2) ?>
                <small class="averageScale">/ 100</small>
              </strong>
            </div>

            <div class="resultMeter"></div>
          </div>
        </section>
      <?php endif; ?>

    </section>

  </main>
</body>
</html>