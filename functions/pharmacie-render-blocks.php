<?php

add_filter( 'lazyblock/fiche-produit/frontend_callback', 'lazyblock_fiche_produit_callback', 10, 2 );
add_filter( 'lazyblock/identite/frontend_callback', 'lazyblock_identite_callback', 10, 2 );
add_filter( 'lazyblock/precautions/frontend_callback', 'lazyblock_precautions_callback', 10, 2 );
add_filter( 'lazyblock/utilisation-he/frontend_callback', 'lazyblock_utilisation_he_callback', 10, 2 );
add_filter( 'lazyblock/conseils/frontend_callback', 'lazyblock_conseils_callback', 10, 2 );
add_filter( 'lazyblock/formules-he/frontend_callback', 'lazyblock_formules_he_callback', 10, 2 );
add_filter( 'lazyblock/resume-formation/frontend_callback', 'lazyblock_resume_formation_callback', 10, 2 );

function is_on_filter() {
  if (is_archive() && !is_single()) return true;
}

function lazyblock_fiche_produit_callback ($output, $attributes) {
  //array_debug($attributes);
  $labels = [
    'indication' => 'Indication',
    'composition' => 'Composition',
    'action' => 'Mode d\'action',
    'utilisation' => 'Posologie',
    'conseils' => 'Conseils associés',
    'avantages' => 'Plus produit',
    'precautions' => 'Précautions d\'utilisation'
  ];
  $is_first = true;
  $dom = new Pharma_DOMDocument();
  $div = $dom->createChildElement($dom, 'div', [
    'id' => 'fiche',
    'class' => 'card fiche-produit-callback'
  ]);
  $ul = $dom->createChildElement($div, 'ul', ['class' => 'list-group list-group-flush']);
  foreach ($labels as $slug => $label) {
    $contenu = $attributes[$slug];
    if (!empty(trim(strip_tags($contenu)))) {
      $a = $dom->createChildElement($ul, 'div', [//'a', [
        //'href' => '#'.$slug,
        'class' => 'list-group-item list-group-item-light',
        //'data-toggle' => 'collapse'
      ]);
      $h5 = $dom->createChildElement($a, 'h5', ['class' => 'mb-0'], $label);
      $collapse = $dom->createChildElement($ul, 'div', [
        //'id' => $slug,
        //'class' => $is_first ? 'collapse show' : 'collapse',
        //'data-parent' => '#fiche'
      ]);
      $li = $dom->createChildElement($collapse, 'li', ['class' => 'list-group-item border-top rounded-0'], $contenu);
      $is_first = false;
    }
  }

  return html_entity_decode($dom->saveHTML());
}

