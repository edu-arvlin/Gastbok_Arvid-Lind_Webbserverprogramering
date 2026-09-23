```php
<?php

# Filnamn
$file = "gastbok.json";
$template = "gastbok.html";


# Läs in JSON-filen
if (file_exists($file)) {

    $data = file_get_contents($file);
    $entries = json_decode($data, true);

} else {

    $entries = [];

}


# Om formuläret har skickats
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["namn"] ?? "");
    $message = trim($_POST["meddelande"] ?? "");


    # Kontrollera att båda fälten innehåller något
    if ($name != "" && $message != "") {

        # Skapa ett nytt inlägg
        $new_entry = array(
            "namn" => $name,
            "meddelande" => $message,
            "tid" => time()
        );


        # Lägg till inlägget
        $entries[] = $new_entry;


        # Spara alla inlägg som JSON
        file_put_contents(
            $file,
            json_encode(
                $entries,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            )
        );

    }


    # Ladda om sidan efter att formuläret skickats
    header("Location: index.php");
    exit;

}


# Skapa HTML för alla inlägg
$inlagg = "";


foreach ($entries as $entry) {

    $name = htmlspecialchars($entry["namn"]);
    $message = nl2br(htmlspecialchars($entry["meddelande"]));

    $time = date(
        "Y-m-d H:i:s",
        $entry["tid"]
    );


    $inlagg .= "
        <article class=\"inlagg-box\">

            <div class=\"inlagg-header\">

                <h3>$name</h3>

                <span>$time</span>

            </div>

            <p>$message</p>

        </article>
    ";

}


# Om det inte finns några inlägg
if (count($entries) == 0) {

    $inlagg = "<p class=\"inga-inlagg\">Det finns inga inlägg ännu.</p>";

}


# Läs HTML-mallen
$html = file_get_contents($template);


# Byt ut {{INLAGG}} mot våra inlägg
$html = str_replace(
    "{{INLAGG}}",
    $inlagg,
    $html
);


# Skriv ut den färdiga HTML-sidan
echo $html;

?>
```
