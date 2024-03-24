<?php
// Feed_reader By McRobert
// Funzione per ottenere la data in base alla lingua del browser
function getLocalizedDate($date) {
    // Ottieni la lingua preferita del browser
    $language = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en';

    // Mappa delle lingue supportate per la data
    $locales = [
        'it' => 'it_IT',
        'en' => 'en_US',
        'de' => 'de_DE'
    ];

    // Imposta la lingua locale
    setlocale(LC_TIME, $locales[$language] ?? 'en_US');
    
    // Abilita la segnalazione di tutti gli errori tranne E_DEPRECATED per la data, non ho trovato altro modo...
    error_reporting(E_ALL & ~E_DEPRECATED);
    // Formatta e restituisce la data
    return strftime('%d %b %Y', strtotime($date));
    // Riabilita la segnalazione di tutti gli errori
    error_reporting(E_ALL);
}

    // Funzione per leggere il feed RSS o ATOM
    function readFeed($url, $limit, $chars, $includeDate, $includeImages, $includeIcon, $includeCreator) {
    // Recupera il contenuto del feed
    $feed = file_get_contents($url);
    
    if ($feed === false) {
        
        echo "<div class='alert alert-danger' role='alert'><i class='bi bi-bug' style='font-size: 1rem;'></i> <b>Error: Unable to read feed from $url</b></div>\n";
        return [];
    }

    // Analizza il feed
    $xml = simplexml_load_string($feed);

    if ($xml === false) {
        echo "<div class='alert alert-danger' role='alert'><i class='bi bi-bug' style='font-size: 1rem;'></i><b>Error: Unable to parse feed from $url</b></div>\n";
        return [];
    }

    // Inizializza un array per le notizie
    $news = [];

    // Contatore per limitare il numero di notizie
    $count = 0;

    // Icona del sito
    $siteIcon = '';

    // Scorre gli elementi <entry> o <item> nel feed
    foreach ($xml->entry ?? $xml->channel->item as $item) {
        // Controllo del limite di notizie
        if ($count >= $limit) break;

        // Titolo della notizia
        $title = (string) $item->title;

        // Link della notizia
        $link = (string) $item->link;

        // Contenuto della notizia
        $description = (string) $item->description;

        // Rimuovi "Read more" o "Continua a leggere" dalla descrizione
        $description = preg_replace('/Read More|Continua a leggere|Continua...| […] |…|Read More.../i', '', $description);

        // Data della notizia
        $pubDate = (string) $item->pubDate;

        // Formatta la data
        $formattedDate = $includeDate ? getLocalizedDate($pubDate) : '';

        // Immagini della notizia
        $images = [];
        if ($includeImages) {
            // Controlla se ci sono immagini nel contenuto
            $content = $item->content ?? null;
            if ($content) {
                preg_match_all('/<img[^>]+>/i', $content, $matches);
                foreach ($matches[0] as $imgTag) {
                    preg_match('/src="([^"]+)/i', $imgTag, $src);
                    if (isset($src[1])) {
                        $images[] = $src[1];
                    }
                }
            }
            // Controlla se ci sono immagini negli elementi <enclosure>
            foreach ($item->enclosure ?? [] as $enclosure) {
                if ((string)$enclosure->attributes()->type == 'image/jpeg') {
                    $images[] = (string)$enclosure->attributes()->url;
                }
            }
            // Controlla se ci sono immagini negli elementi <media:content>
            foreach ($item->children('media', true)->content ?? [] as $content) {
                if ((string)$content->attributes()->type == 'image/jpg') {
                    $images[] = (string)$content->attributes()->url;
                }
            }
            // Controlla se ci sono immagini negli elementi <media:thumbnail>
            foreach ($item->children('media', true)->thumbnail ?? [] as $thumbnail) {
                $images[] = (string)$thumbnail->attributes()->url;
            }
            // Controlla se ci sono immagini nel contenuto <content:encoded>
            if ($item->children('content', true)->encoded) {
                preg_match_all('/<img[^>]+>/i', $item->children('content', true)->encoded, $matches);
                foreach ($matches[0] as $imgTag) {
                    preg_match('/src="([^"]+)/i', $imgTag, $src);
                    if (isset($src[1])) {
                        $images[] = $src[1];
                    }
                }
            }
            // Controlla se ci sono immagini nel contenuto <description>
            if ($item->description) {
                preg_match_all('/<img[^>]+>/i', $item->description, $matches);
                foreach ($matches[0] as $imgTag) {
                    preg_match('/src="([^"]+)/i', $imgTag, $src);
                    if (isset($src[1])) {
                        $images[] = $src[1];
                    }
                }
            }
        }

        // Controllo delle immagini duplicate
        if ($includeImages && count($images) > 1) {
            $images = array_unique($images);
        }

        // Creatore della notizia
        $creator = '';
        if ($includeCreator && isset($item->creator)) {
            $creator = (string) $item->creator;
        } elseif ($includeCreator && isset($item->children('dc', true)->creator)) {
            $creator = (string) $item->children('dc', true)->creator;
        }

        // Costruisci l'array della notizia
        $news[] = [
            'title' => $title,
            'link' => $link,
            'description' => mb_strimwidth(strip_tags($description), 0, $chars, '...'),
            'pubDate' => $formattedDate,
            'images' => $images,
            'creator' => $creator
        ];

        // Incrementa il contatore
        $count++;
    }

    // Icona del sito
    if ($includeIcon) {
        $siteIcon = (string) ($xml->channel->image->url ?? $xml->channel->icon ?? '');
    }

    // Restituisci l'array di notizie
    return [
        'news' => $news,
        'siteIcon' => $siteIcon
    ];
}
