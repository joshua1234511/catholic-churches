<?php

namespace Drupal\openchurch_homepage\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Render\Markup;

/**
 * Provides a 'Open Church User Menu' block.
 *
 * @Block(
 *   id = "0pen_church_user_menu_block",
 *   admin_label = @Translation("Open Church User Menu block"),
 * )
 */
class OpenChurchUserMenu extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    global $base_url;
    $userName = "";
    $node = \Drupal::routeMatch()->getParameter('node');
    if ($node instanceof \Drupal\node\NodeInterface) {
      $user = $node->getOwner();
      if($user){
        $userName = $user->getDisplayName();
        $userName = _openchurch_clean_alias($userName);
      }
    } else {
      $path = \Drupal::request()->getpathInfo();
      $path = trim($path,'/');
      $arg  = explode('/',$path);
      if(isset($arg[0]) && isset($arg[2])){
        $userName = $arg[2];
      }else if(isset($arg[0]) && isset($arg[1]) && !isset($arg[2])){
        $userName = $arg[1];
      }
    }
    $o = '<div class="user-specific">';
    $o .= '<ul>';
    $o .= '<li><a href="'.$base_url.'/ministries/'.$userName.'">Ministries</a></li>';
    $o .= '<li><a href="'.$base_url.'/media/podcasts/'.$userName.'">Sermon(Podcasts)</a></li>';
    $o .= '<li><a href="'.$base_url.'/blog/'.$userName.'">Blogs</a></li>';
    $o .= '<li><a href="'.$base_url.'/events/'.$userName.'">Events</a></li>';
    $o .= '<li><a href="'.$base_url.'/charity/'.$userName.'">Charity</a></li>';
    $o .= '<li><a href="'.$base_url.'/about/staff/'.$userName.'">Staff</a></li>';
    $o .= '<li><a href="'.$base_url.'/media/video/'.$userName.'">Videos</a></li>';
    $o .= '<li><a href="'.$base_url.'/media/bulletins/'.$userName.'">Bulletins</a></li>';
    $o .= '</ul>';
    $o .= '</div>';

    $form['#markup'] = Markup::create($o);
    $form['#cache']['max-age'] = 0;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['0pen_church_user_menu_block_settings'] = $form_state->getValue('0pen_church_user_menu_block_settings');
  }

}
