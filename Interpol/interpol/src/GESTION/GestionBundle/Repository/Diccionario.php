<?php

namespace GESTION\GestionBundle\Repository;

/**
 * Description of Interpol
 *
 * @author pfa27667140
 */
class Diccionario
{
    private $Paises = array(
        '100' => array('ID' => '100', 'COD_A' => 'AD', 'COD_B' => 'AND', 'DESCRIPCION' => 'Andorra'),
        '101' => array('ID' => '101', 'COD_A' => 'AE', 'COD_B' => 'ARE', 'DESCRIPCION' => 'Emiratos Árabes Unidos'),
        '102' => array('ID' => '102', 'COD_A' => 'AF', 'COD_B' => 'AFG', 'DESCRIPCION' => 'Afganistán'),
        '103' => array('ID' => '103', 'COD_A' => 'AG', 'COD_B' => 'ATG', 'DESCRIPCION' => 'Antigua y Barbuda'),
        '104' => array('ID' => '104', 'COD_A' => 'AI', 'COD_B' => 'AIA', 'DESCRIPCION' => 'Anguila'),
        '105' => array('ID' => '105', 'COD_A' => 'AL', 'COD_B' => 'ALB', 'DESCRIPCION' => 'Albania'),
        '106' => array('ID' => '106', 'COD_A' => 'AM', 'COD_B' => 'ARM', 'DESCRIPCION' => 'Armenia'),
        '108' => array('ID' => '108', 'COD_A' => 'AO', 'COD_B' => 'AGO', 'DESCRIPCION' => 'Angola'),
        '109' => array('ID' => '109', 'COD_A' => 'AQ', 'COD_B' => 'ATA', 'DESCRIPCION' => 'Antártida'),
        '110' => array('ID' => '110', 'COD_A' => 'AR', 'COD_B' => 'ARG', 'DESCRIPCION' => 'Argentina'),
        '111' => array('ID' => '111', 'COD_A' => 'AS', 'COD_B' => 'ASM', 'DESCRIPCION' => 'Samoa Americana'),
        '112' => array('ID' => '112', 'COD_A' => 'AT', 'COD_B' => 'AUT', 'DESCRIPCION' => 'Austria'),
        '113' => array('ID' => '113', 'COD_A' => 'AU', 'COD_B' => 'AUS', 'DESCRIPCION' => 'Australia'),
        '114' => array('ID' => '114', 'COD_A' => 'AW', 'COD_B' => 'ABW', 'DESCRIPCION' => 'Aruba'),
        '115' => array('ID' => '115', 'COD_A' => 'AX', 'COD_B' => 'ALA', 'DESCRIPCION' => 'Åland'),
        '116' => array('ID' => '116', 'COD_A' => 'AZ', 'COD_B' => 'AZE', 'DESCRIPCION' => 'Azerbaiyán'),
        '117' => array('ID' => '117', 'COD_A' => 'BA', 'COD_B' => 'BIH', 'DESCRIPCION' => 'Bosnia y Herzegovina'),
        '118' => array('ID' => '118', 'COD_A' => 'BB', 'COD_B' => 'BRB', 'DESCRIPCION' => 'Barbados'),
        '119' => array('ID' => '119', 'COD_A' => 'BD', 'COD_B' => 'BGD', 'DESCRIPCION' => 'Bangladés'),
        '120' => array('ID' => '120', 'COD_A' => 'BE', 'COD_B' => 'BEL', 'DESCRIPCION' => 'Bélgica'),
        '121' => array('ID' => '121', 'COD_A' => 'BF', 'COD_B' => 'BFA', 'DESCRIPCION' => 'Burkina Faso'),
        '122' => array('ID' => '122', 'COD_A' => 'BG', 'COD_B' => 'BGR', 'DESCRIPCION' => 'Bulgaria'),
        '123' => array('ID' => '123', 'COD_A' => 'BH', 'COD_B' => 'BHR', 'DESCRIPCION' => 'Baréin'),
        '124' => array('ID' => '124', 'COD_A' => 'BI', 'COD_B' => 'BDI', 'DESCRIPCION' => 'Burundi'),
        '125' => array('ID' => '125', 'COD_A' => 'BJ', 'COD_B' => 'BEN', 'DESCRIPCION' => 'Benín'),
        '126' => array('ID' => '126', 'COD_A' => 'BM', 'COD_B' => 'BMU', 'DESCRIPCION' => 'Bermudas'),
        '127' => array('ID' => '127', 'COD_A' => 'BN', 'COD_B' => 'BRN', 'DESCRIPCION' => 'Brunéi'),
        '128' => array('ID' => '128', 'COD_A' => 'BO', 'COD_B' => 'BOL', 'DESCRIPCION' => 'Bolivia'),
        '129' => array('ID' => '129', 'COD_A' => 'BR', 'COD_B' => 'BRA', 'DESCRIPCION' => 'Brasil'),
        '130' => array('ID' => '130', 'COD_A' => 'BS', 'COD_B' => 'BHS', 'DESCRIPCION' => 'Bahamas'),
        '131' => array('ID' => '131', 'COD_A' => 'BT', 'COD_B' => 'BTN', 'DESCRIPCION' => 'Bután'),
        '132' => array('ID' => '132', 'COD_A' => 'BV', 'COD_B' => 'BVT', 'DESCRIPCION' => 'Isla Bouvet'),
        '133' => array('ID' => '133', 'COD_A' => 'BW', 'COD_B' => 'BWA', 'DESCRIPCION' => 'Botsuana'),
        '134' => array('ID' => '134', 'COD_A' => 'BY', 'COD_B' => 'BLR', 'DESCRIPCION' => 'Bielorrusia'),
        '135' => array('ID' => '135', 'COD_A' => 'BZ', 'COD_B' => 'BLZ', 'DESCRIPCION' => 'Belice'),
        '136' => array('ID' => '136', 'COD_A' => 'CA', 'COD_B' => 'CAN', 'DESCRIPCION' => 'Canadá'),
        '137' => array('ID' => '137', 'COD_A' => 'CC', 'COD_B' => 'CCK', 'DESCRIPCION' => 'Islas Cocos'),
        '138' => array('ID' => '138', 'COD_A' => 'CD', 'COD_B' => 'COD', 'DESCRIPCION' => 'República Democrática del Congo'),
        '139' => array('ID' => '139', 'COD_A' => 'CF', 'COD_B' => 'CAF', 'DESCRIPCION' => 'República Centroafricana'),
        '140' => array('ID' => '140', 'COD_A' => 'CG', 'COD_B' => 'COG', 'DESCRIPCION' => 'República del Congo'),
        '141' => array('ID' => '141', 'COD_A' => 'CH', 'COD_B' => 'CHE', 'DESCRIPCION' => 'Suiza'),
        '142' => array('ID' => '142', 'COD_A' => 'CI', 'COD_B' => 'CIV', 'DESCRIPCION' => 'Costa de Marfil'),
        '143' => array('ID' => '143', 'COD_A' => 'CK', 'COD_B' => 'COK', 'DESCRIPCION' => 'Islas Cook'),
        '144' => array('ID' => '144', 'COD_A' => 'CL', 'COD_B' => 'CHL', 'DESCRIPCION' => 'Chile'),
        '145' => array('ID' => '145', 'COD_A' => 'CM', 'COD_B' => 'CMR', 'DESCRIPCION' => 'Camerún'),
        '146' => array('ID' => '146', 'COD_A' => 'CN', 'COD_B' => 'CHN', 'DESCRIPCION' => 'República Popular China'),
        '147' => array('ID' => '147', 'COD_A' => 'CO', 'COD_B' => 'COL', 'DESCRIPCION' => 'Colombia'),
        '148' => array('ID' => '148', 'COD_A' => 'CR', 'COD_B' => 'CRI', 'DESCRIPCION' => 'Costa Rica'),
        '150' => array('ID' => '150', 'COD_A' => 'CU', 'COD_B' => 'CUB', 'DESCRIPCION' => 'Cuba'),
        '151' => array('ID' => '151', 'COD_A' => 'CV', 'COD_B' => 'CPV', 'DESCRIPCION' => 'Cabo Verde'),
        '342' => array('ID' => '342', 'COD_A' => 'CW', 'COD_B' => 'CUW', 'DESCRIPCION' => 'Curazao'),
        '152' => array('ID' => '152', 'COD_A' => 'CX', 'COD_B' => 'CXR', 'DESCRIPCION' => 'Isla de Navidad'),
        '153' => array('ID' => '153', 'COD_A' => 'CY', 'COD_B' => 'CYP', 'DESCRIPCION' => 'Chipre'),
        '154' => array('ID' => '154', 'COD_A' => 'CZ', 'COD_B' => 'CZE', 'DESCRIPCION' => 'República Checa'),
        '155' => array('ID' => '155', 'COD_A' => 'D', 'COD_B' => 'DE', 'DESCRIPCION' => 'Alemania'),
        '156' => array('ID' => '156', 'COD_A' => 'DJ', 'COD_B' => 'DJI', 'DESCRIPCION' => 'Yibuti'),
        '157' => array('ID' => '157', 'COD_A' => 'DK', 'COD_B' => 'DNK', 'DESCRIPCION' => 'Dinamarca'),
        '158' => array('ID' => '158', 'COD_A' => 'DM', 'COD_B' => 'DMA', 'DESCRIPCION' => 'Dominica'),
        '159' => array('ID' => '159', 'COD_A' => 'DO', 'COD_B' => 'DOM', 'DESCRIPCION' => 'República Dominicana'),
        '160' => array('ID' => '160', 'COD_A' => 'DZ', 'COD_B' => 'DZA', 'DESCRIPCION' => 'Argelia'),
        '161' => array('ID' => '161', 'COD_A' => 'EC', 'COD_B' => 'ECU', 'DESCRIPCION' => 'Ecuador'),
        '162' => array('ID' => '162', 'COD_A' => 'EE', 'COD_B' => 'EST', 'DESCRIPCION' => 'Estonia'),
        '163' => array('ID' => '163', 'COD_A' => 'EG', 'COD_B' => 'EGY', 'DESCRIPCION' => 'Egipto'),
        '164' => array('ID' => '164', 'COD_A' => 'EH', 'COD_B' => 'ESH', 'DESCRIPCION' => 'Sahara Occidental'),
        '165' => array('ID' => '165', 'COD_A' => 'ER', 'COD_B' => 'ERI', 'DESCRIPCION' => 'Eritrea'),
        '166' => array('ID' => '166', 'COD_A' => 'ES', 'COD_B' => 'ESP', 'DESCRIPCION' => 'España'),
        '167' => array('ID' => '167', 'COD_A' => 'ET', 'COD_B' => 'ETH', 'DESCRIPCION' => 'Etiopía'),
        '168' => array('ID' => '168', 'COD_A' => 'FI', 'COD_B' => 'FIN', 'DESCRIPCION' => 'Finlandia'),
        '169' => array('ID' => '169', 'COD_A' => 'FJ', 'COD_B' => 'FJI', 'DESCRIPCION' => 'Fiyi'),
        '170' => array('ID' => '170', 'COD_A' => 'FK', 'COD_B' => 'FLK', 'DESCRIPCION' => 'Islas Malvinas'),
        '171' => array('ID' => '171', 'COD_A' => 'FM', 'COD_B' => 'FSM', 'DESCRIPCION' => 'Micronesia'),
        '172' => array('ID' => '172', 'COD_A' => 'FO', 'COD_B' => 'FRO', 'DESCRIPCION' => 'Islas Feroe'),
        '173' => array('ID' => '173', 'COD_A' => 'FR', 'COD_B' => 'FRA', 'DESCRIPCION' => 'Francia'),
        '174' => array('ID' => '174', 'COD_A' => 'GA', 'COD_B' => 'GAB', 'DESCRIPCION' => 'Gabón'),
        '175' => array('ID' => '175', 'COD_A' => 'GB', 'COD_B' => 'GBR', 'DESCRIPCION' => 'Reino Unido'),
        '176' => array('ID' => '176', 'COD_A' => 'GD', 'COD_B' => 'GRD', 'DESCRIPCION' => 'Granada'),
        '177' => array('ID' => '177', 'COD_A' => 'GE', 'COD_B' => 'GEO', 'DESCRIPCION' => 'Georgia'),
        '178' => array('ID' => '178', 'COD_A' => 'GF', 'COD_B' => 'GUF', 'DESCRIPCION' => 'Guayana Francesa'),
        '179' => array('ID' => '179', 'COD_A' => 'GH', 'COD_B' => 'GHA', 'DESCRIPCION' => 'Ghana'),
        '180' => array('ID' => '180', 'COD_A' => 'GI', 'COD_B' => 'GIB', 'DESCRIPCION' => 'Gibraltar'),
        '181' => array('ID' => '181', 'COD_A' => 'GL', 'COD_B' => 'GRL', 'DESCRIPCION' => 'Groenlandia'),
        '182' => array('ID' => '182', 'COD_A' => 'GM', 'COD_B' => 'GMB', 'DESCRIPCION' => 'Gambia'),
        '183' => array('ID' => '183', 'COD_A' => 'GN', 'COD_B' => 'GNB', 'DESCRIPCION' => 'Guinea'),
        '184' => array('ID' => '184', 'COD_A' => 'GP', 'COD_B' => 'GLP', 'DESCRIPCION' => 'Guadalupe'),
        '185' => array('ID' => '185', 'COD_A' => 'GQ', 'COD_B' => 'GNQ', 'DESCRIPCION' => 'Guinea Ecuatorial'),
        '186' => array('ID' => '186', 'COD_A' => 'GR', 'COD_B' => 'GRC', 'DESCRIPCION' => 'Grecia'),
        '187' => array('ID' => '187', 'COD_A' => 'GS', 'COD_B' => 'SGS', 'DESCRIPCION' => 'Islas Georgias del Sur y Sandwich del Sur'),
        '188' => array('ID' => '188', 'COD_A' => 'GT', 'COD_B' => 'GTM', 'DESCRIPCION' => 'Guatemala'),
        '189' => array('ID' => '189', 'COD_A' => 'GU', 'COD_B' => 'GUM', 'DESCRIPCION' => 'Guam'),
        '190' => array('ID' => '190', 'COD_A' => 'GW', 'COD_B' => 'GIN', 'DESCRIPCION' => 'Guinea-Bisáu'),
        '191' => array('ID' => '191', 'COD_A' => 'GY', 'COD_B' => 'GUY', 'DESCRIPCION' => 'Guyana'),
        '192' => array('ID' => '192', 'COD_A' => 'HK', 'COD_B' => 'HKG', 'DESCRIPCION' => 'Hong Kong'),
        '193' => array('ID' => '193', 'COD_A' => 'HM', 'COD_B' => 'HMD', 'DESCRIPCION' => 'Islas Heard y McDonald'),
        '194' => array('ID' => '194', 'COD_A' => 'HN', 'COD_B' => 'HND', 'DESCRIPCION' => 'Honduras'),
        '195' => array('ID' => '195', 'COD_A' => 'HR', 'COD_B' => 'HRV', 'DESCRIPCION' => 'Croacia'),
        '196' => array('ID' => '196', 'COD_A' => 'HT', 'COD_B' => 'HTI', 'DESCRIPCION' => 'Haití'),
        '197' => array('ID' => '197', 'COD_A' => 'HU', 'COD_B' => 'HUN', 'DESCRIPCION' => 'Hungría'),
        '198' => array('ID' => '198', 'COD_A' => 'ID', 'COD_B' => 'IDN', 'DESCRIPCION' => 'Indonesia'),
        '199' => array('ID' => '199', 'COD_A' => 'IE', 'COD_B' => 'IRL', 'DESCRIPCION' => 'Irlanda'),
        '200' => array('ID' => '200', 'COD_A' => 'IL', 'COD_B' => 'ISR', 'DESCRIPCION' => 'Israel'),
        '201' => array('ID' => '201', 'COD_A' => 'IN', 'COD_B' => 'IND', 'DESCRIPCION' => 'India'),
        '202' => array('ID' => '202', 'COD_A' => 'IO', 'COD_B' => 'IOT', 'DESCRIPCION' => 'Territorio Británico del Océano Índico'),
        '203' => array('ID' => '203', 'COD_A' => 'IQ', 'COD_B' => 'IRQ', 'DESCRIPCION' => 'Irak'),
        '204' => array('ID' => '204', 'COD_A' => 'IR', 'COD_B' => 'IRN', 'DESCRIPCION' => 'Irán'),
        '205' => array('ID' => '205', 'COD_A' => 'IS', 'COD_B' => 'ISL', 'DESCRIPCION' => 'Islandia'),
        '206' => array('ID' => '206', 'COD_A' => 'IT', 'COD_B' => 'ITA', 'DESCRIPCION' => 'Italia'),
        '207' => array('ID' => '207', 'COD_A' => 'JM', 'COD_B' => 'JAM', 'DESCRIPCION' => 'Jamaica'),
        '208' => array('ID' => '208', 'COD_A' => 'JO', 'COD_B' => 'JOR', 'DESCRIPCION' => 'Jordania'),
        '209' => array('ID' => '209', 'COD_A' => 'JP', 'COD_B' => 'JPN', 'DESCRIPCION' => 'Japón'),
        '210' => array('ID' => '210', 'COD_A' => 'KE', 'COD_B' => 'KEN', 'DESCRIPCION' => 'Kenia'),
        '211' => array('ID' => '211', 'COD_A' => 'KG', 'COD_B' => 'KGZ', 'DESCRIPCION' => 'Kirguistán'),
        '212' => array('ID' => '212', 'COD_A' => 'KH', 'COD_B' => 'KHM', 'DESCRIPCION' => 'Camboya'),
        '213' => array('ID' => '213', 'COD_A' => 'KI', 'COD_B' => 'KIR', 'DESCRIPCION' => 'Kiribati'),
        '214' => array('ID' => '214', 'COD_A' => 'KM', 'COD_B' => 'COM', 'DESCRIPCION' => 'Comoras'),
        '215' => array('ID' => '215', 'COD_A' => 'KN', 'COD_B' => 'KNA', 'DESCRIPCION' => 'San Cristóbal y Nieves'),
        '216' => array('ID' => '216', 'COD_A' => 'KR', 'COD_B' => 'PRK', 'DESCRIPCION' => 'Corea del Sur'),
        '217' => array('ID' => '217', 'COD_A' => 'KP', 'COD_B' => 'KOR', 'DESCRIPCION' => 'Corea del Norte'),
        '218' => array('ID' => '218', 'COD_A' => 'KW', 'COD_B' => 'KWT', 'DESCRIPCION' => 'Kuwait'),
        '219' => array('ID' => '219', 'COD_A' => 'KY', 'COD_B' => 'CYM', 'DESCRIPCION' => 'Islas Caimán'),
        '220' => array('ID' => '220', 'COD_A' => 'KZ', 'COD_B' => 'KAZ', 'DESCRIPCION' => 'Kazajistán'),
        '221' => array('ID' => '221', 'COD_A' => 'LA', 'COD_B' => 'LAO', 'DESCRIPCION' => 'Laos'),
        '222' => array('ID' => '222', 'COD_A' => 'LB', 'COD_B' => 'LBN', 'DESCRIPCION' => 'Líbano'),
        '223' => array('ID' => '223', 'COD_A' => 'LC', 'COD_B' => 'LCA', 'DESCRIPCION' => 'Santa Lucía'),
        '224' => array('ID' => '224', 'COD_A' => 'LI', 'COD_B' => 'LIE', 'DESCRIPCION' => 'Liechtenstein'),
        '225' => array('ID' => '225', 'COD_A' => 'LK', 'COD_B' => 'LKA', 'DESCRIPCION' => 'Sri Lanka'),
        '226' => array('ID' => '226', 'COD_A' => 'LR', 'COD_B' => 'LBR', 'DESCRIPCION' => 'Liberia'),
        '227' => array('ID' => '227', 'COD_A' => 'LS', 'COD_B' => 'LSO', 'DESCRIPCION' => 'Lesoto'),
        '228' => array('ID' => '228', 'COD_A' => 'LT', 'COD_B' => 'LTU', 'DESCRIPCION' => 'Lituania'),
        '229' => array('ID' => '229', 'COD_A' => 'LU', 'COD_B' => 'LUX', 'DESCRIPCION' => 'Luxemburgo'),
        '230' => array('ID' => '230', 'COD_A' => 'LV', 'COD_B' => 'LVA', 'DESCRIPCION' => 'Letonia'),
        '231' => array('ID' => '231', 'COD_A' => 'LY', 'COD_B' => 'LBY', 'DESCRIPCION' => 'Libia'),
        '232' => array('ID' => '232', 'COD_A' => 'MA', 'COD_B' => 'MAR', 'DESCRIPCION' => 'Marruecos'),
        '233' => array('ID' => '233', 'COD_A' => 'MC', 'COD_B' => 'MCO', 'DESCRIPCION' => 'Mónaco'),
        '234' => array('ID' => '234', 'COD_A' => 'MD', 'COD_B' => 'MDA', 'DESCRIPCION' => 'Moldavia'),
        '235' => array('ID' => '235', 'COD_A' => 'ME', 'COD_B' => 'MNE', 'DESCRIPCION' => 'Montenegro'),
        '343' => array('ID' => '343', 'COD_A' => 'MF', 'COD_B' => 'MAF', 'DESCRIPCION' => 'San Martín'),
        '236' => array('ID' => '236', 'COD_A' => 'MG', 'COD_B' => 'MDG', 'DESCRIPCION' => 'Madagascar'),
        '237' => array('ID' => '237', 'COD_A' => 'MH', 'COD_B' => 'MHL', 'DESCRIPCION' => 'Islas Marshall'),
        '238' => array('ID' => '238', 'COD_A' => 'MK', 'COD_B' => 'MKD', 'DESCRIPCION' => 'Macedonia'),
        '239' => array('ID' => '239', 'COD_A' => 'ML', 'COD_B' => 'MLI', 'DESCRIPCION' => 'Malí'),
        '240' => array('ID' => '240', 'COD_A' => 'MM', 'COD_B' => 'MMR', 'DESCRIPCION' => 'Myanmar'),
        '241' => array('ID' => '241', 'COD_A' => 'MN', 'COD_B' => 'MNG', 'DESCRIPCION' => 'Mongolia'),
        '242' => array('ID' => '242', 'COD_A' => 'MO', 'COD_B' => 'MAC', 'DESCRIPCION' => 'Macao'),
        '243' => array('ID' => '243', 'COD_A' => 'MP', 'COD_B' => 'MNP', 'DESCRIPCION' => 'Islas Marianas del Norte'),
        '244' => array('ID' => '244', 'COD_A' => 'MQ', 'COD_B' => 'MTQ', 'DESCRIPCION' => 'Martinica'),
        '245' => array('ID' => '245', 'COD_A' => 'MR', 'COD_B' => 'MRT', 'DESCRIPCION' => 'Mauritania'),
        '246' => array('ID' => '246', 'COD_A' => 'MS', 'COD_B' => 'MSR', 'DESCRIPCION' => 'Montserrat'),
        '247' => array('ID' => '247', 'COD_A' => 'MT', 'COD_B' => 'MLT', 'DESCRIPCION' => 'Malta'),
        '248' => array('ID' => '248', 'COD_A' => 'MU', 'COD_B' => 'MUS', 'DESCRIPCION' => 'Mauricio'),
        '249' => array('ID' => '249', 'COD_A' => 'MV', 'COD_B' => 'MDV', 'DESCRIPCION' => 'Maldivas'),
        '250' => array('ID' => '250', 'COD_A' => 'MW', 'COD_B' => 'MWI', 'DESCRIPCION' => 'Malaui'),
        '251' => array('ID' => '251', 'COD_A' => 'MX', 'COD_B' => 'MEX', 'DESCRIPCION' => 'México'),
        '252' => array('ID' => '252', 'COD_A' => 'MY', 'COD_B' => 'MYS', 'DESCRIPCION' => 'Malasia'),
        '253' => array('ID' => '253', 'COD_A' => 'MZ', 'COD_B' => 'MOZ', 'DESCRIPCION' => 'Mozambique'),
        '254' => array('ID' => '254', 'COD_A' => 'NA', 'COD_B' => 'NAM', 'DESCRIPCION' => 'Namibia'),
        '255' => array('ID' => '255', 'COD_A' => 'NC', 'COD_B' => 'NCL', 'DESCRIPCION' => 'Nueva Caledonia'),
        '256' => array('ID' => '256', 'COD_A' => 'NE', 'COD_B' => 'NER', 'DESCRIPCION' => 'Níger'),
        '257' => array('ID' => '257', 'COD_A' => 'NF', 'COD_B' => 'NFK', 'DESCRIPCION' => 'Isla Norfolk'),
        '258' => array('ID' => '258', 'COD_A' => 'NG', 'COD_B' => 'NGA', 'DESCRIPCION' => 'Nigeria'),
        '259' => array('ID' => '259', 'COD_A' => 'NI', 'COD_B' => 'NIC', 'DESCRIPCION' => 'Nicaragua'),
        '260' => array('ID' => '260', 'COD_A' => 'NL', 'COD_B' => 'NLD', 'DESCRIPCION' => 'Países Bajos'),
        '261' => array('ID' => '261', 'COD_A' => 'NO', 'COD_B' => 'NOR', 'DESCRIPCION' => 'Noruega'),
        '262' => array('ID' => '262', 'COD_A' => 'NP', 'COD_B' => 'NPL', 'DESCRIPCION' => 'Nepal'),
        '263' => array('ID' => '263', 'COD_A' => 'NR', 'COD_B' => 'NRU', 'DESCRIPCION' => 'Nauru'),
        '264' => array('ID' => '264', 'COD_A' => 'NU', 'COD_B' => 'NIU', 'DESCRIPCION' => 'Niue'),
        '265' => array('ID' => '265', 'COD_A' => 'NZ', 'COD_B' => 'NZL', 'DESCRIPCION' => 'Nueva Zelanda'),
        '266' => array('ID' => '266', 'COD_A' => 'OM', 'COD_B' => 'OMN', 'DESCRIPCION' => 'Omán'),
        '267' => array('ID' => '267', 'COD_A' => 'PA', 'COD_B' => 'PAN', 'DESCRIPCION' => 'Panamá'),
        '268' => array('ID' => '268', 'COD_A' => 'PE', 'COD_B' => 'PER', 'DESCRIPCION' => 'Perú'),
        '269' => array('ID' => '269', 'COD_A' => 'PF', 'COD_B' => 'PYF', 'DESCRIPCION' => 'Polinesia Francesa'),
        '270' => array('ID' => '270', 'COD_A' => 'PG', 'COD_B' => 'PNG', 'DESCRIPCION' => 'Papúa Nueva Guinea'),
        '271' => array('ID' => '271', 'COD_A' => 'PH', 'COD_B' => 'PHL', 'DESCRIPCION' => 'Filipinas'),
        '272' => array('ID' => '272', 'COD_A' => 'PK', 'COD_B' => 'PAK', 'DESCRIPCION' => 'Pakistán'),
        '273' => array('ID' => '273', 'COD_A' => 'PL', 'COD_B' => 'POL', 'DESCRIPCION' => 'Polonia'),
        '274' => array('ID' => '274', 'COD_A' => 'PM', 'COD_B' => 'SPM', 'DESCRIPCION' => 'San Pedro y Miquelón'),
        '275' => array('ID' => '275', 'COD_A' => 'PN', 'COD_B' => 'PCN', 'DESCRIPCION' => 'Pitcairn'),
        '276' => array('ID' => '276', 'COD_A' => 'PR', 'COD_B' => 'PRI', 'DESCRIPCION' => 'Puerto Rico'),
        '277' => array('ID' => '277', 'COD_A' => 'PS', 'COD_B' => 'PSE', 'DESCRIPCION' => 'Palestina'),
        '278' => array('ID' => '278', 'COD_A' => 'PT', 'COD_B' => 'PRT', 'DESCRIPCION' => 'Portugal'),
        '279' => array('ID' => '279', 'COD_A' => 'PW', 'COD_B' => 'PLW', 'DESCRIPCION' => 'Palaos'),
        '280' => array('ID' => '280', 'COD_A' => 'PY', 'COD_B' => 'PRY', 'DESCRIPCION' => 'Paraguay'),
        '281' => array('ID' => '281', 'COD_A' => 'QA', 'COD_B' => 'QAT', 'DESCRIPCION' => 'Catar'),
        '282' => array('ID' => '282', 'COD_A' => 'RE', 'COD_B' => 'REU', 'DESCRIPCION' => 'Reunión'),
        '283' => array('ID' => '283', 'COD_A' => 'RO', 'COD_B' => 'ROU', 'DESCRIPCION' => 'Rumania'),
        '149' => array('ID' => '149', 'COD_A' => 'RS', 'COD_B' => 'SRB', 'DESCRIPCION' => 'Serbia'),
        '284' => array('ID' => '284', 'COD_A' => 'RU', 'COD_B' => 'RUS', 'DESCRIPCION' => 'Rusia'),
        '285' => array('ID' => '285', 'COD_A' => 'RW', 'COD_B' => 'RWA', 'DESCRIPCION' => 'Ruanda'),
        '286' => array('ID' => '286', 'COD_A' => 'SA', 'COD_B' => 'SAU', 'DESCRIPCION' => 'Arabia Saudita'),
        '287' => array('ID' => '287', 'COD_A' => 'SB', 'COD_B' => 'SLB', 'DESCRIPCION' => 'Islas Salomón'),
        '288' => array('ID' => '288', 'COD_A' => 'SC', 'COD_B' => 'SYC', 'DESCRIPCION' => 'Seychelles'),
        '289' => array('ID' => '289', 'COD_A' => 'SD', 'COD_B' => 'SDN', 'DESCRIPCION' => 'Sudán'),
        '290' => array('ID' => '290', 'COD_A' => 'SE', 'COD_B' => 'SWE', 'DESCRIPCION' => 'Suecia'),
        '291' => array('ID' => '291', 'COD_A' => 'SG', 'COD_B' => 'SGP', 'DESCRIPCION' => 'Singapur'),
        '292' => array('ID' => '292', 'COD_A' => 'SH', 'COD_B' => 'SHN', 'DESCRIPCION' => 'Santa Elena, Ascensión y Tristán de Acuña'),
        '293' => array('ID' => '293', 'COD_A' => 'SI', 'COD_B' => 'SVN', 'DESCRIPCION' => 'Eslovenia'),
        '294' => array('ID' => '294', 'COD_A' => 'SJ', 'COD_B' => 'SJM', 'DESCRIPCION' => 'Svalbard y Jan Mayen'),
        '295' => array('ID' => '295', 'COD_A' => 'SK', 'COD_B' => 'SVK', 'DESCRIPCION' => 'Eslovaquia'),
        '296' => array('ID' => '296', 'COD_A' => 'SL', 'COD_B' => 'SLE', 'DESCRIPCION' => 'Sierra Leona'),
        '297' => array('ID' => '297', 'COD_A' => 'SM', 'COD_B' => 'SMR', 'DESCRIPCION' => 'San Marino'),
        '298' => array('ID' => '298', 'COD_A' => 'SN', 'COD_B' => 'SEN', 'DESCRIPCION' => 'Senegal'),
        '299' => array('ID' => '299', 'COD_A' => 'SO', 'COD_B' => 'SOM', 'DESCRIPCION' => 'Somalia'),
        '300' => array('ID' => '300', 'COD_A' => 'SR', 'COD_B' => 'SUR', 'DESCRIPCION' => 'Surinam'),
        '344' => array('ID' => '344', 'COD_A' => 'SS', 'COD_B' => 'SSD', 'DESCRIPCION' => 'Sudán del Sur'),
        '301' => array('ID' => '301', 'COD_A' => 'ST', 'COD_B' => 'STP', 'DESCRIPCION' => 'Santo Tomé y Príncipe'),
        '302' => array('ID' => '302', 'COD_A' => 'SV', 'COD_B' => 'SLV', 'DESCRIPCION' => 'El Salvador'),
        '303' => array('ID' => '303', 'COD_A' => 'SY', 'COD_B' => 'SYR', 'DESCRIPCION' => 'Siria'),
        '304' => array('ID' => '304', 'COD_A' => 'SZ', 'COD_B' => 'SWZ', 'DESCRIPCION' => 'Suazilandia'),
        '305' => array('ID' => '305', 'COD_A' => 'TC', 'COD_B' => 'TCA', 'DESCRIPCION' => 'Islas Turcas y Caicos'),
        '306' => array('ID' => '306', 'COD_A' => 'TD', 'COD_B' => 'TCD', 'DESCRIPCION' => 'Chad'),
        '308' => array('ID' => '308', 'COD_A' => 'TG', 'COD_B' => 'TGO', 'DESCRIPCION' => 'Togo'),
        '309' => array('ID' => '309', 'COD_A' => 'TH', 'COD_B' => 'THA', 'DESCRIPCION' => 'Tailandia'),
        '310' => array('ID' => '310', 'COD_A' => 'TJ', 'COD_B' => 'TJK', 'DESCRIPCION' => 'Tayikistán'),
        '311' => array('ID' => '311', 'COD_A' => 'TK', 'COD_B' => 'TKL', 'DESCRIPCION' => 'Tokelau'),
        '312' => array('ID' => '312', 'COD_A' => 'TL', 'COD_B' => 'TLS', 'DESCRIPCION' => 'Timor Oriental'),
        '313' => array('ID' => '313', 'COD_A' => 'TM', 'COD_B' => 'TKM', 'DESCRIPCION' => 'Turkmenistán'),
        '314' => array('ID' => '314', 'COD_A' => 'TN', 'COD_B' => 'TUN', 'DESCRIPCION' => 'Túnez'),
        '315' => array('ID' => '315', 'COD_A' => 'TO', 'COD_B' => 'TON', 'DESCRIPCION' => 'Tonga'),
        '316' => array('ID' => '316', 'COD_A' => 'TR', 'COD_B' => 'TUR', 'DESCRIPCION' => 'Turquía'),
        '317' => array('ID' => '317', 'COD_A' => 'TT', 'COD_B' => 'TTO', 'DESCRIPCION' => 'Trinidad y Tobago'),
        '318' => array('ID' => '318', 'COD_A' => 'TV', 'COD_B' => 'TUV', 'DESCRIPCION' => 'Tuvalu'),
        '319' => array('ID' => '319', 'COD_A' => 'TW', 'COD_B' => 'TWN', 'DESCRIPCION' => 'Taiwán'),
        '320' => array('ID' => '320', 'COD_A' => 'TZ', 'COD_B' => 'TZA', 'DESCRIPCION' => 'Tanzania'),
        '321' => array('ID' => '321', 'COD_A' => 'UA', 'COD_B' => 'UKR', 'DESCRIPCION' => 'Ucrania'),
        '322' => array('ID' => '322', 'COD_A' => 'UG', 'COD_B' => 'UGA', 'DESCRIPCION' => 'Uganda'),
        '323' => array('ID' => '323', 'COD_A' => 'UM', 'COD_B' => 'UMI', 'DESCRIPCION' => 'Islas Ultramarinas Menores de Estados Unidos'),
        '324' => array('ID' => '324', 'COD_A' => 'US', 'COD_B' => 'USA', 'DESCRIPCION' => 'Estados Unidos'),
        '325' => array('ID' => '325', 'COD_A' => 'UY', 'COD_B' => 'URY', 'DESCRIPCION' => 'Uruguay'),
        '326' => array('ID' => '326', 'COD_A' => 'UZ', 'COD_B' => 'UZB', 'DESCRIPCION' => 'Uzbekistán'),
        '327' => array('ID' => '327', 'COD_A' => 'VA', 'COD_B' => 'VAT', 'DESCRIPCION' => 'Vaticano'),
        '328' => array('ID' => '328', 'COD_A' => 'VC', 'COD_B' => 'VCT', 'DESCRIPCION' => 'San Vicente y las Granadinas'),
        '329' => array('ID' => '329', 'COD_A' => 'VE', 'COD_B' => 'VEN', 'DESCRIPCION' => 'Venezuela'),
        '330' => array('ID' => '330', 'COD_A' => 'VG', 'COD_B' => 'VGB', 'DESCRIPCION' => 'Islas Vírgenes Británicas'),
        '331' => array('ID' => '331', 'COD_A' => 'VI', 'COD_B' => 'VIR', 'DESCRIPCION' => 'Islas Vírgenes de los Estados Unidos'),
        '332' => array('ID' => '332', 'COD_A' => 'VN', 'COD_B' => 'VNM', 'DESCRIPCION' => 'Vietnam'),
        '333' => array('ID' => '333', 'COD_A' => 'VU', 'COD_B' => 'VUT', 'DESCRIPCION' => 'Vanuatu'),
        '334' => array('ID' => '334', 'COD_A' => 'WF', 'COD_B' => 'WLF', 'DESCRIPCION' => 'Wallis y Futuna'),
        '335' => array('ID' => '335', 'COD_A' => 'WS', 'COD_B' => 'WSM', 'DESCRIPCION' => 'Samoa'),
        '336' => array('ID' => '336', 'COD_A' => 'YE', 'COD_B' => 'YEM', 'DESCRIPCION' => 'Yemen'),
        '337' => array('ID' => '337', 'COD_A' => 'YT', 'COD_B' => 'MYT', 'DESCRIPCION' => 'Mayotte'),
        '338' => array('ID' => '338', 'COD_A' => 'ZA', 'COD_B' => 'ZAF', 'DESCRIPCION' => 'Sudáfrica'),
        '339' => array('ID' => '339', 'COD_A' => 'ZM', 'COD_B' => 'ZMB', 'DESCRIPCION' => 'Zambia'),
        '340' => array('ID' => '340', 'COD_A' => 'ZW', 'COD_B' => 'ZWE', 'DESCRIPCION' => 'Zimbabue'),
        '107' => array('ID' => '107', 'COD_A' => 'KK', 'COD_B' => 'KK ', 'DESCRIPCION' => 'Antillas Neerlandesas'),
        '500' => array('ID' => '500', 'COD_A' => 'AA', 'COD_B' => 'AA ', 'DESCRIPCION' => 'ex URSS', 'I'),
        '501' => array('ID' => '501', 'COD_A' => 'BB', 'COD_B' => 'BB ', 'DESCRIPCION' => 'ex Yugoslavia', 'I'),
        '913' => array('ID' => '913', 'COD_A' => 'CC', 'COD_B' => 'CC ', 'DESCRIPCION' => 'Kosovo bajo mandato de la UNMIK', 'I'),
        '920' => array('ID' => '920', 'COD_A' => 'DD', 'COD_B' => 'DD ', 'DESCRIPCION' => 'TPIY (Tribunal Penal Internacional para la ex Yugoslavia)', 'I'),
        '921' => array('ID' => '921', 'COD_A' => 'EE', 'COD_B' => 'EE ', 'DESCRIPCION' => 'TPIR (Tribunal Penal Internacional para Ruanda)', 'I'),
        '922' => array('ID' => '922', 'COD_A' => 'FF', 'COD_B' => 'FF ', 'DESCRIPCION' => 'TESL (Tribunal Especial para Sierra Leona)', 'I'),
        '923' => array('ID' => '923', 'COD_A' => 'GG', 'COD_B' => 'GG ', 'DESCRIPCION' => 'CPI (Corte Penal Internacional)', 'I'),
        '924' => array('ID' => '924', 'COD_A' => 'HH', 'COD_B' => 'HH ', 'DESCRIPCION' => 'TEL (Tribunal Especial para el Líbano)', 'I'),
        '997' => array('ID' => '997', 'COD_A' => 'II', 'COD_B' => 'II ', 'DESCRIPCION' => 'Formación OIPC', 'I'),
        '999' => array('ID' => '999', 'COD_A' => 'JJ', 'COD_B' => 'JJ ', 'DESCRIPCION' => 'OIPC', 'I'),
        '001' => array('ID' => '001', 'COD_A' => 'SX', 'COD_B' => 'SXM', 'DESCRIPCION' => 'Sint Maarten', 'A'),
        '002' => array('ID' => '002', 'COD_A' => 'IM', 'COD_B' => 'IMN', 'DESCRIPCION' => 'Isla de Man', 'A'),
        '003' => array('ID' => '003', 'COD_A' => 'BL', 'COD_B' => 'BLM', 'DESCRIPCION' => 'San Bartolomé', 'A'),
        '004' => array('ID' => '004', 'COD_A' => 'BQ', 'COD_B' => 'BES', 'DESCRIPCION' => 'Bonaire, San Eustaquio y Saba', 'A'),
        '005' => array('ID' => '005', 'COD_A' => 'GG', 'COD_B' => 'GGY', 'DESCRIPCION' => 'Guernsey', 'A'),
        '006' => array('ID' => '006', 'COD_A' => 'JE', 'COD_B' => 'JEY', 'DESCRIPCION' => 'Jersey', 'A')
    );

