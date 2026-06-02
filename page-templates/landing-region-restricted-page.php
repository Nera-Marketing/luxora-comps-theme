<?php
/*
Template Name: Region Restricted Landing Page
*/
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php the_title(); ?></title>
    <?php wp_head(); ?>
    <style>
        /* Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; font-family: Arial, sans-serif; }
        .landing-container { width: 100%; height: 100%; background-color: #39453d; /* Replace with any color you like */ display: flex; justify-content: center; align-items: center; flex-direction: column; text-align: center; }
        .landing-logo { max-width: 250px; /* adjust as needed */ width: 80%; margin-bottom: 30px; }
        .message-box { background: rgba(0,0,0,0.5); padding: 30px 40px; border-radius: 15px; color: white; font-size: 1.2rem; max-width: 400px; line-height: 1.5; }
        @media (max-width: 768px) { .landing-logo { max-width: 180px; } .message-box { font-size: 1rem; padding: 20px; } }
    </style>
</head>
<body <?php body_class(); ?>>

<div class="landing-container">
    <!-- Logo Image -->
    <img src="<?php the_field('landing_logo'); ?>" alt="Luxora Draws Logo" class="landing-logo">

    <div class="message-box">
        <?php the_field('landing_message'); ?>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>