<?php
/*
Plugin Name: WP Instant Search
Plugin URI: https://kevin-benabdelhak.fr/plugins/wp-instant-search/
Description: Améliorez l'expérience de recherche dans votre tableau de bord WordPress avec une fonction de recherche instantanée et filtrage dynamique.
Version: 1.0
Author: Kevin Benabdelhak
Author URI: https://kevin-benabdelhak.fr
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wp-instant-search
*/

if (!defined('ABSPATH')) {exit;}

function wp_instant_search_scripts($hook) {
    if ('edit.php' !== $hook ) {
        return;
    }
    wp_enqueue_script('jquery');
    
    $js_interne = "
    jQuery(document).ready(function($) {
        var champRecherche = $('#posts-filter input[name=\"s\"]');
        var timeout = null;

        function normaliserChaine(str) {
            return str.replace(/['’]/g, '');
        }

        champRecherche.on('input', function() {
            clearTimeout(timeout);
            var termeRecherche = normaliserChaine(champRecherche.val().toLowerCase());

            timeout = setTimeout(function() {
                $('#the-list tr').each(function() {
                    var titreElement = $(this).find('.column-title .row-title, .column-name .row-title');
                    var titre = normaliserChaine(titreElement.text().toLowerCase());

                    if (titre.indexOf(termeRecherche) !== -1 || termeRecherche.length === 0) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }, 300);
        });

        champRecherche.focus();
    });
    ";

    wp_add_inline_script('jquery', $js_interne);
}
add_action('admin_enqueue_scripts', 'wp_instant_search_scripts');