function lazyblock_identite_callback ($output, $attributes, $archive = false) {
  $is_archive = $archive ? $archive : is_on_filter();
  $dom = new Pharma_DOMDocument();
  $id = get_the_ID();
  if (!$is_archive) $row = $dom->createChildElement($dom, 'div', ['class' => 'row lazyblock-identite']);
  //Populate identites array
  $famille = get_the_term_list($id, 'famille');
  $drogues = get_the_terms($id, 'drogues_vegetales');
  $composition = $attributes['composition'];
  $origines = get_the_terms($id, 'origines_geographiques');
  $p_famille = '<i class="far fa-folder-open"></i> Famille des ' . $famille;
  $p_organe = '<i class="fab fa-pagelines"></i> Organe(s) distillé(s) : ';
  for ($i=0; $i < count($drogues); $i++) {
    $p_organe .= $drogues[$i]->name;
    if ($i != count($drogues)-1) $p_organe .= ", ";
  }
  $has_composition = !empty($attributes['composition']) && $attributes['composition'] != false;
  if ($has_composition) {
    $p_composition = '<i class="far fa-atom"></i> Molécule(s) principale(s) : ';
    for ($i=0; $i < count($composition); $i++) {
      $molecule = $composition[$i]['molecule'];
      $molecule_link = get_term_link(get_term_by('name', $composition[$i]['molecule'], 'principes_actifs'), 'principes_actifs');
      $p_composition .= '<a href="'.$molecule_link.'">'.$molecule.'</a>';
      if ($i != count($composition)-1) $p_composition .= ", ";
    }
  }
  $has_origines = !empty($origines) && $origines != false;
  if ($has_origines) {
    $p_origines = '<i class="far fa-globe-americas"></i> Origine(s) : ';
    for ($i=0; $i < count($origines); $i++) {
      $p_origines .= $origines[$i]->name;
      if ($i != count($origines)-1) $p_origines .= ", ";
    }
  }

  $identites = ['famille', 'organe', 'composition', 'origines'];
  if (!$has_origines) unset($identites[3]);
  if (!$has_composition) unset($identites[2]);
  foreach ($identites as $identite) {
    if (!$is_archive) {
      $col = $dom->createChildElement($row, 'div', ['class' => 'col-sm-6']);
      $card = $dom->createChildElement($col, 'div', ['class' => 'card mb-4']);
      $card_body = $dom->createChildElement($card, 'div', ['class' => 'card-body'], ${"p_$identite"});
    } else {
      $p = $dom->createChildElement($dom, 'p', ['class' => 'mb-0'], ${"p_$identite"});
    }
  }

  // Return anticipé si on est sur la page d'archive, pour afficher uniquement le résumé
  if ($is_archive) return html_entity_decode($dom->saveHTML());

  // Ajout de propriétés et usages (entrés comme termes, n'apparaissent pas dans le corps du block)
  $h3 = $dom->createChildElement($dom, 'h3', [], 'Propriétés principales');
  foreach (get_the_terms($id, 'proprietes') as $propriete) {
    $list_proprietes[$propriete->parent][] = $propriete;
  }
  $usages = get_the_terms($id, 'usages');
  $card_columns = $dom->createChildElement($dom, 'div', ['class' => 'card-columns']);
  foreach ($list_proprietes as $parent_propriete => $child_propriete) {
    $term = get_term_by('term_taxonomy_id', $parent_propriete, 'propriete');
    if (is_object($term)) :
      $card = $dom->createChildElement($card_columns, 'div', ['class' => 'card']);
      $card_body = $dom->createChildElement($card, 'div', ['class' => 'card-body']);
      $h5 = $dom->createChildElement($card_body, 'h5', [], get_term_by('term_taxonomy_id', $parent_propriete, 'propriete')->name);
      $i=0;
      foreach ($child_propriete as $child) {
        $content = $child->name;
        if ($i < count($child_propriete)-1) {
          $content .= ', ';
          $i++;
        }
        $a = $dom->createChildElement($card_body, 'a', ['href' => esc_url(get_term_link($child, 'proprietes'))], $content);
      }
    endif; // if is_object
  } //foreach $list_proprietes

  $h5 = $dom->createChildElement($dom, 'h5', ['class' => 'mt-3'], 'Utilisation traditionnelle');
  foreach ($usages as $usage) {
    $usage = $dom->createChildElement($dom, 'a', [
      'href' => get_term_link($usage, 'usages'),
      'class' => 'badge badge-pill badge-light mr-3'
    ], $usage->name);
  }

  return html_entity_decode($dom->saveHTML());
}

