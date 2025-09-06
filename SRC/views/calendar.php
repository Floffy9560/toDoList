<?php ob_start() ?>

<section class="calendar">

      <h1>Calendrier des tâches</h1>

      <div class="calendar-nav">
            <button id="prev-month">&lt; Mois précédent</button>
            <span id="current-month"></span>
            <button id="next-month">Mois suivant &gt;</button>
      </div>

      <div id="calendar-container"></div>

</section>

<?php if (!empty($message)): ?>
      <div class="alert-success" id="successMessage"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<?php include 'modals/addTask.php' ?>


<?php
render('default', true, [
      'title' => "Calendrier des tâches",
      'style' => "assets/css/calendar.css",
      'js' => 'assets/js/calendar.js',
      'content' => ob_get_clean()
]) ?>