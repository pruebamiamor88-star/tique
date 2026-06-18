<?php
header('Content-Type: application/json; charset=utf-8');

// Listado de 27 aeropuertos obtenidos de producción
$airports = array(
    array(
        "id" => "403",
        "displayText" => "Bogotá (BOG)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>Bogotá</em> (BOG)",
        "type" => 0,
        "isActive" => true,
        "code" => "BOG",
        "country" => "CO"
    ),
    array(
        "id" => "414",
        "displayText" => "Medellín - José María Córdova (MDE)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>Medellín - José María Córdova</em> (MDE)",
        "type" => 0,
        "isActive" => true,
        "code" => "MDE",
        "country" => "CO"
    ),
    array(
        "id" => "405",
        "displayText" => "Cali (CLO)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>Cali</em> (CLO)",
        "type" => 0,
        "isActive" => true,
        "code" => "CLO",
        "country" => "CO"
    ),
    array(
        "id" => "406",
        "displayText" => "Cartagena (CTG)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>Cartagena</em> (CTG)",
        "type" => 0,
        "isActive" => true,
        "code" => "CTG",
        "country" => "CO"
    ),
    array(
        "id" => "402",
        "displayText" => "Barranquilla (BAQ)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>Barranquilla</em> (BAQ)",
        "type" => 0,
        "isActive" => true,
        "code" => "BAQ",
        "country" => "CO"
    ),
    array(
        "id" => "426",
        "displayText" => "Santa Marta (SMR)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>Santa Marta</em> (SMR)",
        "type" => 0,
        "isActive" => true,
        "code" => "SMR",
        "country" => "CO"
    ),
    array(
        "id" => "404",
        "displayText" => "Bucaramanga (BGA)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>Bucaramanga</em> (BGA)",
        "type" => 0,
        "isActive" => true,
        "code" => "BGA",
        "country" => "CO"
    ),
    array(
        "id" => "408",
        "displayText" => "Cúcuta (CUC)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>Cúcuta</em> (CUC)",
        "type" => 0,
        "isActive" => true,
        "code" => "CUC",
        "country" => "CO"
    ),
    array(
        "id" => "419",
        "displayText" => "Pereira (PEI)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>Pereira</em> (PEI)",
        "type" => 0,
        "isActive" => true,
        "code" => "PEI",
        "country" => "CO"
    ),
    array(
        "id" => "400",
        "displayText" => "Armenia (AXM)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>Armenia</em> (AXM)",
        "type" => 0,
        "isActive" => true,
        "code" => "AXM",
        "country" => "CO"
    ),
    array(
        "id" => "413",
        "displayText" => "Manizales (MZL)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>Manizales</em> (MZL)",
        "type" => 0,
        "isActive" => true,
        "code" => "MZL",
        "country" => "CO"
    ),
    array(
        "id" => "418",
        "displayText" => "Pasto (PSO)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>Pasto</em> (PSO)",
        "type" => 0,
        "isActive" => true,
        "code" => "PSO",
        "country" => "CO"
    ),
    array(
        "id" => "416",
        "displayText" => "Montería (MTR)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>Montería</em> (MTR)",
        "type" => 0,
        "isActive" => true,
        "code" => "MTR",
        "country" => "CO"
    ),
    array(
        "id" => "429",
        "displayText" => "Valledupar (VUP)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>Valledupar</em> (VUP)",
        "type" => 0,
        "isActive" => true,
        "code" => "VUP",
        "country" => "CO"
    ),
    array(
        "id" => "424",
        "displayText" => "San Andrés (ADZ)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>San Andrés</em> (ADZ)",
        "type" => 0,
        "isActive" => true,
        "code" => "ADZ",
        "country" => "CO"
    ),
    array(
        "id" => "410",
        "displayText" => "Ibagué (IBE)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>Ibagué</em> (IBE)",
        "type" => 0,
        "isActive" => true,
        "code" => "IBE",
        "country" => "CO"
    ),
    array(
        "id" => "417",
        "displayText" => "Neiva (NVA)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>Neiva</em> (NVA)",
        "type" => 0,
        "isActive" => true,
        "code" => "NVA",
        "country" => "CO"
    ),
    array(
        "id" => "430",
        "displayText" => "Villavicencio (VVC)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>Villavicencio</em> (VVC)",
        "type" => 0,
        "isActive" => true,
        "code" => "VVC",
        "country" => "CO"
    ),
    array(
        "id" => "431",
        "displayText" => "Yopal (EYP)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>Yopal</em> (EYP)",
        "type" => 0,
        "isActive" => true,
        "code" => "EYP",
        "country" => "CO"
    ),
    array(
        "id" => "420",
        "displayText" => "Popayán (PPN)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>Popayán</em> (PPN)",
        "type" => 0,
        "isActive" => true,
        "code" => "PPN",
        "country" => "CO"
    ),
    array(
        "id" => "422",
        "displayText" => "Quibdó (UIB)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>Quibdó</em> (UIB)",
        "type" => 0,
        "isActive" => true,
        "code" => "UIB",
        "country" => "CO"
    ),
    array(
        "id" => "423",
        "displayText" => "Riohacha (RCH)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>Riohacha</em> (RCH)",
        "type" => 0,
        "isActive" => true,
        "code" => "RCH",
        "country" => "CO"
    ),
    array(
        "id" => "415",
        "displayText" => "Medellín - Olaya Herrera (EOH)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>Medellín - Olaya Herrera</em> (EOH)",
        "type" => 0,
        "isActive" => true,
        "code" => "EOH",
        "country" => "CO"
    ),
    array(
        "id" => "421",
        "displayText" => "Puerto Asís (PUU)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>Puerto Asís</em> (PUU)",
        "type" => 0,
        "isActive" => true,
        "code" => "PUU",
        "country" => "CO"
    ),
    array(
        "id" => "425",
        "displayText" => "San José del Guaviare (SJE)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>San José del Guaviare</em> (SJE)",
        "type" => 0,
        "isActive" => true,
        "code" => "SJE",
        "country" => "CO"
    ),
    array(
        "id" => "427",
        "displayText" => "Tame (TME)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>Tame</em> (TME)",
        "type" => 0,
        "isActive" => true,
        "code" => "TME",
        "country" => "CO"
    ),
    array(
        "id" => "428",
        "displayText" => "Tumaco (TCO)",
        "displayDestinationHtml" => "Colombia",
        "displayHtml" => "<em>Tumaco</em> (TCO)",
        "type" => 0,
        "isActive" => true,
        "code" => "TCO",
        "country" => "CO"
    )
);

// Función para normalizar textos para búsquedas (quitar acentos y a minúsculas)
function normalizarText($str) {
    $str = mb_strtolower($str, 'UTF-8');
    $str = str_replace(
        array('á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'),
        array('a', 'e', 'i', 'o', 'u', 'u', 'n'),
        $str
    );
    return $str;
}

// Obtener datos del cuerpo del POST
$input = json_decode(file_get_contents('php://input'), true);
$query = isset($input['query']) ? trim($input['query']) : '';

if ($query === '') {
    echo json_encode(array('error' => 'No se recibió una consulta válida'));
    exit;
}

$normalizedQuery = normalizarText($query);
$matches = array();

foreach ($airports as $airport) {
    $normText = normalizarText($airport['displayText']);
    $normCode = normalizarText($airport['code']);
    
    // Buscar coincidencia en el código IATA o en el nombre visible
    if (strpos($normCode, $normalizedQuery) !== false || strpos($normText, $normalizedQuery) !== false) {
        $matches[] = $airport;
    }
}

echo json_encode($matches, JSON_UNESCAPED_UNICODE);
?>
