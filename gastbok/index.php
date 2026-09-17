<?php

# definiera filnamnet för gästboken
$file = "gastbok.json";

# tar in data från filen
if (file_exists($file)) {
    $data = file_get_contents($file);
    $entries = json_decode($data, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["namn"];
    $message = $_POST["meddelande"];

    $new_entry = array(
        "namn" => $name,
        "meddelande" => $message,
        "tid" => time()
    );

    # lägger till den nya posten i listan
    $entries[] = $new_entry;

    # sparar listan tillbaka till filen
    file_put_contents($file, json_encode($entries));

    $newentry = array(
        "namn" => $name,
        "meddelande" => $message,
        "tid" => time()
    );
}

# spara listan tillbaka till filen
file_put_contents($file, json_encode($entries));
?>