function lazyblock_precautions_callback ($output, $attributes) {
  if (is_on_filter()) return "<!-- " . __FUNCTION__ . " -->";
  $options = [
    'title' => true,
    'col' => 6
  ];
  if (get_post_type() == 'produit') {
    $options['title'] = false;
    $options['col'] = 12;
  }
  $dom = new Pharma_DOMDocument();
	$precautions = $attributes;
	if ($precautions['femme_enceinte_ok']) {
		$precautions_class[] = [
			'class' => 'success',
			'text' => 'Peut être utilisé chez la femme enceinte. '
		];
	}
	if ($precautions['femme_allaitante_ok']) {
		$precautions_class[] = [
			'class' => 'success',
			'text' => 'Peut être utilisé chez la femme allaitante. '
		];
	}
	if ($age_minimal = $precautions['age_minimal']) {
		$precautions_class[] = [
      'icon' => 'child',
			'class' => 'warning',
			'text' => 'Ne pas utiliser avant '.$age_minimal.' ans. '
		];
	}
  if (!empty($attributes['precaution'][0]['precaution_status']) && !empty($attributes['precaution'][0]['precaution_commentaire'])) {
    foreach ($attributes['precaution'] as $prec) {
      $class = get_icon_and_class('precaution', $prec['precaution_status']);
      $precautions_class[] = [
        'class' => $class->class,
        'icon' => $class->icon,
        'text' => $prec['precaution_commentaire']
      ];
    }
  }
  if ($options['title']) {
    $h3 = $dom->createChildElement($dom, 'h3', ['class' => 'mt-4'], "Précautions d'emploi");
  }
  $row = $dom->createChildElement($dom, 'div', ['class' => 'row']);
  foreach ($precautions_class as $index => $precaution) :
    $col = $dom->createChildElement($row, 'div', ['class' => 'col-sm-' . $options['col']]);
    $alert = $dom->createChildElement($col, 'div', ['class' => 'alert alert-'. $index .' alert-'.$precaution['class']]);
    $icon = $dom->createChildElement($alert, 'i', ['class' => 'fas fa-'.$precaution['icon'].' mr-2']);
    $text = $dom->createChildElement($alert, 'span', [], $precaution['text']);
  endforeach;

  if (get_post_type() == 'aromatherapie') {
    $col = $dom->createChildElement($row, 'div', ['class' => 'col-sm-' . $options['col']]);
    $alert = $dom->createChildElement($col, 'div', ['class' => 'alert alert-info']);
    $icon = $dom->createChildElement($alert, 'i', ['class' => 'fas fa-plus-square mr-1']);
    $link = $dom->createChildElement($alert, 'a', [
      'class' => 'alert-link',
      'data-toggle' => 'collapse',
      'href' => '#precautions_generales'
    ], 'Précautions pour les huiles essentielles');
    $collapse = $dom->createChildElement($alert, 'div', [
      'class' => 'collapse',
      'id' => 'precautions_generales'
    ]);
    $ul = $dom->createChildElement($collapse, 'ul', ['class' => 'mb-0 mt-2']);
    $precautions_he = [
      'Ne pas appliquer près des yeux, des oreilles et du nez (sauf indication spécifique)',
      'Ne pas diffuser en continu, ni en présence d\'un jeune enfant',
      'Ne pas laisser à la portée des enfants',
      'Ne pas utiliser chez l\'enfant de moins de 7 ans sans avis médical',
      'Ne pas utiliser chez la femme enceinte ou allaitante',
      'Ne pas utiliser chez les personnes âgées sans avis médical',
      'Ne pas utiliser en cas d\'épilepsie, d\'allergie aux molécules aromatiques',
      'Ne pas utiliser en continu',
      'Ne pas utiliser plus de 2 ou 3 huiles essentielles en même temps',
      'Ne pas utiliser une huile essentielle pure, sauf mention contraire',
      'Se laver les mains à l\'eau et au savon après application'
    ];
    foreach ($precautions_he as $prec_he) {
      $dom->createChildElement($ul, 'li', [], $prec_he);
    }
  }

  return html_entity_decode($dom->saveHTML());
}

function lazyblock_utilisation_he_callback ($output, $attributes) {
  if (is_on_filter()) return "<!-- " . __FUNCTION__ . " -->";
  $dom = new Pharma_DOMDocument();
  $h5 = $dom->createChildElement($dom, 'h3', ['class' => 'mt-4'], "Modes d'utilisation");
  $columns = $dom->createChildElement($dom, 'div', ['class' => 'card-columns mb-3']);

  $utilisations_array = [];
  $utilisations = $attributes;

  $ambiance['title'] = "Ambiance olfactive";
  $ambiance['recos'] = $utilisations['ambiance_recos'];
  $ambiance['commentaire'] = $utilisations['ambiance_commentaire'];
  $utilisations_array[] = $ambiance;

  $diffusion['title'] = "Diffusion atmosphérique";
  $diffusion['recos'] = $utilisations['diffusion_recos'];
  $diffusion['commentaire'] = $utilisations['diffusion_commentaire'];
  $utilisations_array[] = $diffusion;

  $application['title'] = "Application cutanée";
  $application['recos'] = $utilisations['application_recos'];
  $application['commentaire'] = $utilisations['application_commentaire'];
  $utilisations_array[] = $application;

  $orale['title'] = "Prise orale";
  $orale['recos'] = $utilisations['orale_recos'];
  $orale['commentaire'] = $utilisations['orale_commentaire'];
  $utilisations_array[] = $orale;

  $inhalation_seche['title'] = "Inhalation sèche";
  $inhalation_seche['recos'] = $utilisations['inhalation_seche_recos'];
  $inhalation_seche['commentaire'] = $utilisations['inhalation_seche_commentaire'];
  $utilisations_array[] = $inhalation_seche;

  $inhalation_humide['title'] = "Inhalation humide";
  $inhalation_humide['recos'] = $utilisations['inhalation_humide_recos'];
  $inhalation_humide['commentaire'] = $utilisations['inhalation_humide_commentaire'];
  $utilisations_array[] = $inhalation_humide;

  $sauna_facial['title'] = "Sauna facial";
  $sauna_facial['recos'] = $utilisations['sauna_facial_recos'];
  $sauna_facial['commentaire'] = $utilisations['sauna_facial_commentaire'];
  $utilisations_array[] = $sauna_facial;

  foreach ($utilisations_array as $utilisation) :
    $class = get_icon_and_class('utilisation', $utilisation['recos']);
    if (!empty($utilisation['recos']) && $utilisation['recos'] != "0" || !empty($utilisation['commentaire'])) :
      $card = $dom->createChildElement($columns, 'div', ['class' => 'card border-'.$class->class]);
      $card_body = $dom->createChildElement($card, 'div', ['class' => 'card-body']);
      $card_class = ($utilisation['recos'] == "-1") ? $class->class : "";
      $card_title = $dom->createChildElement($card_body, 'h5', ['class' => 'card-title text-'.$card_class.' mb-0']);
      $card_icon = $dom->createChildElement($card_title, 'i', ['class' => 'far fa-'.$class->icon.' mr-1 text-'.$class->class]);
      $card_title_text = $dom->createChildElement($card_title, 'span', [], $utilisation['title']);
      if ($utilisation['recos'] != "-1") {
        $h6 = $dom->createChildElement($card_body, 'h6', ['class' => 'text-muted mt-1']);
        for ($i=0; $i < $utilisation['recos']; $i++) {
          $dom->createChildElement($h6, 'i', ['class' => 'far fa-star']);
        }
      }
      $card_text = $dom->createChildElement($card_body, 'p', ['class' => 'card-text'], nl2br($utilisation['commentaire']));
    endif;
  endforeach;

  return html_entity_decode($dom->saveHTML());
}

