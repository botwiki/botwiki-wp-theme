<?php

class Describe_Topic {
  public function __construct() {
    add_shortcode( 'describe', array( $this, 'describe' ) );
  }

  public function describe( $atts ) {
    $description = '';
    $topic = strtolower( trim( $atts['topic'] ) );

    switch ( $topic ) {
      case 'elon musk':
        $description = <<<TEXT
        <a href="https://www.reddit.com/r/EnoughMuskSpam/comments/7vuvf0/why_i_hate_elon_musk_and_why_you_should_hate_him/">Elon Musk</a>, 
        <a href="https://www.vox.com/identities/2019/9/30/20891314/elon-musk-tesla-labor-violation-nlrb">union-busting</a>
        billionaire heir to an <a href="https://www.businessinsider.co.za/how-elon-musks-family-came-to-own-an-emerald-mine-2018-2">apartheid emerald fortune</a>,
        <a href="https://en.wikipedia.org/wiki/Neuralink#Animal_testing">animal torturer</a>,
        and a <a href="https://gizmodo.com/heres-elon-musk-giving-donald-trump-a-stern-talking-to-1791966416">Donald Trump collaborator</a>.
  TEXT;
        break;
    }

    return $description;
  }
}

$describe_topic_init = new Describe_Topic();
