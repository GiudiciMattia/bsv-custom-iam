<?php
// File shortcode placeholder

function bsv_modifica_dati_hr() {
    if (!is_user_logged_in()) {
        return '<p>Utente non autenticato.</p>';
    }

    $user = wp_get_current_user();
    $email = esc_sql($user->user_email);

    $db_host = '10.0.7.100';
    $db_user = 'remote';
    $db_password = 'P@ssw0rd';
    $db_name = 'BSV-SEGRETERIA';

    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        return "<p>Errore connessione DB HR: " . esc_html($conn->connect_error) . "</p>";
    }

    $msg = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aggiorna_hr'])) {
    $id = intval($_POST['id']);

    $codice_fiscale = $conn->real_escape_string($_POST['codice_fiscale']);
    $nome = $conn->real_escape_string($_POST['nome']);
    $cognome = $conn->real_escape_string($_POST['cognome']);
    $cellulare = $conn->real_escape_string($_POST['cellulare']);
    $incarico = $conn->real_escape_string($_POST['incarico']);
    $stato_civile = $conn->real_escape_string($_POST['stato_civile']);
    $indirizzo = $conn->real_escape_string($_POST['indirizzo']);
    $cap = $conn->real_escape_string($_POST['cap']);
    $citta = $conn->real_escape_string($_POST['citta_residenza']);
    $provincia_residenza = $conn->real_escape_string($_POST['provincia_residenza']);
    $iban = $conn->real_escape_string($_POST['iban']);
    $stagione = $conn->real_escape_string($_POST['stagione']);
    $carta_identita = $conn->real_escape_string($_POST['carta_identita']);
    $luogo_nascita = $conn->real_escape_string($_POST['luogo_nascita']);
    $provincia_nascita = $conn->real_escape_string($_POST['provincia_nascita']);
    $data_nascita = $conn->real_escape_string($_POST['data_nascita']);
    $partita_iva = $conn->real_escape_string($_POST['partita_iva']);

    // Campi referente sempre acquisiti
    $referente_nome = $conn->real_escape_string($_POST['referente_nome'] ?? '');
    $referente_cognome = $conn->real_escape_string($_POST['referente_cognome'] ?? '');
    $referente_rel_carica = $conn->real_escape_string($_POST['referente_rel_carica'] ?? '');
    $referente_email = $conn->real_escape_string($_POST['referente_email'] ?? '');
    $referente_cellulare = $conn->real_escape_string($_POST['referente_cellulare'] ?? '');

	$account_facebook = $conn->real_escape_string($_POST['account_facebook'] ?? '');
	$account_instagram = $conn->real_escape_string($_POST['account_instagram'] ?? '');
	$preferenze = $conn->real_escape_string($_POST['preferenze'] ?? '');
	$presa_visione = isset($_POST['presa_visione']) ? intval($_POST['presa_visione']) : 0;
		
		
    $update = "
        UPDATE `anagrafica_hr` SET 
            `CODICE FISCALE` = '$codice_fiscale',
            `NOME` = '$nome',
            `COGNOME` = '$cognome',
            `CELLULARE` = '$cellulare',
            `INCARICO` = '$incarico',
            `STATO CIVILE` = '$stato_civile',
            `INDIRIZZO DI RESIDENZA` = '$indirizzo',
            `CAP` = '$cap',
            `CITTA' DI RESIDENZA` = '$citta',
            `PROVINCIA DI RESIDENZA` = '$provincia_residenza',
            `IBAN` = '$iban',
            `STAGIONE SPORTIVA` = '$stagione',
            `NUMERO CARTA IDENTITA'` = '$carta_identita',
            `LUOGO DI NASCITA` = '$luogo_nascita',
            `PROVINCIA DI NASCITA` = '$provincia_nascita',
            `DATA DI NASCITA` = '$data_nascita',
            `PARTITA IVA (se posseduta)` = '$partita_iva',
            `REFERENTE_NOME` = '$referente_nome',
            `REFERENTE_COGNOME` = '$referente_cognome',
            `REFERENTE_REL_CARICA` = '$referente_rel_carica',
            `REFERENTE_EMAIL` = '$referente_email',
            `REFERENTE_CELLULARE` = '$referente_cellulare',
			`ACCOUNT_FACEBOOK` = '$account_facebook',
			`ACCOUNT_INSTAGRAM` = '$account_instagram',
			`PREFERENZE` = '$preferenze',
			`PRESA_VISIONE` = $presa_visione
        WHERE `ID` = $id
    ";

    if ($conn->query($update) === TRUE) {
        $msg = "<p style='color:green; font-weight:bold;'>Dati aggiornati con successo.</p>";
    } else {
        $msg = "<p style='color:red;'>Errore aggiornamento: " . esc_html($conn->error) . "</p>";
    }
}

    $stmt = $conn->prepare("SELECT * FROM `anagrafica_hr` WHERE `EMAIL` LIKE ?");
    $like_email = "%$email%";
    $stmt->bind_param("s", $like_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return "<p>Nessun dato HR trovato per l’email <strong>" . esc_html($email) . "</strong>.</p>";
    }

	
	
    $row = $result->fetch_assoc();
    $stmt->close();
    $conn->close();

    ob_start();

    echo $msg;
    ?>