function lazyblock_conseils_callback ($output, $attributes) {
  if (is_on_filter()) return "<!-- " . __FUNCTION__ . " -->";
  $conseil = $attributes;
  $dom = new Pharma_DOMDocument();
  $card = $dom->createChildElement($dom, 'div', ['class' => 'card']);//$columns, 'div', ['class' => 'card']);
  if ($conseil['titre']) {
    $card_header = $dom->createChildElement($card, 'div', ['class' => 'card-header']);
    $h6 = $dom->createChildElement($card_header, 'h6', ['class' => 'mb-0'], $conseil['titre']);
  }
  $card_body = $dom->createChildElement($card, 'div', ['class' => 'card-body'], links_to_popover($conseil['conseil']));
  if ($conseil['source']) {
    $card_footer = $dom->createChildElement($card, 'div', ['class' => 'card-footer py-1']);
    if ($source = get_post_by_url($conseil['source'])) {
      $source = $dom->createChildElement($card_footer, 'em', ['class' => 'text-muted'], $source->post_title);
    }
  }

  return html_entity_decode($dom->saveHTML());
}

function lazyblock_formules_he_callback ($output, $attributes) {
  if (is_on_filter()) return "<!-- " . __FUNCTION__ . " -->";
  $formule = $attributes;
	$dom = new Pharma_DOMDocument();
	$card = $dom->createChildElement($dom, 'div', ['class' => 'card']);
  if ($formule['titre']) {
    $card_header = $dom->createChildElement($card, 'div', ['class' => 'card-header']);
    $h6 = $dom->createChildElement($card_header, 'h6', ['class' => 'mb-0'], $formule['titre']);
  }
  $card_body = $dom->createChildElement($card, 'div', ['class' => 'card-body']);
  $table = $dom->createChildElement($card_body, 'table', ['class' => 'table table-bordered table-sm']);
  $tbody = $dom->createChildElement($table, 'tbody');
  foreach ($formule['ingredients'] as $ingredient) {
    $tr = $dom->createChildElement($tbody, 'tr');
    $dom->createChildElement($tr, 'td', [], links_to_popover($ingredient['ingredient']));
    $dom->createChildElement($tr, 'td', [], $ingredient['quantite']);
  }
  $dom->createChildElement($card_body, 'span', [], links_to_popover($formule['instructions']));
  if ($formule['source']) {
    $card_footer = $dom->createChildElement($card, 'div', ['class' => 'card-footer py-1']);
    if ($source = get_post_by_url($formule['source'])) {
      $source = $dom->createChildElement($card_footer, 'em', ['class' => 'text-muted'], $source->post_title);
    }
  }
	return html_entity_decode($dom->saveHTML());
}

