<?php

/**
 * Create condition options.
 */
class context_node_edited_since_context_condition_date_since extends context_condition {
  function condition_values() {
    $values = array();
    $values['week'] = 'week';
    $values['month'] = 'month';
    return $values;
  }

/**
 * Change the condition form widget to a select box.
 * todo: Implement this featuer, a user should only be able to select one option
 */
/*  function condition_form($context) {
    // Get the condition form
    $form = parent::condition_form($context);

    // Change form type to selectbox
    $form['#type'] = 'select';

    return $form;
  } */

  function execute($node) {
    // get the UID fo the current user
    global $user;
    $user_id = $user->uid;

    // Get the content type of the currently edited node
    $content_type = $node->type;

    // select the nodes edited by this user and get the edit date of the most
    // recent revision. So we know how long ago thuis user edited a node like
    // this.
    $last_edit_date = db_query("SELECT r.timestamp
                                FROM {node_revision} r
                                INNER JOIN {node} n
                                ON r.nid = n.nid
                                WHERE r.uid = :user AND n.type = :content_type
                                ORDER BY r.timestamp DESC
                                LIMIT 1",
            array(':user' => $user_id,
            ':content_type' => $content_type,
            ))->fetchField();

    // We are going to compare the found date with the context setting
    if($this->get_contexts('month')) {
      // create the date for 1 month ago
      $month_ago = strtotime('-1 month');
      if ($month_ago > $last_edit_date ) {
        // Current content type has not been edited by current user for more
        // than a month.
        foreach ($this->get_contexts('month') as $context) {
            $this->condition_met($context, 'month');
        }
      }
    } elseif ($this->get_contexts('week')) {
      // create the date for 1 week ago
      $week_ago = strtotime('- 1 week');

      if ($week_ago > $last_edit_date ) {
        // Current content type has not been edited by current user for more
        // than a week.
        foreach ($this->get_contexts('week') as $context) {
            $this->condition_met($context, 'week');
          }
      }
    }
  }
}