    private $Documentos = array(
        '1' => array("ID" => '1', "COD" => "", "DESCRIPCION" => "Sin especificar"),
        '2' => array("ID" => '2', "COD" => "ALP", "DESCRIPCION" => "Aliens Travel Document"),
        '3' => array("ID" => '3', "COD" => "ARN", "DESCRIPCION" => "Alien Registration Number"),
        '4' => array("ID" => '4', "COD" => "BTC", "DESCRIPCION" => "Birth Certificate"),
        '5' => array("ID" => '5', "COD" => "BUP", "DESCRIPCION" => "Business Passport"),
        '6' => array("ID" => '6', "COD" => "CLP", "DESCRIPCION" => "Collective Passport"),
        '7' => array("ID" => '7', "COD" => "CMC", "DESCRIPCION" => "Crew Member Certificate"),
        '8' => array("ID" => '8', "COD" => "COI", "DESCRIPCION" => "Certificate of identity"),
        '9' => array("ID" => '9', "COD" => "COP", "DESCRIPCION" => "Consular Passport"),
        '10' => array("ID" => '10', "COD" => "CTD", "DESCRIPCION" => "Collective Transit Visa"),
        '11' => array("ID" => '11', "COD" => "CVS", "DESCRIPCION" => "Collective Visa"),
        '12' => array("ID" => '12', "COD" => "CZP", "DESCRIPCION" => "Citizen s Passport"),
        '13' => array("ID" => '13', "COD" => "DCP", "DESCRIPCION" => "Diplomatic Courier Passport"),
        '14' => array("ID" => '14', "COD" => "DIP", "DESCRIPCION" => "Diplomatic Passport"),
        '15' => array("ID" => '15', "COD" => "DIV", "DESCRIPCION" => "Document of identity for Visa"),
        '16' => array("ID" => '16', "COD" => "DRV", "DESCRIPCION" => "Driving licence"),
        '17' => array("ID" => '17', "COD" => "EAP", "DESCRIPCION" => "Emergency Alien s Passport"),
        '18' => array("ID" => '18', "COD" => "ECC", "DESCRIPCION" => "Vehicle EARPCCO Clearance Certificate"),
        '19' => array("ID" => '19', "COD" => "EMC", "DESCRIPCION" => "Emergency Certificate"),
        '20' => array("ID" => '20', "COD" => "EMD", "DESCRIPCION" => "Emergency Document"),
        '21' => array("ID" => '21', "COD" => "EMP", "DESCRIPCION" => "Emergency Passport"),
        '22' => array("ID" => '22', "COD" => "GLP", "DESCRIPCION" => "Local Passport-Travel Document"),
        '23' => array("ID" => '23', "COD" => "HUP", "DESCRIPCION" => "Hunting permit"),
        '24' => array("ID" => '24', "COD" => "I", "DESCRIPCION" => "All Identity document"),
        '25' => array("ID" => '25', "COD" => "IDC", "DESCRIPCION" => "Identity card"),
        '26' => array("ID" => '26', "COD" => "LAP", "DESCRIPCION" => "Special Entry"),
        '27' => array("ID" => '27', "COD" => "MDP", "DESCRIPCION" => "Ministerial Service Passport"),
        '28' => array("ID" => '28', "COD" => "MIP", "DESCRIPCION" => "Military Passport"),
        '29' => array("ID" => '29', "COD" => "MPP", "DESCRIPCION" => "Member of Parliement Passport"),
        '30' => array("ID" => '30', "COD" => "MSP", "DESCRIPCION" => "Ministerial Passport"),
        '31' => array("ID" => '31', "COD" => "NAP", "DESCRIPCION" => "National Passport"),
        '32' => array("ID" => '32', "COD" => "NID", "DESCRIPCION" => "National IDentification Number"),
        '33' => array("ID" => '33', "COD" => "OCP", "DESCRIPCION" => "Official Collective Passport"),
        '34' => array("ID" => '34', "COD" => "OFP", "DESCRIPCION" => "Official Passport"),
        '35' => array("ID" => '35', "COD" => "OT1", "DESCRIPCION" => "Other 1"),
        '36' => array("ID" => '36', "COD" => "OT2", "DESCRIPCION" => "Other 2"),
        '37' => array("ID" => '37', "COD" => "OT3", "DESCRIPCION" => "Other 3"),
        '38' => array("ID" => '38', "COD" => "OTH", "DESCRIPCION" => "Other"),
        '39' => array("ID" => '39', "COD" => "OTX", "DESCRIPCION" => "Other x"),
        '40' => array("ID" => '40', "COD" => "OTx", "DESCRIPCION" => "Other x"),
        '41' => array("ID" => '41', "COD" => "P", "DESCRIPCION" => "All passport types"),
        '42' => array("ID" => '42', "COD" => "PAS", "DESCRIPCION" => "Passport"),
        '43' => array("ID" => '43', "COD" => "PRP", "DESCRIPCION" => "Permanent Residence Permit"),
        '44' => array("ID" => '44', "COD" => "PTR", "DESCRIPCION" => "Permit to re-enter"),
        '45' => array("ID" => '45', "COD" => "RES", "DESCRIPCION" => "Residence permit"),
        '46' => array("ID" => '46', "COD" => "RFP", "DESCRIPCION" => "Travel Document for Refugees"),
        '47' => array("ID" => '47', "COD" => "SCC", "DESCRIPCION" => "Vehicle SARPCCO Clearance Certificate"),
        '48' => array("ID" => '48', "COD" => "SEP", "DESCRIPCION" => "Service Passport"),
        '49' => array("ID" => '49', "COD" => "SIB", "DESCRIPCION" => "Seaman s Book"),
        '50' => array("ID" => '50', "COD" => "SOC", "DESCRIPCION" => "Social Security Number"),
        '51' => array("ID" => '51', "COD" => "SPE", "DESCRIPCION" => "Special Document"),
        '52' => array("ID" => '52', "COD" => "SPP", "DESCRIPCION" => "Special Passport"),
        '53' => array("ID" => '53', "COD" => "STP", "DESCRIPCION" => "Student Passport"),
        '54' => array("ID" => '54', "COD" => "TCA", "DESCRIPCION" => "Document for citizens Abroad"),
        '55' => array("ID" => '55', "COD" => "TDS", "DESCRIPCION" => "Transit Visa"),
        '56' => array("ID" => '56', "COD" => "TLP", "DESCRIPCION" => "Travel Document in Lieu of National Passport"),
        '57' => array("ID" => '57', "COD" => "TRC", "DESCRIPCION" => "Travel Certificate"),
        '58' => array("ID" => '58', "COD" => "TRP", "DESCRIPCION" => "Temporary Residence Permit"),
        '59' => array("ID" => '59', "COD" => "TTD", "DESCRIPCION" => "Temporary Travel Document"),
        '60' => array("ID" => '60', "COD" => "V", "DESCRIPCION" => "All Visa types"),
        '61' => array("ID" => '61', "COD" => "VOC", "DESCRIPCION" => "Vehicle Owner Certificate"),
        '62' => array("ID" => '62', "COD" => "VRB", "DESCRIPCION" => "Navigation License"),
        '63' => array("ID" => '63', "COD" => "VRD", "DESCRIPCION" => "Vehicle Registration Document"),
        '64' => array("ID" => '64', "COD" => "VSA", "DESCRIPCION" => "Visa"),
        '65' => array("ID" => '65', "COD" => "YOP", "DESCRIPCION" => "Youth-Child Passport")
    );