function lazyblock_resume_formation_callback ($output, $attributes) {
  $dom = new Pharma_DOMDocument();

  $columns = empty($attributes['columns']) ? 5 : $attributes['columns'];
  $titles = $attributes['titles'];
  $contents = $attributes['contents'];
  $block_color = empty($attributes['color']) ? 'black' : $attributes['color'];
  $colors = [];
  $processed_columns = [];

  $row = $dom->createChildElement($dom, 'div', [
    'class' => 'row-flex-' . $columns . ' d-flex flex-wrap text-center mb-4',
    'style' => 'border: 5px solid ' . $block_color . '; border-radius: 0.5rem !important;'
  ]);

  for ($i=0; $i < $columns; $i++) {
    $title = $titles[$i];
    $span = empty($title['span']) ? 1 : $title['span'];
    $color = empty($title['color']) ? 'red' : $title['color'];
    $processed_columns[] = [
      'width' => $span,
      'title' => $title['title'],
      'color' => $color,
      'columns' => range($i, $i + $span - 1)
    ];
    $i = $i + $span - 1;
  }

  foreach ($contents as $content) {
    for ($l=0; $l < count($processed_columns); $l++) {
      $current_column = $content['column'] - 1;
      if (in_array($current_column, $processed_columns[$l]['columns'])) {
        $processed_columns[$l]['innerColumns'][$current_column][] = $content;
      }
    }
  }

  foreach ($processed_columns as $title_key => $title_column) {
    $color = $title_column['color'];
    $first_class_before = ' before-'.strtolower(reset($title_column['innerColumns'])[0]['before']);
    unset(reset($title_column['innerColumns'])[0]['before']);
    $title_col = $dom->createChildElement($row, 'div', ['class' => 'col-parent col-flex-' . $title_column['width'].$first_class_before]);
    $title = $dom->createChildElement($title_col, 'h6', [
      'class' => 'text-light text-uppercase font-weight-bold mb-0 p-2',
      'style' => 'background-color: ' . $color . ';'
    ], $title_column['title']);
    $inner_row = $dom->createChildElement($title_col, 'div', ['class' => 'row-flex-' . count($title_column['innerColumns']) . ' d-flex flex-wrap']);
    $is_first = true;
    foreach ($title_column['innerColumns'] as $inner_column) {
      $inner_col = $dom->createChildElement($inner_row, 'div', ['class' => 'col-flex col-flex-1']);
      foreach ($inner_column as $content) {
        $inner_class = $is_first ? '' : strtolower($content['before']);
        $is_first = false;
        $col = $dom->createChildElement($inner_col, 'div', ['class' => 'col-child before-' . $inner_class]);
        $product = explode('/', wp_parse_url(untrailingslashit($content['produit']))['path']);
        $product_post = get_page_by_path($product[2], OBJECT, $product[1]);
        $dom->createChildElement($col, 'p', [
          'class' => 'font-weight-bold',
          'style' => 'color: ' . $color . ';'
        ], $content['subtitle']);
        $dom->createChildElement($col, 'img', [
          'src' => get_the_post_thumbnail_url($product_post),
          'style' => 'max-height: 200px;'
        ]);
        $dom->createChildElement($col, 'h6', [], $product_post->post_title);
        $dom->createChildElement($col, 'p', [], $content['posologie']);
      }
    }
  }

  return html_entity_decode($dom->saveHTML());
}

add_filter( 'lazyblock/plus-moins/frontend_callback', 'lazyblock_plus_moins_callback', 10, 2 );

function lazyblock_plus_moins_callback ($output, $attributes) {
  $dom = new Pharma_DOMDocument();
  $row = $dom->createChildElement($dom, 'div', ['class' => 'row']);
  $plus = $dom->createChildElement($row, 'div', ['class' => 'col-sm-6 border']);
  $plus_title = $dom->createChildElement($plus, 'div', ['class' => 'col-sm text-center text-success py-2']);
  $dom->createChildElement($plus_title, 'i', ['class' => 'fa fa-plus']);
  $dom->createChildElement($plus, 'div', ['class' => 'col-sm'], $attributes["plus"]);
  $moins = $dom->createChildElement($row, 'div', ['class' => 'col-sm-6 border']);
  $moins_title = $dom->createChildElement($moins, 'div', ['class' => 'col-sm text-center text-danger py-2']);
  $dom->createChildElement($moins_title, 'div', ['class' => 'fa fa-minus']);
  $dom->createChildElement($moins, 'div', ['class' => 'col-sm'], $attributes["moins"]);
  return html_entity_decode($dom->saveHTML());
}

add_shortcode('card_columns', 'render_card_columns');

function render_card_columns ($attrs, $content) {
  //var_dump($content);
  if (is_on_filter()) return "<!-- " . __FUNCTION__ . " -->";
  $dom = new Pharma_DOMDocument();
  $h3 = $dom->createChildElement($dom, 'h3', ['class' => 'mt-4'], "Conseils et idées de formulation");
  $columns = $dom->createChildElement($dom, 'div', ['class' => 'card-columns'], substr($content, 4, -3));
  return html_entity_decode($dom->saveHTML());
}
