<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include plugin_dir_path(__FILE__) . '../includes/reset-password-handler.php';
}
?>
<form method="post" class="bsv-reset-form" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
    <h2>Richiesta Reset Password</h2>
    <label>Nome: <input type="text" name="nome" required></label>
    <label>Cognome: <input type="text" name="cognome" required></label>
    <label>Email: <input type="email" name="email" required></label>
    <input type="hidden" name="prefisso" value="+39" >
	<label>Telefono: <input type="text" name="telefono" required></label>
    <input type="submit" name="bsv_reset_request" value="Verifica dati">
</form>