    private $Offence_Code = array(
        'FGM' => 'PASAPORTE EXTRAVIADO',
        'FIM' => 'CÉDULA DE IDENTIDAD EXTRAVIADA',
        'FKM' => 'DOCUMENTO ADMINISTRATIVO EXTRAVIADO',
        'I' => 'DELITOS CONTRA LAS PERSONAS',
        'IC' => 'Delitos contra los niños',
        'ICM' => 'DELITOS CONTRA LOS NIÑOS',
        'IF' => 'Prófugos',
        'IFA' => 'AUXILIO A UN DELINCUENTE',
        'IFE' => 'EVADIDO',
        'IFL' => 'EN LIBERTAD',
        'IH' => 'Genocidio, crímenes de guerra y crímenes contra la humanidad',
        'IHG' => 'GENOCIDIO',
        'IHH' => 'CRÍMENES CONTRA LA HUMANIDAD',
        'IHW' => 'CRIMEN DE GUERRA',
        'II' => 'Tráfico y trata de seres humanos e Inmigración clandestina',
        'III' => 'INMIGRACIÓN CLANDESTINA',
        'IIO' => 'ORGANIZADOR DE OPERACIONES DE INMIGRACIÓN CLANDESTINA',
        'IIP' => 'ORGANIZADOR DE OPERACIONES DE TRATA DE SERES HUMANOS',
        'IIT' => 'TRATA DE SERES HUMANOS',
        'IK' => 'Secuestro',
        'IKA' => 'DELITO CONTRA LA FAMILIA O SUSTRACCIÓN DE MENOR',
        'IKK' => 'SECUESTRO O DETENCIÓN ILEGAL',
        'IKP' => 'PIRATERÍA MARÍTIMA / ROBO EN ALTA MAR',
        'IL' => 'Delitos contra la vida y la salud',
        'ILA' => 'AGRESIÓN O MALOS TRATOS',
        'ILB' => 'TORTURA O ACTOS DE BARBARIE',
        'ILG' => 'PROFANACIÓN DE SEPULTURA',
        'ILM' => 'LESIONES CON RESULTADO DE MUERTE, HOMICIDIO O ASESINATO',
        'ILO' => 'ÓRGANOS (se estudiará la posible inclusión de las HORMONAS)',
        'ILT' => 'AMENAZAS',
        'IM' => 'Personas desaparecidas',
        'IMM' => 'PERSONA DESAPARECIDA',
        'IO' => 'Delincuencia organizada y delincuencia transnacional',
        'IOO' => 'ORGANIZACIÓN, ASOCIACIÓN O GRUPO DELICTIVO',
        'IR' => 'Delito contra la seguridad del tráfico',
        'IRO' => 'DELITO CONTRA LA SEGURIDAD DEL TRÁFICO',
        'IS' => 'Delitos sexuales',
        'ISE' => 'EXPLOTACIÓN SEXUAL O PROSTITUCIÓN',
        'ISP' => 'PRODUCCIÓN O DISTRIBUCIÓN DE PORNOGRAFÍA',
        'ISR' => 'VIOLACIÓN',
        'ISS' => 'DELITOS SEXUALES',
        'IT' => 'Terrorismo',
        'ITA' => 'APOYO LOGÍSTICO AL TERRORISMO',
        'ITB' => 'TERRORISMO BIOLÓGICO',
        'ITC' => 'TERRORISMO QUÍMICO',
        'ITF' => 'FINANCIACIÓN DEL TERRORISMO',
        'ITM' => 'MIEMBRO DE GRUPO TERRORISTA',
        'ITR' => 'TERRORISMO RADIOLÓGICO O NUCLEAR',
        'ITT' => 'DELITO RELACIONADO CON EL TERRORISMO',
        'ITZ' => 'ACTOS TERRORISTAS',
        'IU' => 'Restos humanos sin identificar',
        'IUC' => 'CADÁVER POR IDENTIFICAR',
        'NLC' => 'No figura en la lista',
        'O' => '--',
        'OLDFGM' => '--',
        'OLDFIM' => '--',
        'OLDFKM' => '--',
        'OLDVCC' => '--',
        'OLDZAZ' => '--',
        'OLDZCA' => '--',
        'OLDZCE' => '--',
        'OLDZCI' => '--',
        'OLDZCN' => '--',
        'OLDZCS' => '--',
        'OLDZED' => '--',
        'P' => 'DELITOS CONTRA LA PROPIEDAD, LAS EMPRESAS Y EL ESTADO',
        'PA' => 'Delitos relacionados con las obras de arte',
        'PAA' => 'OBRAS DE ARTE',
        'PC' => 'Falsificaciones e imitaciones',
        'PCF' => 'FALSIFICACIÓN',
        'PCM' => 'FALSIFICACIÓN DE MONEDA',
        'PCO' => 'CRIMEN FARMACOLÓGICO',
        'PCP' => 'DELITO CONTRA LA PROPIEDAD INTELECTUAL E INDUSTRIAL',
        'PCT' => 'FALSIFICACIÓN DE DOCUMENTOS DE VIAJE',
        'PD' => 'Delitos relacionados con las drogas',
        'PDA' => 'AGENTES DOPANTES Y ANABOLIZANTES',
        'PDC' => 'CANNABIS',
        'PDD' => 'DROGAS',
        'PDH' => 'HEROÍNA, MORFINA Y OPIO',
        'PDO' => 'COCAÍNA',
        'PDP' => 'SUSTANCIAS SICOTRÓPICAS',
        'PE' => 'Delincuencia contra el medio ambiente',
        'PEB' => 'AGENTES BACTERIOLÓGICOS, BIOLÓGICOS Y NUCLEARES',
        'PEC' => 'SUSTANCIAS QUÍMICAS',
        'PEE' => 'DELITO CONTRA EL MEDIO AMBIENTE',
        'PEW' => 'DELITO CONTRA LA FAUNA Y LA FLORA SILVESTRES',
        'PF' => 'Defraudaciones',
        'PFB' => 'FRAUDE BANCARIO O FINANCIERO',
        'PFC' => 'COHECHO O CORRUPCIÓN',
        'PFF' => 'ESTAFA',
        'PFG' => 'FRAUDE CONTRA LA ADMINISTRACIÓN PÚBLICA',
        'PFM' => 'FRAUDE COMERCIAL',
        'PFV' => 'INFRACCIÓN DE LA REGLAMENTACIÓN SOBRE DIVISAS',
        'PH' => 'Piratería',
        'PHH' => 'SECUESTRO DE MEDIO DE TRANSPORTE (AERONAVE, EMBARCACIÓN, AUTOBÚS, AUTOMÓVIL, ETC.)',
        'PI' => 'Delincuencia informática',
        'PIH' => 'DELITOS DE ALTA TECNOLOGÍA',
        'PM' => 'Blanqueo de capitales',
        'PMM' => 'BLANQUEO DE CAPITALES',
        'PT' => 'Hurto o robo',
        'PTA' => 'HURTO CON AGRAVANTES',
        'PTB' => 'Robo con fuerza en las cosas',
        'PTE' => 'Extorsión',
        'PTR' => 'RECEPTACIÓN DE BIENES ROBADOS',
        'PTS' => 'HURTO',
        'PTW' => 'ROBO A MANO ARMADA',
        'PTY' => 'Robo con violencia o intimidación',
        'PV' => 'Gamberrismo, vandalismo y daños',
        'PVV' => 'VANDALISMO, DAÑOS, SAQUEO Y GAMBERRISMO',
        'PW' => 'Delitos perpetrados mediante el uso de armas y explosivos',
        'PWA' => 'INCENDIO PROVOCADO',
        'PWC' => 'MUNICIONES, COMPONENTES, ARMAS DE FUEGO, ARMAS O EXPLOSIVOS',
        'PWE' => 'UTILIZACIÓN DE ARTEFACTO EXPLOSIVO (ATENTADO CON BOMBA)',
        'VCC' => 'ASOCIACIÓN ILÍCITA',
        'ZAZ' => 'RELEVANCIA INTERNACIONAL (OTROS TIPOS)',
        'ZCA' => 'TENTATIVA',
        'ZCE' => 'FALSO TESTIMONIO',
        'ZCI' => 'INCITACIÓN',
        'ZCN' => 'ASOCIACIÓN ILÍCITA',
        'ZCS' => 'PRESUNCIÓN',
        'ZED' => 'CONDUCCIÓN BAJO LA INFLUENCIA DEL ALCOHOL/DE DROGAS'
    );



