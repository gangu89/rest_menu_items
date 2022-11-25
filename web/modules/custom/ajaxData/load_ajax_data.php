<?php

function view_more_blog($limitcount){
$nids = \Drupal::entityQuery('node')->range($limitcount,1)->execute();
$blogData = \Drupal\node\Entity\Node::loadMultiple($nids);
$Pflag =0;
foreach($blogData as $key => $value)
{
echo '<div><h2>'.$value->title->value.'</h2></div>';
}
}