<style>
.bsv-hr-form {
    max-width: 900px;
    margin: 2rem auto;
    background: #f9f9f9;
    padding: 25px;
    border-radius: 10px;
    font-family: Arial, sans-serif;
}

.bsv-hr-form label {
    font-weight: bold;
    margin-bottom: 5px;
    color: #333;
}

.bsv-hr-form input[type="text"],
.bsv-hr-form input[type="date"],
.bsv-hr-form input[type="email"] {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    background: #fff;
    font-size: 1em;
}

.bsv-hr-form .bsv-flex-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 20px;
}

.bsv-hr-form .bsv-flex-row > div {
    flex: 1 1 48%;
}

.bsv-hr-form .bsv-full-width {
    flex: 1 1 100%;
}

.bsv-section-title {
    font-size: 1.2em;
    font-weight: bold;
    color: #0076BB;
    border-bottom: 2px solid #0076BB;
    margin: 25px 0 15px;
    padding-bottom: 5px;
}

.bsv-hr-form input[type="submit"] {
    background-color: #0076BB;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 6px;
    font-size: 1em;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.bsv-hr-form input[type="submit"]:hover {
    background-color: #005a94;
}

@media (max-width: 768px) {
    .bsv-hr-form .bsv-flex-row > div {
        flex: 1 1 100%;
    }
}
</style>

<form method="post" class="bsv-hr-form">
    <input type="hidden" name="id" value="<?php echo esc_attr($row['ID']); ?>" />

   <?php if (isset($row['MAGGIORENNE']) && intval($row['MAGGIORENNE']) === 1): ?>

        <div class="bsv-section-title">Referente</div>
		
		<div class="bsv-flex-row">
            <div>
                ATLETA MINORENNE
                <?php echo esc_attr($row['REFERENTE_REL_CARICA']); ?>
            </div>
			<div>
                <label>Ruolo/Relazione:</label>
                <input type="text" name="referente_rel_carica" value="<?php echo esc_attr($row['REFERENTE_REL_CARICA']); ?>" />
            </div>	
        </div>

        <div class="bsv-flex-row">
            <div>
                <label>Nome Referente:</label>
                <input type="text" name="referente_nome" value="<?php echo esc_attr($row['REFERENTE_NOME']); ?>" />
            </div>
            <div>
                <label>Cognome Referente:</label>
                <input type="text" name="referente_cognome" value="<?php echo esc_attr($row['REFERENTE_COGNOME']); ?>" />
            </div>
        </div>
        <div class="bsv-flex-row">
            <div>
                <label>Email Referente:</label>
                <input type="email" name="referente_email" value="<?php echo esc_attr($row['REFERENTE_EMAIL']); ?>" />
            </div>
			<div>
                <label>Cellulare Referente:</label>
                <input type="text" name="referente_cellulare" value="<?php echo esc_attr($row['REFERENTE_CELLULARE']); ?>" />
            </div>
        </div>
	
    <?php endif; ?>

    <div class="bsv-section-title">Dati Anagrafici</div>

    <div class="bsv-flex-row">
        <div>
            <label>Nome:</label>
            <input type="text" name="nome" value="<?php echo esc_attr($row['NOME']); ?>" required />
        </div>
        <div>
            <label>Cognome:</label>
            <input type="text" name="cognome" value="<?php echo esc_attr($row['COGNOME']); ?>" required />
        </div>
    </div>

    <div class="bsv-flex-row">
        <div>
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo esc_attr($row['EMAIL']); ?>" readonly />
        </div>
        <div>
            <label>Cellulare:</label>
            <input type="text" name="cellulare" value="<?php echo esc_attr($row['CELLULARE']); ?>" />
        </div>
    </div>

    <div class="bsv-flex-row">
        <div>
            <label>Codice Fiscale:</label>
            <input type="text" name="codice_fiscale" value="<?php echo esc_attr($row['CODICE FISCALE']); ?>" />
        </div>
        <div>
            <label>Carta Identità:</label>
            <input type="text" name="carta_identita" value="<?php echo esc_attr($row["NUMERO CARTA IDENTITA'"]); ?>" />
        </div>
    </div>

    <div class="bsv-flex-row">
        <div>
            <label>Partita IVA:</label>
            <input type="text" name="partita_iva" value="<?php echo esc_attr($row["PARTITA IVA (se posseduta)"]); ?>" />
        </div>
        <div>
            <label>IBAN:</label>
            <input type="text" name="iban" value="<?php echo esc_attr($row['IBAN']); ?>" />
        </div>
    </div>

    <div class="bsv-flex-row">
        <div>
            <label>Indirizzo:</label>
            <input type="text" name="indirizzo" value="<?php echo esc_attr($row['INDIRIZZO DI RESIDENZA']); ?>" />
        </div>
        <div>
            <label>CAP:</label>
            <input type="text" name="cap" value="<?php echo esc_attr($row['CAP']); ?>" />
        </div>
    </div>

    <div class="bsv-flex-row">
        <div>
            <label>Città:</label>
            <input type="text" name="citta_residenza" value="<?php echo esc_attr($row["CITTA' DI RESIDENZA"]); ?>" />
        </div>
        <div>
            <label>Provincia:</label>
            <input type="text" name="provincia_residenza" value="<?php echo esc_attr($row["PROVINCIA DI RESIDENZA"]); ?>" />
        </div>
    </div>

    <div class="bsv-flex-row">
        <div>
            <label>Luogo di Nascita:</label>
            <input type="text" name="luogo_nascita" value="<?php echo esc_attr($row["LUOGO DI NASCITA"]); ?>" />
        </div>
        <div>
            <label>Provincia di Nascita:</label>
            <input type="text" name="provincia_nascita" value="<?php echo esc_attr($row["PROVINCIA DI NASCITA"]); ?>" />
        </div>
    </div>

    <div class="bsv-flex-row">
        <div>
            <label>Data di Nascita:</label>
            <input type="date" name="data_nascita" value="<?php echo esc_attr(date('Y-m-d', strtotime($row['DATA DI NASCITA']))); ?>" />
        </div>
        <div>
            <label>Stato Civile:</label>
            <input type="text" name="stato_civile" value="<?php echo esc_attr($row['STATO CIVILE']); ?>" />
        </div>
    </div>

    <div class="bsv-flex-row">
        <div>
            <label>Incarico:</label>
            <input type="text" name="incarico" value="<?php echo esc_attr($row['INCARICO']); ?>" />
        </div>
        <div>
            <label>Stagione Sportiva:</label>
            <input type="text" name="stagione" value="<?php echo esc_attr($row['STAGIONE SPORTIVA']); ?>" />
        </div>
    </div>

<div class="bsv-section-title">Social & Preferenze</div>

<div class="bsv-flex-row">
    <div>
        <label>Account Facebook:</label>
        <input type="text" name="account_facebook" value="<?php echo esc_attr($row['ACCOUNT_FACEBOOK']); ?>" />
    </div>
    <div>
        <label>Account Instagram:</label>
        <input type="text" name="account_instagram" value="<?php echo esc_attr($row['ACCOUNT_INSTAGRAM']); ?>" />
    </div>
</div>

<div class="bsv-full-width">
    <label>Preferenze personali / Note:</label>
    <textarea name="preferenze" rows="4" style="width:100%; padding:10px;"><?php echo esc_textarea($row['PREFERENZE']); ?></textarea>
</div>

<div class="bsv-full-width" style="margin-top: 15px;">
    <label><strong>Presa visione e consensi privacy:</strong></label>
    <div style="display: flex; gap: 20px; margin-top: 8px;">
        <label><input type="radio" name="presa_visione" value="1" <?php checked($row['PRESA_VISIONE'], 1); ?> /> Sì</label>
        <label><input type="radio" name="presa_visione" value="0" <?php checked($row['PRESA_VISIONE'], 0); ?> /> No</label>
    </div>
</div>
	
	
    <div class="bsv-full-width" style="text-align: center; margin-top: 30px;">
        <input type="submit" name="aggiorna_hr" value="Aggiorna Dati" />
    </div>
</form>

<?php

    return ob_get_clean();
}
add_shortcode('modifica_dati_hr', 'bsv_modifica_dati_hr');