    private $cabello = array(
        'BLA' => 'Negro',
        'BRO' => 'Castaño',
        'BROD' => 'Castaño oscuro',
        'BROF' => 'Claro',
        'BROL' => 'Castaño claro',
        'GRY' => 'Canoso',
        'GRYG' => 'Entrecano',
        'HAIB' => 'Calvo',
        'HAID' => 'Teñido',
        'OTHA' => 'Albino',
        'OTHD' => 'Oscuro',
        'RED' => 'Pelirrojo',
        'REDA' => 'Castaño rojizo',
        'WHI' => 'Blanco',
        'YELB' => 'Rubio',
        'YELBA' => 'Rubio ceniza',
        'YELBD' => 'Rubio oscuro',
        'YELBL' => 'Rubio claro',
        'YELS' => 'Rubio rojizo'
    );

    private $ojos = array(
        'BLA' => 'Negros',
        'BLU' => 'Azules',
        'BLUGR' => 'Blue-green',
        'BLUL' => 'Azul claro',
        'BLULA' => 'Lavender Blue',
        'BRO' => 'Castaños',
        'BROD' => 'Castaño oscuro',
        'BROGR' => 'Brownish-green',
        'BROH' => 'Avellana',
        'BROL' => 'Castaño claro',
        'GRE' => 'Verdes',
        'GRY' => 'Grises',
        'OTHA' => 'Albinos',
        'OTHC' => 'De colores diferentes',
        'OTHD' => 'Oscuros',
        'OTHL' => 'Claros'


    );

