<?php


if (isset($_POST['confirm_reset'])) {
    $nome     = escapeshellarg($_POST['nome']);
    $cognome  = escapeshellarg($_POST['cognome']);
    $email    = escapeshellarg($_POST['email']);
    $telefono = escapeshellarg($_POST['telefono']);

    $py_script = '/usr/local/bin/reset_ad_password.py';
    $cmd = "/usr/bin/python3 $py_script $nome $cognome $email $telefono";

    exec($cmd . ' 2>&1', $output, $status);
	
if ($status === 0) {
    echo '<div class="bsv-reset-message success">Password resettata e inviata via email.</div>';
} else {
    echo '<div class="bsv-reset-message error">
        <strong>Errore durante il reset della password.</strong><br>
        Contattare il supporto tecnico per ulteriori verifiche.
    </div>';
}


    // ❗ Fermare qui l’esecuzione per non rieseguire la query a DB
    return;
}

// Dati inviati dal form
$nome     = trim($_POST['nome']);
$cognome  = trim($_POST['cognome']);
$email    = trim($_POST['email']);
$prefisso = trim($_POST['prefisso']);
$telefono = trim($_POST['telefono']);
$telefono_completo = $prefisso . $telefono;


// Connessione a DB esterno
$mysqli = new mysqli("10.0.7.100", "remote", "P@ssw0rd", "BSV-SEGRETERIA");
if ($mysqli->connect_error) {
    echo "<p style='color:red;'>Errore di connessione al database.</p>";
    return;
}

// Query per verifica dati
$stmt = $mysqli->prepare("
    SELECT * FROM anagrafica_hr 
    WHERE NOME = ? AND COGNOME = ? AND EMAIL = ? AND CELLULARE = ?
");
$stmt->bind_param("ssss", $nome, $cognome, $email, $telefono_completo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Dati corretti → chiedi conferma reset
echo '<div class="bsv-reset-message success">
    <strong>Dati verificati.</strong><br>
    L’utente è stato trovato nel sistema.<br>
    Premi il pulsante qui sotto per confermare il reset della password.
</div>';


    echo '
    <form method="post" style="margin-top: 15px;">
        <input type="hidden" name="nome" value="'.esc_attr($nome).'">
        <input type="hidden" name="cognome" value="'.esc_attr($cognome).'">
        <input type="hidden" name="email" value="'.esc_attr($email).'">
        <input type="hidden" name="telefono" value="'.esc_attr($telefono_completo).'">
        <input type="submit" name="confirm_reset" value="Conferma Reset Password" style="padding: 10px 20px; background: #d69e2e; color: white; border: none; border-radius: 5px; cursor: pointer;">
    </form>';
} else {
    // Nessuna corrispondenza → avviso personalizzato
echo '<div class="bsv-reset-message error">
    <strong>Account non trovato.</strong><br>
    I dati inseriti non corrispondono a nessun utente registrato.<br>
    Ti invitiamo a contattare il tuo referente di società per ricevere assistenza.
</div>';

}

$stmt->close();
$mysqli->close();



