<?php
	// include config file
	include_once("config.php");
	session_start();

	$sql = "
		SELECT
			CASE
				WHEN u_i.id IS NOT NULL THEN u_i.id
				ELSE u_o.id
			END AS id,
			MAX(m.messages) AS latest_message,
			MAX(m.create_at) AS most_recent_time
		FROM mensajeria
		LEFT JOIN users u_i ON u_i.id = mensajeria.id_incoming AND u_i.id != {$_SESSION['id']}
		LEFT JOIN users u_o ON u_o.id = mensajeria.id_outgoing AND u_o.id != {$_SESSION['id']}
		LEFT JOIN messages m
			ON (m.outgoing = mensajeria.id_outgoing AND m.incoming = mensajeria.id_incoming)
			OR (m.outgoing = mensajeria.id_incoming AND m.incoming = mensajeria.id_outgoing)
		WHERE id_outgoing = {$_SESSION['id']} OR id_incoming = {$_SESSION['id']} AND estado = '1'
		GROUP BY
			CASE
				WHEN u_i.id IS NOT NULL THEN u_i.id
				ELSE u_o.id
			END
		ORDER BY mensajeria.fecha_mensaje DESC;
	";
	$query = mysqli_query($conn, $sql);

	if (!$query) {
		echo "La consulta fallo";
	} else {
		if (mysqli_num_rows($query) == 0) {
			echo '<div id="errors-online">No hay usuarios activos ahora</div>';
		} elseif (mysqli_num_rows($query) > 0) {
			include_once("dataMensajeria.php");
		} else {
			echo "Hubo un fallo, mientras se buscaba usuarios";
		}
	}
?>
