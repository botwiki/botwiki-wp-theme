<?php
use ColorThief\ColorThief;
$Parsedown = new Parsedown();

?>
<!DOCTYPE html>
<html>
<head>
  <title>converter v0.1.0</title>
</head>
<body>
  <h1>converter v0.1.0</h1>
<?php
  // echo "<pre><code>";
  // echo "</code></pre>";
  // die();

  $path = get_template_directory() . "/botwiki-import/content/bots/";
  $files = scandir($path);

  $files_count = count($files)-2;

  ?>
  <h2>processing <?php echo $files_count; ?> files...</h2>
  <pre><code>
  <?php
  // var_dump($files);
  ?>
  </code></pre>
  <?php

  function make_slug($string) {
    $string = strtolower($string);
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    $string = preg_replace("/[\s-]+/", " ", $string);
    $string = preg_replace("/[\s_]/", "-", $string);
    return $string;
  }

  $current = 0;

  foreach ($files as $file) {
    $current++;

    if (strpos($file, '.md') !== false){
      error_log("processing $file...($current/$files_count)");

      $file_contents = file_get_contents($path . $file);

      preg_match("/\/\*[\s\S]*?\*\/|([^:]|^)\/\/.*$/", $file_contents, $matches);
      $file_meta_arr = explode("\n", str_replace(["/*\n", "\n*/"], '', $matches)[0]);

      $file_meta = array();

      foreach ($file_meta_arr as $meta) {
        $__meta = explode(": ", $meta);
        // array_push($file_meta, array($__meta[0] => $__meta[1]));
        $file_meta[$__meta[0]] = $__meta[1];
      }

      $file_content = trim(str_replace($matches[0], '', $file_contents));
      $file_content_html = trim($Parsedown->text($file_content));

      $dom = new DomDocument();
      $dom->loadHTML($file_content_html);

      $bot_author_info = '';

      foreach ($dom->getElementsByTagName('a') as $node){
        $bot_author_info .= $node->nodeValue . ', ' . $node->getAttribute("href")."\n";
      }

      // $file_meta = $file_meta[0];
      echo "<h3>file:</h3>";
      echo "<pre><code>";
      echo $path . $file;
      echo "</code></pre>";

      echo "<h3>file meta:</h3>";
      echo "<pre><code>";
      echo($file_meta);
      echo "</code></pre>";

      echo "<h3>file content html:</h3>";
      echo "<pre><code>";
      echo($file_content_html);
      echo "</code></pre>";

      echo "<h3>bot author info:</h3>";
      echo "<pre><code>";
      echo($bot_author_info);
      echo "</code></pre>";

      $programing_languages = array();

      $bot_meta = array();
      $networks = array();
      $bot_meta['bot_url'] = $file_meta['Link'];

      if (strpos($file_meta['Tags'], 'twitter') !== false){
        array_push($networks, 'twitter-bots');
      }

      if (strpos($file_meta['Tags'], 'facebook-messenger') !== false){
        array_push($networks, 'facebook-messenger-bots');
      }

      if (strpos($file_meta['Tags'], 'telegram') !== false){
        array_push($networks, 'telegram-bots');
      }

      if (strpos($file_meta['Tags'], 'tumblr') !== false){
        array_push($networks, 'tumblr-bots');
      }

      if (strpos($file_meta['Tags'], 'slack') !== false){
        array_push($networks, 'slack-bots');
      }

      if (strpos($file_meta['Tags'], 'snapchat') !== false){
        array_push($networks, 'snapchat-bots');
      }

      if (strpos($file_meta['Tags'], 'kik') !== false){
        array_push($networks, 'kik-bots');
      }

      // TODO: Add the rest of the networks

      if (strpos($file_meta['Tags'], 'opensource') !== false){
        if (strpos($file_meta['Tags'], 'python') !== false){
          array_push($programing_languages, 'python');
        }

        if (strpos($file_meta['Tags'], 'node.js') !== false){
          array_push($programing_languages, 'nodejs');
        }

        if (strpos($file_meta['Tags'], 'ruby') !== false){
          array_push($programing_languages, 'ruby');
        }

        if (strpos($file_meta['Tags'], 'php') !== false){
          array_push($programing_languages, 'php');
        }

        if (strpos($file_meta['Tags'], 'java') !== false){
          array_push($programing_languages, 'java');
        }

        $bot_meta['bot_source_url'] = $file_meta['Source'];      
      }

      $original_bot_tags = explode(',', $file_meta['Tags']);
      $bot_tags = array();

      foreach ($original_bot_tags as $tag) {
        if (!in_array($tag, [
          'twitter',
          'twitterbot',
          'kik',
          'kikbot',
          'snapchat',
          'snapchatbot',
          'slack',
          'tumblr',
          'tumblrbot',
          'telegram',
          'telegrambot',
          'slackbot',
          'bot',
          'active',
          'open source',
          'interactive',
          'nodejs,',
          'node',
          'nodejs',
          'python',
          'ruby',
          'php',
          'java',
          'colour'
        ])){
          array_push($bot_tags, $tag);    
        }
      }

      foreach ($bot_tags as $tag) {
        wp_insert_term($tag, 'post_tag', array(
          'description' => '',
          'slug' => make_slug($tag)
        ));
      }

      $post_data = array(
        'post_author' => 2,
        'post_content' => $file_content_html,
        'post_title' => $file_meta['Title'],
        'post_excerpt' => $file_meta['Description'],
        'post_status' => 'draft',
        'post_type' => 'bot',
        'post_category' => '',
        'tax_input' => array(
          'post_tag' => $bot_tags
        ),
        'meta_input' => $bot_meta
      );

      $post_id = wp_insert_post($post_data);
      $image_path = $path . 'images/' . str_replace('@', '', $file_meta['Title']) . '.png';

      update_post_meta($post_id, 'bot_author_info', $bot_author_info);

      foreach ($bot_meta as $key => $value) {
        update_post_meta($post_id, $key, $value);
      }

      foreach ($networks as $network) {
        wp_set_object_terms( $post_id, $network, 'network' );
      }

      foreach ($programing_languages as $language) {
        wp_set_object_terms( $post_id, $language, 'programing_language' );
      }

      try {
        $dominant_color = ColorThief::getColor($image_path);
        update_post_meta($post_id, 'dominant_color', json_encode($dominant_color));
      } catch (Exception $e) {
        // noop
      }

      global $helpers;

      $helpers->add_post_thumbnail($post_id, $image_path, $file_meta['Description']);
    }
    echo "<hr/>";

  }

  error_log("done...");
?>
</body>
</html>
