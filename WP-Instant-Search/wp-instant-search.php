<?php
/*
Plugin Name: WP Instant Search
Plugin URI: https://kevin-benabdelhak.fr/plugins/wp-instant-search/
Description: Améliorez l'expérience de recherche dans votre tableau de bord WordPress avec une fonction de recherche instantanée.
Version: 1.1
Author: Kevin Benabdelhak
Author URI: https://kevin-benabdelhak.fr
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wp-instant-search
*/

if (!defined('ABSPATH')) {
    exit;
}

/* trie le tableau de publication instantanément en cachant les publications dont les mots du titre ne commencent pas les mêmes caractères */
require_once plugin_dir_path(__FILE__) . 'includes/trier-publications.php';

/* possibilité d'augmenter le nombre de publications (+ de 999 ) */
require_once plugin_dir_path(__FILE__) . 'includes/nombre-publications.php';