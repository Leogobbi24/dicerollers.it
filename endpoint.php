<?php
if(date('j')%2==0){

    if(strpos($_SERVER['REQUEST_URI'], 'localhost')===false)
        $conn = new mysqli("localhost", "root", "", "autoblog");
    else
        $conn = new mysqli("localhost", "ldiceroy_root", "h049iyz8j8tn", "ldiceroy_dicerollers");
    $sql="SELECT * FROM keywords WHERE text_body='' ORDER BY created ASC LIMIT 0,1";
    $result=$conn->query($sql);
    
    while($keyword=$result->fetch_array(MYSQLI_ASSOC)){
    
        $textLenght=($keyword['text_lenght']==0) ? 600 : $keyword['text_lenght'];
    
        $prompt="Scrivi in notazione html un articolo di blog di circa ".$textLenght." parole con ottimizzazione SEO per la parola chiave ".$keyword['keyword'].". Non serve che inserisci la parte di <head> e tutti i tag meta e non inserire nemmeno delle note. Dividi il testo in 3 o 4 sezioni e per ciascuna inserisci un titolo <h2>. Per inserire il grassetto, non utilizzare gli asterischi ma il tag <strong>. Utilizza al massimo 1 o 2 elenchi puntati con i tag <ul> ed <li>. Non inserire il grassetto dentro i titoli. Utilizza il grassetto su alcune delle parole pi첫 rilevanti, tipo la parola chiave. Fai in modo che ci sia sempre esattamente un titolo <h1>. Inserisci i paragrafi di testo all'interno di tag <p>. Non inserire sezioni di conclusione.";
    
        if($keyword['prompt_details'])
            $prompt.=" Ecco ulteriori dettagli per la creazione di questo articolo: ".$keyword['prompt_details'];
    
        $textBody=aiRequest($prompt);
        $textBody=addTypo(str_replace("*", "", $textBody));
        $update="UPDATE keywords SET created=".time().", text_body = '".addslashes($textBody)."' WHERE id=".$keyword['id'];
        $conn->query($update);
    
        //echo $prompt."<br><br>";
        echo $textBody."<br><br>";
        echo "<a href='".$keyword['url'].".html' target='blank'>localhost/autoblog/".$keyword['url'].".html</a><br><br>";
    
        $prompt="Scrivi una meta description per questo testo non più lunga di 100 caratteri. Nella tua risposta inserisci solo il contenuto della meta description e niente altro: ".addslashes($textBody);
        $metaDescription=aiRequest($prompt);
    
        echo $metaDescription;
        
        $update="UPDATE keywords SET meta_description = '".addslashes(trim($metaDescription))."' WHERE id=".$keyword['id'];
        $conn->query($update);
    
        $update="UPDATE keywords SET title = '".ucfirst($keyword['keyword'])."', url = '".str_replace(" ","-",$keyword['keyword'])."' WHERE id=".$keyword['id'];
        $conn->query($update);
    }
}

//TODO da rifattorizzare parametrizzando il numero di errori che vengono inseriti in ogni articolo
function addTypo($text){

    $targetTypoCharacter=strlen($text)/3;

    $characterToDuplicate="";
    while($characterToDuplicate==""){

        $random=rand(-20,20);
        $characterToDuplicate=substr($text, $targetTypoCharacter+$random, 1);
    }

    $text = substr_replace($text, $characterToDuplicate, $targetTypoCharacter+$random, 0);

    $targetTypoCharacter+=$targetTypoCharacter;
    $characterToDuplicate="";
    while($characterToDuplicate==""){

        $random=rand(-5,5);
        $characterToDuplicate=substr($text, $targetTypoCharacter+$random, 1);
    }

    $text = substr_replace($text, $characterToDuplicate, $targetTypoCharacter+$random, 0);

    return $text;
}

function aiRequest($prompt, $returnArray=false){

    $curl = curl_init();

    $prompt=addslashes($prompt);

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=AIzaSyAlOfOsvxP1PTfYdUxlp6J6IkWvt9PlaqA',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{"contents":[{"parts":[{"text":"'.$prompt.'"}]}]}',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    $response = json_decode(curl_exec($curl),true);

    curl_close($curl);

    if($returnArray)
        return $response;
    else
        return $response['candidates'][0]['content']['parts'][0]['text'];
}
?>