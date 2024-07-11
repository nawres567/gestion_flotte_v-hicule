<?php
// Include the database connection file
include 'config.php';

// Fonction pour obtenir le nom du mois en français
function getFrenchMonthName($month) {
    $months = [
        1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
        5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
        9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
    ];
    return $months[$month];
}

// Récupérer le mois, l'année et le jour actuels ou depuis les paramètres GET
$currentMonth = isset($_GET['month']) ? $_GET['month'] : date('n');
$currentYear = isset($_GET['year']) ? $_GET['year'] : date('Y');
$currentDay = isset($_GET['day']) ? $_GET['day'] : '';

// Calculer le premier et dernier jour du mois sélectionné
$firstDayOfMonth = date('Y-m-01', strtotime("$currentYear-$currentMonth-01"));
$lastDayOfMonth = date('Y-m-t', strtotime("$currentYear-$currentMonth-01"));

// Construire la requête SQL pour récupérer les entretiens
$sql = "SELECT * FROM llx_product_extrafields WHERE DATE(date) BETWEEN '$firstDayOfMonth' AND '$lastDayOfMonth'";
if (!empty($currentDay)) {
    $selectedDate = date('Y-m-d', strtotime("$currentYear-$currentMonth-$currentDay"));
    $sql .= " AND DATE(date) = '$selectedDate'";
}
$result = $conn->query($sql);

// Créer un tableau associatif des entretiens par date pour faciliter l'affichage dans le calendrier
$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $eventDate = date('Y-m-d', strtotime($row['date']));
        $events[$eventDate][] = $row; // Regrouper les entretiens par date
    }
}

// Fermer la connexion à la base de données
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier des Entretiens - AutoFlotte</title>
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/calendar.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

<section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bxs-car'></i>
            <span class="text">AutoFlotte</span>
        </a>
        <ul class="side-menu top">
            <li >
                <a href="dashbord.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="vehicule.php">
                    <i class='bx bxs-car'></i>
                    <span class="text">Véhicules</span>
                </a>
            </li>
            <li >
                <a href="listConducteurs.php">
                    <i class='bx bxs-group'></i>
                    <span class="text">Conducteurs</span>
                </a>
            </li>
            <li class="active">
                <a href="carburant.php">
                    <i class='bx bxs-gas-pump'></i>
                    <span class="text">Suivi du carburant</span>
                </a>
            </li>
            <li >
                <a href="listEntretien.php">
                    <i class='bx bxs-wrench'></i>
                    <span class="text">Maintenance</span>
                </a>
            </li>
			<li >
              <a href="inspections.php"> <!-- Ajout du lien pour les inspections -->
            <i class='bx bxs-check-shield'></i>
            <span class="text">Inspections</span>
              </a>
            </li>
            <li>
                <a href="#">
                    <i class='bx bxs-map'></i>
                    <span class="text">Localisation</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="#">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
            <li>
                <a href="logout.php" class="logout">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>

<section id="content">
    <nav>
        <i class='bx bx-menu'></i>
        <a href="#" class="nav-link">Categories</a>
        <form action="#">
            <div class="form-input"></div>
        </form>
        <input type="checkbox" id="switch-mode" hidden>
        <label for="switch-mode" class="switch-mode"></label>
        <a href="#" class="notification">
            <i class='bx bxs-bell'></i>
            
        </a>
        <a href="profil.php" class="profile">
            <img src="img/people.webp">
        </a>
    </nav>
    <div class="notification-popup">
    <div class="notification-content">
        <span class="close">&times;</span>
        <h2>Prochains entretiens</h2>
        <ul>
            <?php
            // Fetch upcoming maintenance events
            $today = date('Y-m-d');
            $upcomingEvents = [];
            foreach ($events as $eventDate => $eventDetails) {
                if ($eventDate >= $today) {
                    foreach ($eventDetails as $event) {
                        $upcomingEvents[] = "<li>{$event['nom']} le {$eventDate}</li>";
                    }
                }
            }
            // Display upcoming events in the popup
            if (count($upcomingEvents) > 0) {
                echo implode('', $upcomingEvents);
            } else {
                echo "<li>Aucun entretien prévu prochainement</li>";
            }
            ?>
        </ul>
    </div>
