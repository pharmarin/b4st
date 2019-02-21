<?php
// prise en compte du dossier de traduction du theme enfant à la place du parent
function my_child_theme_setup() {
 load_child_theme_textdomain( 'b4st', get_template_directory() ); // languages étant le chemin du dossier dans lequel se trouvent vos fichiers .po et .mo
}
add_action( 'after_setup_theme', 'my_child_theme_setup' );