    private $complexion = array(
        'AT' => 'Atlética',
        'DW' => 'Enana',
        'FA' => 'Gruesa',
        'ME' => 'Normal',
        'OB' => 'Obesa',
        'SI' => 'Delgada',
        'SL' => 'Esbelta',
        'SM' => 'Menuda',
        'ST' => 'Corpulenta',
        'SY' => 'Robusta',
        'TA' => 'Alta',
        'TH' => 'Flaca'
    );

    // Propósito asociado de la notificación (associated_nominal_purpose_id). Agregar acá nuevos códigos a medida que se detecten.
    private $propositos = array(
        'AR' => 'Detención',
        'BT' => 'Prohibición de viajar',
        'EA' => 'Embargo de armas',
        'FA' => 'Congelación de activos',
        'FI' => 'Para información únicamente',
        'IA' => 'Identify Assets',
        'ID' => 'Identificación',
        'IT' => 'PELIGRO INMINENTE',
        'LA' => 'Locate Assets',
        'LO' => 'Localización',
        'MA' => 'Monitor Assets discreetly AND/OR Continuously',
        'MP' => 'MODUS OPERANDI',
        'MR' => 'MODUS OPERANDI Y SOLICITUD DE INFORMACIÓN',
        'OA' => 'Obtain information about Assets',
        'OI' => 'Obtención de información',
        'OT' => 'Other',
        'RD' => 'Remisión de las consultas a la OCN de origen',
        'TA' => 'Adopción de medidas de protección',
        'WA' => 'Advertencia'
    );