function bsv_documenti_hr() {
    if (!is_user_logged_in()) return '<p>Utente non autenticato.</p>';

    $user = wp_get_current_user();
    $email = esc_sql($user->user_email);

    $db_host = '10.0.7.100';
    $db_user = 'remote';
    $db_password = 'P@ssw0rd';
    $db_name = 'BSV-SEGRETERIA';

    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) return "<p>Errore connessione DB: " . esc_html($conn->connect_error) . "</p>";

    $stmt = $conn->prepare("SELECT * FROM anagrafica_hr WHERE EMAIL LIKE ?");
    $like_email = "%$email%";
    $stmt->bind_param("s", $like_email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) return "<p>Nessun dato trovato per l’email <strong>" . esc_html($email) . "</strong>.</p>";
    $row = $result->fetch_assoc();
    $codice_fiscale = $row['CODICE FISCALE'];
    $id_hr = $row['ID'];

    // Visita medica (tblProperties)
    $res_visita = $conn->query("SELECT valore FROM tblProperties WHERE idPadre = $id_hr AND tipo = 'VisitaMedica' ORDER BY id DESC LIMIT 1");
    $visita_file = '';
    if ($res_visita && $res_visita->num_rows > 0) {
        $valore = $res_visita->fetch_assoc()['valore'];
        $visita_file = explode('#', $valore)[0];
    }
	
