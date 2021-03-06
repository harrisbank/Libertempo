<?php

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'define.php';
defined( '_PHP_CONGES' ) or die( 'Restricted access' );

$session=(isset($_GET['session']) ? $_GET['session'] : ((isset($_POST['session'])) ? $_POST['session'] : session_id()) ) ;

include_once ROOT_PATH .'fonctions_conges.php' ;
include_once INCLUDE_PATH .'fonction.php';
include_once INCLUDE_PATH .'session.php';
include_once ROOT_PATH .'fonctions_calcul.php';

// verif des droits du user à afficher la page
verif_droits_user($session, "is_resp");


    /*************************************/
    // recup des parametres reçus :
    // SERVER
    $PHP_SELF=$_SERVER['PHP_SELF'];
    // GET / POST
    $onglet = getpost_variable('onglet', "page_principale");

    /*********************************/
    /*   COMPOSITION DES ONGLETS...  */
    /*********************************/

    $onglets = array();


    $onglets['page_principale'] = _('resp_menu_button_retour_main');

    if( $_SESSION['config']['user_saisie_demande'] )
        $onglets['traitement_demandes'] = _('resp_menu_button_traite_demande');

    if( $_SESSION['config']['resp_ajoute_conges'] )
        $onglets['ajout_conges'] = _('resp_ajout_conges_titre');

    if (false)
        $onglets['cloture_exercice'] = _('button_cloture');

    if ( !isset($onglets[ $onglet ]) && !in_array($onglet, array('traite_user')))
        $onglet = 'page_principale';

    /*********************************/
    /*   COMPOSITION DU HEADER...    */
    /*********************************/

    $add_css = '<style>#onglet_menu .onglet{ width: '. (str_replace(',', '.', 100 / count($onglets) )).'% ;}</style>';
    header_menu('', 'Libertempo : '._('divers_responsable_maj_1'),$add_css);

    /*********************************/
    /*   AFFICHAGE DES ONGLETS...  */
    /*********************************/

    echo '<div id="onglet_menu">';
    foreach($onglets as $key => $title) {
        echo '<div class="onglet '.($onglet == $key ? ' active': '').'" >
            <a href="'.$PHP_SELF.'?session='.$session.'&onglet='.$key.'">'. $title .'</a>
        </div>';
    }
    echo '</div>';


    /*********************************/
    /*   AFFICHAGE DE L'ONGLET ...    */
    /*********************************/


    /** initialisation des tableaux des types de conges/absences  **/
    // recup du tableau des types de conges (seulement les conges)
    $tab_type_cong=recup_tableau_types_conges();

    // recup du tableau des types de conges exceptionnels (seulement les conges exceptionnels)
    $tab_type_conges_exceptionnels=array();
  if ($_SESSION['config']['gestion_conges_exceptionnels'])
        $tab_type_conges_exceptionnels=recup_tableau_types_conges_exceptionnels();

    echo '<div class="'.$onglet.' main-content">';
        include_once ROOT_PATH . 'responsable/resp_'.$onglet.'.php';
    echo '</div>';

    /*********************************/
    /*   AFFICHAGE DU BOTTOM ...   */
    /*********************************/

    bottom();
    exit;