    // Estado de la notificación (status_id de la notificación). Agregar acá nuevos códigos a medida que se detecten.
    private $estados = array(
        'CA' => 'ACTIVIDAD DELICTIVA',
        'CC' => 'Convicto y/o Acusado',
        'CH' => 'Charged',
        'CO' => 'Convicted(ES)',
        'DE' => 'Fallecido (identificado)',
        'MI' => 'Desaparecido',
        'NI' => 'Investigación cerrada',
        'OD' => 'OBJETO O DISPOSITIVO',
        'PE' => 'EVASIÓN DE RECLUSOS',
        'PP' => 'En espera de conservación parcial',
        'PT' => 'Posible peligro',
        'RE' => 'Antecedentes policiales',
        'RI' => 'Requ. for info.',
        'SP' => 'Sospechoso',
        'TB' => 'Será eliminado',
        'UD' => 'Cadáver por identificar',
        'UI' => 'Investigación abierta',
        'UN' => 'Objeto de sanciones impuestas por la ONU',
        'UP' => 'Persona no identificada',
        'VI' => 'Víctima',
        'WA' => 'Buscado',
        'WI' => 'Testigo'
    );

    private $coloresId = array(
        'ALU' => 'Aluminio',
        'BEI' => 'Beige',
        'BLA' => 'Negro',
        'BLU' => 'Azul',
        'BRO' => 'Marrón',
        'BRZ' => 'Bronce',
        'CLA' => 'Vino',
        'COP' => 'Cobre',
        'CRM' => 'Crema',
        'GOL' => 'Oro',
        'GRA' => 'Gris',
        'GRE' => 'Verde',
        'MAR' => 'Granate',
        'MTL' => 'Metálico',
        'NAV' => 'Azul Marino',
        'ORA' => 'Naranja',
        'PIN' => 'Rosa',
        'PUR' => 'Púrpura',
        'RED' => 'Rojo',
        'ROS' => 'Rosado',
        'RUS' => 'Óxido',
        'SIL' => 'Plata',
        'TAN' => 'Tostado',
        'TUR' => 'Turquesa',
        'WHI' => 'Blanco',
        'YEL' => 'Amarillo'
    );