function is_valid_pdf($file) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    return $mime_type === 'application/pdf' && $ext === 'pdf';
}	

$upload_dir = '/var/www/wordpress/wp-content/uploads/wpforms/staging/';
$upload_url = content_url('/uploads/wpforms/staging/');

    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

		if (isset($_POST['upload_cf']) && isset($_FILES['cf_pdf'])) {
			if (!is_valid_pdf($_FILES['cf_pdf'])) {
				echo "<p style='color:red;'>Il file caricato non è un PDF valido.</p>";
			} else {
				$filename = 'CodiceFiscale_' . $codice_fiscale . '_CF.pdf';
				if (move_uploaded_file($_FILES['cf_pdf']['tmp_name'], $upload_dir . $filename)) {
					$url = $upload_url . $filename;
					$conn->query("UPDATE anagrafica_hr SET LINK_CF_PDF = '$url' WHERE ID = $id_hr");
					// 🔁 Ricarica i dati aggiornati
					$res = $conn->query("SELECT * FROM anagrafica_hr WHERE ID = $id_hr");
					$row = $res->fetch_assoc();
					$msgCF = "<p style='color:green;'>Documento Codice Fiscale aggiornato.</p>";
				} else {
					$msgCF = "<p style='color:red;'>Errore durante il caricamento del file.</p>";
				}
			}
		}

		if (isset($_POST['upload_ci']) && isset($_FILES['ci_pdf'])) {
			if (!is_valid_pdf($_FILES['ci_pdf'])) {
				$msg = "<p style='color:red;'>Il file caricato non è un PDF valido.</p>";
			} else {
				$filename = 'DocID_' . $codice_fiscale . '_CI.pdf';
				$target = $upload_dir . $filename;

				if (move_uploaded_file($_FILES['ci_pdf']['tmp_name'], $target)) {
					$url = $upload_url . $filename;
					$conn->query("UPDATE anagrafica_hr SET LINK_CI_PDF = '$url' WHERE ID = $id_hr");

					// Ricarica i dati aggiornati
					$res = $conn->query("SELECT * FROM anagrafica_hr WHERE ID = $id_hr");
					$row = $res->fetch_assoc();

					$msgCI = "<p style='color:green;'>Documento Carta d'Identità aggiornato.</p>";
				} else {
					$msgCI = "<p style='color:red;'>Errore durante il caricamento del file.</p>";
				}
			}
		}

		if (isset($_POST['upload_visita']) && isset($_FILES['visita_pdf'])) {
			if (!is_valid_pdf($_FILES['visita_pdf'])) {
				$msg = "<p style='color:red;'>Il file caricato non è un PDF valido.</p>";
			} else {
				$filename = 'VisitaMedica_' . $codice_fiscale . '_' . time() . '.pdf';
				$target = $upload_dir . $filename;

				if (move_uploaded_file($_FILES['visita_pdf']['tmp_name'], $target)) {
					$valore = $filename . '#' . date('Y-m-d');
					$conn->query("INSERT INTO tblProperties (idPadre, tipo, valore) VALUES ($id_hr, 'VisitaMedica', '$valore')");

					// Aggiorna il valore da visualizzare
					$visita_file = $filename;

					$msgVM = "<p style='color:green;'>Documento Visita Medica aggiornato.</p>";
				} else {
					$msgVM = "<p style='color:red;'>Errore durante il caricamento del file.</p>";
				}
			}
		}

	// Recupera elenco CUD disponibili (assumendo tblProperties)
