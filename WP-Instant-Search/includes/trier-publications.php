<?php 

if (!defined('ABSPATH')) {
    exit;
}


function wp_scripts_recherche_instantanee($hook) {
    if (!in_array($hook, ['edit.php', 'edit.php?post_type=page', 'edit.php?post_type=product'])) {
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
add_action('admin_enqueue_scripts', 'wp_scripts_recherche_instantanee');