    private $Caution = array(
        'AMN' => 'Amnésica',
        'ARM' => 'Armada',
        'DAN' => 'Peligrosa',
        'DEP' => 'Depresiva',
        'DIS' => 'Controles discretos. Recolectar la mayor información posible acerca de esta persona sin llamar su atención. Con esta medida no se solicita la detención de la persona.',
        'DRU' => 'Drogadicta',
        'ESC' => 'Propensa a la evasión',
        'ILL' => 'Enferma mental',
        'INF' => 'Enferma contagiosa',
        'OTH' => 'Otros',
        'PES' => 'Persona propensa a cometer delitos sexuales contra menores',
        'PEU' => 'Persona propensa a utilizar imágenes de menores con fines pornográficos por afán de lucro',
        'SUI' => 'Propensa al suicidio',
        'TRE' => 'En tratamiento médico',
        'VIO' => 'Violenta'
    );

    private $Alias_Qualification = array(
        'AL' => 'Alias',
        'BN' => 'Apellido de nacimiento',
        'CO' => 'Componente',
        'DN' => 'Apellido tras el divorcio',
        'EP' => 'Persona real',
        'MD' => 'Apellido de casada',
        'MN' => 'Apellido de soltera',
        'NC' => 'Apodo',
        'OS' => 'Otra manera de escribirlo',
        'RN' => 'Nombre auténtico'
    );