$cud_links = [];
$current_year = intval(date('Y'));
$anno = $current_year - 1;

$res_cud = $conn->query("
    SELECT valore 
    FROM tblProperties 
    WHERE idPadre = $id_hr AND tipo = 'CUD' AND stagione_sportiva = '$stagione' AND valore LIKE '%$anno%'
    ORDER BY id DESC LIMIT 1
");
$cud_link = $res_cud && $res_cud->num_rows ? explode('#', $res_cud->fetch_assoc()['valore'])[0] : '';
	
    $conn->close();

    ob_start();

    ?>

    <style>
		.bsv-doc-container {
			display: flex;
			flex-wrap: wrap;
			gap: 20px;
			justify-content: space-between;
			max-width: 1000px;
			margin: 0 auto;
		}
		.bsv-doc-box {
			flex: 1 1 calc(50% - 10px);
			background: #f4f4f4;
			border: 1px solid #ddd;
			border-radius: 8px;
			padding: 20px;
			font-family: Arial, sans-serif;
		}
		.bsv-doc-box h3 {
			color: #0076BB;
			margin-bottom: 15px;
		}
		.bsv-doc-box a {
			color: #0076BB;
			text-decoration: underline;
		}
		.bsv-doc-box form {
			margin-top: 10px;
		}
		@media (max-width: 768px) {
			.bsv-doc-box {
				flex: 1 1 100%;
			}
		}

		.bsv-doc-box form.bsv-inline-form {
			display: flex;
			align-items: center;
			gap: 10px;
			flex-wrap: wrap;
			margin-top: 10px;
		}
		.bsv-doc-box form.bsv-inline-form select {
			width: 120px;
			padding: 5px;
		}
		.bsv-doc-box form.bsv-inline-form input[type="submit"] {
			padding: 6px 12px;
			font-size: 14px;	
		}
	</style>

<div class="bsv-doc-container">

    <div class="bsv-doc-box">
		<?php echo $msgCF; ?>
        <h3>Codice Fiscale</h3>
        <?php if (!empty($row['LINK_CF_PDF'])): ?>
            <p><a href="<?php echo esc_url($row['LINK_CF_PDF']); ?>" target="_blank">Visualizza Codice Fiscale</a></p>
        <?php else: ?>
            <p><em>Documento non caricato.</em></p>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="cf_pdf" required />
            <input type="submit" name="upload_cf" value="Sostituisci" />
        </form>
    </div>

    <div class="bsv-doc-box">
		<?php echo $msgCI; ?>		
        <h3>Carta Identità</h3>
        <?php if (!empty($row['LINK_CI_PDF'])): ?>
            <p><a href="<?php echo esc_url($row['LINK_CI_PDF']); ?>" target="_blank">Visualizza Carta Identità</a></p>
        <?php else: ?>
            <p><em>Documento non caricato.</em></p>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="ci_pdf" required />
            <input type="submit" name="upload_ci" value="Sostituisci" />
        </form>
    </div>

    <div class="bsv-doc-box">
		<?php echo $msgVM; ?>		
        <h3>Visita Medica</h3>
        <?php if (!empty($visita_file)): ?>
            <p><a href="<?php echo esc_url($upload_url . $visita_file); ?>" target="_blank">Visualizza Visita Medica</a></p>
        <?php else: ?>
            <p><em>Documento non caricato.</em></p>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="visita_pdf" required />
            <input type="submit" name="upload_visita" value="Sostituisci" />
        </form>
    </div>

	<div class="bsv-doc-box">
		<h3>Download Certificazione Unica (CUD)</h3>
		<form method="post" class="bsv-inline-form">
			<label for="select_anno">Anno:</label>
			<select name="anno_cud" id="select_anno">
				<option value="<?php echo $anno; ?>"><?php echo $anno; ?></option>
			</select>
			<input type="submit" name="scegli_anno_cud" value="Visualizza" />
		</form>

		<?php if (isset($_POST['scegli_anno_cud'])): ?>
			<?php if (!empty($cud_link)): ?>
				<p><a href="<?php echo esc_url($cud_link); ?>" target="_blank">Scarica CUD anno <?php echo esc_html($anno); ?></a></p>
			<?php else: ?>
				<p><em>Certificazione Unica per l’anno <?php echo esc_html($anno); ?> non disponibile.</em></p>
			<?php endif; ?>
		<?php endif; ?>
	</div>

</div>


    <?php
    return ob_get_clean();
}
add_shortcode('documenti_hr', 'bsv_documenti_hr');