</div>

    <main>
        <div class="head-title">
            <div class="left">
                <h1>Calendrier des Entretiens</h1>
                <ul class="breadcrumb">
                    <li><a href="#">Maintenance</a></li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li><a href="#" class="active">Calendrier</a></li>
                </ul>
            </div>
        </div>

        
        <div class="calendar">
            <div class="calendar-header">
                <form action="#" method="GET">
                    <label for="month">Mois :</label>
                    <select name="month" id="month">
                        <?php
                        for ($m = 1; $m <= 12; ++$m) {
                            $monthName = getFrenchMonthName($m);
                            $selected = ($m == $currentMonth) ? 'selected' : '';
                            echo "<option value='$m' $selected>$monthName</option>";
                        }
                        ?>
                    </select>
                    <label for="year">Année :</label>
                    <input type="number" name="year" id="year" value="<?php echo $currentYear; ?>">
                    <label for="day">Jour :</label>
                    <select name="day" id="day">
                        <option value="">Tous</option>
                        <?php
                        for ($d = 1; $d <= 31; ++$d) {
                            $selected = ($d == $currentDay) ? 'selected' : '';
                            echo "<option value='$d' $selected>$d</option>";
                        }
                        ?>
                    </select>
                    <button type="submit">Filtrer</button>
                </form>
            </div>
            <div class="calendar-body">
                <?php
                // Nombre de jours dans le mois sélectionné
                $daysInMonth = date('t', strtotime("$currentYear-$currentMonth-01"));

                // Jour de la semaine du premier jour du mois
                $firstDayOfWeek = date('N', strtotime("$currentYear-$currentMonth-01"));

                // Générer les jours vides pour compléter la semaine du début du mois
                for ($i = 1; $i < $firstDayOfWeek; $i++) {
                    echo "<div class='calendar-day empty'></div>";
                }

                // Boucle pour afficher chaque jour du mois
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $date = date('Y-m-d', strtotime("$currentYear-$currentMonth-$day"));
                    $dayOfWeek = date('N', strtotime($date)); // 1 (lundi) à 7 (dimanche)

                    echo "<div class='calendar-day'>";
                    echo "<span class='day-number'>$day</span>";

                    // Afficher les entretiens pour ce jour
                    if (isset($events[$date])) {
                        foreach ($events[$date] as $event) {
                            // Déterminer la classe CSS en fonction de l'état de l'événement
                            $eventClass = '';
                            switch ($event['etat']) {
                                case 'en cours':
                                    $eventClass = 'event-yellow';
                                    break;
                                case 'terminé':
                                    $eventClass = 'event-green';
                                    break;
                                case 'à faire':
                                    $eventClass = 'event-blue';
                                    break;
                                default:
                                    $eventClass = ''; // Gérer les autres états si nécessaire
                                    break;
                            }

                            echo "<div class='event $eventClass'>";
                            echo "<span class='event-title'>{$event['nom']}</span>";
                            echo "<br>";
                            echo "<span class='event-time'>{$event['tache']}</span>";
                            echo "</div>";
                        }
                    }

                    echo "</div>"; // Fermer calendar-day

                    // Ajouter une nouvelle ligne après chaque semaine complète (7 jours)
                    if ($dayOfWeek == 7 && $day != $daysInMonth) {
                        echo "</div><div class='calendar-body'>";
                    }
                }
                ?>
            </div>
        </div>
    </main>
</section>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bellIcon = document.querySelector('.notification');
    const notificationPopup = document.querySelector('.notification-popup');
    const closeBtn = document.querySelector('.notification-popup .close');

    bellIcon.addEventListener('click', function() {
        notificationPopup.classList.toggle('show');
    });

    closeBtn.addEventListener('click', function() {
        notificationPopup.classList.remove('show');
    });
});
</script>

<!-- SCRIPTS -->
<script src="script.js"></script>
</body>
</html>