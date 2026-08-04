<?php
// views/components/table_helpers.php
// Helpers pour les tableaux de transactions : regrouper les lignes par jour
// et afficher un séparateur ("Aujourd'hui", "Hier", ou la date) entre chaque groupe.

if (!function_exists('table_day_label')) {
    function table_day_label(string $dateStr): string
    {
        $date = date('Y-m-d', strtotime($dateStr));
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));

        if ($date === $today) {
            return "Aujourd'hui";
        }
        if ($date === $yesterday) {
            return 'Hier';
        }
        return date('d/m/Y', strtotime($dateStr));
    }
}

// Affiche la ligne séparatrice si le jour de $dateStr diffère du dernier jour affiché.
// $lastDay est passé par référence pour suivre l'état entre chaque itération de la boucle.
if (!function_exists('table_render_day_separator')) {
    function table_render_day_separator(string $dateStr, ?string &$lastDay, int $colspan): void
    {
        $day = date('Y-m-d', strtotime($dateStr));
        if ($day === $lastDay) {
            return;
        }
        $lastDay = $day;
        echo '<tr class="table-day-separator"><td colspan="' . $colspan . '">'
            . htmlspecialchars(table_day_label($dateStr))
            . '</td></tr>';
    }
}