    // Consulta de documento
    public function getPaises()
    {

        $respuesta = $this->array_sort($this->Paises, "ID");

        return $respuesta;
    }

    public function getPais($IdPais)
    {

        $respuesta = $this->Paises[$IdPais];

        return $respuesta;
    }

    // Consulta de documento
    public function getDocumentos()
    {

        $respuesta = $this->array_sort($this->Documentos, "COD");

        return $respuesta;
    }

    public function getDocumento($IdDocumento)
    {

        $respuesta = $this->Documentos[$IdDocumento];

        return $respuesta;
    }

    public function getOffencesCode()
    {

        $respuesta = $this->Offence_Code;

        return $respuesta;
    }
    public function getOffenceCode($Offencecode)
    {

        $respuesta = $this->Offence_Code[$Offencecode];

        return $respuesta;
    }

    public function getCaution()
    {
        $respuesta = $this->Caution;

        return $respuesta;
    }

    public function getCautions($Cautioncode)
    {
        $respuesta = $this->Caution[$Cautioncode];
        return $respuesta;
    }

    // Devuelve COD => DESCRIPCION a partir de $Documentos, para traducir el código que trae la API
    public function getDocumentosPorCodigo()
    {
        $respuesta = array();
        foreach ($this->Documentos as $doc) {
            $respuesta[$doc['COD']] = $doc['DESCRIPCION'];
        }

        return $respuesta;
    }

    public function getCabello()
    {
        return $this->cabello;
    }

    public function getOjos()
    {
        return $this->ojos;
    }

    public function getComplexion()
    {
        return $this->complexion;
    }

    public function getPropositos()
    {
        return $this->propositos;
    }

    public function getEstados()
    {
        return $this->estados;
    }

    public function getColoresId()
    {
        return $this->coloresId;
    }

    public function getAlias_Qualification()
    {
        return $this->Alias_Qualification;
    }
    private function array_sort($array, $on, $order = SORT_ASC)
    {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }



}   //  class Diccionario
?>