// [pagamenti_hr] shortcode
function bsv_pagamenti_hr_shortcode() {
	
	/*
	
    if (!is_user_logged_in()) return '<p>Utente non autenticato.</p>';

    $user = wp_get_current_user();
    $email = esc_sql($user->user_email);

    // Connessione DB
    $conn = new mysqli('10.0.7.100', 'remote', 'P@ssw0rd', 'BSV-SEGRETERIA');
    if ($conn->connect_error) return '<p>Errore DB.</p>';

    // Recupera codice fiscale e stagione sportiva
    $stmt = $conn->prepare("SELECT `CODICE FISCALE`, `STAGIONE SPORTIVA` FROM anagrafica_hr WHERE `EMAIL` = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) return '<p>Utente non trovato.</p>';

    $user_data = $result->fetch_assoc();
    $cf = $user_data['CODICE FISCALE'];
    $stagione = $user_data['STAGIONE SPORTIVA'];

    $periodi = ['Settembre', 'Dicembre', 'Marzo'];

    // Inizializza pagamenti se non presenti
    foreach ($periodi as $periodo) {
        $check = $conn->query("SELECT 1 FROM pagamenti_hr WHERE codice_fiscale = '$cf' AND periodo = '$periodo' AND stagione_sportiva = '$stagione'");
        if ($check->num_rows === 0) {
            $conn->query("INSERT INTO pagamenti_hr (codice_fiscale, periodo, stagione_sportiva) VALUES ('$cf', '$periodo', '$stagione')");
        }
    }

    // Aggiorna pagamento se inviato
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aggiorna_pagamento'])) {
        $periodo = $conn->real_escape_string($_POST['periodo']);
        $metodo = $conn->real_escape_string($_POST['metodo']);
        $stato = $conn->real_escape_string($_POST['stato']);
        $data = date('Y-m-d');
        $url = null;

        if (isset($_FILES['ricevuta']) && $_FILES['ricevuta']['error'] === 0 && $_FILES['ricevuta']['type'] === 'application/pdf') {
            $upload_dir = wp_upload_dir();
            $filename = 'pagamento_' . $cf . '_' . $periodo . '_' . time() . '.pdf';
            $dest_path = $upload_dir['basedir'] . '/pagamenti/' . $filename;
            if (!file_exists($upload_dir['basedir'] . '/pagamenti')) {
                mkdir($upload_dir['basedir'] . '/pagamenti', 0755, true);
            }
            move_uploaded_file($_FILES['ricevuta']['tmp_name'], $dest_path);
            $url = $upload_dir['baseurl'] . '/pagamenti/' . $filename;
        }

        $sql = "UPDATE pagamenti_hr SET data_pagamento = '$data', metodo_pagamento = '$metodo', stato_pagamento = '$stato'";
        if ($url) $sql .= ", ricevuta_url = '$url'";
        $sql .= " WHERE codice_fiscale = '$cf' AND periodo = '$periodo' AND stagione_sportiva = '$stagione'";
        $conn->query($sql);
    }

    // Leggi pagamenti
    $res = $conn->query("SELECT * FROM pagamenti_hr WHERE codice_fiscale = '$cf' AND stagione_sportiva = '$stagione'");
    $pagamenti = [];
    while ($r = $res->fetch_assoc()) {
        $pagamenti[$r['periodo']] = $r;
    }

    ob_start();
    echo '<div class="bsv-hr-form"><h2>Situazione Pagamenti - ' . esc_html($stagione) . '</h2>';
    echo '<table style="width:100%; border-collapse:collapse;">';
    echo '<tr><th>Periodo</th><th>Stato</th><th>Data</th><th>Metodo</th><th>Ricevuta</th><th>Aggiorna</th></tr>';

    foreach ($periodi as $p) {
        $r = $pagamenti[$p];
        echo '<tr style="border-bottom:1px solid #ccc;">';
        echo '<td>' . $p . '</td>';
        echo '<td>' . esc_html($r['stato_pagamento']) . '</td>';
        echo '<td>' . ($r['data_pagamento'] ? date('d/m/Y', strtotime($r['data_pagamento'])) : '-') . '</td>';
        echo '<td>' . esc_html($r['metodo_pagamento']) . '</td>';
        echo '<td>' . ($r['ricevuta_url'] ? '<a href="' . esc_url($r['ricevuta_url']) . '" target="_blank">PDF</a>' : '-') . '</td>';

        echo '<td><form method="post" enctype="multipart/form-data">';
        echo '<input type="hidden" name="periodo" value="' . esc_attr($p) . '" />';
        echo '<select name="stato">
                <option value="Pagato">Pagato</option>
                <option value="Non pagato">Non pagato</option>
                <option value="In attesa">In attesa</option>
              </select>';
        echo '<input type="text" name="metodo" placeholder="Metodo (es. Bonifico)" />';
        echo '<input type="file" name="ricevuta" accept="application/pdf" />';
        echo '<input type="submit" name="aggiorna_pagamento" value="Aggiorna" />';
        echo '</form></td>';
        echo '</tr>';
    }

    echo '</table></div>';
    return ob_get_clean();
*/
}
add_shortcode('pagamenti_hr', 'bsv_pagamenti_hr_shortcode');





