<?php 

if (!defined('ABSPATH')) {
    exit;
}



// modifier le nombre d'éléments par page pour toutes les publications
add_filter('get_user_metadata', 'modifier_nombre_publications_par_page', 10, 4);
function modifier_nombre_publications_par_page($check, $object_id, $meta_key, $single) {
    if (in_array($meta_key, ['edit_post_per_page', 'edit_page_per_page', 'edit_product_per_page'])) {
        $utilisateur_par_page = get_user_meta($object_id, '_custom_edit_post_per_page', true);
        return !empty($utilisateur_par_page) ? intval($utilisateur_par_page) : 20; 
    }
    return $check;
}




// remplacer le input par un nouveau
add_action('admin_footer', 'modifier_nombre_publications_par_page_js');
function modifier_nombre_publications_par_page_js() {
    global $pagenow;
    if ($pagenow != 'edit.php') return; 

    $type_publication = isset($_GET['post_type']) ? $_GET['post_type'] : 'post'; 
    $types_publication_supportes = ['post', 'page', 'product']; 

    if (!in_array($type_publication, $types_publication_supportes)) return; 

    ?>
    <style>
        #screen-options-wrap .screen-per-page, .screen-options label {
            display: none;
        }
    </style>

    <script>
        (function($) {
            $(document).ready(function() {
                var $divOptionsEcran = $('.screen-options'),
                    utilisateurParPage = <?= get_user_meta(get_current_user_id(), '_custom_edit_post_per_page', true) ?: 20; ?>;
                
                $divOptionsEcran.append(
                    '<div id="custom-edit-post-per-page" class="screen-options">' +
                        '<label for="custom_edit_post_per_page">Éléments par page: </label>' +
                        '<input type="number" step="1" min="1" id="custom_edit_post_per_page" value="' + utilisateurParPage + '" style="width: 80px;" />' +
                        '<button id="save_custom_per_page" class="button button-primary">Sauvegarder</button>' +
                    '</div>'
                );

                $('#save_custom_per_page').on('click', function() {
                    var nouveauParPage = parseInt($('#custom_edit_post_per_page').val(), 10) || 20;

                    $.post(ajaxurl, {
                        action: 'sauvegarder_nombre_publications_par_page',
                        per_page: nouveauParPage,
                        nonce: '<?= wp_create_nonce("sauvegarder_nombre_publications_par_page"); ?>'
                    }, function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert('Échec de la sauvegarde de la valeur.');
                        }
                    });
                });
            });
        })(jQuery);
    </script>
    <?php
}

// sauvegarder la valeur 
add_action('wp_ajax_sauvegarder_nombre_publications_par_page', 'sauvegarder_nombre_publications_par_page');
function sauvegarder_nombre_publications_par_page() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'sauvegarder_nombre_publications_par_page')) {
        wp_send_json_error();
    }
    $nombre_par_page = isset($_POST['per_page']) ? intval($_POST['per_page']) : 20;
    update_user_meta(get_current_user_id(), '_custom_edit_post_per_page', $nombre_par_page);
    
    wp_send_json_success();
}