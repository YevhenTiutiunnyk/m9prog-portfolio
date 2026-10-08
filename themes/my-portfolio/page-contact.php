<?php
$contact_errors = array();
$contact_values = array(
    'contact_name'    => '',
    'contact_email'   => '',
    'contact_company' => '',
    'contact_message' => '',
);

if (isset($_POST['yevhen_contact_nonce'])) {

    if (! wp_verify_nonce($_POST['yevhen_contact_nonce'], 'yevhen_contact')) {
        $contact_errors[] = 'Beveiligingscontrole mislukt. Probeer het opnieuw.';
    }

    $contact_values['contact_name']    = sanitize_text_field(wp_unslash($_POST['contact_name'] ?? ''));
    $contact_values['contact_email']   = sanitize_email(wp_unslash($_POST['contact_email'] ?? ''));
    $contact_values['contact_company'] = sanitize_text_field(wp_unslash($_POST['contact_company'] ?? ''));
    $contact_values['contact_message'] = sanitize_textarea_field(wp_unslash($_POST['contact_message'] ?? ''));

    if ('' === $contact_values['contact_name']) {
        $contact_errors[] = 'Vul je naam in.';
    }

    if ('' === $contact_values['contact_email']) {
        $contact_errors[] = 'Vul je e-mailadres in.';
    } elseif (! is_email($contact_values['contact_email'])) {
        $contact_errors[] = 'Dit e-mailadres klopt niet.';
    }

    if ('' === $contact_values['contact_message']) {
        $contact_errors[] = 'Schrijf een bericht.';
    }

    if (empty($contact_errors) && yevhen_is_spam($contact_values)) {
        $contact_errors[] = 'Je bericht is als spam gemarkeerd. Neem contact op via e-mail.';
    }

    if (empty($contact_errors)) {

        $to      = get_option('admin_email');
        $subject = 'Nieuw bericht via je portfolio';
        $body    = "Naam: {$contact_values['contact_name']}\n"
            . "E-mail: {$contact_values['contact_email']}\n"
            . "Bedrijf: {$contact_values['contact_company']}\n\n"
            . $contact_values['contact_message'];
        $headers = array('Reply-To: ' . $contact_values['contact_email']);

        if (wp_mail($to, $subject, $body, $headers)) {
            wp_safe_redirect(get_permalink() . '?sent=1');
            exit;
        }

        $contact_errors[] = 'Het bericht kon niet verzonden worden.';
    }
}

get_header();
?>

<main id="main" class="site-main section">
    <div class="container">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <?php the_title('<h1>', '</h1>'); ?>
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="page-thumbnail">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php endif; ?>
                    <?php the_content(); ?>
                    <?php if (isset($_GET['sent'])) : ?>
                        <div class="form-success" role="status">
                            <p>Bedankt! Je bericht is verzonden.</p>
                        </div>
                    <?php endif; ?>
                    <?php if (! empty($contact_errors)) : ?>
                        <div class="form-errors" role="alert">
                            <p>Er ging iets mis:</p>
                            <ul>
                                <?php foreach ($contact_errors as $error) : ?>
                                    <li><?php echo esc_html($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <p>* verplicht veld</p>
                    <form class="contact-form" method="post" action="<?php echo esc_url(get_permalink()); ?>">
                        <?php wp_nonce_field('yevhen_contact', 'yevhen_contact_nonce'); ?>

                        <p class="field">
                            <label for="contact-name">Naam <span class="required" aria-hidden="true">*</span></label>
                            <input type="text" id="contact-name" name="contact_name" required autocomplete="name" value="<?php echo esc_attr($contact_values['contact_name']); ?>">
                        </p>
                        <p class="field">
                            <label for="contact-email">E-mail <span class="required" aria-hidden="true">*</span></label>
                            <input type="email" id="contact-email" name="contact_email" required autocomplete="email" value="<?php echo esc_attr($contact_values['contact_email']); ?>">
                        </p>
                        <p class="field">
                            <label for="contact-company">Bedrijf </label>
                            <input type="text" id="contact-company" name="contact_company" autocomplete="organization" value="<?php echo esc_attr($contact_values['contact_company']); ?>">
                        </p>
                        <p class="field">
                            <label for="contact-message">Bericht <span class="required" aria-hidden="true">*</span></label>
                            <textarea id="contact-message" name="contact_message" required rows="6"><?php echo esc_textarea($contact_values['contact_message']); ?></textarea>
                        </p>

                        <button type="submit">Verstuur bericht</button>
                    </form>

                    <p class="form-privacy">
                        Je bericht wordt gecontroleerd op spam door Akismet. Daarbij worden je
                        IP-adres, e-mailadres en bericht naar Automattic (VS) verzonden.
                    </p>
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <p>Nothing found.</p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>