function login_e_area_utente_shortcode() {
    ob_start();

    // URL di destinazione dopo login (modifica qui)
    $landing_url = home_url('/area-utente');

    if (isset($_POST['log']) && isset($_POST['pwd'])) {
        $creds = array();
        $creds['user_login'] = sanitize_user($_POST['log']);
        $creds['user_password'] = $_POST['pwd'];
        $creds['remember'] = true;

        $secure = is_ssl(); // TRUE se HTTPS
        $user = wp_signon($creds, $secure);

        if (is_wp_error($user)) {
            echo '<div class="login-error">Login fallito: credenziali errate o utente non autorizzato.</div>';
        } else {
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID, true, $secure);

            // Redirect immediato lato server
            wp_redirect($landing_url);
            exit;
        }
    }

    if (is_user_logged_in()) {
        wp_redirect($landing_url);
        exit;
    }

    ?>
    <form method="post" class="login-area">
        <label for="log">Username</label><br>
        <input type="text" name="log" id="log" required><br><br>

        <label for="pwd">Password</label><br>
        <input type="password" name="pwd" id="pwd" required><br><br>

        <label>
            <input type="checkbox" name="rememberme" checked> Ricordami
        </label><br><br>


<div style="margin-top: 20px; text-align: center;">
    <a href="#" onclick="this.closest('form').submit(); return false;" 
       class="button buttonViola" 
       style="margin-bottom:10px; display:inline-block;">
       Accedi
    </a>

    <a href="<?php echo esc_url(home_url('/registrazione-nuovo-utente/')); ?>" 
       class="button buttonViola" 
       style="margin-bottom:10px; display:inline-block; margin-left:10px;">
       Registrati
    </a>
    
    <br>

    <a href="<?php echo esc_url( home_url('/recupera-password/') ); ?>" 
       style="font-size: 0.9em; color: #0076BB; text-decoration: none;">
       Hai dimenticato la password?
    </a>
	
    
