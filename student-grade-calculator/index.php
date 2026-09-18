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
  if ($course === "") $errors[] = "Course / section is required.";

  foreach ($rawGrades as $i => $grade) {
    if (
      $grade === "" ||
      !is_numeric($grade) ||
      !filter_var($grade, FILTER_VALIDATE_INT) && (string)(int)$grade !== (string)$grade ||
      $grade < 0 ||
      $grade > 100
    ) {
      $errors[] = "Grade " . ($i + 1) . " must be a whole number from 0 to 100.";
    } else {
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
  <main class="app-shell">
    <section class="intro">
      <h1>Student Grade Calculator</h1>
    </section>

    <section class="calculator">
      <div class="section-heading">
        <div>
          <h2>Student details</h2>
        </div>
      </div>

      <?php if ($errors): ?>
        <div class="alert error" role="alert">
          <strong>Please check your entries:</strong>
          <ul>
            <?php foreach ($errors as $error): ?>
              <li><?= e($error) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="POST" action="index.php" id="gradeForm">
        <div class="field-grid">
          <label class="field field-wide">
            <span>Student name</span>
            <input type="text" name="student_name" value="<?= e($studentName) ?>" required>
          </label>

          <label class="field">
            <span>Student ID</span>
            <input type="text" name="student_id" value="<?= e($studentId) ?>" required>
          </label>

          <label class="field">
            <span>Course</span>
            <input type="text" name="course" value="<?= e($course) ?>" required>
          </label>
        </div>

        <div class="grades-heading">
          <div>
            <h3>Grades</h3>
          </div>
        </div>

        <div class="grade-grid">
          <?php for ($i = 0; $i < 3; $i++): ?>
            <label class="field grade-field">
              <span>Subject <?= $i + 1 ?></span>
              <div class="grade-input">
                <input type="number" name="grade<?= $i + 1 ?>" min="0" max="100" step="1" value="<?= e($grades[$i]) ?>" required>
              </div>
            </label>
          <?php endfor; ?>
        </div>

        <div class="actions">
          <button type="submit" class="button primary">Calculate result</button>
        </div>
      </form>

      <?php if ($result): ?>
        <section class="result <?= strtolower($result["status"]) ?>" aria-live="polite">
          <div class="average-row">
            <div>
              <span><?= e($result["status"]) ?></span>
              <strong><?= number_format($result["average"], 2) ?><small> / 100</small></strong>
            </div>
            <div class="meter">
                  
            </div>
          </div>
        </section>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>