</div>
	
	
	
	
	
    </form>
    <?php

    return ob_get_clean();
}
add_shortcode('login_e_area_utente', 'login_e_area_utente_shortcode');


add_action('template_redirect', 'redirect_custom_pages_if_not_logged_in');

function redirect_custom_pages_if_not_logged_in() {
    $protected_pages = array('area-utente', 'datianagrafici', 'esci', 'documenti', 'pagamenti', 'media');

    if (is_page($protected_pages) && !is_user_logged_in()) {
        wp_redirect(site_url('/intranet'));
        exit;
    }
}

function render_usermenu_as_buttons() {
    ob_start();
    wp_nav_menu(array(
        'menu' => 'usermenu',
        'container' => 'div',
        'container_class' => 'usermenu-buttons',
        'menu_class' => 'usermenu-list',
        'fallback_cb' => false
    ));
    return ob_get_clean();
}
add_shortcode('usermenu_buttons', 'render_usermenu_as_buttons');

function logout_redirect_shortcode() {
    wp_logout();
    wp_redirect(home_url('/'));
    exit;
}
add_shortcode('logout_redirect', 'logout_redirect_shortcode');

add_theme_support('custom-header', [
  'video' => true
]);

function bsv_stampa_dati_utente_attuale() {
    if (!is_user_logged_in()) {
        return '<p>Utente non autenticato.</p>';
    }

    $user = wp_get_current_user();
    $output = "<h3>Dati Utente Autenticato</h3>";
    $output .= "<ul>";
    $output .= "<li><strong>Username:</strong> " . esc_html($user->user_login) . "</li>";
    $output .= "<li><strong>Email:</strong> " . esc_html($user->user_email) . "</li>";
    $output .= "<li><strong>Nome Completo:</strong> " . esc_html($user->display_name) . "</li>";
    $output .= "<li><strong>Ruoli:</strong> " . implode(', ', array_map('esc_html', $user->roles)) . "</li>";
    $output .= "</ul>";

//    // Mostra anche i meta utente
//    $user_meta = get_user_meta($user->ID);
//    $output .= "<h4>User Meta</h4><pre>" . esc_html(print_r($user_meta, true)) . "</pre>";
//
//    // Verifica e mostra sessione PHP, se presente
//    if (session_status() === PHP_SESSION_NONE) {
//        session_start();
//    }
//    if (!empty($_SESSION)) {
//        $output .= "<h4>Sessione PHP</h4><pre>" . esc_html(print_r($_SESSION, true)) . "</pre>";
//    }

    return $output;
}
add_shortcode('dati_utente', 'bsv_stampa_dati_utente_attuale');
